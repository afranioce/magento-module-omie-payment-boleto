<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Info;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\Info\Instructions;

class Info extends Instructions
{
    /**
     * @var string
     */
    protected $_template = 'Omie_PaymentBoleto::info/info.phtml';

    private ?int $installmentQuantity = null;

    private ?float $installmentAmount = null;

    private PriceCurrencyInterface $priceCurrency;

    public function __construct(
        Context $context,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->priceCurrency = $priceCurrency;
    }

    public function getInstallmentQuantity()
    {
        if ($this->installmentQuantity === null) {
            $this->installmentQuantity = $this->getInfo()->getAdditionalInformation(
                'installment_quantity'
            ) ?: trim($this->getMethod()->getConfigData('installment_quantity'));
        }

        return $this->installmentQuantity;
    }

    public function getInstallmentAmount()
    {
        if ($this->installmentAmount === null) {
            $this->installmentAmount = $this->getInfo()->getAdditionalInformation(
                'installment_amount'
            ) ?: trim($this->getMethod()->getConfigData('installment_amount'));
        }

        return $this->installmentAmount;
    }

    public function getFormatedPrice(float $amount): string
    {
        return $this->priceCurrency->convertAndFormat($amount);
    }
}
