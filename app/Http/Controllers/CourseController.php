<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Curriculum;
use App\Models\ExamField;
use App\Models\Fee;
use App\Models\LearningField;
use App\Models\Ranking;
use App\Models\Stadium;
use App\Models\Student;
use App\Models\StudentExamField;
use App\Models\StudentStatus;
use App\Models\User;
use App\Services\FeeTransferService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    protected $feeTransferService;

    public function __construct(FeeTransferService $feeTransferService)
    {
        $this->feeTransferService = $feeTransferService;
    }

    public function listCourse(Request $request) {
        $vehicleTypeSelect = $request->input('vehicle_type');
        if ($vehicleTypeSelect == 1) {
            $vehicleType = 0;
        } else {
            $vehicleType = 1;
        }

        $listCourses = Course::whereHas('ranking', function ($query) use ($vehicleType) {
            $query->where('vehicle_type', $vehicleType);
        })->get();

        return response()->json($listCourses);
    }

    public function index(Request $request)
    {
        $examFields = ExamField::all();
        $learningFields = LearningField::all();
        $studentsAll = Student::all();
        $sales = User::role('salesperson')->with('roles')->get();
        $query = Course::where('id', '!=', 99999999)
            ->with([
                'curriculum',
                'ranking:id,name,vehicle_type',
                'learningFields',
                'examFields',
                // Load students + fees + courseStudents + exam fields theo course_student
                'students' => fn ($q) => $q->with([
                    'fees',
                    'courseStudents.studentExamFields',
                ]),
            ]);
        $coursesAll = Course::all();

        // Lọc theo mã khóa học
        if ($request->filled('code')) {
            $query->where('id', $request->code);
        }

        // Lọc theo giáo trình
        if ($request->filled('ranking_id')) {
            $query->where('ranking_id', $request->ranking_id);
        }

        // Lọc theo trạng thái khóa học
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tháng năm
        if ($request->filled('month_year')) {
            $monthYear = $request->input('month_year');
            [$year, $month] = explode('-', $monthYear);

            $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

            $query->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth]);
            });
        }

        // Lọc theo học phí
        if ($request->filled('tuition_fee_min')) {
            $query->where('tuition_fee', '>=', $request->tuition_fee_min);
        }
        if ($request->filled('tuition_fee_max')) {
            $query->where('tuition_fee', '<=', $request->tuition_fee_max);
        }

        if ($request->filled('student_count_min')) {
            $query->where('number_students', '>=', $request->student_count_min);
        }
        if ($request->filled('student_count_max')) {
            $query->where('number_students', '<=', $request->student_count_max);
        }

        $courses = $query->latest()->get();

        if ($request->filled('student_name') || $request->filled('student_code') || $request->filled('student_status') || $request->filled('learning_status') || $request->filled('only_debt')) {
            // Xử lý lọc theo học viên và các trường liên quan
            $filteredCourses = $courses->filter(function ($course) use ($request) {
                $students = $course->students;

                // Lọc học viên theo mã
                if ($request->filled('student_code')) {
                    $students = $students->filter(fn($s) => str_contains($s->code, $request->student_code));
                }

                // Lọc học viên theo tên
                if ($request->filled('student_name')) {
                    $students = $students->filter(fn($s) => str_contains(Str::lower($s->name), Str::lower($request->student_name)));
                }

                // Lọc học viên theo trạng thái
                if ($request->filled('student_status')) {
                    $students = $students->filter(fn($s) => $s->status === $request->student_status);
                }

                // Lọc học viên theo tình trạng học (giờ & km)
                if ($request->filled('learning_status')) {
                    $statuses = $students->map(function ($s) use ($course) {
                        return $s->studentStatuses->where('course_id', $course->id);
                    });

                    if ($request->learning_status == 'completed') {
                        $students = $students->filter(function ($s) use ($course) {
                            $statuses = $s->studentStatuses->where('course_id', $course->id);
                            return $statuses->sum('hours') >= 10 && $statuses->sum('km') >= 50;
                        });
                    } elseif ($request->learning_status == 'in_progress') {
                        $students = $students->filter(function ($s) use ($course) {
                            $statuses = $s->studentStatuses->where('course_id', $course->id);
                            return $statuses->sum('hours') < 10 || $statuses->sum('km') < 50;
                        });
                    }
                }

                // Lọc học viên còn nợ
                if ($request->filled('only_debt')) {
                    $students = $students->filter(function ($student) use ($course) {
                        $tuitionFee = $course->students()->where('student_id', $student->id)->first()->pivot->tuition_fee;
                        $paid = $student->fees->where('course_id', $course->id)->sum('amount');
                        return $tuitionFee > $paid;
                    });
                }

                $course->setRelation('students', $students);
                return $students->isNotEmpty();
            });
        } else {
            $filteredCourses = $courses;
        }

        // Phân trang lại thủ công
        $perPage = 30;
        $page = $request->get('page', 1);
        $pagedCourses = $filteredCourses->forPage($page, $perPage)->values();
        $courses = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedCourses,
            $filteredCourses->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $teachers = User::role('instructor')->with('roles')->get();
        $rankings = Ranking::all();

        // Tính toán thêm như remaining_fee, total_paid, etc.
        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                // Lấy course_student_id từ pivot
                $courseStudentId = $student->pivot->id;

                // 1) Học phí
                $tuitionFee   = $course->students()->where('student_id', $student->id)->first()->pivot->tuition_fee;

                // Nếu bảng fees đã chuyển sang course_student_id, dùng dòng dưới:
                // $feesForCourse = $student->fees->where('course_student_id', $courseStudentId);

                // Nếu Fees CHƯA chuyển, tạm giữ cách theo course_id:
                $feesForCourse = $student->fees->where('course_id', $course->id);

                $totalPaid     = $feesForCourse->sum('amount');
                $student->remaining_fee = $tuitionFee - $totalPaid;
                $student->total_paid    = $totalPaid;
                $student->tuition_fee   = $tuitionFee;

                // 2) Giờ/Km: lấy từ student_statuses theo course_student_id
                $statuses = StudentStatus::where('course_student_id', $courseStudentId)->get();
                $student->total_hours = $statuses->sum('hours');
                $student->total_km    = $statuses->sum('km');

                // 3) Kết quả thi: lấy từ student_exam_fields theo course_student_id
                $student->exam_results = StudentExamField::where('course_student_id', $courseStudentId)->get();
                // Nếu cần tách theo type_exam:
                // $student->exam_results_lt = $student->exam_results->where('type_exam', 1)->values();
                // $student->exam_results_th = $student->exam_results->where('type_exam', 2)->values();
                // $student->exam_results_tn = $student->exam_results->where('type_exam', 3)->values();
            }
        }


        $stadiums = Stadium::all();
        return view('admin.courses.index', compact('coursesAll', 'courses', 'teachers', 'rankings', 'stadiums', 'examFields', 'learningFields', 'studentsAll', 'sales'));
    }

    public function create()
    {
        $curriculums = Curriculum::all();
        $examFields = ExamField::all();
        $learningFields = LearningField::all();
        $rankings = Ranking::all();
        return view('admin.courses.create', compact('curriculums', 'examFields', 'learningFields', 'rankings'));
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            DB::beginTransaction();

            $numberStudents = 0;
            $courseData = $request->except(['exam_fields', 'learning_fields', 'hours', 'km']);
            $courseData['number_students'] = $numberStudents;
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $courseData['duration_days'] = $start->diffInDays($end) + 1;
            $ranking = Ranking::find($request->ranking_id);
            if (!$ranking) {
                throw new \Exception('Không tìm thấy Ranking với ID đã cung cấp.');
            }
            $curriculum = Curriculum::where('type', $ranking->vehicle_type)->first();
            if (!$curriculum) {
                return redirect()->back()->withInput()->withErrors(['curriculum_id' => 'Không tìm thấy giáo trình phù hợp với loại xe: ' . $ranking->vehicle_type]);
            }
            $courseData['curriculum_id'] = $curriculum->id;
            $course = Course::create($courseData);

            if ($ranking->vehicle_type == 0) {
                $allExamFieldIds = ExamField::whereIn('applies_to_all_rankings', [1,3])->pluck('id')->toArray();
                $allLearningFieldIds = LearningField::where('applies_to_all_rankings', 1)->pluck('id')->toArray();
            } else {
                $allExamFieldIds = ExamField::whereNotIn('applies_to_all_rankings', [3])->pluck('id')->toArray();
                $allLearningFieldIds = LearningField::pluck('id')->toArray();
            }
            $course->examFields()->attach($allExamFieldIds);
            $course->learningFields()->attach($allLearningFieldIds);

            $course->update([
                'duration' => $ranking->min_hours,
                'km' => $ranking->min_km,
                'min_night_hours' => $ranking->min_night_hours,
                'min_automatic_car_hours' => $ranking->min_automatic_car_hours,
            ]);

            DB::commit();
            return redirect()->route('courses.index')->with('success', 'Khóa học đã được thêm thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi thêm khóa học: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function show(Course $course)
    {
        $course->load(['curriculum', 'students:id,name,student_code']);
        return response()->json([
            'course' => [
                'id' => $course->id,
                'name' => $course->code,
            ],
            'students' => $course->students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'code' => $student->student_code,
                ];
            }),
        ]);
    }

    public function edit(Course $course)
    {
        $course->load(['curriculum', 'examFields', 'learningFields']);
        $curriculums = Curriculum::all();
        $examFields = ExamField::all();
        $learningFields = LearningField::all();
        $rankings = Ranking::all();
        return view('admin.courses.edit', compact('course', 'rankings', 'curriculums', 'examFields', 'learningFields'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        try {
            DB::beginTransaction();

            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $durationDays = $start->diffInDays($end) + 1;
            $ranking = Ranking::find($request->ranking_id);
            if (!$ranking) {
                throw new \Exception('Không tìm thấy Ranking với ID đã cung cấp.');
            }
            $curriculum = Curriculum::where('type', $ranking->vehicle_type)->first();
            if (!$curriculum) {
                return redirect()->back()->withInput()->withErrors(['curriculum_id' => 'Không tìm thấy giáo trình phù hợp với loại xe: ' . $ranking->vehicle_type]);
            }
            $curriculumId = $curriculum->id;

            if ($course->students()->count() > 0 && $course->ranking_id != $request->ranking_id) {
                $rankingOld = Ranking::find($course->ranking_id);
                if ($rankingOld->vehicle_type != $ranking->vehicle_type) {
                    return redirect()->back()->withInput()->withErrors(['ranking_id' => 'Không thể đổi hạng bằng có loại xe khác nhau cho khóa học này vì khóa học đã có học viên.']);
                }
            }

            $course->update([
                'code' => $request->code ?? $course->code,
                'curriculum_id' => $curriculumId ?? $course->curriculum_id,
                'number_bc' => $request->number_bc ?? $course->number_bc,
                'date_bci' => $request->date_bci ? Carbon::parse($request->date_bci) : $course->date_bci,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : $course->start_date,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date) : $course->end_date,
                'decision_kg' => $request->decision_kg ?? $course->decision_kg,
                'duration_days' => $durationDays,
                'tuition_fee' => $request->tuition_fee ?? $course->tuition_fee,
                'dat_date' => $request->dat_date ?? null,
                'cabin_date' => $request->cabin_date ?? null,
                'ranking_id' => $request->ranking_id,
            ]);

            if ($ranking->vehicle_type == 0) {
                $allExamFieldIds = ExamField::whereIn('applies_to_all_rankings', [1,3])->pluck('id')->toArray();
                $allLearningFieldIds = LearningField::where('applies_to_all_rankings', 1)->pluck('id')->toArray();
            } else {
                $allExamFieldIds = ExamField::whereNotIn('applies_to_all_rankings', [3])->pluck('id')->toArray();
                $allLearningFieldIds = LearningField::pluck('id')->toArray();
            }
            $course->examFields()->sync($allExamFieldIds);
            $course->learningFields()->sync($allLearningFieldIds);

            $course->update([
                'duration' => $ranking->min_hours,
                'km' => $ranking->min_km,
                'min_night_hours' => $ranking->min_night_hours,
                'min_automatic_car_hours' => $ranking->min_automatic_car_hours,
            ]);

            DB::commit();

            return redirect()->route('courses.index')->with('success', 'Khóa học đã được cập nhật thành công.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lỗi khi cập nhật khóa học: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật khóa học. Vui lòng thử lại.');
        }
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Khóa học đã được xóa.');
    }

    public function addStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'tuition_fee' => 'required|numeric|min:0',
            'health_check_date' => 'required|date',
            'contract_date' => 'required|date',
            'stadium_id' => 'nullable|exists:stadiums,id',
            'learn_teacher_id' => 'nullable|exists:users,id',
            'sale_id' => 'nullable|exists:users,id',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($request->student_id);
            $course  = Course::findOrFail($request->course_id);
            $ranking = $course->ranking;

            // Lấy record course_student “ảo”
            $courseStudent = CourseStudent::where('student_id', $student->id)
                ->where('course_id', 99999999)
                ->first();

            if (!$courseStudent) {
                throw new \Exception('Không tìm thấy bản ghi khóa học ảo của học viên.');
            }

            if (!$course->students->contains($student->id)) {

                // Nếu khác ranking thì clone dữ liệu trước khi cập nhật
                if ($course->ranking_id != $student->ranking_id) {
                    $cloneData = $courseStudent->replicate();
                    $cloneData->save();
                    $newCourseStudent = CourseStudent::find($cloneData->id);
                } else {
                    $newCourseStudent = null;
                }

                $updateData = [
                    'course_id'          => $course->id,
                    'contract_date'      => \Carbon\Carbon::parse($request->contract_date)->format('Y-m-d'),
                    'teacher_id'         => $request->learn_teacher_id ?? null,
                    'stadium_id'         => $request->stadium_id,
                    'health_check_date'  => \Carbon\Carbon::parse($request->health_check_date)->format('Y-m-d'),
                    'sale_id'            => $request->sale_id ?? $student->sale_id,
                    'hours'              => $ranking->hours ?? 0,
                    'km'                 => $ranking->km ?? 0,
                    'status'             => 1,
                    'tuition_fee'        => $request->tuition_fee ?? $course->tuition_fee,
                    'start_date'         => $course->start_date,
                    'end_date'           => $course->end_date,
                    'note'               => $request->note,
                    'updated_at'         => now(),
                ];

                if ($ranking && $ranking->vehicle_type == 1) {
                    $request->validate([
                        'give_chip_hour'  => 'nullable|date_format:H:i',
                        'order_chip_hour' => 'nullable|date_format:H:i',
                    ]);
                    $updateData['gifted_chip_hours']  = $request->give_chip_hour  ?? '00:00';
                    $updateData['reserved_chip_hours'] = $request->order_chip_hour ?? '00:00';
                }

                // Cập nhật bản ghi course_student (từ ảo -> thật)
                $courseStudent->update($updateData);

                // === TẠO/SYNC BẢN GHI THEO course_student_id ===
                $courseStudentId = $courseStudent->id;

                // 1) Exam fields (student_exam_fields): dùng course_student_id
                foreach ($course->examFields()->get() as $examField) {
                    for ($i = 1; $i < 5; $i++) {
                        // Giữ nguyên điều kiện phân loại LT/TH như cũ
                        if ($i == 1 && $examField->is_practical != 1) continue;
                        if ($i == 2 && $examField->is_practical != 0) continue;

                        \App\Models\StudentExamField::updateOrCreate(
                            [
                                'course_student_id' => $courseStudentId,
                                'exam_field_id'     => $examField->id,
                                'type_exam'         => $i,
                            ],
                            [] // không có cột khác bắt buộc cập nhật
                        );
                    }
                }

                // 2) Learning statuses (student_statuses): dùng course_student_id
                foreach ($course->learningFields()->pluck('learning_fields.id') as $learningFieldId) {
                    \App\Models\StudentStatus::updateOrCreate(
                        [
                            'course_student_id'  => $courseStudentId,
                            'learning_field_id'  => $learningFieldId,
                        ],
                        [] // thêm default nếu bạn có cột mặc định (hours/km=0) bằng fillable trong model/migration
                    );
                }

                // Cập nhật thống kê số HV
                Course::countAndUpdateStudents($course->id);

                // --- Xử lý phí (giữ nguyên tham số) ---
                $this->feeTransferService->handleFeeTransfer(
                    $student,
                    $course,
                    $courseStudentId,
                    $newCourseStudent ? $newCourseStudent->id : null,
                    $updateData['tuition_fee']
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thêm học viên vào khóa học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi thêm học viên vào khóa học', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }

    public function addStudents(Request $request)
    {
        $request->validate([
            'student_id'           => 'required|array',
            'student_id.*'         => 'exists:students,id',
            'course_id'            => 'required|exists:courses,id',
            'tuition_fee'          => 'required|array',
            'tuition_fee.*'        => 'required|string',
            'health_check_date'    => 'required|array',
            'health_check_date.*'  => 'required|date',
            'contract_date'        => 'required|array',
            'contract_date.*'      => 'required|date',
            'stadium_id'           => 'nullable|array',
            'stadium_id.*'         => 'nullable|exists:stadiums,id',
            'learn_teacher_id'     => 'nullable|array',
            'learn_teacher_id.*'   => 'nullable|exists:users,id',
            'sale_id'              => 'nullable|array',
            'sale_id.*'            => 'nullable|exists:users,id',
            'note'                 => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $course  = Course::findOrFail($request->course_id);
            $ranking = $course->ranking;

            foreach ($request->student_id as $index => $studentId) {
                $student = Student::findOrFail($studentId);

                $courseStudent = CourseStudent::where('student_id', $student->id)
                    ->where('course_id', 99999999)
                    ->first();

                if (!$courseStudent) {
                    throw new \Exception('Không tìm thấy bản ghi khóa học ảo của học viên.');
                }

                if (!$course->students->contains($student->id)) {

                    if ($course->ranking_id != $student->ranking_id) {
                        $cloneData = $courseStudent->replicate();
                        $cloneData->save();
                        $newCourseStudent = CourseStudent::find($cloneData->id);
                    } else {
                        $newCourseStudent = null;
                    }

                    $updateData = [
                        'course_id'         => $course->id,
                        'contract_date'     => \Carbon\Carbon::parse($request->contract_date[$index])->format('Y-m-d'),
                        'teacher_id'        => $request->learn_teacher_id[$index] ?? null,
                        'stadium_id'        => $request->stadium_id[$index] ?? null,
                        'health_check_date' => \Carbon\Carbon::parse($request->health_check_date[$index])->format('Y-m-d'),
                        'sale_id'           => $request->sale_id[$index] ?? $student->sale_id,
                        'hours'             => $ranking->hours ?? 0,
                        'km'                => $ranking->km ?? 0,
                        'status'            => 1,
                        'tuition_fee'       => (float) str_replace('.', '', $request->tuition_fee[$index]),
                        'start_date'        => $course->start_date,
                        'end_date'          => $course->end_date,
                        'note'              => $request->note[$index] ?? null,
                    ];

                    if ($ranking && $ranking->vehicle_type == 1) {
                        $request->validate([
                            'give_chip_hour'   => 'nullable|array',
                            'give_chip_hour.*' => 'nullable|date_format:H:i',
                            'order_chip_hour'  => 'nullable|array',
                            'order_chip_hour.*'=> 'nullable|date_format:H:i',
                        ]);
                        $updateData['gifted_chip_hours']  = $request->give_chip_hour[$index]  ?? '00:00';
                        $updateData['reserved_chip_hours'] = $request->order_chip_hour[$index] ?? '00:00';
                    }

                    // cập nhật record “ảo” thành record thật
                    $courseStudent->update($updateData);

                    // === TẠO/SYNC THEO course_student_id ===
                    $courseStudentId = $courseStudent->id;

                    // (1) student_exam_fields
                    foreach ($course->examFields()->get() as $examField) {
                        for ($i = 1; $i < 5; $i++) {
                            if ($i == 1 && $examField->is_practical != 1) continue;
                            if ($i == 2 && $examField->is_practical != 0) continue;

                            \App\Models\StudentExamField::updateOrCreate(
                                [
                                    'course_student_id' => $courseStudentId,
                                    'exam_field_id'     => $examField->id,
                                    'type_exam'         => $i,
                                ],
                                []
                            );
                        }
                    }

                    // (2) student_statuses
                    foreach ($course->learningFields()->pluck('learning_fields.id') as $learningFieldId) {
                        \App\Models\StudentStatus::updateOrCreate(
                            [
                                'course_student_id' => $courseStudentId,
                                'learning_field_id' => $learningFieldId,
                            ],
                            []
                        );
                    }

                    Course::countAndUpdateStudents($course->id);

                    // Phí
                    $this->feeTransferService->handleFeeTransfer(
                        $student,
                        $course,
                        $courseStudentId,
                        $newCourseStudent ? $newCourseStudent->id : null,
                        $updateData['tuition_fee']
                    );
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thêm học viên vào khóa học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi thêm học viên vào khóa học', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }

    public function removeStudent(Course $course, Student $student)
    {
        // Kiểm tra xem sinh viên có thuộc khóa học này không
        if (!$course->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Sinh viên không tồn tại trong khóa học.');
        }

        // Xóa quan hệ giữa học viên và khóa học, cập nhật lại số học viên của course
        $course->students()->detach($student->id);
        StudentExamField::where('student_id', $student->id)->where('course_id', $course->id)->delete();
        StudentStatus::where('student_id', $student->id)->where('course_id', $course->id)->delete();
        Course::countAndUpdateStudents($course->id);

        return redirect()->back()->with('success', 'Xóa sinh viên khỏi khóa học thành công!');
    }

    public function getAvailableStudents(Request $request)
    {
        $courseId = $request->input('course_id');

        $students = Student::where('is_student', true)
            ->whereDoesntHave('courses', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->get(['id', 'name', 'student_code']);

        return response()->json($students);
    }

    public function courseAlls()
    {
        $courses = Course::with('ranking')->get();
        return response()->json($courses);
    }
}
