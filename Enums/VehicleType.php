<?php

declare(strict_types=1);

namespace CarSubscription\Enums;

use Shared\Traits\EnumFromName;

enum VehicleType: string
{
    use EnumFromName;

    case COMMERCIAL = 'Nutzfahrzeug';
    case COMPACT_VAN = 'Kompaktvan';
    case CONVERTIBLE = 'Cabriolet';
    case HATCHBACK = 'Schräghecklimousine';
    case COUPE = 'Coupé';
    case MOTORHOME = 'Wohnmobil / Wohnwagen';
    case SEDAN = 'Limousine';
    case SMALL_CAR = 'Kleinwagen';
    case SUV = 'SUV';
    case VAN = 'Van';
    case WAGON = 'Kombi';
    case OTHER = 'Unbekannt';

    public static function getEnumByString(string $key): ?self
    {
        $key = strtolower($key);

        return match (1) {
            preg_match('/suv/', $key) => self::SUV,
            preg_match('/schrägheck|hatchback/', $key) => self::HATCHBACK,
            preg_match('/limousine|sedan/', $key) => self::SEDAN,
            preg_match('/kombi|wagon/', $key) => self::WAGON,
            preg_match('/kleinwagen|small.?car/', $key) => self::SMALL_CAR,
            preg_match('/ompakt.?van/', $key) => self::COMPACT_VAN,
            preg_match('/van/', $key) => self::VAN,
            preg_match('/cabriolet|convertible/', $key) => self::CONVERTIBLE,
            preg_match('/nutz|commerc/', $key) => self::COMMERCIAL,
            preg_match('/wohn|home|caravan/', $key) => self::MOTORHOME,
            preg_match('/coup/', $key) => self::COUPE,
            default => self::OTHER,
        };
    }
}
