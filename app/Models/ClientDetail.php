<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    use HasFactory;

    const PRIMARY = 'primary';
    const SECONDARY = 'secondary';
    const SIGNATURE_DIR = 'clients/signatures/';

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
    
    /**
     * getPriSignatureAttribute
     *
     * @param  mixed $value
     * @return string
     */
    public function getSignatureAttribute($value): string 
    {
        return isset($value) ? serveFile(self::SIGNATURE_DIR, $value) : null;
    }
}
