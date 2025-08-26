<?php

namespace App\Http\Requests;

use App\Models\Ranking;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
        if ($this->filled('ranking_id')) {
            $ranking = Ranking::find($this->input('ranking_id'));
            $vehicleType = $ranking?->vehicle_type;
        }
        
        $rules = [
            'code' => 'required|unique:courses,code',
            'ranking_id' => 'required|exists:rankings,id',
            'date_bci' => 'nullable|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'decision_kg' => 'nullable|string',
            'tuition_fee' => 'required|integer',
        ];

        if ($vehicleType == 1) {
            $rules['cabin_date'] = [
                'nullable', 'date',
                $this->dateWithinCourseRange('cabin_date', 'Ngày học Cabin')
            ];
            $rules['dat_date'] = [
                'nullable', 'date',
                $this->dateWithinCourseRange('dat_date', 'Ngày học DAT')
            ];
        }
        
        return $rules;
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
            'code.required' => 'Mã khóa học là bắt buộc.',
            'code.unique' => 'Mã khóa học đã tồn tại.',
            'date_bci.date' => 'Ngày bắt đầu báo cáo không hợp lệ.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'decision_kg.string' => 'Quyết định khóa học phải là chuỗi.',
            'tuition_fee.required' => 'Học phí là bắt buộc.',
            'tuition_fee.integer' => 'Học phí phải là số nguyên.',
            'cabin_date.date' => 'Ngày học Cabin không hợp lệ.',
            'dat_date.date' => 'Ngày học DAT không hợp lệ.',
        ];
    }
}
