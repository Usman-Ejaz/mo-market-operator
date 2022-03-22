@component('mail::message')

{!! $description !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
