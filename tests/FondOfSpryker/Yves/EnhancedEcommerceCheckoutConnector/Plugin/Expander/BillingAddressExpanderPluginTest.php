<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Expander;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;

class BillingAddressExpanderPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    protected $expanderMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderPluginInterface
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->factoryMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expanderMock = $this->getMockBuilder(EnhancedEcommerceDataLayerExpanderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new BillingAddressExpanderPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createBillingAddressExpander')
            ->willReturn($this->expanderMock);

        $this->expanderMock->expects($this->atLeastOnce())
            ->method('expand')
            ->willReturn([]);

        $this->plugin->expand('pageType', [], []);
    }
}
