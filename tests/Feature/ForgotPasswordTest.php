<?php

namespace Tests\Feature;

use App\AuthApi\Mails\PasswordForgotMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use DatabaseMigrations;

    private string $userEmail = 'doNotLoveread@gmail.com';
    private string $userExistEmail = 'loveread@gmail.com';

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->state([
            'email' => $this->userExistEmail,
        ])->create();
    }

    public function testSuccessWithNonExistingEmail()
    {
        Mail::fake();
        $response = $this->postJson('/api/password_forgot', [
            'email' => $this->userEmail,
        ]);
        Mail::assertNothingSent();
        $this->assertDatabaseMissing('password_resets', [
            'email' => $this->userExistEmail,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            "status" => "success"
        ]);
    }

    public function testSuccessWithExistingEmail()
    {
        Mail::fake();
        $response = $this->postJson('/api/password_forgot', [
            'email' => $this->userExistEmail,
        ]);
        Mail::assertSent(PasswordForgotMail::class);
        $this->assertDatabaseHas('password_resets', [
            'email' => $this->userExistEmail,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            "status" => "success"
        ]);
    }


}
