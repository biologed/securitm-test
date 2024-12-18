<?php

namespace App\Services;

use App\Events\UserDeleted;
use App\Events\UserSaved;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class UsersService
{
    const int TTL = 1440; # 1 day

    public function __construct(
        private readonly UsersRepository $usersRepository
    ) {}

    public function getAll(array $data): Paginator
    {
        $key = 'users.' . $data['order'] . $data['sort'] . $data['per_page'];
        return Cache::remember($key, self::TTL, fn () => $this->usersRepository->getAll($data));
    }

    public function getById(int $id): User
    {
        $key = 'user.' . $id;
        return Cache::remember($key, self::TTL, fn () => $this->usersRepository->getById($id));
    }

    public function save(array $data): User
    {
        return $this->usersRepository->save($data);
    }

    public function update(array $data): int
    {
        $user = $this->getById($data['id']);
        //delete user from cache
        event(new UserSaved($user));
        return $this->usersRepository->setModel($user)->update($data);
    }

    public function delete(int $id): int
    {
        $user = $this->getById($id);
        //delete user from cache
        event(new UserDeleted($user));
        $this->usersRepository->setModel($user)->delete();
        return 1;
    }
}
