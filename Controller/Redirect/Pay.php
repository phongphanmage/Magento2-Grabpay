<?php
namespace Vnecoms\GrabPay\Controller\Redirect;

use Vnecoms\GrabPay\Rest\OneTimeCharge;

class Pay extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Vnecoms\GrabPay\Helper\Data
     */
    protected $helper;

    /**
     * @var \Vnecoms\GrabPay\Model\OneTime\Pay
     */
    protected $payment;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var Magento\Framework\HTTP\Client\Curl
     */
    protected $curl;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Vnecoms\GrabPay\Model\TransactionFactory
     */
    protected $transaction;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Vnecoms\GrabPay\Logger\Logger
     */
    protected $logger;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    /**
     * Pay constructor.
     * @param \Vnecoms\GrabPay\Helper\Data $helper
     * @param \Vnecoms\GrabPay\Model\OneTime\Pay $payment
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param \Magento\Framework\Registry $registry
     * @param \Vnecoms\GrabPay\Model\TransactionFactory $transaction
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Vnecoms\GrabPay\Logger\Logger $logger
     * @param \Magento\Checkout\Model\Session $session
     */
    public function __construct(
        \Vnecoms\GrabPay\Helper\Data $helper,
        \Vnecoms\GrabPay\Model\OneTime\Pay $payment,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\Registry $registry,
        \Vnecoms\GrabPay\Model\TransactionFactory $transaction,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Vnecoms\GrabPay\Logger\Logger $logger,
        \Magento\Checkout\Model\Session $session
    ) {
        $this->curl = $curl;
        $this->helper = $helper;
        $this->payment = $payment;
        $this->orderFactory = $orderFactory;
        $this->_coreRegistry = $registry;
        $this->transaction = $transaction;
        $this->timezone = $timezone;
        $this->logger = $logger;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder() {
        return $this->session->getLastRealOrder();
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $order = $this->getOrder();

        if (!$order || $order->getId() < 1) {
            $this->_redirect('checkout/cart');
            return;
        }
        
        $gp = new OneTimeCharge(
            $this->payment->getConfigValue('partner_id'),
            $this->payment->getConfigValue('partner_secret'),
            $this->payment->getConfigValue('client_id'),
            $this->payment->getConfigValue('client_secret'),
            $this->payment->getConfigValue('merchant_id')
        );

        if (!$this->payment->isSandBox()) {
            $gp->useProduction();
        }

        $product_name = '';
        foreach ($order->getAllItems() as $item) {
            $product_name .= $item->getName();;
        }

        $response = $gp->initCharge(
            $order->getIncrementId(),
            $order->getIncrementId(),
            $order->getBaseGrandTotal() * 100,
            $product_name
        );

        if ($response && isset($response['partnerTxID'])) {
            try {
                $transaction = $this->transaction->create()->load($order->getIncrementId(), "order_id");
                $state = base64_encode($order->getIncrementId());
                if ($transaction->getId()) {
                    $transaction
                        ->setData("txn_id", $response["partnerTxID"])
                        ->setData("state", $state)
                        ->save();
                } else {
                    $this->transaction->create()->setData([
                        "partner_txn_id" => $response["partnerTxID"],
                        "order_id" => $order->getIncrementId(),
                        "state" => $state
                    ])->save();
                }
                $codeVerifier = $order->getIncrementId().$order->getCreatedAt().$this->payment->getConfigValue('partner_id');
                $authLink = $gp->getOauthAuthorizeUrl(
                    base64_encode($codeVerifier),
                    $response['request'],
                    $this->payment->getCallbackUrl(),
                    "payment.one_time_charge",
                    $state
                );

                return $this->getResponse()->setRedirect($authLink);

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('checkout/cart');
                return;
            }
        } else {
            $this->messageManager->addError(__('Something wrong when init payment Grabpay.'));
            $this->_redirect('checkout/cart');
            return;
        }
    }
}
