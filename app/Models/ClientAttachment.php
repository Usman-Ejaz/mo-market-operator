<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['phrase_string'];

    const DIR = 'clients/attachments/';

    public function getFileAttribute($value) 
    {
        return serveFile(self::DIR, $value);
    }    

    public function category() 
    {
        return ucwords(Client::REGISTER_CATEGORIES[$this->category_id]);
    }

    public function getPhraseStringAttribute()
    {
        if ($this->category_id === null)
        {
            return $this->phrase ? __('client.general_keys.' . $this->phrase) : '';
        }

        return $this->phrase ? __('client.keys.' . strtolower($this->category()) . '.' . $this->phrase) : "";
    }

}
