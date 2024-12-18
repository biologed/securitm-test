<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

abstract class UsersEvents
{
    use SerializesModels;
    public function __construct(
        private readonly User $user
    ) {}

    public function getUserId(): int {
        return $this->user->id;
    }
}
