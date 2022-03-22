<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Vnecoms\GrabPay\Block\PayLater;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

/**
 * PayLater Layout Processor
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * Checkout payment page placement
     */
    private const PLACEMENT = 'payment';

    /**
     * @var PayLaterConfig|\Vnecoms\GrabPay\Model\OneTime\Pay
     */
    private $payLaterConfig;

    /**
     * LayoutProcessor constructor.
     * @param \Vnecoms\GrabPay\Model\OneTime\Pay $payLaterConfig
     */
    public function __construct(
        \Vnecoms\GrabPay\Model\OneTime\Pay $payLaterConfig
    )
    {
        $this->payLaterConfig = $payLaterConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function process($jsLayout)
    {
        if (!$this->payLaterConfig->getConfigValue("allow_paylater")) {
            unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
                ['children']['payment']['children']['payments-list']['children']['grabpay-method-extra-content']['children']
                ['paylater-place-order']);

            return $jsLayout;
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['payments-list']['children']['grabpay-method-extra-content']['children']
            ['paylater-place-order'])
        ) {
            $payLaterPlaceOrder = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['payments-list']['children']['grabpay-method-extra-content']['children']
            ['paylater-place-order'];

            $componentConfig = $payLaterPlaceOrder['config'] ?? [];
            $defaultConfig = [
                'displayAmount' => true,
                'amountComponentConfig' => [
                    'component' => 'Vnecoms_GrabPay/js/view/amountProviders/checkout'
                ]
            ];
            $config = array_replace($defaultConfig, $componentConfig);

            $componentAttributes = $payLaterPlaceOrder['config']['attributes'] ?? [];
            $componentAttributes["data-min-price"] = $this->payLaterConfig->getConfigValue("min_price_total");
            $componentAttributes["data-max-price"] = $this->payLaterConfig->getConfigValue("max_price_total");
            
            $config['attributes'] = $componentAttributes;

            $payLaterPlaceOrder['config'] = $config;
        }

        return $jsLayout;
    }
}
