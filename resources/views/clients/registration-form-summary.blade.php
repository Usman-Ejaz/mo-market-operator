<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Form</title>
</head>
<body>
    <div style="width: 100%">
        <h3 style="text-align: center; margin-bottom: 30px;">
            Registration Application
        </h3>
        <table style="width: 100%">
            <tr>
                <td colspan="2"><strong>Name: </strong> {{ $client->name }} </td>
                <td colspan="2"><strong>Business: </strong> {{ $client->business }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Type: </strong> {{ __('client.registration_types.' . $client->type) }} </td>
                <td colspan="2"><strong>Categories: </strong> {{ $client->category_labels }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Address Line One: </strong> {{ $client->address_line_one }} </td>
                <td colspan="2"><strong>Address Line Two: </strong> {{ $client->address_line_two }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>City: </strong> {{ $client->city }} </td>
                <td colspan="2"><strong>State: </strong> {{ $client->state }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Zip Code: </strong> {{ $client->zipcode }} </td>
                <td colspan="2"><strong>Country: </strong> {{ $client->country }} </td>
            </tr>
        </table>

        <h4 style="margin-top: 30px;">{{ ucfirst($primaryDetails->type) }} Details</h4>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td colspan="2"><strong>Name: </strong> {{ $primaryDetails->name }} </td>
                <td colspan="2"><strong>Email: </strong> {{ $primaryDetails->email }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Address Line One: </strong> {{ $primaryDetails->address_line_one }} </td>
                <td colspan="2"><strong>Address Line Two: </strong> {{ $primaryDetails->address_line_two }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>City: </strong> {{ $primaryDetails->city }} </td>
                <td colspan="2"><strong>State: </strong> {{ $primaryDetails->state }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Zip Code: </strong> {{ $primaryDetails->zipcode }} </td>
                <td colspan="2"> </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Telephone: </strong> {{ $primaryDetails->telephone }} </td>
                <td colspan="2"><strong>Facsimile Telephone: </strong> {{ $primaryDetails->facsimile_telephone }} </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Signature: </strong></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <img src="{{ $primaryDetails->signature }}" width="100px;" height="200px;">
                </td>
                <td colspan="2"></td>
            </tr>
        </table>

        @if ($secondaryDetails)
            <h4 style="margin-top: 30px;">{{ ucfirst($primaryDetails->type) }} Details</h4>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td colspan="2"><strong>Name: </strong> {{ $primaryDetails->name }} </td>
                    <td colspan="2"><strong>Email: </strong> {{ $primaryDetails->email }} </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Address Line One: </strong> {{ $primaryDetails->address_line_one }} </td>
                    <td colspan="2"><strong>Address Line Two: </strong> {{ $primaryDetails->address_line_two }} </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>City: </strong> {{ $primaryDetails->city }} </td>
                    <td colspan="2"><strong>State: </strong> {{ $primaryDetails->state }} </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Zip Code: </strong> {{ $primaryDetails->zipcode }} </td>
                    <td colspan="2"> </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Telephone: </strong> {{ $primaryDetails->telephone }} </td>
                    <td colspan="2"><strong>Facsimile Telephone: </strong> {{ $primaryDetails->facsimile_telephone }} </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Signature: </strong></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <img src="{{ $primaryDetails->signature }}" width="100px;" height="200px;">
                    </td>
                    <td colspan="2"></td>
                </tr>
            </table>
        @endif

        @if ($files_count > 0)
            <h3 style="margin-top: 30px;"></h3>
            <br />
            <h5>{{ __('General Attachments') }}</h5>

            <ol type="1">
                @foreach ($generalAttachments as $item)
                    <li>
                        <p style="display: inline">{{ $item->phrase_string }}</p>: {{ getFileOriginalName($item->file) }}
                    </li>
                @endforeach
            </ol>

            @foreach ($categoryAttachments as $key => $items)
            <h5>{{ __('client.categories.' . $client->type . '.' . \App\Models\Client::REGISTER_CATEGORIES[$key]) }}</h5>
            <ol type="1">
                @foreach ($items as $item)
                    <li>
                        <p style="display: inline">{{ $item->phrase_string }}</p>: {{ getFileOriginalName($item->file) }}
                    </li>
                @endforeach
            </ol>
            @endforeach
        @endif
    </div>
</body>
</html>
