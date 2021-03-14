<?php

declare(strict_types=1);

namespace Omie\Payment\Boleto\Model\Method;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Payment\Block\Form;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Omie\Payment\Boleto\Helper\InstallmentsCalculator;
use Omie\Payment\Boleto\Info\Info;

class Boleto extends AbstractMethod
{
    public const CODE = 'omie_boleto';

    protected $_code = self::CODE;

    protected $_formBlockType = Form::class;

    protected $_infoBlockType = Info::class;

    protected $_isOffline = true;

    protected $_canOrder = true;

    protected $_canCapture = true;

    protected $_canCapturePartial = true;

    protected $_canRefund = true;

    protected $_canRefundInvoicePartial = true;

    protected $_canAuthorize = true;

    protected $_canVoid = true;

    private InstallmentsCalculator $installmentsCalculator;

    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Data $paymentData,
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
        InstallmentsCalculator $installmentsCalculator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory
        );

        $this->installmentsCalculator = $installmentsCalculator;
    }

    public function getInstructions(): string
    {
        return trim($this->getConfigData('instructions'));
    }

    public function assignData(DataObject $data)
    {
        parent::assignData($data);

        $info = $this->getInfoInstance();
        $installmentQuantity = $data->getAdditionalData('installment_quantity')
            ? (int) $data->getAdditionalData('installment_quantity')
            : 1;
        $expirationDays = $data->getAdditionalData('expiration_days')
            ? (int) $data->getAdditionalData('expiration_days')
            : 1;

        $info->setAdditionalInformation('installment_quantity', $installmentQuantity);
        $info->setAdditionalInformation('expiration_days', $expirationDays);
        $info->setAdditionalInformation('installment_amount', (float) $data->getAdditionalData('installment_amount'));

        $this->installmentsCalculator->setInstallmentDataBeforeAuthorization($installmentQuantity);

        return $this;
    }
}
