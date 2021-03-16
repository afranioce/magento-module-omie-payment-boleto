<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Controller\Payment;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Omie\Payment\Boleto\Helper\InstallmentsCalculatorInterface;

class Installments implements HttpGetActionInterface
{
    private CheckoutSession $checkoutSession;

    private InstallmentsCalculatorInterface $installmentsCalculator;

    private JsonFactory $resultJsonFactory;

    public function __construct(
        CheckoutSession $checkoutSession,
        InstallmentsCalculatorInterface $installmentsCalculator,
        JsonFactory $resultJsonFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->installmentsCalculator = $installmentsCalculator;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $installments = $this->installmentsCalculator->getInstallments($this->getPaymentAmount());

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($installments);

        return $resultJson;
    }

    protected function getPaymentAmount(): float
    {
        return $this->checkoutSession->getQuote()->getGrandTotal();
    }
}
