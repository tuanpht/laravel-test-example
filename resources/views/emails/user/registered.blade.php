@component('mail::message')
Hi {{ $userName }},<br>
Please verify your email.

@component('mail::button', ['url' => $url])
Click here!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
