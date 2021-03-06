<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceCheckoutConnectorFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverterMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $productModelMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory
     */
    protected $factory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $configMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
     */
    protected $localeClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
     */
    protected $productStorageClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface
     */
    protected $storageClientMock;

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

        $this->integerToDecimalConverterMock = $this->getMockBuilder(IntegerToDecimalConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToLocaleClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productStorageClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productModelMock = $this->getMockBuilder(ProductModel::class)
            ->setConstructorArgs([$this->integerToDecimalConverterMock, $this->localeClientMock, $this->productStorageClientMock])
            ->getMock();

        $this->storageClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToStoreClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new EnhancedEcommerceCheckoutConnectorFactory();
        $this->factory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testCreatePaymentSelectionExpander(): void
    {
        $this->assertInstanceOf(
            EnhancedEcommerceDataLayerExpanderInterface::class,
            $this->factory->createPaymentSelectionExpander()
        );
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
            ->willReturn($this->integerToDecimalConverterMock);

        $this->assertInstanceOf(
            IntegerToDecimalConverterInterface::class,
            $this->factory->getIntegerToDecimalConverter()
        );
    }

    /**
     * @return void
     */
    public function testGetStoreClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->storageClientMock);

        $this->assertInstanceOf(
            EnhancedEcommerceCheckoutConnectorToStoreClientInterface::class,
            $this->factory->getStoreClient()
        );
    }

    /**
     * @return void
     */
    public function testGetProductStorageClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->productStorageClientMock);

        $this->assertInstanceOf(
            EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface::class,
            $this->factory->getProductStorageClient()
        );
    }

    /**
     * @return void
     */
    public function testGetLocaleClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->localeClientMock);

        $this->assertInstanceOf(
            EnhancedEcommerceCheckoutConnectorToLocaleClientInterface::class,
            $this->factory->getLocaleClient()
        );
    }
}
