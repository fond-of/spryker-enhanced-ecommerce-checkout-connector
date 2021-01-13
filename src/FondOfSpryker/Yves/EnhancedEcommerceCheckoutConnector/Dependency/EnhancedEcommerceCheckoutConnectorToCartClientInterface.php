<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency;

use Generated\Shared\Transfer\QuoteTransfer;

interface EnhancedEcommerceCheckoutConnectorToCartClientInterface
{
    /**
     * Specification:
     *  - Gets current quote from session
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote(): QuoteTransfer;
}
