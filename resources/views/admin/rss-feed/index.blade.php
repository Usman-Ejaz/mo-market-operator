<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title><![CDATA[ {{ config('app.name') }} ]]></title>
        <link><![CDATA[ {{ config('settings.client_app_base_url') }}feed.xml ]]></link>
        <description><![CDATA[ Your website description ]]></description>
        <language>en</language>
        <pubDate>{{ now()->format(config('settings.datetime_format')) }}</pubDate>

        @foreach($todaysPublishedRecords as $record)
            <item>
                <title><![CDATA[{{ $record->title ?? $record->name }}]]></title>
                <link>{{ config('settings.client_app_base_url') }}{{ $record->post_category ? strtolower($record->post_category) . '/' : '' }}{{ $record->slug }}</link>
                <description><![CDATA[{!! $record->description !!}]]></description>
                <pubDate>{{ $record->created_at }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>