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

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
    
    /**
     * getFileAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getFileAttribute($value) 
    {
        return serveFile(self::DIR, $value);
    }
    
    /**
     * getPhraseStringAttribute
     *
     * @return mixed
     */
    public function getPhraseStringAttribute()
    {
        if ($this->category_id === null)
        {
            return $this->phrase ? __('client.general_keys.' . $this->phrase) : '';
        }

        return $this->phrase ? __('client.keys.' . strtolower($this->category()) . '.' . $this->phrase) : "";
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */
    
    /**
     * category
     *
     * @return mixed
     */
    public function category() 
    {
        return ucwords(Client::REGISTER_CATEGORIES[$this->category_id]);
    }
}
