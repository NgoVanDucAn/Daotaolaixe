<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
            'code' => 'required|unique:courses,code,' . $this->route('course')->id,
            'ranking_id' => 'required|exists:rankings,id',
            'date_bci' => 'nullable|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'decision_kg' => 'nullable|string',
            'tuition_fee' => 'required|integer',
            'cabin_date' => [
                'nullable', 'date',
                $this->dateWithinCourseRange('cabin_date', 'Ngày học Cabin')
            ],

            'dat_date' => [
                'nullable', 'date',
                $this->dateWithinCourseRange('dat_date', 'Ngày học DAT')
            ],
        ];
    }

    protected function dateWithinCourseRange(string $field, string $label): \Closure
    {
        return function ($attribute, $value, $fail) use ($label) {
            $start = $this->input('start_date');
            $end = $this->input('end_date');

            if ($value) {
                if ($start && $value < $start) {
                    $fail("$label phải lớn hơn hoặc bằng ngày bắt đầu.");
                }
                if ($end && $value > $end) {
                    $fail("$label phải nhỏ hơn hoặc bằng ngày kết thúc.");
                }
            }
        };
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Mã khóa học không được để trống.',
            'code.unique' => 'Mã khóa học đã tồn tại, vui lòng chọn mã khác.',
            'date_bci.date' => 'Ngày bắt đầu báo cáo phải là định dạng ngày hợp lệ.',
            'start_date.required' => 'Ngày bắt đầu không được để trống.',
            'start_date.date' => 'Ngày bắt đầu phải là định dạng ngày hợp lệ.',
            'end_date.required' => 'Ngày kết thúc không được để trống.',
            'end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ.',
            'decision_kg.string' => 'Quyết định khóa học phải là chuỗi ký tự.',
            'tuition_fee.required' => 'Học phí không được để trống.',
            'tuition_fee.integer' => 'Học phí phải là số nguyên.',
            'cabin_date.date' => 'Ngày học Cabin không hợp lệ.',
            'dat_date.date' => 'Ngày học DAT không hợp lệ.',
        ];
    }
}
