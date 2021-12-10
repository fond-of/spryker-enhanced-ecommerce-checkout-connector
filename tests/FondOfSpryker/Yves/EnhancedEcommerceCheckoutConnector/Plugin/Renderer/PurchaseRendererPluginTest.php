<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PaymentSelectionRenderer;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PurchaseRenderer;
use Generated\Shared\Transfer\OrderTransfer;
use Twig\Environment;

class PurchaseRendererPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\PurchaseRenderer
     */
    protected $rendererMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer\PurchaseRendererPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->factoryMock = $this->getMockBuilder(EnhancedEcommerceCheckoutConnectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->rendererMock = $this->getMockBuilder(PurchaseRenderer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->orderTransferMock = $this->getMockBuilder(OrderTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new PurchaseRendererPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicableTrue(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable(ModuleConstants::PAGE_TYPE_PURCHASE, [
            ModuleConstants::PARAM_ORDER => $this->orderTransferMock
        ]));
    }

    /**
     * @return void
     */
    public function testIsApplicableFalseWrongPageType(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable('somePage', [
            ModuleConstants::PARAM_ORDER => $this->orderTransferMock
        ]));
    }

    /**
     * @return void
     */
    public function testIsApplicableFalseOrderNotSet(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable(ModuleConstants::PAGE_TYPE_PURCHASE, []));
    }

    /**
     * @return void
     */
    public function testIsApplicableFalseOrderNotInstanceOfOrderTranfser(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable(ModuleConstants::PAGE_TYPE_PURCHASE, [
            ModuleConstants::PARAM_ORDER => ''
        ]));
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->factoryMock->expects(static::atLeastOnce())
            ->method('createPaymentSelectionRenderer')
            ->willReturn($this->rendererMock);

        $this->rendererMock->expects(static::atLeastOnce())
            ->method('render')
            ->with($this->twigMock, ModuleConstants::PAGE_TYPE_PURCHASE, [])
            ->willReturn('response as string');

        static::assertEquals('response as string', $this->plugin->render($this->twigMock, ModuleConstants::PAGE_TYPE_PAYMENT_SELECTION, [
            ModuleConstants::PARAM_ORDER => $this->orderTransferMock
        ]));
    }
}
