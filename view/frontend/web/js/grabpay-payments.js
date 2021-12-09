
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'grabpay',
                component: 'Vnecoms_GrabPay/js/payments/grabpay'
            }
        );
        return Component.extend({});
    }
);