<?php
namespace Vnecoms\GrabPay\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\View\Asset\Repository $assetRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\View\Asset\Repository $assetRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->assetRepository = $assetRepository;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return bool|string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getLogoUrl()
    {
        $fileId = 'Vnecoms_GrabPay::images/logo.png';
        $params = [
            'area' => 'frontend' // frontend or backend
        ];
        try {
            return $this->assetRepository->getUrl($fileId, $params);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getMinTotal() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $payment = $objectManager->get('Vnecoms\GrabPay\Model\OneTime\Pay');
        return $payment->getConfigValue("min_price_total");
    }

    /**
     * @return mixed
     */
    public function getMaxTotal() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $payment = $objectManager->get('Vnecoms\GrabPay\Model\OneTime\Pay');
        return $payment->getConfigValue("max_price_total");
    }

    /**
     * @return mixed
     */
    public function isEnablePayment() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $payment = $objectManager->get('Vnecoms\GrabPay\Model\OneTime\Pay');
        return $payment->getConfigValue("active");
    }

    /**
     * @return mixed
     */
    public function isAllowPayLater() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $payment = $objectManager->get('Vnecoms\GrabPay\Model\OneTime\Pay');
        return $payment->getConfigValue("allow_paylater") && $this->isEnablePayment();
    }

    /**
     * @param $product
     * @return int
     */
    public function getProductPriceForGrabpay($product)
    {
        $regularPrice = 0;
        $specialPrice = 0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId());
        if($_product->getTypeId() == 'simple' || $_product->getTypeId() == 'virtual'){
            $regularPrice = $_product->getPriceInfo()->getPrice('regular_price')->getValue();
            $specialPrice = $_product->getPriceInfo()->getPrice('special_price')->getValue();
        }

        if ($_product->getTypeId() == 'configurable') {
            $basePrice = $_product->getPriceInfo()->getPrice('regular_price');
            $regularPrice = $basePrice->getMinRegularAmount()->getValue();
            $specialPrice = $_product->getFinalPrice();
        }

        if ($_product->getTypeId() == 'bundle') {
            $regularPrice = $_product->getPriceInfo()->getPrice('regular_price')->getMinimalPrice()->getValue();
            $specialPrice = $_product->getPriceInfo()->getPrice('final_price')->getMinimalPrice()->getValue();
        }

        if ($_product->getTypeId() == 'grouped') {
            $usedProds = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
            foreach ($usedProds as $child) {
                if ($child->getId() != $_product->getId()) {
                    if($regularPrice == 0){
                        $regularPrice = $child->getPrice();
                    }
                    if($regularPrice > $child->getPrice()){
                        $regularPrice = $child->getPrice();
                    }
                    if($specialPrice == 0){
                        $specialPrice = $child->getFinalPrice();
                    }
                    if($specialPrice > $child->getFinalPrice()){
                        $specialPrice = $child->getFinalPrice();
                    }
                }
            }
        }

        $productPrice = $regularPrice;
        if($specialPrice > 0 && $productPrice >= $specialPrice){
            $productPrice = $specialPrice;
        }

        return $productPrice;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencyAmountGrabpay() {
        $currency = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        return $currency." {{amount}}";
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencyGrabpay() {
        $currency = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        return $currency;
    }

}