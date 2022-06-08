<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CreatedModifiedBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'designation',
        'department',
        'show_notifications',
        'image',
        'active',
        'created_by',
        'modified_by'
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
         'active' => 1
    ];

    public const STORAGE_DIRECTORY = 'users/';


    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
        
    /**
     * getActiveAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getActiveAttribute($attribute)
    {
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }
    
    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

     /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getImageAttribute($value) 
    {
        return !empty($value) ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }


    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */


    /**
     * role
     *
     * @return mixed
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     * */
    
    /**
     * roles
     *
     * @return mixed
     */
    public function roles()
    {
        return Role::latest()->get();
    }       
    
    /**
     * activeOptions
     *
     * @return mixed
     */
    public function activeOptions()
    {
        return [
            0 => 'Inactive',
            1 => 'Active'
        ];
    }
    
    /**
     * removeImage
     *
     * @return void
     */
    public function removeImage()
    {
        removeFile(self::STORAGE_DIRECTORY, $this->image);
    }


    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */
    
    /**
     * scopeNotifiable
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeNotifiable($query)
    {
        return $query->where('show_notifications', '=', 1);
    }
    
    /**
     * scopeSkipOwnAccount
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeSkipOwnAccount($query)
    {
        return $query->where('id', '!=', auth()->id());
    }
}
