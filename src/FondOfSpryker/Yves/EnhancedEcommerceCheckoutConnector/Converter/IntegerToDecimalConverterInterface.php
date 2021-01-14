<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter;

interface IntegerToDecimalConverterInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convert($value);
}
