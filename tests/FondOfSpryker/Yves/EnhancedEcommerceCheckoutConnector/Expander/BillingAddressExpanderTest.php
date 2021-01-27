<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModel;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BillingAddressExpanderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $productModelMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    protected $expander;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ItemTransfer
     */
    protected $itemTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCartConnector\EnhancedEcommerceCartConnectorConfig
     */
    protected $configMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->cartClientMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productModelMock = $this->getMockBuilder(ProductModelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productModelMock = $this->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expander = new BillingAddressExpander(
            $this->cartClientMock,
            $this->productModelMock,
            $this->configMock
        );
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn([$this->itemTransferMock, $this->itemTransferMock, $this->itemTransferMock]);

        $result = $this->expander->expand('pageType', [], []);

        $this->assertArrayHasKey('event', $result);
        $this->assertArrayHasKey('eventCategory', $result);
        $this->assertArrayHasKey('eventAction', $result);
        $this->assertArrayHasKey('eventLabel', $result);
        $this->assertArrayHasKey('ecommerce', $result);
        $this->assertArrayHasKey('checkout', $result['ecommerce']);
        $this->assertArrayHasKey('actionField', $result['ecommerce']['checkout']);
        $this->assertArrayHasKey('step', $result['ecommerce']['checkout']['actionField']);
    }
}
