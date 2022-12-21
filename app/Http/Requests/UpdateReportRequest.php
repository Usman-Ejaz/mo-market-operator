<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            'name' => ["required", "string"],
            'publish_date' => ["required", "date"],
            'report_attributes' => ["required", "array"],
            'report_attributes.*' => ["string"],
            'file_attachments' => ["array"],
            'file_attachments.*' => ["file", 'mimes:xls,xlsx,pdf,doc,docx', "max:25000"],
        ];
    }

    public function messages()
    {
        return [
            'file_attachments.*.mimes' => "The attached file must be: xls,xlsx,pdf,doc,docx.",
        ];
    }
}
