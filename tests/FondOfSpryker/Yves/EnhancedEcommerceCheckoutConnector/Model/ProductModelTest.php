<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
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
     * @var ProductModelInterface
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

        $this->model = new ProductModel($this->integerToDecimalConverter);
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

        $this->model->createFromItemTransfer($this->itemTransferMock);
    }
}
