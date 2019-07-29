<?php

namespace Tests\Unit\Http\Requests\Web;

use Tests\TestCase;
use App\Http\Requests\Web\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Illuminate\Validation\PresenceVerifierInterface;
use App\Http\Controllers\Web\RegisterController;
use App\Services\Web\UserService;
use Tests\Integration\SetupDatabaseTrait;
use App\Models\User;

class RegisterControllerTest extends TestCase
{
    use SetupDatabaseTrait;

    /** @var UserService|\Mockery\MockInterface */
    private $userService;

    public function setUp(): void
    {
        parent::setUp();

        // Mock and make laravel resolves mocked instance
        $this->userService = $this->mock(UserService::class);
    }

    private function validInputs($overrides = [])
    {
        return array_merge([
            'name' => str_repeat('a', RegisterRequest::NAME_MAX_LENGTH),
            'email' => 'user@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ], $overrides);
    }

    /**
     * @dataProvider provideTestNameIsRequired
     */
    public function testNameIsRequired($inputs)
    {
        $response = $this->postJson(
            action([RegisterController::class, 'register']),
            $this->validInputs($inputs)
        );

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['name']]);
    }

    public function provideTestNameIsRequired()
    {
        return [
            [['name' => '   ']],
            [['name' => '']],
        ];
    }

    public function testValidateNameFailedWhenExceedingMaxlength()
    {
        $response = $this->postJson(
            action([RegisterController::class, 'register']),
            $this->validInputs([
                'name' => str_repeat('a', RegisterRequest::NAME_MAX_LENGTH + 1),
            ])
        );

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['name']]);
    }

    /**
     * @param array $inputs Request data
     *
     * @dataProvider provideDataValidateEmailFailedWhenEmailInvalid
     */
    public function testValidateEmailFailedWhenEmailInvalid($inputs)
    {
        $response = $this->postJson(
            action([RegisterController::class, 'register']),
            $this->validInputs($inputs)
        );

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['email']]);
    }

    public function provideDataValidateEmailFailedWhenEmailInvalid()
    {
        return [
            [['email' => '']],
            [['email' => '    ']],
            [['email' => 'missingdomain']],
            [['email' => '@missinguser.com']],
            [['email' => 'invalidchar &*@example.com']],
            [['email' => 'exceedlength' . str_repeat('a', RegisterRequest::EMAIL_MAX_LENGTH + 1) . '@example.com']],
        ];
    }

    public function testValidateEmailFailedWhenEmailExisted()
    {
        $existedEmail = 'user@example.com';
        factory(User::class)->create([
            'email' => $existedEmail,
        ]);

        $response = $this->postJson(
            action([RegisterController::class, 'register']),
            $this->validInputs([
                'email' => $existedEmail,
            ])
        );

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['email']]);
    }

    /**
     * @param array $inputs Request data
     *
     * @dataProvider provideDataValidatePasswordFailedWhenInputInvalid
     */
    public function testValidatePasswordFailedWhenInputInvalid($inputs)
    {
        $response = $this->postJson(
            action([RegisterController::class, 'register']),
            $this->validInputs($inputs)
        );

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['password']]);
    }

    public function provideDataValidatePasswordFailedWhenInputInvalid()
    {
        return [
            [['password' => '']],
            [['password' => '         ']],
            [['password' => str_repeat('a', RegisterRequest::PASSWORD_MIN_LENGTH - 1)]],
            [
                [
                    'password' => '12345678',
                    'password_confirmation' => '1234567899',
                ],
            ],
        ];
    }
}
