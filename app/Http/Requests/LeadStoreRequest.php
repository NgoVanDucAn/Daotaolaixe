<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadStoreRequest extends FormRequest
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
        $studentId = $this->input('student_id');
        return [
            'name' => 'required|max:50',
            'email' => [
            'required',
            'email',
            'max:255',
            $studentId 
                ? Rule::unique('students', 'email')->ignore($studentId)
                : 'unique:students,email',
            ],
            'phone' => [
                'required',
                'max:20',
                $studentId
                    ? Rule::unique('students', 'phone')->ignore($studentId)
                    : 'unique:students,phone',
            ],
            'address' => 'required|max:255',
            'dob' => 'nullable|date',
            'sale_support' => 'nullable|exists:users,id',
            'lead_source' => 'nullable|exists:lead_sources,id',
            'description' => 'nullable|max:500',
            'student_id' => 'nullable|exists:students,id',
            'interest_level' => 'required|in:1,2,3',
            'status_lead' => 'required|in:1,2,3,4,5',
        ];
    }

    public function messages()
    {
        return [
            'student_id.exists' => 'Học viên đã chọn không tồn tại.',
            'name.required' => 'Tên là bắt buộc.',
            'name.max' => 'Tên không được vượt quá 50 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'sale_support.exists' => 'Người hỗ trợ không hợp lệ.',
            'lead_source.exists' => 'Nguồn khách hàng không hợp lệ.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'interest_level.required' => 'Mức độ quan tâm là bắt buộc.',
            'interest_level.in' => 'Mức độ quan tâm đã chọn không hợp lệ.',
            'status_lead.required' => 'Trạng thái là bắt buộc.',
            'status_lead.in' => 'Trạng thái đã chọn không hợp lệ.',
        ];
    }
}
