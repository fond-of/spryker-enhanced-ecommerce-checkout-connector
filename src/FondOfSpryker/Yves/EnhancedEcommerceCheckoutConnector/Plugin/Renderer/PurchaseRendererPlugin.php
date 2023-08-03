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
     * @param array<string, mixed> $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        if ($pageType !== ModuleConstants::PAGE_TYPE_PURCHASE) {
            return false;
        }

        if (!isset($twigVariableBag[ModuleConstants::PARAM_ORDER])) {
            return false;
        }

        if (!$twigVariableBag[ModuleConstants::PARAM_ORDER] instanceof OrderTransfer) {
            return false;
        }

        return true;
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
            ->createPurchaseRenderer()
            ->render($twig, $page, $twigVariableBag);
    }
}
