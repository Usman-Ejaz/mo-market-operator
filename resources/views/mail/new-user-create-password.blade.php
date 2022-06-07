@extends('mail.app')

@section('contents')
<tr>
    <td style="padding: 30px 40px">
        <div style="text-align: center">
            <img src="{{ asset('images/email/password.png') }}" alt="logo"/>
        </div>
        <h1 style="font-size: 28px;color: #003566;font-weight: normal;max-width: 320px;font-weight: normal;margin-left: auto;margin-right: auto;">
            {{-- Your password has been updated successfully! --}}
            Please create your new password.
        </h1>

        @component('mail.button')

            @slot('title')
                Create password
            @endslot

            @slot('link')
                {{ $url }}
            @endslot

        @endcomponent

        <p style="margin-top: 20px; max-width: 400px; margin-left: auto; margin-right: auto; color: #545454; text-align: center">
            Thanks for starting this journey with us! As with every new path, we know it can be
            difficult to navigate at first.
        </p>
    </td>
</tr>
@endsection