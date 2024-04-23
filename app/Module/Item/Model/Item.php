<?php

declare(strict_types=1);

namespace App\Module\Item\Model;

final readonly class Item
{
    public function __construct(private string $sku, private float $price)
    {
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
