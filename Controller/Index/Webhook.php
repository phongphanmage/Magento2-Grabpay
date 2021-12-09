<?php
namespace Vnecoms\GrabPay\Controller\Index;

use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\Order\StatusResolver;

class Webhook extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Vnecoms\GrabPay\Model\TransactionFactory
     */
    protected $transaction;

    /**
     * @var \Vnecoms\GrabPay\Logger\Logger
     */
    protected $logger;

    /**
     * @var \Magento\Sales\Model\Service\InvoiceService
     */
    protected $_invoiceService;

    /**
     * @var OrderSender
     */
    protected $orderSender;

    /**
     * @var StatusResolver
     */
    private $statusResolver;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $_transactionFactory;

    /**
     * Index constructor.
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Vnecoms\GrabPay\Model\TransactionFactory $transaction
     * @param \Vnecoms\GrabPay\Logger\Logger $logger
     * @param \Magento\Sales\Model\Service\InvoiceService $invoiceService
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param StatusResolver $statusResolver
     * @param OrderSender $orderSender
     */
    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\Action\Context $context,
        \Vnecoms\GrabPay\Model\TransactionFactory $transaction,
        \Vnecoms\GrabPay\Logger\Logger $logger,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        StatusResolver $statusResolver,
        OrderSender $orderSender
    ) {
        $this->transaction = $transaction;
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
        $this->_invoiceService = $invoiceService;
        $this->statusResolver = $statusResolver;
        $this->orderSender = $orderSender;
        $this->_transactionFactory = $transactionFactory;
        parent::__construct($context);
    }

    /**
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        try {
            $body = @file_get_contents('php://input');
            $body = json_decode($body, true);

            if (!isset($body['partnerTxID'])) {
                $message = __('Payment Failed with GrabPay. Reason: Order do not exist');
                $this->logger->info($message);
                return;
            }

            $order = $this->_getOrder($body['partnerTxID']);
            if (!$order->getId()) {
                $message = __('Payment Failed with GrabPay. Reason: Order do not exist');
                $this->logger->info($message);
                return;
            }

            $transaction = $this->transaction->create()->load($body['partnerTxID'], "order_id");

            if (!$transaction->getId()) {
                $message = __('Payment Failed with GrabPay. Reason: Order do not exist');
                $this->logger->info($message);
                return;
            }

            $txnId = $transaction->getData("txn_id") ? $transaction->getData("txn_id") : $body['txID'];

            switch ($body["txType"]) {
                case "Init":
                    $order->setStatus(Order::STATE_PENDING_PAYMENT)->save();
                    break;
                case "Auth":
                    if ($body['status'] == "success") {
                        $comment = __('Payment authorized with GrabPay.');
                        $order->addStatusToHistory(Order::STATE_PENDING_PAYMENT, $comment, $isCustomerNotified = false)
                            ->save();
                    } else {
                        $order->cancel();
                    }
                    break;
                case "Capture":
                    if ($body['status'] == "success") {
                        $this->captureOrder($txnId, $order);
                    } else {
                        $order->cancel();
                    }
                    break;
            }

        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }

    /**
     * @param $paymentIntentId
     * @param $order
     */
    protected function captureOrder($paymentIntentId, $order) {
        $captureCase = \Magento\Sales\Model\Order\Invoice::CAPTURE_OFFLINE;

        $invoice = $this->invoiceOrder($order, $paymentIntentId, $captureCase);

        $comment = __('Payment captured with GrabPay. ');
        $comment .= __('Transaction ID: '). $paymentIntentId;

        $payment = $order->getPayment();
        $transactionType = \Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE;
        $payment->setLastTransId($paymentIntentId);
        $payment->setTransactionId($paymentIntentId);

        $payment->setPreparedMessage(
            $comment
        );

        $transaction = $payment->addTransaction($transactionType, $invoice, true);
        $transaction->save();

        if ($order->canUnhold())
        {
            $order->addStatusToHistory($status = false, $comment, $isCustomerNotified = false)
                ->setHoldBeforeState(\Magento\Sales\Model\Order::STATE_PROCESSING)
                ->save();
        }
        else
        {
            if ($order->getState() === \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT ||
                $order->getState() === \Magento\Sales\Model\Order::STATE_NEW
            ) {
                $state = \Magento\Sales\Model\Order::STATE_PROCESSING;
                $status = $this->statusResolver->getOrderStatusByState($order, $state);
                $order->setState($state);
                $order->setStatus($status);
                $order->addStatusHistoryComment($comment);
                $order->save()
                ;
            }
        }

        if ($invoice && !$order->getEmailSent()) {
            $this->orderSender->send($order);
            $order->addStatusHistoryComment(
                __('You notified customer about invoice #%1.', $invoice->getIncrementId())
            )->setIsCustomerNotified(
                true
            )->save();
        }
    }

    /**
     * @param $order
     * @param null $transactionId
     * @param string $captureCase
     * @param null $amount
     * @param bool $save
     * @return \Magento\Sales\Api\Data\InvoiceInterface|Order\Invoice|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function invoiceOrder($order, $transactionId = null, $captureCase = \Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE, $amount = null, $save = true)
    {
        if ($save)
            $dbTransaction = $this->_transactionFactory->create();

        if ($order->canInvoice())
        {
            $invoice = $this->_invoiceService->prepareInvoice($order);
            $invoice->setRequestedCaptureCase($captureCase);

            if ($transactionId)
            {
                $invoice->setTransactionId($transactionId);
                $order->getPayment()->setLastTransId($transactionId);
            }

            $invoice->register();

            if ($save)
                $dbTransaction->addObject($invoice)
                    ->addObject($order)
                    ->save();

            return $invoice;
        }
        else
        {
            foreach($order->getInvoiceCollection() as $invoice)
            {
                if ($invoice->canCapture())
                {
                    $invoice->setRequestedCaptureCase($captureCase);

                    $invoice->pay();

                    if ($save)
                        $dbTransaction->addObject($invoice)
                            ->addObject($order)
                            ->save();

                    return $invoice;
                }
            }
        }

        return null;
    }

    /**
     * @param $Id
     * @return mixed
     */
    protected function _getOrder($Id) {
        $orderFactory = $this->orderFactory->create();
        return $orderFactory->loadByIncrementId($Id);
    }
}