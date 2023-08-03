<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter;

class IntegerToDecimalConverter implements IntegerToDecimalConverterInterface
{
    /**
     * @var int
     */
    public const PRICE_PRECISION = 100;

    /**
     * @param int $value
     *
     * @return float
     */
    public function convert(int $value): float
    {
        return (float)bcdiv(
            (string)$value,
            (string)static::PRICE_PRECISION,
            2,
        );
    }
}
