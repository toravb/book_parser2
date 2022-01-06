<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\GenerateUniqueTokenService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use DatabaseMigrations;

    private string $userPassword = 'passpass';
    private string $userEmail = 'customer@bp.in';
    private string $verifyToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->verifyToken = GenerateUniqueTokenService::createTokenWithoutUserId();

        $this->artisan('passport:install');

        User::factory()->state([
            'email' => $this->userEmail,
            'password' => Hash::make($this->userPassword),
            'verify_token' => $this->verifyToken
        ])->create();
    }

    public function testUserSuccessfulVerifyEmail()
    {
        $response = $this->postJson('/api/verify_email', [
            'email' => $this->userEmail,
            'token' => $this->verifyToken
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
    }

    public function testFailVerifyWhenEmailIsIncorrect()
    {
        $response = $this->postJson('/api/verify_email', [
            'email' => 'anton@gmail.com',
            'token' => $this->verifyToken
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => "error",
            'message' => "Not Found!"
        ]);
    }

    public function testFailVerifyWhenTokenIsIncorrect()
    {
        $response = $this->postJson('/api/verify_email', [
            'email' => $this->userEmail,
            'token' => Str::random(22)
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'status' => "error",
            'message' => "Not Found!"
        ]);
    }
}
