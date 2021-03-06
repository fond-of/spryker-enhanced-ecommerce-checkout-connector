<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\BillingAddressExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\PaymentSelectionExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\PurchaseExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\SummaryExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PaymentSelectionRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig getConfig()
 */
class EnhancedEcommerceCheckoutConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createBillingAddressExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new BillingAddressExpander($this->getCartClient(), $this->createProductModel(), $this->getConfig());
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createPaymentSelectionExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new PaymentSelectionExpander();
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createPurchaseExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new PurchaseExpander(
            $this->getCartClient(),
            $this->createProductModel(),
            $this->getConfig(),
            $this->getStoreClient(),
            $this->getIntegerToDecimalConverter()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createSummaryExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new SummaryExpander();
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel
     */
    public function createProductModel(): ProductModelInterface
    {
        return new ProductModel(
            $this->getIntegerToDecimalConverter(),
            $this->getLocaleClient(),
            $this->getProductStorageClient()
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
            $this->getConfig()
        );
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
