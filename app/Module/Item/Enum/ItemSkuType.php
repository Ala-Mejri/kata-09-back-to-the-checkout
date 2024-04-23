<?php

declare(strict_types=1);

namespace App\Module\Item\Enum;

enum ItemSkuType: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';

    public function getPrice(): float
    {
        return match ($this) {
            self::A => 50,
            self::B => 30,
            self::C => 20,
            self::D => 15,
        };
    }
}
