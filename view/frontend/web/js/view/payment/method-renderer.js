define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'mercurypayment',
            component: 'Mercury_Payment/js/view/payment/method-renderer/mercury-method'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
