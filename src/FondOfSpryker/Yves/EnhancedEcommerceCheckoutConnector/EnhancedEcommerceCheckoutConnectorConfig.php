<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class EnhancedEcommerceCheckoutConnectorConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getPaymentMethodMapping(): array
    {
        return $this->get(ModuleConstants::PAYMENT_METHODS, [
            ModuleConstants::PAYMENT_METHOD_PREPAYMENT_SELECTION => ModuleConstants::PAYMENT_METHOD_PREPAYMENT_NAME,
            ModuleConstants::PAYMENT_METHOD_PAYPAL_SELECTION => ModuleConstants::PAYMENT_METHOD_PAYPAL_NAME,
            ModuleConstants::PAYMENT_METHOD_CREDITCARD_SELECTION => ModuleConstants::PAYMENT_METHOD_CREDITCARD_NAME,
        ]);
    }
}
