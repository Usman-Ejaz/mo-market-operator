@extends('mail.app')

@section('contents')
<tr>
    <td style="padding: 30px 40px; text-align: left;">
        <h3 style="color: #003566;max-width: 320px;margin-right: auto;">
            Dear {{ $contactPageQuery->name }},
        </h3>

        <p style="color: #545454; font-size: 14px;">
            Thank you for showing your interest in {{ config('app.name') }}.
        </p>

        <p style="margin-top: 20px; max-width: 400px; color: #545454; font-size: 14px">
            {{ $contactPageQuery->comments }}
        </p>
        
        {{-- <p style="color: #545454; font-size: 14px; text-align: left; margin: 25px auto 5px 30px;">
            <span > <strong>{{ __('Name') }}</strong>: </span> {{ $contactPageQuery->name }}
        </p>
        <p style="color: #545454; font-size: 14px; text-align: left; margin: 5px auto 5px 30px;">
            <span> <strong>{{ __('Email') }}</strong>: </span> {{ $contactPageQuery->email }}
        </p>
        <p style="color: #545454; font-size: 14px; text-align: left; margin: 5px auto 5px 30px;">
            <span> <strong>{{ __('Subject') }}</strong>: </span> {{ $contactPageQuery->subject }}
        </p>
        <p style="color: #545454; font-size: 14px; text-align: left; margin: 5px auto 0px 30px;">
            <span> <strong>{{ __('Message Body') }}</strong>: </span>
        </p>
        <p style="color: #545454; font-size: 14px; text-align: left; margin: 5px auto 0px 30px;">
            {{ $contactPageQuery->message }}
        </p> --}}
    </td>
</tr>
@endsection
