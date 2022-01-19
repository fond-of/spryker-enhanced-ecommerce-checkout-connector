<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel;
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
        $this->factory->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testCreatePaymentSelectionRenderer(): void
    {
        $this->containerMock->expects(static::atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects(static::atLeastOnce())
            ->method('get')
            ->willReturn($this->cartClientMock, $this->integerToDecimalConverterMock, $this->localeClientMock, $this->productStorageClientMock);

        $this->factory->createPaymentSelectionRenderer();
    }

    /**
     * @return void
     */
    public function testGetCartClient(): void
    {
        $this->containerMock->expects(static::atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects(static::atLeastOnce())
            ->method('get')
            ->willReturn($this->cartClientMock);

        $this->factory->getCartClient();
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

        $this->factory->getIntegerToDecimalConverter();
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

        $this->factory->getStoreClient();
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

        $this->factory->getProductStorageClient();
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

        $this->factory->getLocaleClient();
    }
}
