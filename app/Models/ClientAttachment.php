<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    const DIR = 'clients/attachments/';

    public function getFileAttribute($value) 
    {
        return serveFile(self::DIR, $value);
    }    

    public function category() 
    {
        return ucwords(Client::REGISTER_CATEGORIES[$this->category_id]);
    }

    public function scopeFindRecord($query, $clientId, $categoryId, $phrase) 
    {
        return $query->where([
            'client_id' => $clientId, 
            'category_id' => $categoryId, 
            'phrase' => strtolower($phrase)
        ]);
    }

    public function getPhraseAttribute($value) 
    {
        if ($this->category_id === null)
        {
            return $value ? __('client.general_keys.' . $value) : '';
        }

        return $value ? __('client.keys.' . $this->category() . '.' . $value) : "";
    }

}
