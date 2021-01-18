<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface ProductModelInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function createFromItemTransfer(ItemTransfer $itemTransfer): EnhancedEcommerceProductTransfer;
}
