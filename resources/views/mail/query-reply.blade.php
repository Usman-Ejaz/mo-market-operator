@component('mail::message')
# Introduction

@component('mail::panel')
    <strong>{{ __('Name') }}:</strong> {{ $contactPageQuery->name }} <br>
    <strong>{{ __('Email') }}:</strong> {{ $contactPageQuery->email }} <br>
    <strong>{{ __('Subject') }}:</strong> {{ $contactPageQuery->subject }} <br>
    <strong>{{ __('Message') }}:</strong> {{ $contactPageQuery->message }} <br>
    <strong>{{ __('Reply') }}:</strong> {{ $contactPageQuery->comments }} <br>
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
