<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\BillingAddressRenderer;
use Twig\Environment;

class BillingAddressRendererPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer\BillingAddressRenderer
     */
    protected $rendererMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer\BillingAddressRendererPlugin
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

        $this->rendererMock = $this->getMockBuilder(BillingAddressRenderer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new BillingAddressRendererPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicableTrue(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable(ModuleConstants::PAGE_TYPE_BILLING_ADDRESS, []));
    }

    /**
     * @return void
     */
    public function testIsApplicableFalse(): void
    {
        static::assertEquals(true, $this->plugin->isApplicable('somePage', []));
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->factoryMock->expects(static::atLeastOnce())
            ->method('createBillingAddressRenderer')
            ->willReturn($this->rendererMock);

        $this->rendererMock->expects(static::atLeastOnce())
            ->method('render')
            ->with($this->twigMock, ModuleConstants::PAGE_TYPE_BILLING_ADDRESS, [])
            ->willReturn('response as string');

        static::assertEquals('response as string', $this->plugin->render($this->twigMock, ModuleConstants::PAGE_TYPE_BILLING_ADDRESS, []));
    }
}
