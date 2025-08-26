<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:10|max:15|unique:students,phone,',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'identity_card' => 'nullable|string|min:9|max:12|unique:students,identity_card',
            'address' => 'nullable|string|max:255',
            'role' => 'required|exists:roles,name',
        ];

        if ($this->input('role') === 'instructor') {
            $rules['ranking_ids'] = ['nullable', 'array'];
            $rules['ranking_ids.*'] = ['exists:rankings,id'];
            $rules['vehicle_id'] = ['nullable', 'exists:vehicles,id'];
        }
    
        return $rules;
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Trường tên là bắt buộc.',
            'name.string' => 'Họ tên phải là chuỗi.',
            'name.max' => 'Tên không được vượt quá 50 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Vui lòng nhập một địa chỉ email hợp lệ.',
            'email.max' => 'Email không được quá 255 ký tự.',
            'email.unique' => 'Email này đã được đăng ký.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi.',
            'phone.min' => 'Số điện thoại không được dưới 10 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'image.max' => 'Dung lượng hình ảnh không được vượt quá 2MB.',
            'dob.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'dob.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'identity_card.string' => 'CMND/CCCD phải là chuỗi.',
            'identity_card.min' => 'CMND/CCCD phải có ít nhất 9 số.',
            'identity_card.max' => 'CMND/CCCD không được vượt quá 12 số.',
            'identity_card.unique' => 'CMND/CCCD đã tồn tại.',
            'address.string' => 'Địa chỉ phải là chuỗi.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'role.required' => 'Bạn phải chọn một vai trò.',
            'role.exists' => 'Vai trò đã chọn không hợp lệ.',
            'ranking_ids.array' => 'Hạng bằng phải là một danh sách.',
            'ranking_ids.*.exists' => 'Một hoặc nhiều hạng bằng không hợp lệ.',
            'vehicle_id.exists' => 'Xe đã chọn không hợp lệ.',
        ];
    }
}
