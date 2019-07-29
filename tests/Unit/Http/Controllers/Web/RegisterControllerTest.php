<?php

namespace Tests\Unit\Http\Controllers\Web;

use Tests\TestCase;
use App\Http\Controllers\Web\RegisterController;
use Mockery;
use App\Services\Web\UserService;
use App\Http\Requests\Web\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegisterControllerTest extends TestCase
{
    /** @var RegisterController */
    private $registerController;

    /** @var UserService|\Mockery\MockInterface */
    private $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = Mockery::mock(UserService::class);

        $this->registerController = new RegisterController($this->userService);
    }

    public function testShowFormRegister()
    {
        $view = $this->registerController->showFormRegister()->name();

        $this->assertEquals('auth.register', $view);
    }

    public function testShowPageRegisterSuccess()
    {
        $view = $this->registerController->showRegisterSuccess()->name();

        $this->assertEquals('auth.register_success', $view);
    }

    public function testRegisterSuccess()
    {
        $inputs = [
            'name' => 'User name',
            'email' => 'Valid email',
            'password' => 's3cret@*',
            'other_not_related_input' => 'dummy',
        ];
        $request = RegisterRequest::create(null, 'POST', $inputs);

        $filteredInputs = array_only($inputs, ['name', 'email', 'password']);

        $this->userService
            ->shouldReceive('create')
            ->with($filteredInputs)
            ->andReturn(new User($filteredInputs));
        Mail::fake();

        $response = $this->registerController->register($request);

        Mail::assertQueued(UserRegistered::class);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(
            action([RegisterController::class, 'showRegisterSuccess']),
            $response->headers->get('Location')
        );
    }

    public function testVerifyRedirectNotFoundIfUserIdNotExists()
    {
        $fakeRequest = RegisterRequest::create('');

        $this->userService
            ->shouldReceive('findById')
            ->andReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->registerController->verify($fakeRequest);
    }

    public function testVerifySuccess()
    {
        $fakeRequest = RegisterRequest::create('');

        $this->userService
            ->shouldReceive('findById')
            ->andReturn(new User);

        $this->userService
            ->shouldReceive('verifyUser')
            ->andReturn(true);

        $view = $this->registerController->verify($fakeRequest);

        $this->assertEquals('auth.verify.message', $view->name());
    }

    public function testShowFormVerification()
    {
        $view = $this->registerController->showFormVerification()->name();

        $this->assertEquals('auth.verify.resend', $view);
    }

    public function testResendVerificationLink()
    {
        $inputs = [
            'email' => 'email@example.com',
        ];
        $request = RegisterRequest::create(null, 'POST', $inputs);
        $fakeUser = new User;

        $this->userService
            ->shouldReceive('findByEmail')
            ->with($inputs['email'])
            ->andReturn($fakeUser);
        Mail::fake();

        $response = $this->registerController->resendVerificationLink($request);

        Mail::assertQueued(UserRegistered::class);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(
            action([RegisterController::class, 'showFormVerification']),
            $response->headers->get('Location')
        );
        $this->assertArrayHasKey('resent', $response->getSession()->all());
    }
}
