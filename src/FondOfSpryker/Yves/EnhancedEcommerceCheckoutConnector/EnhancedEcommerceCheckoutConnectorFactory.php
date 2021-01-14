<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\BillingAddressExpander;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\PaymentSelectionExpander;
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
        return new BillingAddressExpander($this->getCartClient(), $this->createProductModel());
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
    public function createSummaryExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new SummaryExpander();
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel
     */
    public function createProductModel(): ProductModelInterface
    {
        return new ProductModel($this->getIntegerToDecimalConverter());
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

    public function getIntegerToDecimalConverter(): IntegerToDecimalConverterInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::CONVERTER_INTERGER_TO_DECIMAL);
    }
}
