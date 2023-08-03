<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer;

use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Twig\Environment;

class PaymentRenderer implements EnhancedEcommerceRendererInterface
{
    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array<string, mixed> $twigVariableBag
     *
     * @return string
     */
    public function render(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $twig->render($this->getTemplate(), []);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCheckoutConnector/partials/payment.js.twig';
    }
}
