<?php
namespace Vnecoms\GrabPay\Controller\Redirect;

use Magento\Framework\Registry;
use Vnecoms\GrabPay\Rest\OneTimeCharge;

class Index extends \Magento\Framework\App\Action\Action
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
     * Index constructor.
     * @param \Vnecoms\GrabPay\Helper\Data $helper
     * @param \Vnecoms\GrabPay\Model\OneTime\Pay $payment
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param Registry $registry
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
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if (!isset($params['state'])) {
            $this->_redirect('checkout/cart');
            return;
        }
        $state = $params['state'];
        $transaction = $this->transaction->create()->load($state, "state");

        if (!$transaction->getId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $order = $this->_getOrder($transaction->getOrderId());
        if (!$order->getId()) {
            $this->_redirect('checkout/cart');
            return;
        }
        try {
            if (isset($params['code'])) {
                $code = $params['code'];
                //Get OAuth token

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
                $codeVerifier = $order->getIncrementId().$order->getCreatedAt().$this->payment->getConfigValue('partner_id');

                $result = $gp->getAccessToken(
                    $code,
                    $this->payment->getCallbackUrl(),
                    base64_encode($codeVerifier)
                );

                if ($result && isset($result['access_token'])) {
                    //Generating the HMAC Signature
                    $response = $gp->completeCharge($result['access_token'],  $transaction->getData("partner_txn_id"));

                    if (!isset($response['txID'])) {
                        $order->cancel();
                        $error_message = "";
                        $message = __('Payment Failed with GrabPay. Reason: '.$error_message);
                        $order->addStatusHistoryComment($message, \Magento\Sales\Model\Order::STATE_CANCELED);
                        $order->save();
                        $this->_redirect('checkout/cart');
                    } else {
                        $transaction
                            ->setData("txn_id", $response['txID'])
                            ->setData("status", $response['txStatus'])->save();
                        $this->_redirect('checkout/onepage/success');
                    }
                } else {
                    // cancel order
                    $order->cancel();
                    $message = __('Payment Failed with GrabPay');
                    $order->addStatusHistoryComment($message, \Magento\Sales\Model\Order::STATE_CANCELED);
                    $order->save();
                    $this->_getCheckoutSession()->restoreQuote();
                    $this->_redirect('checkout/cart');
                }
            } else if(isset($params['error'])) {
                $this->_getCheckoutSession()->restoreQuote();
                // cancel order
                $order->cancel();
                $message = __('Payment Failed with GrabPay. Reason: '.$params['error']);
                $order->addStatusHistoryComment($message, \Magento\Sales\Model\Order::STATE_CANCELED);
                $order->save();
                $this->_redirect('checkout/cart');
            }
        } catch (\Exception $e) {
            $this->_getCheckoutSession()->restoreQuote();
            $this->logger->info($e->getMessage());
            $this->_redirect('checkout/cart');
        }

    }

    /**
     * Return checkout session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckoutSession()
    {
        return $this->session;
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
