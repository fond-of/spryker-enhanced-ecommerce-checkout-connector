<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Converter;

use InvalidArgumentException;

class IntegerToDecimalConverter implements IntegerToDecimalConverterInterface
{
    public const PRICE_PRECISION = 100;

    /**
     * @param int $value
     *
     * @throws \InvalidArgumentException
     *
     * @return float
     */
    public function convert($value): float
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException(sprintf(
                'Only integer values allowed for conversion to float. Current type is "%s"',
                gettype($value)
            ));
        }

        return (float)bcdiv((string)$value, static::PRICE_PRECISION, 2);
    }
}
