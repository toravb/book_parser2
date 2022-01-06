<?php

namespace Tests\Feature;

use App\AuthApi\Http\Requests\ForgotPasswordRequest;
use App\AuthApi\Http\Requests\LoginRequest;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ForgotPasswordRequestTest extends TestCase
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
        $this->rules = (new ForgotPasswordRequest())->rules();
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
                    'password' => $faker->asciify('*******')
                ]
            ],
            'request_should_fail_when_email_is_invalid' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->word(),
                    'password' => $faker->asciify('*******')
                ]
            ],
            'request_should_fail_when_email_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => $faker->shuffleArray(),
                    'password' => $faker->asciify('*******')
                ]
            ],
            'request_should_fail_when_email_is_grater_when_255' => [
                'passed' => false,
                'data' => [
                    'email' => Str::random(246) . '@gmail.com',
                    'password' => $faker->asciify('*******')
                ]
            ]
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
