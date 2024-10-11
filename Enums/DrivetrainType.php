<?php

declare(strict_types=1);

namespace CarSubscription\Enums;

use Shared\Traits\EnumFromName;

enum DrivetrainType: string
{
    use EnumFromName;

    case ICE = 'Verbrennungsmotor';
    case EV = 'Elektromotor';

    case MHEV = 'Mild-Hybrid';
    case FHEV = 'Vollhybrid';
    case PHEV = 'Plug-in-Hybrid';

    case CNG = 'Erdgas';
    case LPG = 'Autogas';
    case OTHER = 'Unbekannt';

    public static function getEnumByString(string $key): ?self
    {
        $key = strtolower($key);

        return match (1) {
            preg_match('/phev|plug.in/', $key) => self::PHEV,
            preg_match('/mhev|m-hyb|mild-hyb|48v/', $key) => self::MHEV,
            preg_match('/hev|hyb/', $key) => self::FHEV,
            preg_match('/strom|ele.tr/', $key) => self::EV,
            preg_match('/cng/', $key) => self::CNG,
            preg_match('/lpg/', $key) => self::LPG,
            preg_match('/benzin|diesel/', $key) => self::ICE,
            default => self::OTHER,
        };
    }
}
