<?php

namespace App\Providers;

use App\Events\UserDeleted;
use App\Events\UserSaved;
use App\Listeners\ClearUserCache;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        UserSaved::class => [
            ClearUserCache::class,
        ],
        UserDeleted::class => [
            ClearUserCache::class,
        ],
    ];
}

