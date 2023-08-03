<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientBridge;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductModelTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
     */
    protected $localeClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
     */
    protected $productStorageClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer
     */
    protected $calculatedDiscountTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $model;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->integerToDecimalConverter = $this->getMockBuilder(IntegerToDecimalConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToLocaleClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productStorageClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToProductStorageClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->calculatedDiscountTransferMock = $this->getMockBuilder(CalculatedDiscountTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = new ProductModel(
            $this->integerToDecimalConverter,
            $this->localeClientMock,
            $this->productStorageClientMock,
        );
    }

    /**
     * @return void
     */
    public function testCreateFromItemTransfer(): void
    {
        $this->itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku')
            ->willReturn('SKU-XXX-XXX');

        $this->itemTransferMock->method('getName')
            ->willReturn('PRODUCT_NAME');

        $this->itemTransferMock->expects($this->atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn(1);

        $this->itemTransferMock->expects($this->atLeastOnce())
            ->method('getCalculatedDiscounts')
            ->willReturn([$this->calculatedDiscountTransferMock]);

        $this->calculatedDiscountTransferMock->expects($this->atLeastOnce())
            ->method('getVoucherCode')
            ->willReturn('DISCOUNT_CODE');

        $this->model->createFromItemTransfer($this->itemTransferMock);
    }
}
