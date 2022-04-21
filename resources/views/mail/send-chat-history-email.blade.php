@component('mail::message')
Your Chat History
@component('mail::panel')
    @foreach ($history as $chat)
        <strong>{{ __('Question') }}:</strong> {{ $chat->question }} <br />
        <strong>{{ __('Answer') }}:</strong> {!! $chat->answer !!} <br />
        <br />
    @endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
