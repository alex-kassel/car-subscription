<?php

declare(strict_types=1);

namespace CarSubscription\Enums;

use Shared\Traits\EnumFromName;

enum TransmissionType: string {
    use EnumFromName;

    case MANUAL = 'Manuell';
    case AUTOMATIC = 'Automatik';
    case SEMI_AUTOMATIC = 'Halbautomatik';
    case OTHER = 'Unbekannt';

    public static function getEnumByString(string $key): ?self
    {
        $key = strtolower($key);

        return match (1) {
            preg_match('/halb|semi/', $key) => self::SEMI_AUTOMATIC,
            preg_match('/auto/', $key) => self::AUTOMATIC,
            preg_match('/schalt|manu/', $key) => self::MANUAL,
            default => self::OTHER,
        };
    }
}
