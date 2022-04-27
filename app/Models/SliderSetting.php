<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TRANSITIONS = [
        1 => ['label' => 'Ease', 'name' => 'ease'],
        2 => ['label' => 'Linear', 'name' => 'linear'],
        3 => ['label' => 'Ease In', 'name' => 'ease-in'],
        4 => ['label' => 'Ease Out', 'name' => 'ease-out'],
        5 => ['label' => 'Ease In Out', 'name' => 'ease-in-out']
    ];

    public static function get()
    {
        $settings = self::select('transition', 'speed')->first();
        $settings->transition = self::TRANSITIONS[$settings->transition]['name'];
        return $settings;
    }
}
