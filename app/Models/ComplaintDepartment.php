<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintDepartment extends Model
{
    protected $guarded = ['created_at', 'updated_at', 'id'];

    public function pm()
    {
        return $this->belongsTo(User::class, 'pm_id', 'id');
    }

    public function apm()
    {
        return $this->belongsTo(User::class, 'apm_id', 'id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'complaint_department_id');
    }
}
