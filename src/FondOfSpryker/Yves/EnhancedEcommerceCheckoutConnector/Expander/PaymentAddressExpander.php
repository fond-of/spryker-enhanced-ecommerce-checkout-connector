<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class PaymentAddressExpander implements EnhancedEcommerceDataLayerExpanderInterface
{
    public const UNTRANSLATED_KEY = '_';

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(
        EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient,
        MoneyPluginInterface $moneyPlugin
    ) {
        $this->cartClient = $cartClient;
        $this->moneyPlugin = $moneyPlugin;
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
            ->setEventAction(ModuleConstants::EVENT_ACTION)
            ->setEventLabel(ModuleConstants::STEP_PAYMENT_ADDRESS)
            ->setEcommerce([ModuleConstants::EVENT_ACTION => $this->createEnhancedEcommerceCheckoutTransfer()]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(): array
    {
        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField(['step' => 1]);

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

            $enhancedEcommerceProductTranfer = (new EnhancedEcommerceProductTransfer())
                ->setId($itemTransfer->getSku())
                ->setName($this->getProductName($itemTransfer))
                ->setVariant($this->getProductAttrStyle($itemTransfer))
                ->setBrand($this->getBrand($itemTransfer))
                ->setDimension10($this->getSize($itemTransfer))
                ->setPrice('' . $this->moneyPlugin->convertIntegerToDecimal($itemTransfer->getSumPrice()) . '')
                ->setQuantity($itemTransfer->getQuantity());

            $enhancedEcommerceCheckoutTransfer->addProduct($enhancedEcommerceProductTranfer);
        }

        return $enhancedEcommerceCheckoutTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductName(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL];
        }

        return $itemTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getProductAttrStyle(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getBrand(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getSize(ItemTransfer $itemTransfer): string
    {
        $productAttributes = $itemTransfer->getAbstractAttributes();

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED];
        }

        if (isset($productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE])) {
            return $productAttributes[static::UNTRANSLATED_KEY][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE];
        }

        return '';
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
