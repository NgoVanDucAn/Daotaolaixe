<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $this->vehicle->id,
            'model' => 'required|string|max:100',
            'ranking_id' => 'required|exists:rankings,id',
            'type' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'training_license_number' => 'nullable|string|max:50|unique:vehicles,training_license_number,' . $this->vehicle->id,
            'manufacture_year' => 'required|digits:4|integer|min:1980|max:' . date('Y'),
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'license_plate.required' => 'Vui lòng nhập biển số xe.',
            'license_plate.string' => 'Biển số xe phải là chuỗi ký tự.',
            'license_plate.max' => 'Biển số xe không được vượt quá 20 ký tự.',
            'license_plate.unique' => 'Biển số xe đã tồn tại.',

            'model.required' => 'Vui lòng nhập model xe.',
            'model.string' => 'Model phải là chuỗi ký tự.',
            'model.max' => 'Model không được vượt quá 100 ký tự.',

            'ranking_id.required' => 'Vui lòng chọn hạng xe.',
            'ranking_id.exists' => 'Hạng xe không hợp lệ.',

            'type.required' => 'Vui lòng nhập loại xe.',
            'type.string' => 'Loại xe phải là chuỗi ký tự.',
            'type.max' => 'Loại xe không được vượt quá 50 ký tự.',

            'color.required' => 'Vui lòng nhập màu sắc xe.',
            'color.string' => 'Màu sắc phải là chuỗi ký tự.',
            'color.max' => 'Màu sắc không được vượt quá 50 ký tự.',

            'training_license_number.string' => 'Số giấy phép tập lái phải là chuỗi ký tự.',
            'training_license_number.max' => 'Số giấy phép tập lái không được vượt quá 50 ký tự.',
            'training_license_number.unique' => 'Số giấy phép tập lái đã tồn tại.',

            'manufacture_year.required' => 'Vui lòng nhập năm sản xuất.',
            'manufacture_year.digits' => 'Năm sản xuất phải gồm 4 chữ số.',
            'manufacture_year.integer' => 'Năm sản xuất phải là số nguyên.',
            'manufacture_year.min' => 'Năm sản xuất không được nhỏ hơn 1980.',
            'manufacture_year.max' => 'Năm sản xuất không được lớn hơn năm hiện tại.',

            'description.string' => 'Mô tả phải là chuỗi ký tự.',
        ];
    }
}
