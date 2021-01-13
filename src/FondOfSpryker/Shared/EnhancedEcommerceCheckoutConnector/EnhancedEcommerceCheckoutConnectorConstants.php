<?php

namespace FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector;

interface EnhancedEcommerceCheckoutConnectorConstants
{
    public const PAGE_TYPE = 'checkoutBillingAddress';

    public const EVENT_NAME = 'genericEvent';
    public const EVENT_CATEGORY = 'ecommerce';
    public const EVENT_ACTION = 'checkout';

    public const PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED = 'model_untranslated';
    public const PARAM_PRODUCT_ATTR_MODEL = 'model';
    public const PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED = 'style_untranslated';
    public const PARAM_PRODUCT_ATTR_STYLE = 'style';
    public const PARAM_PRODUCT_ATTR_SIZE_UNTRANSLATED = 'size_untranslated';
    public const PARAM_PRODUCT_ATTR_SIZE = 'size';
    public const PARAM_PRODUCT_ATTR_BRAND = 'brand';
}
