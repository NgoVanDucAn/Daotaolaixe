<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamSetRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'license_level' => 'required|string|max:255',
            'type' => 'required|string|in:Đề thi thử,Đề ôn tập,Câu hỏi ôn tập',
            'description' => 'nullable|string',
            'lesson_id' => 'required|integer|exists:lessons,id',
            'quiz_sets' => 'required|array|min:1',
            'quiz_sets.*' => 'required|array|min:1',
            'quiz_sets.*.*' => 'required|integer|exists:quizzes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập Tên Bộ Đề.',
            'license_level.required' => 'Vui lòng nhập Hạng Bằng.',
            'type.required' => 'Vui lòng chọn Loại Bộ Đề.',
            'lesson_id.required' => 'Vui lòng chọn Bài Học.',
            'lesson_id.exists' => 'Bài Học không hợp lệ.',
            'quiz_sets.required' => 'Vui lòng chọn ít nhất một Bộ Câu Hỏi.',
            'quiz_sets.*.required' => 'Mỗi Bộ Câu Hỏi phải chứa ít nhất một câu hỏi.',
            'quiz_sets.*.*.required' => 'Câu hỏi không hợp lệ.',
            'quiz_sets.*.*.exists' => 'Câu hỏi đã chọn không tồn tại.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->fails()) {
                session()->flash('errors', $validator->errors()->all());
            }
        });
    }
}
