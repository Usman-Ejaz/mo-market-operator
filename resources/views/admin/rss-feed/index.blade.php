<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
    <channel>
        <title><![CDATA[ {{ config('app.name') }} ]]></title>
        <link><![CDATA[ {{ config('settings.client_app_base_url') }}feed ]]></link>
        <description><![CDATA[ Your website description ]]></description>
        <language>en</language>
        <pubDate>{{ today() }}</pubDate>

        @foreach($todaysPublishedRecords as $record)
            <item>
                <title><![CDATA[ {{ $record->title ?? $record->name }} ]]></title>
                <link>{{ config('settings.client_app_base_url') }}{{ $record->post_category ? \Illuminate\Support\Str::plural(strtolower($record->post_category)) . '/' : '' }}{{ $record->slug }}</link>
                @if ($record->image && isset($record->image) && !empty($record->image))
                    <media:content url="{{ $record->image }}" medium="image"/>
                @endif
                <content:encoded><![CDATA[ {!! $record->description !!} ]]></content:encoded>
                <pubDate>{{ parseDate($record->published_at) ?: parseDate($record->created_at)  }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>