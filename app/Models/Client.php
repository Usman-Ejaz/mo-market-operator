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
        1 => 'generator',
        2 => 'base supplier', 
        3 => 'pakistani trader', 
        4 => 'bpc', 
        5 => 'captive generator', 
        6 => 'competitive supplier', 
        7 => 'international trader',
                
        8 => 'transmission service provider', 
        9 => 'distribution service provider', 
        10 => 'metering service provider'
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
                    $categories .= $category . ', ';
                }
            }
            $categories = trim($categories, ', ');
            return ucwords($categories);
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
     * getPriSignatureAttribute
     *
     * @param  mixed $value
     * @return string
     */
    public function getPriSignatureAttribute($value): string {
        return serveFile(self::SIGNATURE_DIR, $value);
    }
    
    /**
     * getSecSignatureAttribute
     *
     * @param  mixed $value
     * @return string
     */
    public function getSecSignatureAttribute($value): string {
        return serveFile(self::SIGNATURE_DIR, $value);
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
    
    /**
     * attachments
     * 
     */
    public function attachments() 
    {
        return $this->hasMany(ClientAttachment::class);
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
            ->orderBy('category_id', 'ASC')
            ->get()
            ->groupBy('category_id');
    }
}
