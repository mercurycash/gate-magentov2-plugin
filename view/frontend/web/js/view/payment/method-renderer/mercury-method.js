define([
    'ko',
    'Magento_Checkout/js/view/payment/default'
], function (ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mercury_Payment/payment/mercury',
            transactionResult: ''
        },

        initObservable: function () {
            this._super()
                .observe([
                    'transactionResult'
                ]);

            return this;
        },

        getCode: function() {
            return 'mercurypayment';
        },

        getData: function() {
            return {
                'method': this.item.method,
                'additional_data': {
                    'transaction_result': this.transactionResult()
                }
            };
        },

        getTransactionResults: function() {
            return _.map(window.checkoutConfig.payment.mercurypayment.transactionResults, function(value, key) {
                return {
                    'value': key,
                    'transaction_result': value
                }
            });
        }
    });
});
