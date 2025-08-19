<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'phone' => 'required|string|min:10|max:15|unique:users,phone,' . $this->user->id,
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date|before:today',
            'identity_card' => 'nullable|string|min:9|max:12|unique:users,identity_card,' . $this->user->id,
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|exists:roles,name',
            'ranking_ids'   => ['nullable', 'array'],
            'ranking_ids.*' => ['exists:rankings,id'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
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
            'password.string' => 'Mật khẩu phải là chuỗi.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
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
        ];
    }
}
