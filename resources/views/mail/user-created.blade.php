@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => $url])
Verify Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
