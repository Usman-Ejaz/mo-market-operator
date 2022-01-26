<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function users(){
        return $this->hasMany(User::class);
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function hasPermission($moduleName, $capability)
    {
        $permissionExists = $this->permissions->where('name', $moduleName)->where('capability', $capability)->first();
        if($permissionExists){
            return true;
        }
        return false;
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }
}
