<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

class ApiUsersControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected static ?string $password;
    private string $routePrefix = 'api.users.';

    public function test_api_show_all_users_default(): void
    {
        //Создаем пользователей
        User::factory(10)->create();

        $response = $this->getJson(route($this->routePrefix . 'index'));
        $response->assertOk();
        $response->assertJsonStructure(
            [
                'current_page',
                'data' => [
                    '*' => [
                        'id', 'name', 'ip', 'comment', 'email', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at'
                    ]
                ],
                'first_page_url',
                'from',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to'
            ]
        );
    }

    /**
     * @throws Throwable
     */
    public function test_api_check_show_users_per_page_value_equals_five(): void
    {
        //Создаем пользователей
        User::factory(50)->create();

        $response = $this->getJson(route($this->routePrefix . 'index'));
        $response->assertOk();

        $this->assertEquals('5', $response->decodeResponseJson()['per_page']);
    }

    public function test_api_show_users_on_second_page(): void
    {
        //Создаем пользователей
        User::factory(50)->create();

        $payload = [
            'page' => 2,
        ];

        $response = $this->getJson(route($this->routePrefix . 'index'), $payload);
        $response->assertOk();
        $response->assertJsonStructure(
            [
                'current_page',
                'data' => [
                    '*' => [
                        'id', 'name', 'ip', 'comment', 'email', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at'
                    ]
                ],
                'first_page_url',
                'from',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to'
            ]
        );
    }

    /**
     * @throws Throwable
     */
    public function test_api_get_latest_created_user(): void
    {
        //Создаем пользователя
        $payload = [
            'name' => fake()->name(),
            'ip' => fake()->ipv6(),
            'comment' => fake()->text(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $response = $this->postJson(route($this->routePrefix . 'store'), $payload);
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'ip', 'comment', 'email', 'created_at', 'updated_at']);
        $json = $response->decodeResponseJson();
        $userId = $json['id'];

        //Запрашиваем JSON только что созданного пользователя
        $response = $this->getJson(route($this->routePrefix . 'show', ['id' => $userId]));
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'ip', 'comment', 'email', 'created_at', 'updated_at']);
    }

    public function test_api_create_new_user(): void
    {
        //Создаем пользователя
        $payload = [
            'name' => fake()->name(),
            'ip' => fake()->ipv6(),
            'comment' => fake()->text(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $response = $this->postJson(route($this->routePrefix . 'store'), $payload);
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'ip', 'comment', 'email', 'created_at', 'updated_at']);
    }

    /**
     * @throws Throwable
     */
    public function test_api_update_name_for_user(): void
    {
        //Создаем пользователя
        $payload = [
            'name' => fake()->name(),
            'ip' => fake()->ipv6(),
            'comment' => fake()->text(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $response = $this->postJson(route($this->routePrefix . 'store'), $payload);
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'ip', 'comment', 'email', 'created_at', 'updated_at']);
        $json = $response->decodeResponseJson();
        $oldUserId = $json['id'];
        $oldUserName = $json['name'];

        $payload = [
            'name' => fake()->name(),
        ];

        //Изменяем имя созданного пользователя
        $response = $this->putJson(route($this->routePrefix . 'update', ['id' => $oldUserId]), $payload);
        $response->assertOk();
        $json = $response->decodeResponseJson();
        $newUserId = $json['id'];
        $newUserName = $json['name'];

        $this->assertEquals($oldUserId, $newUserId);
        $this->assertNotEquals($oldUserName, $newUserName);

        echo "Old name is $oldUserName, new name is $newUserName";
    }

    /**
     * @throws Throwable
     */
    public function test_api_delete_user(): void
    {
        //Создаем пользователя
        $payload = [
            'name' => fake()->name(),
            'ip' => fake()->ipv6(),
            'comment' => fake()->text(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];

        $response = $this->postJson(route($this->routePrefix . 'store'), $payload);
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'ip', 'comment', 'email', 'created_at', 'updated_at']);
        $json = $response->decodeResponseJson();
        $userId = $json['id'];

        //Удаляем пользователя
        $response = $this->deleteJson(route($this->routePrefix . 'destroy', ['id' => $userId]), $payload);
        $response->assertOk();
    }
}
