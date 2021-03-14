<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Payment\Model\MethodInterface;
use Omie\Payment\Boleto\Helper\Data;

class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'omie_boleto';

    private Data $dataHelper;

    private MethodInterface $method;

    private Escaper $escaper;

    public function __construct(
        Data $dataHelper,
        PaymentHelper $paymentHelper,
        Escaper $escaper
    ) {
        $this->dataHelper = $dataHelper;
        $this->method = $paymentHelper->getMethodInstance(self::CODE);
        $this->escaper = $escaper;
    }

    public function getConfig(): array
    {
        return $this->method->isAvailable() ? [
            'payment' => [
                self::CODE => [
                    'isActive' => (bool) $this->dataHelper->getPaymentConfig('active'),
                    'title' => $this->escaper->escapeHtml($this->dataHelper->getPaymentConfig('title')),
                ],
            ],
        ] : [];
    }
}
