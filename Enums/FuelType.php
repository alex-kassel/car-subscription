<?php

declare(strict_types=1);

namespace CarSubscription\Enums;

use Shared\Traits\EnumFromName;

enum FuelType: string {
    use EnumFromName;

    case PETROL = 'Benzin';
    case DIESEL = 'Diesel';
    case ELECTRIC = 'Elektro';
    case OTHER = 'Unbekannt';

    public static function getEnumByString(string $key): ?self
    {
        $key = strtolower($key);

        return match (1) {
            preg_match('/diesel/', $key) => self::DIESEL,
            preg_match('/strom|ele.tr/', $key) => self::ELECTRIC,
            preg_match('/benzin|petrol|plug.in/', $key) => self::PETROL,
            default => self::OTHER,
        };
    }
}
