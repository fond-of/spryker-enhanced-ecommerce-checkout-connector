<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector;

interface EnhancedEcommerceCheckoutConnectorConstants
{
    public const PAGE_TYPE_BILLING_ADDRESS = 'checkoutBillingAddress';
    public const PAGE_TYPE_PAYMENT_SELECTION = 'checkoutPayment';
    public const PAGE_TYPE_SUMMARY = 'checkoutSummary';
    public const PAGE_TYPE_PURCHASE = 'purchase';

    public const EVENT_NAME = 'genericEvent';
    public const EVENT_CATEGORY = 'ecommerce';
    public const EVENT_ACTION_CHECKOUT = 'checkout';
    public const EVENT_ACTION_PURCHASE = 'purchase';
    public const EVENT_ACTION_CHECKOUT_OPTION = 'checkout_option';

    public const EVENT_ACTION_PURCHASE_FIELD_ID = 'id';
    public const EVENT_ACTION_PURCHASE_FIELD_CURRENCY_CODE = 'currencyCode';
    public const EVENT_ACTION_PURCHASE_FIELD_AFFILIATION = 'affiliation';
    public const EVENT_ACTION_PURCHASE_FIELD_REVENUE = 'revenue';
    public const EVENT_ACTION_PURCHASE_FIELD_TAX = 'tax';
    public const EVENT_ACTION_PURCHASE_FIELD_SHIPPING = 'shipping';
    public const EVENT_ACTION_PURCHASE_FIELD_COUPON = 'coupon';

    public const PARAM_ORDER = 'order';
    public const PARAM_PRODUCT_ATTRIBUTES = 'attributes';
    public const PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';
    public const PARAM_PRODUCT_ATTR_MODEL = 'model';
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';
    public const PARAM_PRODUCT_ATTR_BRAND = 'brand';

    public const STEP_BILLING_ADDRESS = '1';
    public const STEP_PAYMENT_SELECTION = '2';
    public const STEP_SUMMARY = '3';

    public const PAYMENT_METHODS = 'PAYMENT_METHODS';
    public const PAYMENT_METHOD_PREPAYMENT_NAME = 'prepayment';
    public const PAYMENT_METHOD_PREPAYMENT_SELECTION = 'prepaymentPrepayment';
    public const PAYMENT_METHOD_PAYPAL_NAME = 'paypal';
    public const PAYMENT_METHOD_PAYPAL_SELECTION = 'payoneEWallet';
    public const PAYMENT_METHOD_CREDITCARD_NAME = 'creditcard';
    public const PAYMENT_METHOD_CREDITCARD_SELECTION = 'payoneCreditCard';

    public const VALID_CHECKOUT_DATALAYER_INDEX = 'VALID_CHECKOUT_DATALAYER_INDEX';
}
