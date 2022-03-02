<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;

class SitewideSearch extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        \App\Models\Page::class,
        \App\Models\News::class,
        \App\Models\Job::class,
        \App\Models\Faq::class,
    ];
}
