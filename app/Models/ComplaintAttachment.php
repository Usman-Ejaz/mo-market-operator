<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    public const STORAGE_DIRECTORY = 'complaint-attachments/';

    protected $fillable = ["file_path"];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }
}
