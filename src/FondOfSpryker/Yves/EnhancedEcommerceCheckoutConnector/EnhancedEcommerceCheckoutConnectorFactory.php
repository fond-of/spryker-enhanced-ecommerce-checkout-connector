<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander\PaymentAddressExpander;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class EnhancedEcommerceCheckoutConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createPaymentAddressExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new PaymentAddressExpander($this->getCartClient(), $this->getMoneyPlugin());
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    public function getCartClient(): EnhancedEcommerceCheckoutConnectorToCartClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceCheckoutConnectorDependencyProvider::PLUGIN_MONEY);
    }
}
