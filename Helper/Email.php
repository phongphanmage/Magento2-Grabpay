<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\GrabPay\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Email
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Email constructor.
     * @param \Vnecoms\GrabPay\Model\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Mirasvit\Report\Model\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $templateIdentifier
     * @param $area
     * @param $fromEmailIdentifier
     * @param $toEmail
     * @param array $templateVars
     * @param array $attachments
     * @param array $copyVars
     * @param int $storeId
     * @param string $scope
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendTransactionEmail(
        $templateIdentifier,
        $area,
        $fromEmailIdentifier,
        $toEmail,
        $templateVars = [],
        $attachments = [],
        $copyVars = [],
        $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID,
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ) {
        $this->inlineTranslation->suspend();
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($templateIdentifier)
            ->setTemplateOptions(
                [
                    'area' => $area,
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom($fromEmailIdentifier)
            ->addTo($toEmail);

        if (count($attachments)) {
            foreach ($attachments as $name => $attachment) {
                $transport->addAttachment(
                    file_get_contents($attachment),
                    \Zend_Mime::TYPE_OCTETSTREAM,
                    \Zend_Mime::DISPOSITION_ATTACHMENT,
                    \Zend_Mime::ENCODING_BASE64,
                    basename($name)
                );
            }

        }

        if (count($copyVars)) {
            foreach ($copyVars as $email) {
                $transport->addCc($email);
            }
        }

        $transport->getTransport()->sendMessage();
        $this->inlineTranslation->resume();
    }
}
