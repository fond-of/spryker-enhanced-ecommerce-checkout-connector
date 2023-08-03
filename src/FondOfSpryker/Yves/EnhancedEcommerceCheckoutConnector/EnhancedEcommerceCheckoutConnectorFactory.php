<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\BillingAddressRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PaymentRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PaymentSelectionRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PurchaseRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\SummaryRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig getConfig()
 */
class EnhancedEcommerceCheckoutConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createBillingAddressRenderer(): EnhancedEcommerceRendererInterface
    {
        return new BillingAddressRenderer($this->getCartClient(), $this->createProductModel(), $this->getConfig());
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createPurchaseRenderer(): EnhancedEcommerceRendererInterface
    {
        return new PurchaseRenderer(
            $this->createProductModel(),
            $this->getStoreClient(),
            $this->getIntegerToDecimalConverter(),
        );
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createSummaryRenderer(): EnhancedEcommerceRendererInterface
    {
        return new SummaryRenderer();
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel
     */
    public function createProductModel(): ProductModelInterface
    {
        return new ProductModel(
            $this->getIntegerToDecimalConverter(),
            $this->getLocaleClient(),
            $this->getProductStorageClient(),
        );
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createPaymentSelectionRenderer(): EnhancedEcommerceRendererInterface
    {
        return new PaymentSelectionRenderer(
            $this->getCartClient(),
            $this->createProductModel(),
            $this->getConfig(),
        );
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createPaymentRenderer(): EnhancedEcommerceRendererInterface
    {
        return new PaymentRenderer();
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    public function getCartClient(): EnhancedEcommerceCheckoutConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface
     */
    public function getIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::CONVERTER_INTERGER_TO_DECIMAL);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface
     */
    public function getStoreClient(): EnhancedEcommerceCheckoutConnectorToStoreClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::STORE_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
     */
    public function getProductStorageClient(): EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
     */
    public function getLocaleClient(): EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::LOCALE_CLIENT);
    }
}
