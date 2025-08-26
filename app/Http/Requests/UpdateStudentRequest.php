<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:students,email,' . $this->route('student')->id,
            'phone' => 'required|string|min:10|max:15|unique:students,phone,' . $this->route('student')->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'identity_card' => 'nullable|string|min:9|max:12|unique:students,identity_card,' . $this->route('student')->id,
            'address' => 'nullable|string|max:255',
            'card_id' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'nullable|string',
            'date_of_profile_set' => 'nullable|date',
            'fee_ranking' => 'required|numeric|min:0',
            'paid_fee' => 'nullable|numeric|min:0',
        ];
    }

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
            'card_id.integer' => 'Số thẻ phải là số.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái đã chọn không hợp lệ.',
            'password.string' => 'Mật khẩu phải là chuỗi.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'date_of_profile_set.date' => 'Ngày đăng ký hồ sơ phải là một ngày hợp lệ.',
            'fee_ranking.required' => 'Vui lòng nhập số tiền học phí theo hạng.',
            'fee_ranking.numeric'  => 'Tổng lệ phí hồ sơ phải là số.',
            'fee_ranking.min'      => 'Tổng lệ phí hồ sơ không được nhỏ hơn 0.',
            'paid_fee.numeric'  => 'Lệ phí đã đóng phải là một số.',
            'paid_fee.min'      => 'Lệ phí đã đóng không được nhỏ hơn 0.',
        ];
    }
}
