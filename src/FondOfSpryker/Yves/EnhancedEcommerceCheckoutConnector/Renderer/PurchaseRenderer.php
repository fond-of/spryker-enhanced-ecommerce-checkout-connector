<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Twig\Environment;

class PurchaseRenderer implements EnhancedEcommerceRendererInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface
     */
    protected $integerToDecimalConverter;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $productModel;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface $productModel
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface $storeClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(
        ProductModelInterface $productModel,
        EnhancedEcommerceCheckoutConnectorToStoreClientInterface $storeClient,
        IntegerToDecimalConverterInterface $integerToDecimalConverter
    ) {
        $this->productModel = $productModel;
        $this->storeClient = $storeClient;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
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
        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT_NAME)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_PURCHASE)
            ->setEventLabel(ModuleConstants::STEP_BILLING_ADDRESS)
            ->setEcommerce([
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_CURRENCY_CODE => $this->storeClient->getCurrentStore()->getSelectedCurrencyIsoCode(),
                ModuleConstants::PAGE_TYPE_PURCHASE => $this->createEnhancedEcommerceCheckoutTransfer($twigVariableBag),
            ]);

        return $twig->render($this->getTemplate(), [
            'enhancedEcommerce' => $enhancedEcommerceTransfer->toArray(true, true),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCheckoutConnector/partials/purchase.js.twig';
    }

    /**
     * @param array $twigVariableBag
     *
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(array $twigVariableBag): array
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $twigVariableBag[ModuleConstants::PARAM_ORDER];

        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField([
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_ID => $orderTransfer->getOrderReference(),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_AFFILIATION => $orderTransfer->getStore(),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_REVENUE => $this->getGrandTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_TAX => $this->getTaxTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_SHIPPING => $this->getShippingTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_COUPON => implode(',', $this->getDiscountCodesFromOrder($orderTransfer)),
            ]);

        foreach ($this->mergeMultipleProducts($orderTransfer) as $itemTransfer) {
            $enhancedEcommerceCheckoutTransfer->addProduct(
                $this->productModel->createFromItemTransfer($itemTransfer)
            );
        }

        return $enhancedEcommerceCheckoutTransfer->toArray(true, true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mergeMultipleProducts(OrderTransfer $orderTransfer): array
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer[] $products */
        $products = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (isset($products[$itemTransfer->getSku()])) {
                continue;
            }

            $products[$itemTransfer->getSku()] = $itemTransfer;
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getDiscountCodesFromOrder(OrderTransfer $orderTransfer): array
    {
        $applicableDiscountCodes = [];

        foreach ($orderTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
            $applicableDiscountCodes[] = $calculatedDiscountTransfer->getVoucherCode();
        }

        return $applicableDiscountCodes;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return float
     */
    protected function getShippingTotal(OrderTransfer $orderTransfer): float
    {
        if ($orderTransfer->getTotals() === null) {
            return 0;
        }

        if ($orderTransfer->getTotals()->getShipmentTotal() === null) {
            return 0;
        }

        return $this->integerToDecimalConverter->convert($orderTransfer->getTotals()->getShipmentTotal());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return float
     */
    protected function getTaxTotal(OrderTransfer $orderTransfer): float
    {
        if ($orderTransfer->getTotals() === null) {
            return 0;
        }

        if ($orderTransfer->getTotals()->getTaxTotal() === null) {
            return 0;
        }

        if ($orderTransfer->getTotals()->getTaxTotal()->getAmount() === null) {
            return 0;
        }

        return $this->integerToDecimalConverter->convert($orderTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return float
     */
    protected function getGrandTotal(OrderTransfer $orderTransfer): float
    {
        if ($orderTransfer->getTotals() === null) {
            return 0;
        }

        if ($orderTransfer->getTotals()->getGrandTotal() === null) {
            return 0;
        }

        return $this->integerToDecimalConverter->convert($orderTransfer->getTotals()->getGrandTotal());
    }
}