<?php

namespace Tests\Feature;

use App\AuthApi\Http\Requests\LoginRequest;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetRequestTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \App\Http\Requests\Auth\LoginRequest */
    private $rules;
    /** @var \Illuminate\Validation\Validator */
    private $validator;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = app()->get('validator');
        $this->rules = (new LoginRequest())->rules();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function validationProvider(): array
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create(Factory::DEFAULT_LOCALE);


        return [
            'request_should_fail_when_no_email_is_provided' => [
                'passed' => false,
                'data' => [
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_email_is_invalid' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->word(),
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_email_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->shuffleArray(),
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_email_is_grater_when_255' => [
                'passed' => false,
                'data' => [
                    'email' => Str::random(246) . '@gmail.com',
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_no_password' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_password_is_less_when_6' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->asciify('**'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_password_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->randomElements($array = ['a', 'b', 'c'], $count = 5, $allowDuplicates = true),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_no_token' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                ]
            ],
            'request_should_fail_when_token_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('*******'),
                    'token' => $faker->randomElements($array = ['a', 'b', 'c'], $count = 5, $allowDuplicates = true)
                ]
            ],
            'request_should_fail_when_no_password_confirmation' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_password_is_less_when_6' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->asciify('*******'),
                    'password_confirmation' => $faker->asciify('**'),
                    'token' => Str::random(30)
                ]
            ],
            'request_should_fail_when_password_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->email(),
                    'password' => $faker->randomElements($array = ['a', 'b', 'c'], $count = 5, $allowDuplicates = true),
                    'token' => Str::random(30)
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData)
        );
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }
}
