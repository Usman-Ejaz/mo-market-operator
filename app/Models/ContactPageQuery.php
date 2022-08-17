<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactPageQuery extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_ENUMS = ["pending", "inprocess", "resolved"];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * getStatusAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * deleteFromNotifications
     *
     * @return void
     */
    public function deleteFromNotifications()
    {
        $link = str_replace('/', '\/', route('admin.contact-page-queries.show', $this->id));
        $notifications = DB::table('notifications')->where('data->link', 'like', '%' . $link . '%')->get();

        if ($notifications->count() > 0) {
            $notifications->each(function ($notification) {
                DB::table('notifications')->where('id', $notification->id)->delete();
            });
        }
    }
}
