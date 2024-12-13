<?php
declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use OpenApi\Attributes as OAT;

/**
 * @see https://github.com/zircote/swagger-php/tree/master/Examples
 */
#[OAT\Schema(schema: 'users')]
class User extends Authenticatable
{

    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    #[OAT\Property(type: 'int', example: '1')]
    private int $id;
    #[OAT\Property(type: 'string', example: 'test@test.com')]
    private int $email;
    #[OAT\Property(type: 'string', example: '192.168.1.1')]
    private int $ip;
    #[OAT\Property(type: 'string', example: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...')]
    private int $comment;
    #[OAT\Property(type: 'string', example: 'password')]
    private int $password;
    #[OAT\Property(type: 'string', example: 'token')]
    private int $remember_token;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'ip',
        'comment',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
