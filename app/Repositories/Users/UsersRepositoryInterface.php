<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Pagination\Paginator;

interface UsersRepositoryInterface
{
    public function all(array $data): Paginator;
    public function getById(int $id): User;
    public function save(array $data): User;
    public function update(array $data): void;
    public function delete(int $id): void;
}
