<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
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
            'curriculum_id' => 'required|exists:curriculums,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rankings' => 'required|array',
            'rankings.*' => 'exists:rankings,id',
            'sequence' => ['required','integer',Rule::unique('lessons')->where(function ($query) {
                return $query->where('curriculum_id', $this->curriculum_id)
                             ->where('sequence', $this->sequence);
            }),
            ],
            'quiz_sets' => 'required|array|min:1',
            'quiz_sets.*.title' => 'required|string|max:255',
            'quiz_sets.*.description' => 'required|string|max:500',
            'quiz_sets.*.quizzes' => 'required|array|min:1',
            'quiz_sets.*.quizzes.*.question' => 'required|string|max:255',
            'quiz_sets.*.quizzes.*.mandatory' => 'nullable|in:0,1',
            'quiz_sets.*.quizzes.*.explanation' => 'nullable|string',
            'quiz_sets.*.quizzes.*.name' => 'nullable|string|max:255',
            'quiz_sets.*.quizzes.*.image' => 'nullable|image|max:10240',
            'quiz_sets.*.quizzes.*.tip' => 'nullable|string|max:500',
            'quiz_sets.*.quizzes.*.tip_image' => 'nullable|image|max:10240',
            'quiz_sets.*.quizzes.*.options' => 'required|array|min:2',
            'quiz_sets.*.quizzes.*.options.*.option' => 'required|string|max:255',
            'quiz_sets.*.quizzes.*.options.*.is_correct' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'curriculum_id.required' => 'Curriculum là trường bắt buộc.',
            'curriculum_id.exists' => 'Curriculum không tồn tại trong hệ thống.',
            
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'title.string' => 'Tiêu đề phải là một chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            
            'description.string' => 'Mô tả phải là một chuỗi.',
            
            'sequence.required' => 'Sequence là trường bắt buộc.',
            'sequence.integer' => 'Sequence phải là một số nguyên.',
            'sequence.unique' => 'Sequence này đã tồn tại trong curriculum hiện tại.',
            
            'quiz_sets.required' => 'Danh sách quiz sets là bắt buộc.',
            'quiz_sets.array' => 'Quiz sets phải là một mảng.',
            'quiz_sets.min' => 'Ít nhất phải có một quiz set.',
            
            'quiz_sets.*.title.required' => 'Tiêu đề của quiz set là bắt buộc.',
            'quiz_sets.*.title.string' => 'Tiêu đề của quiz set phải là một chuỗi.',
            'quiz_sets.*.title.max' => 'Tiêu đề của quiz set không được vượt quá 255 ký tự.',

            'quiz_sets.*.description.required' => 'Mô tả của mỗi bộ câu hỏi là bắt buộc.',
            'quiz_sets.*.description.string' => 'Mô tả phải là chuỗi ký tự.',
            'quiz_sets.*.description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            
            'quiz_sets.*.quizzes.required' => 'Danh sách quizzes là bắt buộc.',
            'quiz_sets.*.quizzes.array' => 'Quizzes phải là một mảng.',
            'quiz_sets.*.quizzes.min' => 'Ít nhất phải có một quiz.',
            
            'quiz_sets.*.quizzes.*.question.required' => 'Câu hỏi là trường bắt buộc.',
            'quiz_sets.*.quizzes.*.question.string' => 'Câu hỏi phải là một chuỗi.',
            'quiz_sets.*.quizzes.*.question.max' => 'Câu hỏi không được vượt quá 255 ký tự.',
            
            'quiz_sets.*.quizzes.*.mandatory.in' => 'Trường mandatory phải có giá trị là 0 hoặc 1.',
            
            'quiz_sets.*.quizzes.*.explanation.string' => 'Nội dung giải thích phải là một chuỗi.',

            'quiz_sets.*.quizzes.*.name.string' => 'Tên câu hỏi phải là chuỗi ký tự.',
            'quiz_sets.*.quizzes.*.name.max' => 'Tên câu hỏi không được vượt quá 255 ký tự.',
            
            'quiz_sets.*.quizzes.*.image.image' => 'Tệp tải lên phải là một hình ảnh.',
            'quiz_sets.*.quizzes.*.image.max' => 'Kích thước ảnh không được vượt quá 10MB.',

            'quiz_sets.*.quizzes.*.tip.string' => 'Mẹo cho câu hỏi phải là chuỗi ký tự.',
            'quiz_sets.*.quizzes.*.tip.max' => 'Mẹo cho câu hỏi không được vượt quá 500 ký tự.',

            'quiz_sets.*.quizzes.*.tip_image.image' => 'Hình ảnh cho mẹo của câu hỏi tải lên phải là một hình ảnh.',
            'quiz_sets.*.quizzes.*.tip_image.max' => 'Kích thước hình ảnh cho mẹo của câu hỏi không được vượt quá 10MB.',
            
            'quiz_sets.*.quizzes.*.options.required' => 'Danh sách options là bắt buộc.',
            'quiz_sets.*.quizzes.*.options.array' => 'Options phải là một mảng.',
            'quiz_sets.*.quizzes.*.options.min' => 'Ít nhất phải có hai lựa chọn trong options.',
            
            'quiz_sets.*.quizzes.*.options.*.option.required' => 'Option là trường bắt buộc.',
            'quiz_sets.*.quizzes.*.options.*.option.string' => 'Option phải là một chuỗi.',
            'quiz_sets.*.quizzes.*.options.*.option.max' => 'Option không được vượt quá 255 ký tự.',
            
            'quiz_sets.*.quizzes.*.options.*.is_correct.boolean' => 'Giá trị của trường is_correct phải là boolean (true/false).',
        ];
    }
}
