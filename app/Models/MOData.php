<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MOData extends Model
{
    use HasFactory;

    protected $table = 'm_o_datas';
    protected $guarded = ['created_at', 'updated_at'];

    public function files()
    {
        return $this->hasMany(MODataFiles::class);
    }

    public function extraAttributes()
    {
        return $this->hasMany(MODataExtraAttribute::class);
    }
}
