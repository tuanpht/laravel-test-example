@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Your email has been verified') }}</div>

                <div class="card-body">
                    Thanks for verifying your email. You can <a href="{{ route('web.login') }}">login</a> now.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
