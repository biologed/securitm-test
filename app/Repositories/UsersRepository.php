<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\Paginator;

class UsersRepository
{
    public function __construct(
        private User $model
    ) {}

    public function setModel(User $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getAll(array $data): Paginator
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

    public function update(array $data): bool
    {
        return $this->model->update($data);
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}
