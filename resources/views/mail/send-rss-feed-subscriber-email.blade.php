@component('mail::message')
@foreach ($data as $item)
    <strong>{{ $item->title ?? $item->name }}</strong>
    @if ($item->short_description)
        {{ $item->short_description }}
    @else
        {!! $item->description !!}
    @endif
    @component('mail::button', ['url' => $item->slug])
        Open
    @endcomponent
@endforeach


Thanks,<br>
{{ config('app.name') }}
@endcomponent
