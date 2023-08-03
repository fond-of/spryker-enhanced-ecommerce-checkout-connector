<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRenderePluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory getFactory()
 */
class PaymentRendererPlugin extends AbstractPlugin implements EnhancedEcommerceRenderePluginInterface
{
    /**
     * @param string $pageType
     * @param array<string, mixed> $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return $pageType === ModuleConstants::PAGE_TYPE_PAYMENT_SELECTION;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array<string, mixed> $twigVariableBag
     *
     * @return string
     */
    public function render(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $this->getFactory()
            ->createPaymentRenderer()
            ->render($twig, $page, $twigVariableBag);
    }
}