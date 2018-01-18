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
                type: 'mypayment',
                component: 'Fcamodule_Mypayment/js/view/payment/method-renderer/mypayment'
            }
        );
        return Component.extend({});
    }
);