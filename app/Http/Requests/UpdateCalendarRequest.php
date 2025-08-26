<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreCalendarRequest extends FormRequest
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
            'type' => 'required|in:study,exam,work,meeting,call',
            'name' => 'required|string|min:3',
            'priority' => 'required|in:Low,Normal,High,Urgent',
            'location' => 'required|string|min:3',
            'description' => 'nullable|string',
        ];

        if ($this->type === 'study') {
            $rules['date_start'] = 'required|date';
            $rules['date_end'] = 'required|date|after_or_equal:date_start';
            $rules['vehicle_select'] = 'nullable|exists:vehicles,id';
            $rules['stadium_id'] = 'nullable|exists:stadiums,id';
            $rules['learning_id'] = 'required|exists:learning_fields,id';
            $rules['learn_course_id'] = 'required|exists:courses,id';
            $rules['learn_teacher_id'] = 'required|exists:users,id';
            $rules['learn_student_id'] = 'required|array|min:1';
            $rules['learn_student_id.*'] = 'required|exists:students,id';
        } elseif ($this->type === 'exam') {
            $rules['date'] = 'required|date';
            $rules['time'] = 'required|in:1,2,3';
            $rules['exam_id'] = 'required|exists:exam_fields,id';
            $rules['exam_fee'] = 'required|numeric|min:0';
            $rules['exam_fee_deadline'] = 'required|date|after_or_equal:today';
            $rules['exam_schedule_id'] = 'required|exists:exam_schedules,id';
            $rules['exam_course_id'] = 'required|exists:courses,id';
            $rules['exam_teacher_id'] = 'required|exists:users,id';
            $rules['exam_student_id'] = 'required|array|min:1';
            $rules['exam_student_id.*'] = 'required|exists:students,id';
            foreach ($this->input('exam_student_id', []) as $studentId) {
                $rules["students.$studentId.exam_number"] = 'required|string|max:255';
                $rules["students.$studentId.exam_fee_paid_at"] = 'required|date';
            }
        } elseif ($this->type === 'work') {
            $rules['date_start'] = 'required|date';
            $rules['date_end'] = 'required|date|after_or_equal:date_start';
            $rules['work_assigned_to'] = 'required|exists:users,id';
            $rules['work_support'] = 'nullable|exists:users,id';
        } elseif ($this->type === 'meeting') {
            $rules['date_start'] = 'required|date';
            $rules['date_end'] = 'required|date|after_or_equal:date_start';
            $rules['meeting_assigned_to'] = 'required|exists:users,id';
            $rules['meeting_support'] = 'nullable|exists:users,id';
            $rules['user_participants'] = 'nullable|exists:users,id';
            $rules['student_participants'] = 'nullable|array';
            $rules['student_participants.*'] = 'nullable|exists:students,id';
        } elseif ($this->type === 'call') {
            $rules['date_start'] = 'required|date';
            $rules['date_end'] = 'required|date|after_or_equal:date_start';
            $rules['call_sale_id'] = 'required|exists:users,id';
            $rules['call_student_id'] = 'required|exists:students,id';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = Carbon::parse($this->input('date_start'))->toDateString();
            $end = Carbon::parse($this->input('date_end'))->toDateString();

            if (Carbon::parse($start)->diffInDays(Carbon::parse($end)) > 1) {
                $validator->errors()->add('date_end', 'Ngày kết thúc không được cách ngày bắt đầu quá 1 ngày.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Loại lịch là bắt buộc.',
            'type.in' => 'Loại lịch không hợp lệ.',

            'name.required' => 'Tên lịch là bắt buộc.',
            'name.string' => 'Tên lịch phải là chuỗi ký tự.',
            'name.min' => 'Tên lịch phải có ít nhất 3 ký tự.',

            'priority.required' => 'Mức độ ưu tiên là bắt buộc.',
            'priority.in' => 'Mức độ ưu tiên không hợp lệ.',

            'date_start.required' => 'Ngày bắt đầu là bắt buộc.',
            'date_start.date' => 'Ngày bắt đầu phải có định dạng hợp lệ.',

            'date_end.required' => 'Ngày kết thúc là bắt buộc.',
            'date_end.date' => 'Ngày kết thúc phải có định dạng hợp lệ.',
            'date_end.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',

            'date.required' => 'Ngày thi là bắt buộc.',
            'date.date' => 'Ngày thi phải có định dạng hợp lệ.',

            'time.required' => 'Thời gian thi là bắt buộc.',
            'time.in' => 'Thời gian thi chỉ có thể là buổi sáng, buổi chiều hoặc cả ngày.',
            
            'location.required' => 'Địa điểm là bắt buộc.',
            'location.string' => 'Địa điểm phải là chuỗi ký tự.',
            'location.min' => 'Địa điểm phải có ít nhất 3 ký tự.',

            'description.string' => 'Mô tả phải là chuỗi ký tự.',

            // Messages for 'study' type
            'learning_id.required' => 'Môn học là bắt buộc khi loại là lịch học.',
            'learning_id.exists' => 'Môn học không tồn tại.',
            'learn_course_id.required' => 'Khoá học là bắt buộc khi loại là lịch học.',
            'learn_course_id.exists' => 'Khoá học không tồn tại.',
            'learn_teacher_id.required' => 'Giáo viên là bắt buộc khi loại là lịch học.',
            'learn_teacher_id.exists' => 'Giáo viên không tồn tại.',
            'learn_student_id.required' => 'Danh sách học viên là bắt buộc.',
            'learn_student_id.array' => 'Danh sách học viên phải là một mảng.',
            'learn_student_id.*.exists' => 'Có học viên không tồn tại.',

            // Messages for 'exam' type
            'exam_fee.required' => 'Vui lòng nhập lệ phí thi.',
            'exam_fee.numeric' => 'Lệ phí thi phải là một số.',
            'exam_fee.min' => 'Lệ phí thi không được nhỏ hơn 0.',
            'exam_fee_deadline.required' => 'Vui lòng chọn hạn nộp lệ phí.',
            'exam_fee_deadline.date' => 'Hạn nộp lệ phí phải là ngày hợp lệ.',
            'exam_fee_deadline.after_or_equal' => 'Hạn nộp lệ phí không được nhỏ hơn ngày hôm nay.',
            'exam_id.required' => 'Kỳ thi là bắt buộc khi loại là lịch thi.',
            'exam_id.exists' => 'Kỳ thi không tồn tại.',
            'exam_course_id.required' => 'Khoá học là bắt buộc khi loại là lịch thi.',
            'exam_course_id.exists' => 'Khoá học không tồn tại.',
            'exam_teacher_id.required' => 'Giáo viên là bắt buộc khi loại là lịch thi.',
            'exam_teacher_id.exists' => 'Giáo viên không tồn tại.',
            'exam_student_id.required' => 'Danh sách học viên là bắt buộc.',
            'exam_student_id.array' => 'Danh sách học viên phải là một mảng.',
            'exam_student_id.*.exists' => 'Có học viên không tồn tại.',
            'students.*.exam_number.required' => 'Số báo danh là bắt buộc.',
            'students.*.exam_number.string' => 'Số báo danh phải là chuỗi.',
            'students.*.exam_number.min' => 'Số báo danh phải có tối thiểu 5 ký tự.',
            'students.*.exam_number.max' => 'Số báo danh không được vượt quá 20 ký tự.',
            'students.*.exam_fee_paid_at.required' => 'Ngày nộp lệ phí là bắt buộc.',
            'students.*.exam_fee_paid_at.date_format' => 'Ngày nộp lệ phí phải có định dạng hợp lệ (Y-m-d\TH:i).',

            // Messages for 'work' type
            'work_assigned_to.required' => 'Người được giao công việc là bắt buộc khi loại là lịch công việc.',
            'work_assigned_to.exists' => 'Người được giao công việc không tồn tại.',
            'work_support.exists' => 'Người hỗ trợ không tồn tại.',

            // Messages for 'meeting' type
            'meeting_assigned_to.required' => 'Người được giao tổ chức cuộc họp là bắt buộc khi loại là lịch họp.',
            'meeting_assigned_to.exists' => 'Người được giao tổ chức không tồn tại.',
            'meeting_support.exists' => 'Người hỗ trợ không tồn tại.',
            'user_participants.exists' => 'Người tham gia cuộc họp không tồn tại.',
            'student_participants.array' => 'Danh sách học viên phải là một mảng.',
            'student_participants.*.exists' => 'Có học viên không tồn tại.',

            // Messages for 'call' type
            'call_sale_id.required' => 'Nhân viên sale là bắt buộc khi loại là gọi.',
            'call_sale_id.exists' => 'Nhân viên sale không tồn tại.',
            'call_student_id.required' => 'Học viên là bắt buộc khi loại là gọi.',
            'call_student_id.exists' => 'Học viên không tồn tại.',
        ];
    }
}
