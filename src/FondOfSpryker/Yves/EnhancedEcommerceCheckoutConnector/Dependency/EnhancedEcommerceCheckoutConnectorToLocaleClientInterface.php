<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency;

interface EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale(): string;
}
