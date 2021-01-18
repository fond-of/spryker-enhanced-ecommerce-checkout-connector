<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class PurchaseExpander extends BillingAddressExpander implements EnhancedEcommerceDataLayerExpanderInterface
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
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface $productModel
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig $config
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToStoreClientInterface $storeClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter\IntegerToDecimalConverterInterface $integerToDecimalConverter
     */
    public function __construct(
        EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient,
        ProductModelInterface $productModel,
        EnhancedEcommerceCheckoutConnectorConfig $config,
        EnhancedEcommerceCheckoutConnectorToStoreClientInterface $storeClient,
        IntegerToDecimalConverterInterface $integerToDecimalConverter
    ) {
        parent::__construct($cartClient, $productModel, $config);

        $this->storeClient = $storeClient;
        $this->integerToDecimalConverter = $integerToDecimalConverter;
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
            ->setEventAction(ModuleConstants::EVENT_ACTION_PURCHASE)
            ->setEventLabel(ModuleConstants::STEP_BILLING_ADDRESS)
            ->setEcommerce([
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_CURRENCY_CODE => $this->storeClient->getCurrentStore()->getSelectedCurrencyIsoCode(),
                ModuleConstants::PAGE_TYPE_PURCHASE => $this->createEnhancedEcommerceCheckoutTransfer($twigVariableBag),
            ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @param array $twigVariableBag
     *
     * @return array
     */
    protected function createEnhancedEcommerceCheckoutTransfer(array $twigVariableBag): array
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $twigVariableBag['order'];

        $enhancedEcommerceCheckoutTransfer = (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField([
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_ID => $orderTransfer->getOrderReference(),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_AFFILIATION => $orderTransfer->getStore(),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_REVENUE => $this->getGrandTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_TAX => $this->getTaxTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_SHIPPING => $this->getShippingTotal($orderTransfer),
                ModuleConstants::EVENT_ACTION_PURCHASE_FIELD_COUPON => implode(',', $this->getDiscountCodesFromOrder($orderTransfer)),
            ]);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $enhancedEcommerceCheckoutTransfer->addProduct($this->productModel->createFromItemTransfer($itemTransfer));
        }

        return $this->deleteEmptyIndexesFromDatalayer($enhancedEcommerceCheckoutTransfer->toArray());
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