<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAttributeType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $appends = ["allowed_values"];

    public function attributes()
    {
        return $this->hasMany(ReportAttribute::class);
    }

    //TODO: Maybe in the future add another table for storing the allowed values for a type or a separate system all together for data types
    public function getAllowedValuesAttribute()
    {
        $values = [];
        switch ($this->name) {
            case "month":
                $values = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
                break;
            case "year":
                $startingYear = now()->year - 5;
                for ($i = 0; $i < 15; $i++) {
                    $values[] = $startingYear++;
                }
                break;
            default:
                $values = null;
        }

        return $values;
    }
}
