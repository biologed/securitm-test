<?php

namespace App\Listeners;

use App\Events\UserDeleted;
use App\Events\UserSaved;
use Illuminate\Cache\CacheManager;

class ClearUserCache
{
    public function __construct(
        public CacheManager $cache
    ) {}

    public function handle(UserDeleted|UserSaved $event): void
    {
        $this->cache->forget('user.' . $event->getUserId());
    }
}
