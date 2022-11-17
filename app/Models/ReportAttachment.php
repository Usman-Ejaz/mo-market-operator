<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAttachment extends Model
{
    use HasFactory;

    public const STORAGE_DIRECTORY = 'reports/';

    protected $fillable = ['name', 'file_path'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
