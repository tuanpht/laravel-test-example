@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('One more step...') }}</div>

                <div class="card-body">
                    Thanks for registering. Please check your email to verify your account.

                    {{ __('If you did not receive the email') }}, <a href="{{ route('web.register.resend_verify_link') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
