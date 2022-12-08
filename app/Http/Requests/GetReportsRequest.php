<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetReportsRequest extends FormRequest
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
            'year' => "integer",
            'page' => 'integer',
            'search' => "string",
            'start_date' => ["required_with:end_date", "date"],
            'end_date' => ["date"],
        ];
    }
}
