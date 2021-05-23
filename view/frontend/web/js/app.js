define([
    "jquery",
    "Mercury_Payment/js/react/build/static/js/main.a09f256b"
], function ($) {
    "use strict";

    /**
     * @param {Object} config
     */
    return function (config) {
        let merParam = {};

        $("body").on("click", "#mercurypayment", function() {
            if (typeof config != "undefined") {
                merParam = config;
            }

            let sdk = new window.MercurySDK({
                staticUrl: merParam.url,
                checkoutUrl: merParam.pathCreateTransaction,
                statusUrl: merParam.pathCheckTransaction,
                checkStatusInterval: parseInt(merParam.time, 2),
                mount: "#mercury-cash",
                lang: "en",
                limits: {
                    BTC: parseFloat(merParam.btc),
                    ETH: parseFloat(merParam.eth),
                    DASH: parseFloat(merParam.dash)
                }
            });

            sdk.checkout(parseFloat(merParam.cart_price), merParam.currency, merParam.email);

            sdk.on("close", (obj) => {
                if (obj.status && (obj.status === "TRANSACTION_APROVED")) {
                    jQuery('#checkout_mercury_place_order').trigger("click");
                } else {
                    location.reload();
                }
            });
        });
    };
});
