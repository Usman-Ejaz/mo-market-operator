<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $appends = ['category_labels'];

    protected $guarded = [];

    const TYPE = [
        'market_participant',
        'service_provider'
    ];

    /* Register categories e.g Market Participant, Service Provider */
    const REGISTER_CATEGORIES = [
        1 => 'generator',
        2 => 'base_supplier',
        3 => 'pakistani_trader',
        4 => 'bpc',
        5 => 'captive_generator',
        6 => 'competitive_supplier',
        7 => 'international_trader',
        8 => 'transmission_service_provider',
        9 => 'distribution_service_provider',
        10 => 'metering_service_provider'
    ];


    /**
     * ======================================================
     *                 Model Mutators Functions
     * ======================================================
     */


    /**
     * setCategoriesAttribute
     *
     * @param  string $value
     * @return void
     */
    public function setCategoriesAttribute($value)
    {
        if ($value === null || $value === "") {
            $this->attributes['categories'] = null;
            return;
        }

        $value = explode(",", $value);
        $cats = [];

        foreach (self::REGISTER_CATEGORIES as $key => $category) {
            if (in_array($category, $value)) {
                array_push($cats, $key);
            }
        }

        sort($cats);

        $this->attributes['categories'] = implode(",", $cats);
    }

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * Mutates the comma separated ids into comma separated category names.
     *
     * @return string
     */
    public function getCategoryLabelsAttribute(): string
    {
        $value = $this->categories;
        if (!empty($value)) {
            $categories = "";
            $value = explode(',', $value);
            foreach (self::REGISTER_CATEGORIES as $key => $category) {
                if (in_array($key, $value)) {
                    $category = __('client.categories.' . $this->type . '.' . $category);
                    $categories .= $category . ', ';
                }
            }
            $categories = trim($categories, ', ');
            return $categories;
        }
        return __('None');
    }

    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return $value ? Carbon::parse($value)->format(config('settings.createdat_datetime_format')) : "";
    }

    /**
     * getDecSignatureAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getDecSignatureAttribute($value): string
    {
        return $value ? serveFile(ClientDetail::SIGNATURE_DIR, $value) : "";
    }


    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * Checks if client is approved by admin or not.
     *
     * @return boolean true|false
     */
    public function isApproved()
    {
        return $this->approved == 1;
    }

    /**
     * returns the client status either it is approved|pending
     *
     * @return string Approved|Pending
     */
    public function status()
    {
        if ($this->isApproved()) {
            return __('Approved');
        }

        return __('Pending');
    }

    /**
     * generalAttachments
     *
     */
    public function generalAttachments()
    {
        return $this->attachments()->where('category_id', '=', null)->get();
    }

    /**
     * categoryAttachments
     *
     */
    public function categoryAttachments()
    {
        return $this->attachments()
            ->where('category_id', '!=', null)
            ->where('category_id', '!=', '')
            ->orderBy('category_id', 'ASC')
            ->get()
            ->groupBy('category_id');
    }

    /**
     * primaryDetails
     *
     * @return mixed
     */
    public function primaryDetails()
    {
        return $this->details()->where('type', '=', ClientDetail::PRIMARY)->first();
    }

    /**
     * secondaryDetails
     *
     * @return mixed
     */
    public function secondaryDetails()
    {
        return $this->details()->where('type', '=', ClientDetail::SECONDARY)->first();
    }

    /**
     * removeDetails
     *
     * @return void
     */
    public function removeDetails()
    {
        foreach ($this->details as $data) {

            removeFile(ClientDetail::SIGNATURE_DIR, $data->signature);

            $data->delete();
        }
    }

    /**
     * removeAttachments
     *
     * @return void
     */
    public function removeAttachments()
    {
        foreach ($this->attachments as $attachment) {

            removeFile(ClientAttachment::DIR, $attachment->file);

            $attachment->delete();
        }
    }

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */


    /**
     * details
     *
     * @return mixed
     */
    public function details()
    {
        return $this->hasMany(ClientDetail::class, 'client_id', 'id');
    }


    /**
     * attachments
     *
     * @return mixed
     */
    public function attachments()
    {
        return $this->hasMany(ClientAttachment::class);
    }


    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    public function scopeIncomplete($query)
    {
        return $query->where('profile_complete', '=', 0)->with(['details', 'attachments'])->select('id', 'name', 'created_at');
    }
}
