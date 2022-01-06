<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginSuccessfulTest extends TestCase
{
    use DatabaseMigrations;

    private string $userPassword = 'passpass';
    private string $userEmail = 'customer@bp.in';

    private string $userVerifedPassword = 'passpass';
    private string $userVerifedEmail = 'user@gmail.com';

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');

        User::factory()->unverified()->state([
            'email' => $this->userEmail,
            'password' => Hash::make($this->userPassword)
        ])->create();

        User::factory()->state([
            'email' => $this->userVerifedEmail,
            'password' => Hash::make($this->userVerifedPassword)
        ])->create();
    }

    public function testUserSuccessfulLogin()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->userVerifedEmail,
            'password' => $this->userVerifedPassword
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn(AssertableJson $json) => $json->whereType('token', 'string'));
    }

    public function testFailWithNonVerifiedAccount()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->userEmail,
            'password' => $this->userPassword
        ]);


        $response->assertStatus(422);
        $response->assertJson([
            "message" => "Введены неверные данные.",
            "errors" => [
                "email" => [
                    "Неверный email или пароль. Пожалуйста введите верные данные."
                ]
            ]
        ]);
    }

    public function testFailLoginWhenPasswordIsIncorrect()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->userEmail,
            'password' => '112233445566'
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "Введены неверные данные.",
            "errors" => [
                "email" => [
                    "Неверный email или пароль. Пожалуйста введите верные данные."
                ]
            ]
        ]);
    }

    public function testFailLoginWhenEmailIsIncorrect()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'anton@gmail.com',
            'password' => $this->userPassword
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "Введены неверные данные.",
            "errors" => [
                "email" => [
                    "Неверный email или пароль. Пожалуйста введите верные данные."
                ]
            ]
        ]);
    }
}
