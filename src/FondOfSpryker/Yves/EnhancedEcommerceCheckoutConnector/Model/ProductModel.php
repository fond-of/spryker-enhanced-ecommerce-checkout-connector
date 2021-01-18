<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
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
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(IntegerToDecimalConverterInterface $integerToDecimalConverter)
    {
        $this->integerToDecimalConverter = $integerToDecimalConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function createFromItemTransfer(ItemTransfer $itemTransfer): EnhancedEcommerceProductTransfer
    {
        return (new EnhancedEcommerceProductTransfer())
            ->setId($itemTransfer->getSku())
            ->setName($this->getProductName($itemTransfer))
            ->setVariant($this->getProductAttrStyle($itemTransfer))
            ->setBrand($this->getBrand($itemTransfer))
            ->setDimension10($this->getSize($itemTransfer))
            ->setPrice('' . $this->integerToDecimalConverter->convert($itemTransfer->getSumPrice()) . '')
            ->setQuantity($itemTransfer->getQuantity());
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
}
