<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;

class SummaryExpander implements EnhancedEcommerceDataLayerExpanderInterface
{
    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array
    {
        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT_NAME)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_CHECKOUT)
            ->setEventLabel(ModuleConstants::STEP_SUMMARY)
            ->setEcommerce([ModuleConstants::EVENT_ACTION_CHECKOUT => $this->createEnhancedEcommerceCheckoutTransfer()]);

        return $enhancedEcommerceTransfer->toArray(true, true);
    }

    /**
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(): array
    {
        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField(['step' => ModuleConstants::STEP_SUMMARY]);

        return $this->removeEmptyArrayIndex($enhancedEcommerceCheckoutTransfer->toArray(true, true));
    }

    /**
     * @param array $haystack
     *
     * @return array
     */
    protected function removeEmptyArrayIndex(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeEmptyArrayIndex($haystack[$key]);
            }

            if (!$value) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
