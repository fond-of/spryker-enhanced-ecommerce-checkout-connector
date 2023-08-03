<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector;

interface EnhancedEcommerceCheckoutConnectorConstants
{
    /**
     * @var string
     */
    public const PAGE_TYPE_BILLING_ADDRESS = 'checkoutBillingAddress';

    /**
     * @var string
     */
    public const PAGE_TYPE_PAYMENT_SELECTION = 'checkoutPayment';

    /**
     * @var string
     */
    public const PAGE_TYPE_SUMMARY = 'checkoutSummary';

    /**
     * @var string
     */
    public const PAGE_TYPE_PURCHASE = 'purchase';

    /**
     * @var string
     */
    public const EVENT_NAME = 'genericEvent';

    /**
     * @var string
     */
    public const EVENT_CATEGORY = 'ecommerce';

    /**
     * @var string
     */
    public const EVENT_ACTION_CHECKOUT = 'checkout';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE = 'purchase';

    /**
     * @var string
     */
    public const EVENT_ACTION_CHECKOUT_OPTION = 'checkout_option';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_ID = 'id';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_CURRENCY_CODE = 'currencyCode';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_AFFILIATION = 'affiliation';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_REVENUE = 'revenue';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_TAX = 'tax';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_SHIPPING = 'shipping';

    /**
     * @var string
     */
    public const EVENT_ACTION_PURCHASE_FIELD_COUPON = 'coupon';

    /**
     * @var string
     */
    public const PARAM_ORDER = 'order';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_MODEL = 'model';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';

    /**
     * @var string
     */
    public const PARAM_PRODUCT_ATTR_BRAND = 'brand';

    /**
     * @var string
     */
    public const STEP_BILLING_ADDRESS = '1';

    /**
     * @var string
     */
    public const STEP_PAYMENT_SELECTION = '2';

    /**
     * @var string
     */
    public const STEP_SUMMARY = '3';

    /**
     * @var string
     */
    public const PAYMENT_METHODS = 'PAYMENT_METHODS';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT_NAME = 'prepayment';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PREPAYMENT_SELECTION = 'prepaymentPrepayment';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_NAME = 'paypal';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_PAYPAL_SELECTION = 'payoneEWallet';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDITCARD_NAME = 'creditcard';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDITCARD_SELECTION = 'payoneCreditCard';

    /**
     * @var string
     */
    public const VALID_CHECKOUT_DATALAYER_INDEX = 'VALID_CHECKOUT_DATALAYER_INDEX';
}
