<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest;
use App\Services\Web\UserService;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show register form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showFormRegister()
    {
        return view('auth.register');
    }

    public function showRegisterSuccess()
    {
        return view('auth.register_success');
    }

    public function register(RegisterRequest $request)
    {
        $inputs = $request->all(['name', 'email', 'password']);

        $user = $this->userService->create($inputs);

        Mail::to($user)->send(new UserRegistered($user->getKey(), $user->name));

        return redirect()->action([static::class, 'showRegisterSuccess']);
    }

    public function verify(Request $request)
    {
        $user = $this->userService->findById($request->route('id'));
        if (!$user) {
            abort(404);
        }

        if (!$user->hasVerifiedEmail()) {
            $this->userService->verifyUser($user);
        }

        return view('auth.verify.message');
    }

    public function showFormVerification()
    {
        return view('auth.verify.resend');
    }

    public function resendVerificationLink(Request $request)
    {
        $user = $this->userService->findByEmail($request->input('email'));

        if ($user && !$user->hasVerifiedEmail()) {
            Mail::to($user)->send(new UserRegistered($user->getKey(), $user->name));
        }

        return redirect()->action([static::class, 'showFormVerification'])->with('resent', true);
    }
}
