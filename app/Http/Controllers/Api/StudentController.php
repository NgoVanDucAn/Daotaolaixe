<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\StudentController as StudentInfor;
use App\Models\Calendar;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('courses:id,course_system_code,code,shlx_course_id')
            ->whereHas('courses', function ($query) {
                $query->whereNotNull('course_system_code');
            })
            ->whereNotNull('card_id')
            ->select('id', 'name', 'student_code', 'card_id', 'trainee_id', 'email', 'phone')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'student_code' => $student->student_code,
                    'shlx_card_id' => $student->card_id,
                    'shlx_trainee_id' => $student->trainee_id,
                    'courses' => $student->courses->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'course_code' => $course->code,
                            'shlx_course_id' => $course->shlx_course_id,
                            'shlx_course_system_code' => $course->course_system_code
                        ];
                    })
                ];
            })
        ]);
    }

    public function show($id)
    {
        $student = Student::select('id', 'name', 'student_code', 'email', 'phone', 'image', 'gender', 'dob', 'identity_card', 'address', 'status', 'lead_source')
            ->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Học viên không tồn tại hoặc không hợp lệ.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'name' => $student->name,
                'student_code' => $student->student_code,
                'email' => $student->email,
                'phone' => $student->phone,
                'image' => !empty($student->image) ? "https://cdn.dtlx.app/" . $student->image : null,
                'gender' => $student->gender,
                'dob' => $student->dob,
                'identity_card' => $student->identity_card,
                'address' => $student->address,
                'status' => $student->status,
                'lead_source' => $student->lead_source,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        $studentInfor = new StudentInfor();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Học viên không tồn tại.'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|max:50',
                'email' => 'required|email|unique:students,email,' . $id,
                'phone' => 'required|min:10|max:15|unique:students,phone,' . $id,
                'gender' => 'required|in:male,female,other',
                'dob' => 'required|date|before:today',
                'identity_card' => 'nullable|string|min:9|max:12|unique:students,identity_card,' . $this->route('student')->id,
                'address' => 'required|string|max:255',
            ], [
                'name.required' => 'Tên học viên không được bỏ trống.',
                'name.max' => 'Tên học viên tối đa 50 ký tự.',

                'email.required' => 'Email không được bỏ trống.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email đã tồn tại trong hệ thống.',

                'phone.required' => 'Số điện thoại không được bỏ trống.',
                'phone.min' => 'Số điện thoại tối thiểu 10 số.',
                'phone.max' => 'Số điện thoại tối đa 15 số.',
                'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống.',

                'gender.required' => 'Giới tính là bắt buộc.',
                'gender.in' => 'Giới tính không hợp lệ.',

                'dob.required' => 'Ngày sinh không được bỏ trống.',
                'dob.date' => 'Ngày sinh không đúng định dạng.',
                'dob.before' => 'Ngày sinh phải trước ngày hiện tại.',

                'identity_card.string' => 'CMND/CCCD phải là chuỗi.',
                'identity_card.min' => 'CMND/CCCD phải có ít nhất 9 số.',
                'identity_card.max' => 'CMND/CCCD không được vượt quá 12 số.',
                'identity_card.unique' => 'CMND/CCCD đã tồn tại.',

                'address.required' => 'Địa chỉ không được bỏ trống.',
                'address.max' => 'Địa chỉ tối đa 255 ký tự.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($request->filled('name') && $request->name !== $student->name) {
            $nameWithoutAccents = $studentInfor->removeVietnameseAccents($request->name);
            $validated['student_code'] = $nameWithoutAccents . $student->id;
        }
        
        $allowedFields = collect($validated)->only([
            'name',
            'student_code',
            'email',
            'phone',
            'gender',
            'dob',
            'identity_card',
            'address'
        ])->toArray();

        $student->update($allowedFields);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin học viên thành công.',
            'data' => $student
        ]);
    }

    public function updateShlxTraineeId(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.shlx_trainee_id' => 'required|integer',
        ]);

        $updatedStudents = [];
        foreach ($validated['students'] as $studentData) {
            $student = Student::find($studentData['student_id']);

            if ($student) {
                $student->trainee_id = $studentData['shlx_trainee_id'];
                $student->save();

                $updatedStudents[] = $student;
            }
        }
        
        return response()->json(['message' => 'Cập nhật shlx_trainee_id cho học viên thành công', 'data' => $updatedStudents], 200);
    }

    public function getCoursesOfStudent($id)
    {
        $student = Student::with(['courses:id,code', 'fees', 'studentStatuses' => function ($query) use ($id) {
            $query->where('student_id', $id)->select(
                'id',
                'student_id',
                'course_id',
                'hours',
                'km',
                'night_hours',
                'auto_hours',
                'status'
            );}
        ])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Học viên không tồn tại.'
            ], 404);
        }

        $data = $student->courses->map(function ($course) use ($student) {
            $fees = $student->fees->where('course_id', $course->id);
            $statuses = $student->studentStatuses()->where('course_id', $course->id)->with('learningField:id,name')->get();
            $examStatuses = $student->studentExamFields()->where('course_id', $course->id)->with('examField:id,name')->get();
            $teacher = \App\Models\User::find($course->pivot->teacher_id);
            $stadium = \App\Models\Stadium::find($course->pivot->stadium_id);
            $statusText = match ($course->pivot->status) {
                1 => 'Đang học',
                2 => 'Đã bỏ',
                default => 'Không xác định',
            };
            return [
                'id' => $course->id,
                'code' => $course->code,
                'pivot' => [
                    'student_id' => $course->pivot->student_id,
                    'course_id' => $course->pivot->course_id,
                    'contract_date' => $course->pivot->contract_date,
                    'image' => !empty($course->pivot->contract_image) ? "https://cdn.dtlx.app/" .$course->pivot->contract_image : null,
                    'graduation_date' => $course->pivot->graduation_date,
                    'teacher_id' => $course->pivot->teacher_id,
                    'teacher_name' => optional($teacher)->name,
                    'practice_field' => optional($stadium)->location,
                    'maps' => optional($stadium)->google_maps_url,
                    'note' => $course->pivot->note,
                    'health_check_date' => $course->pivot->health_check_date,
                    'sale_id' => $course->pivot->sale_id,
                    'hours' => $course->pivot->hours,
                    'km' => $course->pivot->km,
                    'status' => $course->pivot->status,
                    'status_text' => $statusText,
                    'tuition_fee' => $course->pivot->tuition_fee,
                    'start_date' => $course->pivot->start_date,
                    'end_date' => $course->pivot->end_date,
                    'exam_field_id' => $course->pivot->exam_field_id,
                    'created_at' => $course->pivot->created_at,
                    'updated_at' => $course->pivot->updated_at,
                    'paid_total' => $fees->sum('amount'),
                ], 'learning_statuses' => $statuses->map(function ($status) use ($course) {
                    $learningField = $course->learningFields->where('id', $status->learning_field_id)->first();
                    return [
                        'learning_field_id' => $status->learning_field_id,
                        'learning_field_name' => optional($status->learningField)->name,
                        'hours' => $status->hours,
                        'km' => $status->km,
                        'night_hours' => $status->night_hours,
                        'auto_hours' => $status->auto_hours,
                        'status' => $status->status,
                        'required_hours' => optional($learningField?->pivot)->hours,
                        'required_km' => optional($learningField?->pivot)->km,
                    ];
                }),
                'exam_statuses' => $examStatuses->map(function ($status) {
                    return [
                        'exam_field_id' => $status->exam_field_id,
                        'exam_field_name' => optional($status->examField)->name,
                        'attempt_number' => $status->attempt_number,
                        'status' => $status->status,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getGroupedSchedules($studentId)
    {
        $student = Student::findOrFail($studentId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Học viên không tồn tại.'
            ], 404);
        }
        
        $calendarController = new CalendarController();
        $studyConfig = $calendarController->getCalendarStatusConfig('study');
        $examConfig = $calendarController->getCalendarStatusConfig('exam');
        $calendars = Calendar::with([
            'students' => function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            },
            'learningField:id,name',
            'examField:id,name',
            'courses:id,code',
            'stadium:id,location,google_maps_url',
            'examSchedule.stadium:id,location,google_maps_url',
            'vehicle:id,license_plate',
        ])
        ->whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->get();

        $groupedByType = collect([
            'study' => [],
            'exam' => [],
        ]);
    
        $grouped = $calendars->groupBy('type')->map(function ($calendarsOfType, $type) use ($studyConfig, $examConfig) {
            $config = ($type === 'study') ? $studyConfig : $examConfig;
            return $calendarsOfType->groupBy(function ($calendar) {
                if ($calendar->learning_field_id) {
                    return 'Học - ' . (optional($calendar->learningField)->name ?? 'Không rõ môn học');
                } elseif ($calendar->examFieldsOfCalendar) {
                    return 'Thi - ' . (optional($calendar->examFieldsOfCalendar)->pluck('name') ?? 'Không rõ môn thi');
                }
                return 'Khác';
            })->map(function ($items) use ($type, $config) {
                return $items->map(function ($calendar) use ($type, $config) {
                    $pivot = $calendar->students->first()?->pivot;

                    $base = [
                        'calendar_id' => $calendar->id,
                        'date_start' => $calendar->date_start,
                        'date_end' => $calendar->date_end,
                        'duration' => $calendar->duration,
                        'description' => $calendar->description,
                        'location' => $calendar->location,
                        'stadium_location' => optional($calendar->stadium)->location ?? optional($calendar->examSchedule?->stadium)->location,
                        'google_maps_url' => optional($calendar->stadium)->google_maps_url ?? optional($calendar->examSchedule?->stadium)->google_maps_url,
                        'course' => $calendar->courses->map(function ($course) {
                            return [
                                'id' => $course->id,
                                'name' => $course->code,
                            ];
                        }),
                        'type' => $calendar->type,
                        'status' => $calendar->status,
                    ];

                    $statusLabel = $config['labels'][$calendar->status] ?? 'Không xác định';
                    $base['status_label'] = $statusLabel;
                    if ($type === 'study') {
                        $base += [
                            'vehicle_license_plate' => optional($calendar->vehicle)->license_plate,
                            'learning_field' => optional($calendar->learningField)->name,
                            'hours' => $pivot?->hours,
                            'km' => $pivot?->km,
                            'night_hours' => $pivot?->night_hours,
                            'auto_hours' => $pivot?->auto_hours,
                        ];
                    }

                    if ($type === 'exam') {
                        $base += [
                            'exam_field' => $calendar->examFieldsOfCalendar->pluck('name'),
                            'exam_fee' => $calendar->exam_fee,
                            'exam_fee_deadline' => $calendar->exam_fee_deadline,
                            'exam_number' => $pivot?->exam_number ?? null,
                            'exam_fee_paid_at' => $pivot?->exam_fee_paid_at ?? null,
                            'score' => $pivot?->score . '%',
                            'result' => $pivot?->correct_answers ?? null,
                            'attempt' => $pivot?->attempt_number ?? null,
                        ];
                    }
    
                    return $base;
                });
            });
        });
    
        // Merge để luôn có đủ cả 'study' và 'exam'
        $result = $groupedByType->merge($grouped);
        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
