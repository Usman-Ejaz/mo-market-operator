<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * get_option
     *
     * @param  mixed $name
     * @return mixed
     */
    public static function get_option($name)
    {
        $setting = Settings::where(['name' => $name])->first();
        return $setting ? $setting->value : null;
    }

    /**
     * update_option
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return mixed
     */
    public static function update_option($name, $value)
    {
        $setting = Settings::updateOrCreate(['name' => $name], ['value' => $value]);
        return !empty($setting->getChanges());
    }
}
