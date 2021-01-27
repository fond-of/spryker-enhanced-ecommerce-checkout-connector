<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductModel implements ProductModelInterface
{
    public const UNTRANSLATED_KEY = '_';

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var string
     */
    protected $currentLocale;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToLocaleClientInterface $localeClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        IntegerToDecimalConverterInterface $integerToDecimalConverter,
        EnhancedEcommerceCheckoutConnectorToLocaleClientInterface $localeClient,
        EnhancedEcommerceCheckoutConnectorToProductStorageClientInterface $productStorageClient
    ) {
        $this->integerToDecimalConverter = $integerToDecimalConverter;
        $this->localeClient = $localeClient;
        $this->currentLocale = $this->localeClient->getCurrentLocale();
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function createFromItemTransfer(ItemTransfer $itemTransfer): EnhancedEcommerceProductTransfer
    {
        $itemTransfer = $this->addLocalizedAbstractAttributesToItemTransfer($itemTransfer);

        return (new EnhancedEcommerceProductTransfer())
            ->setId($itemTransfer->getSku())
            ->setName($this->getProductName($itemTransfer))
            ->setVariant($this->getProductAttrStyle($itemTransfer))
            ->setBrand($this->getBrand($itemTransfer))
            ->setDimension10($this->getSize($itemTransfer))
            ->setPrice('' . $this->integerToDecimalConverter->convert($itemTransfer->getSumPrice()) . '')
            ->setQuantity($itemTransfer->getQuantity())
            ->setCoupon(implode(',', $this->getDiscountCodes($itemTransfer)));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addLocalizedAbstractAttributesToItemTransfer(ItemTransfer $itemTransfer): ItemTransfer
    {
        if (!$itemTransfer->getAbstractAttributes() || count($itemTransfer->getAbstractAttributes()) === 0) {
            $productData = $this->productStorageClient->findProductAbstractStorageData(
                $itemTransfer->getIdProductAbstract(),
                $this->localeClient->getCurrentLocale()
            );

            if (isset($productData[ModuleConstants::PARAM_PRODUCT_ATTRIBUTES])) {
                $itemTransfer->setAbstractAttributes([
                    $this->localeClient->getCurrentLocale() => $productData[ModuleConstants::PARAM_PRODUCT_ATTRIBUTES],
                ]);
            }

        }

        return $itemTransfer;
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

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL];
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

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE];
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

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_BRAND];
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

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED];
        }

        if (isset($productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE])) {
            return $productAttributes[$this->currentLocale][ModuleConstants::PARAM_PRODUCT_ATTR_SIZE];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getDiscountCodes(ItemTransfer $itemTransfer): array
    {
        $applicableDiscountCodes = [];

        foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $applicableDiscountCodes[] = $calculatedDiscountTransfer->getVoucherCode();
        }

        return $applicableDiscountCodes;
    }
}
