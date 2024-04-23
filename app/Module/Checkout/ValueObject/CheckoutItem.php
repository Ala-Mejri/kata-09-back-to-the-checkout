<?php

declare(strict_types=1);

namespace App\Module\Checkout\ValueObject;

use App\Module\Item\Model\Item;

final readonly class CheckoutItem
{
    public function __construct(public Item $item, public int $quantity)
    {
    }

    public function incrementQuantity(): self
    {
        return new self($this->item, $this->quantity + 1);
    }
}
