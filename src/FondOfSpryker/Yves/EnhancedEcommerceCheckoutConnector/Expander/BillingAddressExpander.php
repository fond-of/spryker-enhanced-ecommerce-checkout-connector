<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;

class BillingAddressExpander implements EnhancedEcommerceDataLayerExpanderInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    private $productModel;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface $productModel
     */
    public function __construct(
        EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient,
        ProductModelInterface $productModel
    ) {
        $this->cartClient = $cartClient;
        $this->productModel = $productModel;
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
        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT_NAME)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_CHECKOUT)
            ->setEventLabel(ModuleConstants::STEP_BILLING_ADDRESS)
            ->setEcommerce([ModuleConstants::EVENT_ACTION_CHECKOUT => $this->createEnhancedEcommerceCheckoutTransfer()]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(): array
    {
        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField(['step' => ModuleConstants::STEP_BILLING_ADDRESS]);

        $enhancedEcommerceCheckoutTransfer = $this->addProductsFromQuote($enhancedEcommerceCheckoutTransfer);

        return $this->removeEmptyArrayIndex($enhancedEcommerceCheckoutTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer $enhancedEcommerceCheckoutTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer
     */
    protected function addProductsFromQuote(
        EnhancedEcommerceCheckoutTransfer $enhancedEcommerceCheckoutTransfer
    ): EnhancedEcommerceCheckoutTransfer {
        foreach ($this->cartClient->getQuote()->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAbstractAttributes() || count($itemTransfer->getAbstractAttributes()) === 0) {
                continue;
            }

            $enhancedEcommerceCheckoutTransfer->addProduct($this->productModel->createFromItemTransfer($itemTransfer));
        }

        return $enhancedEcommerceCheckoutTransfer;
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
