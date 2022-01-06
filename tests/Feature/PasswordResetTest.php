<?php

namespace Tests\Feature;

use App\AuthApi\Models\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\AuthApi\Mails\PasswordForgotMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetTest extends TestCase
{
    use DatabaseMigrations;

    private string $userPassword = 'passpass';
    private string $userEmail = 'customer@bp.in';


    private string $userVerifedPassword = 'passpass';
    private string $userVerifedEmail = 'user@gmail.com';
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->state([
            'email' => $this->userVerifedEmail,
            'password' => Hash::make($this->userVerifedPassword)
        ])->create();

        $resetModel = new PasswordReset();
        $reset = $resetModel->create($this->userVerifedEmail);
        $this->token = $reset->token;
    }

    public function testSuccessWithExistingEmail()
    {
        $password = Str::random(8);
        $response = $this->postJson('/api/password_reset', [
            'email' => $this->userVerifedEmail,
            'token' => $this->token,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $this->userVerifedEmail,
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            "status" => "success"
        ]);
    }

    public function testSuccessWithNonExistingEmail()
    {
        $password = Str::random(8);
        $response = $this->postJson('/api/password_reset', [
            'email' => $this->userEmail,
            'token' => $this->token,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $this->userVerifedEmail,
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            "status" => "error",
            "message" => "Вы не прошли процедуру восстановления пароля"
        ]);
    }

    public function testSuccessWithWrongToken()
    {
        $password = Str::random(8);
        $response = $this->postJson('/api/password_reset', [
            'email' => $this->userEmail,
            'token' => $password,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $this->userVerifedEmail,
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            "status" => "error",
            "message" => "Вы не прошли процедуру восстановления пароля"
        ]);
    }
}
