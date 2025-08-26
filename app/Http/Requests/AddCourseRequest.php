<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCourseRequest extends FormRequest
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
            'student_id'       => 'required|exists:students,id',
            'course_id'        => 'required|exists:courses,id',
            'tuition_fee'      => 'required|numeric|min:0',
            'health_check_date'=> 'nullable|date',
            'contract_date'    => 'nullable|date',
            'stadium_id'       => 'nullable|exists:stadiums,id',
            'learn_teacher_id' => 'nullable|exists:users,id',
            'sale_id'          => 'nullable|exists:users,id',
            'note'             => 'nullable|string',
            'give_chip_hour'   => 'nullable|date_format:H:i',
            'order_chip_hour'  => 'nullable|date_format:H:i',
            'paid_fee'  => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Học viên là bắt buộc.',
            'student_id.exists'   => 'Học viên không tồn tại.',
            'course_id.required'  => 'Khóa học là bắt buộc.',
            'course_id.exists'    => 'Khóa học không tồn tại.',
            'tuition_fee.required'=> 'Học phí là bắt buộc.',
            'tuition_fee.numeric' => 'Học phí phải là số.',
            'tuition_fee.min'     => 'Học phí phải lớn hơn hoặc bằng 0.',
            'health_check_date.date' => 'Ngày khám sức khỏe không hợp lệ.',
            'contract_date.date'  => 'Ngày hợp đồng không hợp lệ.',
            'give_chip_hour.date_format'  => 'Giờ chip tặng phải theo định dạng HH:ii.',
            'order_chip_hour.date_format' => 'Giờ chip đặt phải theo định dạng HH:ii.',
        ];
    }
}
