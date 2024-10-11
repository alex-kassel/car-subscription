<?php

declare(strict_types=1);

namespace CarSubscription;

$userFunctionsDir = __DIR__ . '/Helper';

foreach (glob($userFunctionsDir . '/*.php') as $filename) {
    if (basename($filename)[0] !== '_') {
        require_once $filename;
    }
}
