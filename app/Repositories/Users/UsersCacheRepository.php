<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Cache\CacheManager;
use Illuminate\Pagination\Paginator;

class UsersCacheRepository extends UsersRepository
{
    const int TTL = 1440; # 1 day

    public function __construct(
        protected CacheManager $cache,
        protected UsersRepository $repository
    ) {
        parent::__construct(new User());
    }

    public function all(array $data): Paginator
    {
        $key = 'users.' . $data['order'] . $data['sort'] . $data['per_page'];
        return $this->cache->remember($key, self::TTL, function () use ($data) {
            return $this->repository->all($data);
        });
    }

    public function getById(int $id): User
    {
        $key = 'user.' . $id;
        return $this->cache->remember($key, self::TTL, function () use ($id) {
            return $this->repository->getById($id);
        });
    }
}
