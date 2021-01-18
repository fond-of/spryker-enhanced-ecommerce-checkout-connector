<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency;

use Generated\Shared\Transfer\StoreTransfer;

interface EnhancedEcommerceCheckoutConnectorToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
