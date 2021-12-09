/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @api */

define([
    'jquery',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/action/place-order',
    'Magento_Checkout/js/model/payment/additional-validators',
], function ($,
             Component,
             placeOrderAction,
             additionalValidators,
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Vnecoms_GrabPay/payments/grabpay'
        },

        /** Returns payment acceptance mark image path */
        getPaymentAcceptanceMarkSrc: function () {
            return window.checkoutConfig.payment.grabpay.paymentAcceptanceMarkSrc;
        },

        getInstructions: function () {
            return window.checkoutConfig.payment.instructions[this.item.method];
        },

        getData: function () {
            return {
                "method": 'grabpay',
                "additional_data": {
                }
            };
        },

        continueToGrabPay: function (data, event) {
            if (event) {
                event.preventDefault();
            }
            var self = this,
                placeOrder;
            if (additionalValidators.validate()) {
                this.isPlaceOrderActionAllowed(false);
                placeOrder = placeOrderAction(this.getData(), false, this.messageContainer);

                $.when(placeOrder).fail(function () {
                    self.isPlaceOrderActionAllowed(true);
                }).done(this.afterPlaceOrder.bind(this));
                return true;
            }
            return false;
        },

        afterPlaceOrder: function () {
            var method = this.getCode();
            var urlRedirect = window.checkoutConfig.payment[method].redirectUrl;
            window.location.replace(urlRedirect);
        }

    });
});