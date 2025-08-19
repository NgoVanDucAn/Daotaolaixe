<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'rank_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            // Nếu là request thêm mới
            $rules['rank_name'] .= '|unique:curriculums,rank_name,NULL,id,title,' . $this->title;
            $rules['title'] .= '|unique:curriculums,title,NULL,id,rank_name,' . $this->rank_name;
        } elseif ($this->isMethod('put')) {
            // Nếu là request cập nhật
            $rules['rank_name'] .= '|unique:curriculums,rank_name,' . $this->route('curriculum') . ',id,title,' . $this->title;
            $rules['title'] .= '|unique:curriculums,title,' . $this->route('curriculum') . ',id,rank_name,' . $this->rank_name;
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Tên giáo trình là bắt buộc.',
            'rank_name.required' => 'Tên hạng giấy phép là bắt buộc.',
            'title.required' => 'Loại giáo trình là bắt buộc.',
            'rank_name.unique' => 'Sự kết hợp giữa tên hạng giấy phép và loại giáo trình đã tồn tại.',
            'title.unique' => 'Sự kết hợp giữa loại giáo trình và tên hạng giấy phép đã tồn tại.',
        ];
    }
}
