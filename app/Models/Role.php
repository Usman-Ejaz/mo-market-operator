<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * users
     *
     * @return void
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * permissions
     *
     * @return void
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeOrderByName
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('name', 'asc');
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * hasPermission
     *
     * @param  mixed $moduleName
     * @param  mixed $capability
     * @return void
     */
    public function hasPermission($moduleName, $capability)
    {
        $permissionExists = $this->permissions->where('name', $moduleName)->where('capability', $capability)->first();
        if ($permissionExists) {
            return true;
        }
        return false;
    }
}
