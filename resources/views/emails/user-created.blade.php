<!-- resources/views/emails/user-created.blade.php -->

@component('mail::message')
# Welcome to Our Platform

Hello {{ $user->name }},

An account has been created for you on our platform.

Your password: {{ $password }}

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thank you,
The Platform Team
@endcomponent
