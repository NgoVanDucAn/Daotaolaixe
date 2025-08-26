<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamScheduleRequest extends FormRequest
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
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'stadium_id' => 'required|exists:stadiums,id',
            'description' => 'nullable|string',
            'ranking_ids' => 'required|array',
            'ranking_ids.*' => 'exists:rankings,id',
            'exam_field_ids' => 'required|array',
            'exam_field_ids.*' => 'exists:exam_fields,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ranking_ids.required' => 'Vui lòng chọn ít nhất một hạng thi.',
            'ranking_ids.array' => 'Dữ liệu hạng thi không hợp lệ.',
            'ranking_ids.*.exists' => 'Một trong các hạng thi đã chọn không hợp lệ.',

            'exam_field_ids.required' => 'Vui lòng chọn ít nhất một môn thi.',
            'exam_field_ids.array' => 'Dữ liệu môn thi không hợp lệ.',
            'exam_field_ids.*.exists' => 'Một trong các môn thi đã chọn không hợp lệ.',

            'start_time.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_time.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_time.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_time.date' => 'Ngày kết thúc không hợp lệ.',
            'end_time.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
            'stadium_id.required' => 'Vui lòng chọn sân thi.',
            'stadium_id.exists' => 'Sân thi không hợp lệ.',
        ];
    }
}
