<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceCheckoutConnectorFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory
     */
    protected $factory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->integerToDecimalConverter = $this->getMockBuilder(IntegerToDecimalConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new EnhancedEcommerceCheckoutConnectorFactory();
        $this->factory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testGetCartClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->cartClientMock);

        $this->assertInstanceOf(
            EnhancedEcommerceCheckoutConnectorToCartClientInterface::class,
            $this->factory->getCartClient()
        );
    }

    /**
     * @return void
     */
    public function testGetIntegerToDecimalConverter(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->integerToDecimalConverter);

        $this->assertInstanceOf(
            IntegerToDecimalConverterInterface::class,
            $this->factory->getIntegerToDecimalConverter()
        );
    }

    /**
     * @return void
     */
    public function testCreateDataLayerExpander(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->cartClientMock, $this->integerToDecimalConverter,);

        $this->assertInstanceOf(
            EnhancedEcommerceDataLayerExpanderInterface::class,
            $this->factory->createBillingAddressExpander()
        );
    }
}
