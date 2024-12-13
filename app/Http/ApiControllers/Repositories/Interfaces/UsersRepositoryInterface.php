<?php

namespace App\Http\ApiControllers\Repositories\Interfaces;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface UsersRepositoryInterface
{
    public function all(array $data): Paginator;
    public function save(array $data): Collection;
    public function getById(array $data): Collection;
    public function update(array $data): void;
    public function delete(array $data): void;
}
