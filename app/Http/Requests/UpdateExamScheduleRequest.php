<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamScheduleRequest extends FormRequest
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
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'stadium_id' => 'required|exists:stadiums,id',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,canceled',
            'ranking_ids' => 'required|array',
            'ranking_ids.*' => 'exists:rankings,id',
            'exam_field_ids' => 'required|array',
            'exam_field_ids.*' => 'exists:exam_fields,id',
        ];
    }
}
