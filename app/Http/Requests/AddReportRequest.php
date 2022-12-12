<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReportRequest extends FormRequest
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
            'category_id' => ["required", "integer"],
            'sub_category_id' => ["required", "integer"],
            'file_attachments' => ['required', 'array'],
            'file_attachmetns.*' => ['file', 'max:25000'],
            // 'attachment_files' => ["array"],
            // 'attachment_files.*' => ["array:name,file"],
            // 'attachment_files.*.name' => ["string"],
            // 'attachment_files.*.file' => ["file"],
        ];
    }
}
