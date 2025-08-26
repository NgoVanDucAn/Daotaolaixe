<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleExpenseRequest extends FormRequest
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
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|in:simulation,refuel,maintenance,inspection,tire_replacement,other',
            'time' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('note', 'required|string|min:3', function ($input) {
            return $input->type == 'other';
        });
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vui lòng chọn phương tiện.',
            'vehicle_id.exists' => 'Phương tiện đã chọn không tồn tại.',

            'type.required' => 'Vui lòng chọn loại chi phí.',
            'type.in' => 'Loại chi phí không hợp lệ. Các loại hợp lệ gồm: sa hình, đổ xăng, bảo dưỡng, kiểm định, thay lốp, hoặc khác.',

            'time.required' => 'Vui lòng chọn ngày.',
            'time.date' => 'Ngày không đúng định dạng.',

            'user_id.required' => 'Vui lòng chọn người thực hiện.',
            'user_id.exists' => 'Người thực hiện không tồn tại.',

            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.numeric' => 'Số tiền phải là một số.',
            'amount.min' => 'Số tiền phải lớn hơn hoặc bằng 0.',

            'note.string' => 'Ghi chú phải là chuỗi ký tự.',
            'note.required' => 'Ghi chú là bắt buộc nếu chọn loại chi phí là "khác".',
        ];
    }
}
