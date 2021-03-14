/*browser:true*/
/*global define*/
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
                type: 'omie_boleto',
                component: "Omie_PaymentBoleto/js/view/payment/method-renderer/omie_boleto"
            }
        );

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
