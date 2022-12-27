<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = ["subject", "message", "complaint_department_id"];

    public function department()
    {
        return $this->belongsTo(ComplaintDepartment::class, 'complaint_department_id');
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class, 'complaint_id');
    }

    public function complainant()
    {
        return $this->belongsTo(Client::class, 'complainant_id');
    }

    public function scopeForDepartment($query, $departmentID)
    {
        return $query->where('complaint_department_id', $departmentID);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('subject', 'LIKE', "%$searchTerm%")
            ->orWhere('message', 'LIKE', "%$searchTerm%");
    }

    public function scopeForClient($query, Client $client)
    {
        return $query->where('complainant_id', $client->id);
    }
}
