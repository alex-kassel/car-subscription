<?php

declare(strict_types=1);

namespace CarSubscription\Facades;

use Illuminate\Support\Facades\Facade;

class DomainService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CarSubscription\DomainService::class;
    }
}
