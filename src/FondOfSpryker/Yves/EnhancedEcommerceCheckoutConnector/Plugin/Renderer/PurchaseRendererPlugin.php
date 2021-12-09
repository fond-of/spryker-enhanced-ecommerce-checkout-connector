<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRenderePluginInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory getFactory()
 */
class PurchaseRendererPlugin extends AbstractPlugin implements EnhancedEcommerceRenderePluginInterface
{
    /**
     * @param string $pageType
     * @param array $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return $pageType === ModuleConstants::PAGE_TYPE_PURCHASE &&
            isset($twigVariableBag[ModuleConstants::PARAM_ORDER]) &&
            $twigVariableBag[ModuleConstants::PARAM_ORDER] instanceof OrderTransfer;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function render(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $this->getFactory()
            ->createPurchaseRenderer()
            ->render($twig, $page, $twigVariableBag);
    }
}
