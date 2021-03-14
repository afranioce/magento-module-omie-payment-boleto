<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Block\Checkout\Success;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Omie\Payment\Boleto\Helper\Data;
use Omie\Payment\Boleto\Model\Method\Boleto;

class Additional extends Template
{
    private Session $checkoutSession;

    private Data $helperData;

    public function __construct(
        Session $checkoutSession,
        Context $context,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
        $this->helperData = $helperData;
    }

    protected function _toHtml()
    {
        $payment = $this->getOrder()->getPayment();

        if (!$payment || $payment->getMethod() !== Boleto::CODE) {
            return '';
        }

        return parent::_toHtml();
    }

    public function getInstructions(): string
    {
        return $this->helperData->getPaymentBilletInstructions();
    }

    private function getOrder(): Order
    {
        return $this->checkoutSession->getLastRealOrder();
    }
}
