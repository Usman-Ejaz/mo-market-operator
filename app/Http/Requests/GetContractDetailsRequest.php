<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetContractDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sub_category_id' => 'integer',
            'month' => 'string',
            'page' => 'integer',
        ];
    }
}
