<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiUsersControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected static ?string $password;
    private string $routePrefix = 'api.users.';

    public function test_api_create_new_user(): void
    {
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
     * @throws \Throwable
     */
    public function test_api_update_name_for_user(): void
    {
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

        $response = $this->putJson(route($this->routePrefix . 'update', ['id' => $oldUserId]), $payload);
        $response->assertOk();
        $json = $response->decodeResponseJson();
        $newUserId = $json['id'];
        $newUserName = $json['name'];

        $this->assertEquals($oldUserId, $newUserId);
        $this->assertNotEquals($oldUserName, $newUserName);

        echo "Old name is $oldUserName, new name is $newUserName";
    }
}
