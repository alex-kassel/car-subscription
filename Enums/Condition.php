<?php

declare(strict_types=1);

namespace CarSubscription\Enums;

use Shared\Traits\EnumFromName;

enum Condition: string {
    use EnumFromName;

    case NEW = 'Neuwagen';
    case USED = 'Gebrauchtwagen';
    case DEMO = 'Vorführwagen';
    case OTHER = 'Unbekannt';

    public static function getEnumByString(string $key): ?self
    {
        $key = strtolower($key);

        return match (1) {
            preg_match('/neu|new/', $key) => self::NEW,
            preg_match('/gebr|used/', $key) => self::USED,
            preg_match('/vorf|demo/', $key) => self::DEMO,
            default => self::OTHER,
        };
    }
}
