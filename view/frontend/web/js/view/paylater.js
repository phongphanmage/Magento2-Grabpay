/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'uiElement',
    'uiLayout',
    'Vnecoms_GrabPay/js/core/grab-paylater',
    'domReady!'
], function (
    $,
    ko,
    Component,
    layout,
    GrabWidget
) {
    'use strict';

    return Component.extend({

        defaults: {
            template: 'Vnecoms_GrabPay/paylater',
            attributes: {
                class: 'grab-price-divider-widget'
            },
            displayAmount: false,
            amountComponentConfig: {
                name: '${ $.name }.amountProvider',
                component: ''
            }
        },
        grapPay: null,
        currency: null,
        amount: null,

        /**
         * Initialize
         *
         * @returns {*}
         */
        initialize: function () {
            this._super()
                .observe(['amount', 'currency', 'currencyCode']);

            if (this.displayAmount) {
                layout([this.amountComponentConfig]);
            }
            return this;
        },

        /**
         * Get attribute value from configuration
         *
         * @param {String} attributeName
         * @returns {*|null}
         */
        getAttribute: function (attributeName) {
            return typeof this.attributes[attributeName] !== 'undefined' ?
                this.attributes[attributeName] : null;
        },

        /**
         *
         */
        renderMessage: function () {
            GrabWidget.invoke();
        }
    });
});
