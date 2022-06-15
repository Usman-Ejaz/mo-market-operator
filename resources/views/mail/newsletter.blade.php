@extends('mail.app')

@section('contents')
<tr>
    <td style="padding: 20px 40px 30px">
        <h1 style="font-size: 43px; color: #545454; font-weight: 300; text-align: center; margin-top: 5px; margin-bottom: 55px"> 
            <span style="font-weight: 400; border-bottom: 2px solid">Newsletter</span>
        </h1>

        {!! $description !!}
    </td>
</tr>
@endsection

@section('unsubscribe')
    <li style="display: inline-block; margin-right: 20px">
        <span style="width: 0.75px; height: 18px; display: inline-block; margin-right: 12px; vertical-align: middle; background-color: #f6f6f6"></span>
        <a style="color: #f6f6f6; text-decoration: none; font-size: 14px; font-weight: 300" href="{{ $url }}">Unsubscribe</a>
    </li>
@endsection