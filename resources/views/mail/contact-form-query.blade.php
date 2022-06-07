@extends('mail.app')

@section('contents')
<tr>
    <td style="padding: 30px 40px; text-align: left;">
        <h3 style="color: #003566;max-width: 320px;margin-right: auto;">
            Hi Admin,
        </h3>

        <p style="color: #545454; font-size: 14px;">
            A New Contact Request has submitted with the following information.
        </p>

        
        <p style="color: #545454; font-size: 14px; text-align: left; margin: 25px auto 5px 30px;">
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
        </p>

        @component('mail.button')
            @slot('title')
                View Query
            @endslot

            @slot('link')
                {{ route('admin.contact-page-queries.show', $contactPageQuery->id) }}
            @endslot
        @endcomponent
    </td>
</tr>
@endsection
