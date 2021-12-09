<?php
/**
 * Copyright � Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Model\OneTime;

class Pay extends \Magento\Payment\Model\Method\AbstractMethod
{
    const LIVE_URL = "https://partner-api.grab.com";

    const SANDBOX_URL = "https://partner-api.stg-myteksi.com";
    
    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = 'grabpay';

    /**
     * @var bool
     */
    protected $_isInitializeNeeded = true;
    
    /**
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     *
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     *
     * @var Magento\Checkout\Model\Session
     */
    protected $orderSession;

    /**
     * @var \Vnecoms\GrabPay\Helper\Data
     */
    protected $grabPayHelper;
    
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directory_list;

    /**
     * Pay constructor.
     * @param \Vnecoms\GrabPay\Helper\Data $grabPayHelper
     * @param \Magento\Checkout\Model\Session $orderSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directory_list
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param \Magento\Directory\Helper\Data|null $directory
     */
    public function __construct(
        \Vnecoms\GrabPay\Helper\Data $grabPayHelper,
        \Magento\Checkout\Model\Session $orderSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list, 
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        \Magento\Directory\Helper\Data $directory = null
    ) {
        $this->grabPayHelper = $grabPayHelper;
        $this->orderSession = $orderSession;
        $this->orderFactory = $orderFactory;
        $this->customerSession = $customerSession;
        $this->urlBuilder = $urlBuilder;
        $this->directory_list = $directory_list;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory
        );
    }

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return '';
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getConfigValue($key)
    {
        $pathConfig = 'payment/' . $this->_code . "/" . $key;
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue($pathConfig, $storeScope);
    }

    /**
     * @return \Magento\Checkout\Model\Session|Magento\Checkout\Model\Session
     */
    public function getCheckout()
    {
        return $this->orderSession;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getCheckout()->getLastRealOrder();
    }

    /**
     * @param array $params
     * @return string
     */
    public function getCheckoutRedirectUrl($params = []) 
    {
        return $this->urlBuilder->getUrl('grabpay/redirect/pay', $params);
    }

    /**
     * @param array $params
     * @return string
     */
    public function getCallbackUrl($params = [])
    {
        $url = $this->urlBuilder->getUrl('grabpay/redirect/index', $params);
        $url = trim($url, "/");
        return $url;
    }

    /**
     * @param array $params
     * @return string
     */
    public function getTransactionUrl($params = [])
    {
        return $this->urlBuilder->getUrl('grabpay/transaction/init', $params);
    }

    /**
     * @param array $params
     * @return string
     */
    public function getWebHookUrl($params = [])
    {
        return $this->urlBuilder->getUrl('grabpay/webhook/index', $params);
    }

    /**
     * @param array $params
     * @return string
     */
    public function getCancelUrl($params = [])
    {
        return $this->urlBuilder->getUrl('grabpay/index/cancel', $params);
    }

    /**
     * @return string
     */
    public function getCheckoutSuccessUrl()
    {
        return $this->urlBuilder->getUrl('checkout/onepage/success');
    }

    /**
     * @return string
     */
    public function getCheckoutCartUrl()
    {
        return $this->urlBuilder->getUrl('checkout/cart');
    }

    /**
     * @return string
     */
    public function getStoreUrl()
    {
        return $this->urlBuilder->getUrl();
    }

    /**
     * @return bool
     */
    public function isInitializeNeeded()
    {
        return $this->_isInitializeNeeded;
    }

    /**
     * @return mixed
     */
    public function getLogoUrl() {
        return $this->grabPayHelper->getLogoUrl();
    }

    /**
     * @return mixed
     */
    public function getUrlService() {
        if ($this->getConfigValue('sandbox_mode')) {
            $url = \Vnecoms\GrabPay\Model\OneTime\Pay::SANDBOX_URL;
        } else {
            $url = \Vnecoms\GrabPay\Model\OneTime\Pay::LIVE_URL;
        }
        return $url;
    }

    /**
     * @return mixed
     */
    public function isSandBox() {
        return $this->getConfigValue('sandbox_mode');
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
