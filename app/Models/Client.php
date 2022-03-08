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

    const SIGNATURE_DIR = 'clients/signatures/';

    const TYPE = [
        'market_participant', 
        'service_provider'
    ];
    
    /* Register categories e.g Market Participant, Service Provider */
    const REGISTER_CATEGORIES = [
        1 => 'none',
        2 => 'generator',
        3 => 'base supplier', 
        4 => 'pakistani trader', 
        5 => 'bpc', 
        6 => 'captive generator', 
        7 => 'competitive supplier', 
        8 => 'international trader',
        
        9 => 'none',
        10 => 'transmission service provider', 
        11 => 'distribution service provider', 
        12 => 'metering service provider'
    ];


    /**
     * Mutates the categories array into comma separated category ids.
     *
     * @param  array $value
     * @return void
     */
    // public function setCategoriesAttribute($value)
    // {
    //     $ids = '';
    //     foreach (self::REGISTER_CATEGORIES as $key => $category) {
    //         if (in_array($category, $value)) {
    //             $ids .= $key . ',';
    //         }
    //     }
    //     $ids = trim($ids, ',');
    //     $this->categories = $ids;
    // }
        
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
                    $categories .= $category . ',';
                }
            }
            $categories = trim($categories, ',');
            return $categories;
        }
        return "";
    }
    
    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : "";
    }
    
    /**
     * Checks if client is approved by admin or not.
     *
     * @return boolean true|false
     */
    public function isApproved() {
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
}
