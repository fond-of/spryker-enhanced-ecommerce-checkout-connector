<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency;

use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge implements EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage.
     * - Responds with null if product abstract is restricted.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
    }
}
