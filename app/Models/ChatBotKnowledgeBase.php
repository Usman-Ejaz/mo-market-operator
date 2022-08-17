<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotKnowledgeBase extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeSearchByKeyword
     *
     * @param  mixed $query
     * @param  mixed $question
     * @return mixed
     */
    public function scopeSearchByKeyword($query, $question)
    {
        $question = explode(" ", strtolower($question));

        $question = $this->unsetCommonWords($question);

        $query->where(function ($q) use ($question) {
            foreach ($question as $part) {
                $q->orWhere('keywords', 'like', "%{$part}%");
            }
        });

        return $query;
    }

    /**
     * ======================================================
     *                  Model Helper Functions
     * ======================================================
     */

    /**
     * unsetCommonWords
     *
     * @param  mixed $questionArray
     * @return mixed
     */
    private function unsetCommonWords($questionArray)
    {
        $commonWords = ['i', 'am', 'what', 'is', 'in', 'who', 'why', 'we', 'are', 'they', 'will', 'shall', 'should', 'this', 'a'];
        foreach ($commonWords as $word) {
            $index = array_search($word, $questionArray);

            if ($index !== false && $index >= 0) {
                unset($questionArray[$index]);
            }
        }
        return $questionArray;
    }
}
