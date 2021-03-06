<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Plugin\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorFactory getFactory()
 */
class PaymentSelectionExpanderPlugin extends AbstractPlugin implements EnhancedEcommerceDataLayerExpanderPluginInterface
{
    /**
     * @param string $pageType
     * @param array $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return $pageType === EnhancedEcommerceCheckoutConnectorConstants::PAGE_TYPE_PAYMENT_SELECTION;
    }

    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array
    {
        return $this->getFactory()
            ->createPaymentSelectionExpander()
            ->expand($page, $twigVariableBag, $dataLayer);
    }
}
