define([
    'jquery',
], function ($, MercurySDK) {
    'use strict';

    /**
     * @param {Object} config
     */
    return function (config) {
        let merParam = {};

        $('body').on('click', '#mercurypayment', function() {
            if (typeof config != "undefined") {
                merParam = config;
            }

            console.log(MercurySDK);

            let sdk = new MercurySDK({
                checkoutUrl: merParam.pathCreateTransaction,
                statusUrl: merParam.pathCheckTransaction,
                checkStatusInterval: parseInt(merParam.time, 2),
                mount: "#mercury-cash",
                lang: "en",
                limits: {
                    BTC: merParam.btc,
                    ETH: merParam.eth,
                    DASH: merParam.dash
                }
            });

            sdk.checkout(merParam.cart_price, merParam.currency, merParam.email);

            sdk.on("close", (obj) => {
                if (obj.status && (obj.status === "TRANSACTION_APROVED")) {

                } else {
                    location.reload();
                }
            });
        });
    };
});
