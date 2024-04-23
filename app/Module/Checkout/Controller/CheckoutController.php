<?php

declare(strict_types=1);

namespace App\Module\Checkout\Controller;

use App\Module\Checkout\Service\Checkout;
use App\Module\Item\Builder\ItemCollectionBuilder;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\PricingRule\Builder\PricingRulesBuilderInterface;
use App\Shared\Response\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class CheckoutController
{
    public function __construct(
        private ResponseInterface            $response,
        private LoggerInterface              $logger,
        private Checkout                     $checkout,
        private ItemCollectionBuilder        $itemCollectionBuilder,
        private PricingRulesBuilderInterface $pricingRulesBuilder,
    )
    {
    }

    public function checkout(string $skus): Response
    {
        try {
            $pricingRules = $this->pricingRulesBuilder->build();
            $this->checkout->setPricingRules($pricingRules);

            $total = $this->getTotal($skus);
        } catch (InvalidItemSkuException $exception) {
            return $this->response->notFound([$exception->getMessage()]);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['skus' => $skus]);

            return $this->response->error(['An unexpected error occurred!']);
        }

        return $this->response->success(['Total' => $total]);
    }

    /**
     * @throws InvalidItemSkuException
     */
    private function getTotal(string $skus): float
    {
        $checkoutItemCollection = $this->itemCollectionBuilder->build($skus);

        foreach ($checkoutItemCollection as $item) {
            $this->checkout->scan($item);
        }

        return $this->checkout->getTotal();
    }
}
