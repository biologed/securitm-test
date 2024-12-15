<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Pagination\Paginator;

class UsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        protected User $model
    ) {}

    public function all(array $data): Paginator
    {
        return $this->model::orderBy($data['order'], $data['sort'])
            ->simplePaginate($data['per_page'], ['*'], 'page', $data['page']);
    }

    public function getById(int $id): User
    {
        return $this->model::find($id);
    }

    public function save(array $data): User
    {
        return $this->model::create($data);
    }

    public function update(array $data): void
    {
        $this->model = $this->getById($data['id']);
        $this->model->update($data);
    }

    public function delete(int $id): void
    {
        $this->model = $this->getById($id);
        $this->model->delete();
    }
}
