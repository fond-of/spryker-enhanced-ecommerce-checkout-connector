<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\StoreClientInterface;

class EnhancedEcommerceCheckoutConnectorToStoreClientBridge implements EnhancedEcommerceCheckoutConnectorToStoreClientInterface
{
    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(StoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer
    {
        return $this->storeClient->getCurrentStore();
    }
}
