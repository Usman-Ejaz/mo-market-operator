@component('mail::message')
# Introduction

@component('mail::panel')
    <strong>{{ __('Name') }}:</strong> {{ $contactPageQuery->name }} <br>
    <strong>{{ __('Email') }}:</strong> {{ $contactPageQuery->email }} <br>
    <strong>{{ __('Subject') }}:</strong> {{ $contactPageQuery->subject }} <br>
    <strong>{{ __('Message') }}:</strong> {{ $contactPageQuery->message }} <br>
@endcomponent

@component('mail::button', ['url' => route('admin.contact-page-queries.show', $contactPageQuery->id) . '?notification' ])
{{ __('View Query') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
