<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeRequest extends FormRequest
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
        $rules = [
            'payment_date' => 'required|date',
            'amount' => 'required|integer|min:1',
            'collector_id' => 'required|exists:users,id',
            'fee_type' => 'required|integer|between:1,8',
            'is_received' => 'nullable|boolean',
            'note' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['course_student_id'] = 'required|exists:course_students,id';
        }
    
        return $rules;
    }

    public function messages(): array
    {
        return [
            'payment_date.required' => 'Ngày thanh toán là bắt buộc.',
            'payment_date.date' => 'Ngày thanh toán không hợp lệ.',

            'amount.required' => 'Số tiền là bắt buộc.',
            'amount.integer' => 'Số tiền phải là số nguyên.',
            'amount.min' => 'Số tiền tối thiểu là 1.',

            'fee_type.required' => 'Loại thu là bắt buộc.',
            'fee_type.integer' => 'Loại thu phải là số.',
            'fee_type.between' => 'Loại thu không hợp lệ.',

            'collector_id.required' => 'Vui lòng chọn người thu.',
            'collector_id.exists' => 'Người thu không tồn tại trong hệ thống.',
            
            'course_student_id.exists' => 'Khóa học của học viên không hợp lệ.',
            'course_student_id.exists' => 'Khóa học không hợp lệ.',

            'is_received.boolean' => 'Giá trị không hợp lệ.',

            'note.string' => 'Ghi chú phải là chuỗi.',
        ];
    }
}
