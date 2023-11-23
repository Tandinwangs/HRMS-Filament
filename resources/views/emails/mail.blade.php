@component('mail::message')
# Leave Application

{{ $user }} {{ $content }}

Thank you for using our leave application system.

Regards,
{{ config('app.name') }}
@endcomponent
