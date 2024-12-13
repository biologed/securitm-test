<?php

namespace App\Http\ApiControllers\Repositories;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use App\Http\ApiControllers\Repositories\Interfaces\UsersRepositoryInterface;

class UsersRepository implements UsersRepositoryInterface
{

    public function all(array $data): Paginator
    {
        return User::orderBy($data['order'], $data['sort'])
            ->simplePaginate($data['per_page'], ['*'], 'page', $data['page']);
    }

    public function getById(array $data): Collection
    {
        return User::find(['id' => $data['id']]);
    }

    public function save(array $data): Collection
    {
        return (new Collection())->add(User::create($data));
    }

    public function update(array $data): void
    {
        $user = User::find($data['id']);
        $user->update($data);
    }

    public function delete(array $data): void
    {
        $user = User::find($data['id']);
        $user->delete();
    }
}
