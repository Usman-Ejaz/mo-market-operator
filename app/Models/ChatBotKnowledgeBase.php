<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotKnowledgeBase extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Setters
    public function getCreatedAtAttribute ($value) {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }

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
