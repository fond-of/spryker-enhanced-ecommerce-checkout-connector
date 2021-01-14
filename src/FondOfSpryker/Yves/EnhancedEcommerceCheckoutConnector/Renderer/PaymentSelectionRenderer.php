<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig;
use FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Twig\Environment;

class PaymentSelectionRenderer implements EnhancedEcommerceRendererInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface
     */
    protected $productModel;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig
     */
    protected $config;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Dependency\EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\Model\ProductModelInterface $productModel
     * @param \FondOfSpryker\Yves\EnhancedEcommerceCheckoutConnector\EnhancedEcommerceCheckoutConnectorConfig $config
     */
    public function __construct(
        EnhancedEcommerceCheckoutConnectorToCartClientInterface $cartClient,
        ProductModelInterface $productModel,
        EnhancedEcommerceCheckoutConnectorConfig $config
    ) {
        $this->cartClient = $cartClient;
        $this->productModel = $productModel;
        $this->config = $config;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function expand(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $twig->render($this->getTemplate(), [
            'data' => $this->removeEmptyArrayIndex($this->createEnhancedEcommerceTransfer($twigVariableBag)->toArray()),
            'paymentProvider' => $this->config->getPaymentMethodMapping(),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceCheckoutConnector/partials/change-payment-selection.js.twig';
    }

    /**
     * @param array $twigVariableBag
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function createEnhancedEcommerceTransfer(array $twigVariableBag): EnhancedEcommerceTransfer
    {
        return (new EnhancedEcommerceTransfer())
            ->setEvent(ModuleConstants::EVENT_NAME)
            ->setEventCategory(ModuleConstants::EVENT_CATEGORY)
            ->setEventAction(ModuleConstants::EVENT_ACTION_CHECKOUT_OPTION)
            ->setEventLabel(ModuleConstants::STEP_PAYMENT_SELECTION)
            ->setEcCheckoutOption(['checkout_option' => $this->createEnhancedEcommerceCheckoutTransfer()->toArray()]);
    }

    /**
     * @return \Generated\Shared\Transfer\EnhancedEcommerceCheckoutTransfer
     */
    protected function createEnhancedEcommerceCheckoutTransfer(): EnhancedEcommerceCheckoutTransfer
    {
        return (new EnhancedEcommerceCheckoutTransfer())
            ->setActionField([
                'step' => ModuleConstants::STEP_PAYMENT_SELECTION,
                'option' => '',
            ]);
    }

    /**
     * @param array $haystack
     *
     * @return array
     */
    protected function removeEmptyArrayIndex(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeEmptyArrayIndex($haystack[$key]);
            }

            if (!$value) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}
