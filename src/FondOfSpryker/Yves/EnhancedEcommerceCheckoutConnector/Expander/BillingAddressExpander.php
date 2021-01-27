<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig;
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
    protected $productModel;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig
     */
    protected $config;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface $productModel
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig $config
     */
    public function __construct(
        EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient,
        ProductModelInterface $productModel,
        EnhancedEcommerceCheckoutConnectorConfig $config
    ) {
        $this->cartClient = $cartClient;
        $this->productModel = $productModel;
        $this->config = $config;
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
            ->setEcommerce([ModuleConstants::EVENT_ACTION_CHECKOUT => $this->createEnhancedEcommerceCheckoutTransfer($twigVariableBag)]);

        return $enhancedEcommerceTransfer->toArray(true, true);
    }

    /**
     * @param array $twigVariableBag
     *
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(array $twigVariableBag): array
    {
        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField(['step' => ModuleConstants::STEP_BILLING_ADDRESS]);

        foreach ($this->cartClient->getQuote()->getItems() as $itemTransfer) {
            $enhancedEcommerceCheckoutTransfer->addProduct($this->productModel->createFromItemTransfer($itemTransfer));
        }

        return $this->deleteEmptyIndexesFromDatalayer($enhancedEcommerceCheckoutTransfer->toArray(true, true));
    }

    /**
     * @param array $haystack
     *
     * @return array
     */
    protected function deleteEmptyIndexesFromDatalayer(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->deleteEmptyIndexesFromDatalayer($haystack[$key]);
            }

            if (!$value && !in_array($key, $this->config->getValidCheckoutDatalayerIndex())) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
