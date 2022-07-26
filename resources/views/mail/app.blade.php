<!DOCTYPE html>
<html dir="ltr">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" />

    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        a:hover {
            color: #000000;
            opacity: 0.8;
        }

        body,
        td,
        input,
        textarea,
        select {
            font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif;
        }

        p {
            line-height: 1.3;
        }

    </style>
</head>

<body style="margin: 0; background-color: #1a1c1e; color: #545454; font-weight: 400; line-height: normal; font-size: 16px; text-align: center">
    <div style="max-width: 700px; margin: auto; background-color: #f6f6f6">
        <div style="margin-left: 50px; margin-right: 50px; padding-top: 50px">
            <table style="background: #ffffff; width: 100%">
                <tr>
                    <td style="padding: 30px 40px; border-bottom: 1px solid #70707026; text-align: left">
                        <a href="{{ config('settings.client_app_base_url') }}" target="_blank">
                            <img src="{{ asset('images/email/logo.png') }}" alt="logo"/>
                        </a>
                    </td>
                </tr>
                @yield('contents')
            </table>
        </div>
        <table style="width: 100%">
            <tr>
                <td>
                    <h4 style="font-size: 28px; font-weight: 400; margin-bottom: 20px; color: #545454; text-align: center; margin-top: 30px">
                        {{ __("Join Us") }}
                    </h4>
                    <ul class="social-icons" style="padding: 0; list-style: none; text-align: center; display: inline-block; margin: auto; align-items: center; justify-content: center; width: 100%; margin-bottom: 20px;">
                        <li style="margin-right: 10px !important; display: inline-block">
                            <a href="#" style="text-decoration: none; width: 46px; height: 46px; background-color: #ffffff; display: inline-block">
                                <img style="margin-top: 10px" src="{{ asset('images/email/bx_bxl-facebook') }}.png" />
                            </a>
                        </li>
                        <li style="margin-right: 10px !important; display: inline-block">
                            <a href="#" style="text-decoration: none; width: 46px; height: 46px; background-color: #ffffff; display: inline-block">
                                <img style="margin-top: 10px" src="{{ asset('images/email/brandico_linkedin.png') }}" />
                            </a>
                        </li>
                        <li style="margin-right: 10px !important; display: inline-block">
                            <a href="#" style="text-decoration: none; width: 46px; height: 46px; background-color: #ffffff; display: inline-block">
                                <img style="margin-top: 14px" src="{{ asset('images/email/bx_bxl-youtube') }}.png" />
                            </a>
                        </li>
                        <li style="display: inline-block">
                            <a href="#" style="text-decoration: none; width: 46px; height: 46px; background-color: #ffffff; display: inline-block">
                                <img style="margin-top: 10px" src="{{ asset('images/email/el_twitter.png') }}" />
                            </a>
                        </li>
                    </ul>
                    <p style="width: 488px; margin-left: auto; margin-right: auto; color: #545454; text-align: center">
                        {{ __("The email template was sent to you because we want to make world a better place. You could change
                        your subscription settings any time.") }}
                    </p>
                </td>
            </tr>
        </table>
        <table style="width: 100%; background: #003566; padding-left: 50px; padding-right: 50px; margin-top: 30px">
            <tr>
                <td style="text-align: left; color: #f6f6f6; font-size: 14px; font-weight: 300; vertical-align: middle">© {{ date('Y') }} {{ config('app.name') }}</td>
                <td style="text-align: right; color: #f6f6f6; vertical-align: middle">

                    <ul style="list-style: none; margin-top: 16px; margin-bottom: 16px; text-align: right;">
                        <li style="display: inline-block; margin-right: 20px">
                            <span style="width: 0.75px; height: 18px; display: inline-block; margin-right: 12px; vertical-align: middle; background-color: #f6f6f6"></span>
                            <a style="color: #f6f6f6; text-decoration: none; font-size: 14px; font-weight: 300" href="{{ config('settings.client_app_base_url') }}">{{ __("Home") }}</a>
                        </li>
                        <li style="display: inline-block; margin-right: 20px">
                            <span style="width: 0.75px; height: 18px; display: inline-block; margin-right: 12px; vertical-align: middle; background-color: #f6f6f6"></span>
                            <a style="color: #f6f6f6; text-decoration: none; font-size: 14px; font-weight: 300" href="{{ config('settings.client_app_base_url') }}who-we-are">{{ __("About") }}</a>
                        </li>
                        <li style="display: inline-block; margin-right: 20px">
                            <span style="width: 0.75px; height: 18px; display: inline-block; margin-right: 12px; vertical-align: middle; background-color: #f6f6f6"></span>
                            <a style="color: #f6f6f6; text-decoration: none; font-size: 14px; font-weight: 300" href="{{ config('settings.client_app_base_url') }}contact-us">{{ __("Contact") }}</a>
                        </li>
                        @yield('unsubscribe')
                    </ul>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
