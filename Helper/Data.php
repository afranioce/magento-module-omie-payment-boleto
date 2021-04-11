<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public function isActive(): bool
    {
        return (bool) $this->scopeConfig->getValue('payment/omie_billet/active', ScopeInterface::SCOPE_STORE, null);
    }

    public function getTitle(): string 
    {
        return (string) $this->scopeConfig->getValue('payment/omie_billet/title', ScopeInterface::SCOPE_STORE, null);
    }

    public function getPaymentBilletInstructions(): string
    {
        return $this->scopeConfig->getValue('payment/omie_billet/instructions', ScopeInterface::SCOPE_STORE, null);
    }

    public function getInterestRate(): float
    {
        return (float) $this->scopeConfig->getValue(
            'payment/omie_billet/interest_rate',
            ScopeInterface::SCOPE_STORE,
            null
        );
    }
    
    public function getMinimumInstallmentValue(): float
    {
        return (float) $this->scopeConfig->getValue(
            'payment/omie_billet/minimum_installment_value',
            ScopeInterface::SCOPE_STORE,
            null
        );
    }
    
    public function getMaximunInstallmentQuantityPhysicalPerson(): int
    {
        return (int) $this->scopeConfig->getValue(
            'payment/omie_billet/maximum_installment_quantity_physical_person',
            ScopeInterface::SCOPE_STORE,
            null
        );
    }
    
    public function getMaximumInstallmentQuantity(): int
    {
        return (int) $this->scopeConfig->getValue(
            'payment/omie_billet/maximum_installment_quantity',
            ScopeInterface::SCOPE_STORE,
            null
        );
    }
}
