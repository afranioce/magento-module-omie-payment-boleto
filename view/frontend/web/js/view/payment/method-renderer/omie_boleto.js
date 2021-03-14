/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'mage/url',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'mage/translate',
        'Magento_Catalog/js/price-utils',
    ],
    function (
        $,
        url,
        Component,
        quote,
        $t,
        priceUtils
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Omie_PaymentBoleto/payment/omie_boleto',
                active: false,
                installmentQuantity: 1,
                installments: [],
            },

            initialize: function () {
                this._super();
                this.initInstallments();
            },

            initObservable: function () {
                this._super()
                    .observe({
                        'active': false,
                        'installmentQuantity': 1,
                        'installments': [],
                    });

                return this;
            },

            /**
             * @returns {string}
             */
            getCode: function () {
                return 'omie_boleto';
            },

            isActive: function () {
                var active = this.getCode() === this.isChecked();

                this.active(active);

                return active;
            },

            initInstallments: function () {
                this.installments.removeAll();
                this.getInstallments();
            },

            /**
             * @returns {*}
             */
            getData: function () {
                var data = this._super();
                data.additional_data = {
                    installment_quantity: this.installmentQuantity(),
                    installment_amount: (this.installmentQuantity() - 1) in this.installments()
                        ? this.installments()[(this.installmentQuantity() - 1)]
                        : null
                };

                return data;
            },

            /**
             * Formats a float price using the current active currency
             * @param {Number} price
             * @returns {String|*}
             */
            getFormattedPrice: function (price) {
                return priceUtils.formatPrice(price, quote.getPriceFormat(), true);
            },

            isButtonActive: function () {
                return this.isActive() && this.isPlaceOrderActionAllowed();
            },

            getInstallments: function () {
                var self = this;

                $.ajax({
                    url: url.build('omie/payment/installments'),
                    dataType: 'json',
                    success: function(installments) {
                        for (var i in installments) {
                            var installment = installments[i];

                            var installmentLabel = '';

                            if (installment.numberInstallments == 1) {
                                installmentLabel = self.getFormattedPrice(installment.installmentValue) +
                                    ' ' + $t('in cash');
                            } else {
                                installmentLabel = installment.numberInstallments + ' ' + $t('times of') + ' ' +
                                    self.getFormattedPrice(installment.installmentValue);
                                if (installment.interestsApplied == false) {
                                    installmentLabel = installmentLabel + ' ' + $t('interest free');
                                }
                            }

                            self.installments.push({
                                'installmentAmount': installment.installmentValue,
                                'interestApplied': installment.interestsApplied,
                                'quantity': installment.numberInstallments,
                                'totalAmount': installment.installmentValue,
                                'label': installmentLabel
                            });
                        }
                    },
                    async: false,
                });
            },
        });
    }
);
