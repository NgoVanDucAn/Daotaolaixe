<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTipRequest extends FormRequest
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
            'tips' => 'required|array',
            'tips.*.type_tip' => 'required|in:1,2,3',
            'tips.*.content_question' => 'required|string',
            'tips.*.question' => 'required|string',
            'tips.*.quiz_set_id' => 'nullable|exists:quiz_sets,id',
            'tips.*.page_id' => 'nullable|exists:pages,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->input('tips', []) as $index => $tip) {
                $type = $tip['type_tip'] ?? null;

                if (in_array($type, [1, 2]) && empty($tip['quiz_set_id'])) {
                    $validator->errors()->add("tips.$index.quiz_set_id", "Bắt buộc chọn chương khi loại mẹo là 1 hoặc 2.");
                }

                if ($type == 3 && empty($tip['page_id'])) {
                    $validator->errors()->add("tips.$index.page_id", "Bắt buộc chọn trang mô phỏng khi loại mẹo là 3.");
                }
            }
        });
    }
}
