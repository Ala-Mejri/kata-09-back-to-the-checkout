<?php

declare(strict_types=1);

namespace App\Module\Checkout\Controller;

use App\Module\Checkout\Service\CheckoutService;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\PricingRule\Service\PricingRulesBuilderInterface;
use App\Shared\Response\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class CheckoutController
{
    public function __construct(
        private ResponseInterface            $response,
        private LoggerInterface              $logger,
        private CheckoutService              $checkoutService,
        private PricingRulesBuilderInterface $pricingRulesBuilder,
    )
    {
    }

    public function checkout(string $skus): Response
    {
        try {
            $pricingRules = $this->pricingRulesBuilder->build();

            $total = $this->checkoutService->getTotal($skus, $pricingRules);
        } catch (InvalidItemSkuException $exception) {
            return $this->response->notFound([$exception->getMessage()]);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['skus' => $skus]);

            return $this->response->error(['An unexpected error occurred!']);
        }

        return $this->response->success(['Total' => $total]);
    }
}
