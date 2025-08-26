<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\LeadStoreRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Fee;
use App\Models\Student;
use App\Models\User;
use App\Models\LeadSource;
use App\Models\Ranking;
use App\Models\Stadium;
use App\Models\StudentExamField;
use App\Models\StudentStatus;
use App\Services\FeeTransferService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected $feeTransferService;

    public function __construct(FeeTransferService $feeTransferService)
    {
        $this->feeTransferService = $feeTransferService;
    }

    public function index(Request $request)
    {
        $saleSupports = User::role('salesperson')->get();
        $query = Student::where('is_lead', 1)->with(['saleSupport', 'leadSource']);

        if ($request->filled('name')) {
            $query->where('id', $request->name);
        }

        if ($request->filled('assigned_to')) {
            $query->where('sale_support', $request->assigned_to);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('interest_level')) {
            $query->where('interest_level', $request->interest_level);
        }

        $leadsAll = Student::where('is_lead', 1)->get();
        $leads = $query->paginate(30);
        return view('admin.leads.index', compact('leads', 'saleSupports', 'leadsAll'));
    }

    public function create()
    {
        $students = Student::where('is_student', 1)->where('is_lead', 0)->get();
        $saleSupports = User::role('salesperson')->get();
        $leadSources = LeadSource::all();

        return view('admin.leads.create', compact('saleSupports', 'leadSources', 'students'));
    }

    public function store(LeadStoreRequest $request)
    {
        $data = $request->all();
        $data['is_lead'] = 1;
        if (!empty($data['student_id'])) {
            $student = Student::find($data['student_id']);
            if ($student) {
                $student->update($data);
            }
        } else {
            $data['password'] = 123456;
            Student::create($data);
        }
        return redirect()->route('leads.index')->with('success', 'Tạo lead mới thành công.');
    }

    public function show(Student $lead)
    {
        $users = User::role('salesperson')->get();
        $leads = Student::where('is_lead', 1)->get();
        $joinedCourseIds = $lead->courses()->pluck('courses.id');
        $courses = Course::whereNotIn('id', $joinedCourseIds)->get();
        $stadiums = Stadium::all();
        $teachers = User::role('instructor')->get();
        $saleSupports = User::role('salesperson')->get();
        $rankings = Ranking::all();
        return view('admin.leads.show', compact('lead', 'users', 'leads', 'courses', 'stadiums', 'teachers', 'saleSupports', 'rankings'));
    }

    public function edit(Student $lead)
    {
        $users = User::role('salesperson')->get();
        $students = Student::where('is_student', 1)->where('is_lead', 0)->get();
        $saleSupports = User::role('salesperson')->get();
        $leadSources = LeadSource::all();
        $leads = Student::where('is_lead', 1)->get();
        $joinedCourseIds = $lead->courses()->pluck('courses.id');
        $courses = Course::with('ranking')->whereNotIn('id', $joinedCourseIds)->get();
        $stadiums = Stadium::all();
        $teachers = User::role('instructor')->get();
        $rankings = Ranking::all();
        return view('admin.leads.edit', compact('lead', 'users', 'leadSources', 'students', 'saleSupports', 'leads', 'courses', 'stadiums', 'teachers', 'rankings'));
    }

    public function update(Request $request, Student $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'dob' => 'nullable|date',
            'status_lead' => 'required|in:1,2,3,4,5',
            'interest_level' => 'required|in:1,2,3',
            'lead_source' => 'nullable|exists:lead_sources,id',
            'sale_support' => 'required|exists:users,id',
        ]);
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'description' => $validated['description'],
            'dob' => $validated['dob'],
            'status_lead' => $validated['status_lead'],
            'interest_level' => $validated['interest_level'],
            'lead_source' => $validated['lead_source'],
            'sale_support' => $validated['sale_support'],
        ];
        $lead->update($data);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully');
    }

    public function destroy(Student $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully');
    }

    //chưa đầy đủ trong các trường hợp vẫn chưa xử lý hết những thứ bị ảnh hưởng nhé
//    public function addCourse(AddCourseRequest $request)
//    {
//        $NO_CODE_ID = 99999999;
//
//        $toTime = function (?string $v) {
//            if (!$v) return '00:00:00';
//            if (preg_match('/^\d{2}:\d{2}$/', $v)) return $v . ':00';
//            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $v)) return $v;
//            return '00:00:00';
//        };
//
//        $data = $request->validated();
//
//        try {
//            DB::beginTransaction();
//
//            $courseId = (int) $data['course_id'];
//            $student  = Student::lockForUpdate()->findOrFail($data['student_id']);
//            $course   = Course::with('ranking', 'examFields', 'learningFields')->findOrFail($courseId);
//
//            // chặn trùng KHÓA THẬT
//            $alreadyInCourse = $student->courses()->where('course_id', $courseId)->exists();
//            if ($courseId !== $NO_CODE_ID && $alreadyInCourse) {
//                return back()->withErrors(['course_id' => 'Khách hàng này đã có trong khóa học này.'])->withInput();
//            }
//
//            // NO-CODE hiện có (nếu có)
//            $noCodePivotCurrent = CourseStudent::where('student_id', $student->id)
//                ->where('course_id', $NO_CODE_ID)
//                ->latest('id')
//                ->first();
//
//            // ===== CASE A: Gán/ghi vào NO-CODE =====
//            if ($courseId === $NO_CODE_ID) {
//                if (!$noCodePivotCurrent) {
//                    $student->courses()->attach($NO_CODE_ID, [
//                        'tuition_fee'         => $data['tuition_fee'] ?? 0,
//                        'contract_date'       => $request->contract_date ?? null,
//                        'health_check_date'   => $request->health_check_date ?? null,
//                        'stadium_id'          => $request->stadium_id ?? null,
//                        'teacher_id'          => $request->learn_teacher_id ?? null,
//                        'sale_id'             => $request->sale_id ?? $student->sale_id,
//                        'reserved_chip_hours' => '00:00:00',
//                        'gifted_chip_hours'   => '00:00:00',
//                        'status'              => 1,
//                        'created_at'          => now(),
//                        'updated_at'          => now(),
//                    ]);
//                    $noCodePivotCurrent = CourseStudent::where('student_id', $student->id)
//                        ->where('course_id', $NO_CODE_ID)->latest('id')->first();
//                }
//
//                if (!empty($data['paid_fee']) && (float)$data['paid_fee'] > 0) {
//                    Fee::create([
//                        'student_id'        => $student->id,
//                        'course_student_id' => $noCodePivotCurrent->id,
//                        'fee_type'          => 1,
//                        'collector_id'      => auth()->id(),
//                        'amount'            => (float)$data['paid_fee'],
//                        'payment_date'      => now(),
//                        'is_received'       => true,
//                    ]);
//                }
//
//                // đồng bộ students.paid_fee = tổng tiền đang nằm ở tất cả NO-CODE
//                $sumNoCode = (float) Fee::where('student_id', $student->id)
//                    ->where('is_received', true)
//                    ->whereHas('courseStudent', fn($q) => $q->where('course_id', $NO_CODE_ID))
//                    ->sum('amount');
//
//                $student->update([
//                    'became_student_at' => $student->became_student_at ?: now(),
//                    'is_student'        => 1,
//                    'is_lead'           => 0,
//                    'paid_fee'          => $sumNoCode,
//                ]);
//
//                DB::commit();
//                return back()->with('success', 'Đã ghi NO-CODE cho học viên.');
//            }
//
//            // ===== CASE B: Gán vào KHÓA THẬT (và LUÔN tạo NO-CODE mới đi kèm) =====
//            $ranking = $course->ranking;
//            $vehicleTypeIsCar = (int)optional($ranking)->vehicle_type === 1;
//
//            $pivotData = [
//                'contract_date'       => $request->contract_date ?? null,
//                'health_check_date'   => $request->health_check_date ?? null,
//                'stadium_id'          => $request->stadium_id ?? null,
//                'teacher_id'          => $request->learn_teacher_id ?? null,
//                'sale_id'             => $request->sale_id ?? $student->sale_id,
//                'hours'               => (float)($ranking->hours ?? 0),
//                'km'                  => (float)($ranking->km ?? 0),
//                'status'              => 1,
//                'tuition_fee'         => (float)($request->tuition_fee ?? $course->tuition_fee),
//                'start_date'          => $course->start_date,
//                'end_date'            => $course->end_date,
//                'note'                => $request->note ?? null,
//                'created_at'          => now(),
//                'updated_at'          => now(),
//                'reserved_chip_hours' => $vehicleTypeIsCar ? $toTime($request->order_chip_hour ?? '00:00') : '00:00:00',
//                'gifted_chip_hours'   => $vehicleTypeIsCar ? $toTime($request->give_chip_hour  ?? '00:00') : '00:00:00',
//            ];
//
//            // 1) Convert NO-CODE cũ → KHÓA THẬT hoặc attach mới
//            if ($noCodePivotCurrent) {
//                $noCodePivotCurrent->update(array_merge($pivotData, [
//                    'course_id'  => $course->id,
//                    'updated_at' => now(),
//                ]));
//                $targetPivot = $noCodePivotCurrent;
//            } else {
//                $student->courses()->attach($course->id, $pivotData);
//                $targetPivot = CourseStudent::where('student_id', $student->id)
//                    ->where('course_id', $course->id)->latest('id')->first();
//            }
//
//            // 2) LUÔN tạo NO-CODE mới để kèm theo (ví treo tương lai / cho lịch khác hạng)
//            $holderNoCodePivot = CourseStudent::create([
//                'student_id'          => $student->id,
//                'course_id'           => $NO_CODE_ID,
//                'tuition_fee'         => 0,
//                'reserved_chip_hours' => '00:00:00',
//                'gifted_chip_hours'   => '00:00:00',
//                'status'              => 1,
//                'created_at'          => now(),
//                'updated_at'          => now(),
//            ]);
//
//            // 3) DÒNG TIỀN
//            $tuitionFee         = (float) $pivotData['tuition_fee'];
//            $currentOnTarget    = (float) Fee::where('student_id', $student->id)
//                ->where('course_student_id', $targetPivot->id)
//                ->where('is_received', true)
//                ->sum('amount');
//
//            $availableRank      = (float) ($student->fee_ranking ?? 0);
//            $need               = max(0.0, $tuitionFee - $currentOnTarget);
//
//            // 3.1 áp dụng fee_ranking
//            $useRank = min($availableRank, $need);
//            if ($useRank > 0) {
//                $fee = Fee::where('student_id', $student->id)
//                    ->where('course_student_id', $targetPivot->id)
//                    ->first();
//
//                if ($fee) {
//                    $fee->update([
//                        'amount'       => (float)$fee->amount + $useRank,
//                        'payment_date' => now(),
//                        'is_received'  => true,
//                    ]);
//                } else {
//                    Fee::create([
//                        'student_id'        => $student->id,
//                        'course_student_id' => $targetPivot->id,
//                        'fee_type'          => 1,
//                        'collector_id'      => optional(auth())->id(),
//                        'amount'            => $useRank,
//                        'payment_date'      => now(),
//                        'is_received'       => true,
//                    ]);
//                }
//                $availableRank   -= $useRank;
//                $currentOnTarget += $useRank;
//                $need            -= $useRank;
//            }
//
//            // 3.2 áp dụng students.paid_fee
//            $availablePaid = (float) ($student->paid_fee ?? 0);
//            $usePaid       = min($availablePaid, $need);
//            if ($usePaid > 0) {
//                $fee = Fee::where('student_id', $student->id)
//                    ->where('course_student_id', $targetPivot->id)
//                    ->first();
//
//                if ($fee) {
//                    $fee->update([
//                        'amount'       => (float)$fee->amount + $usePaid,
//                        'payment_date' => now(),
//                        'is_received'  => true,
//                    ]);
//                } else {
//                    Fee::create([
//                        'student_id'        => $student->id,
//                        'course_student_id' => $targetPivot->id,
//                        'fee_type'          => 1,
//                        'collector_id'      => optional(auth())->id(),
//                        'amount'            => $usePaid,
//                        'payment_date'      => now(),
//                        'is_received'       => true,
//                    ]);
//                }
//                $availablePaid   -= $usePaid;
//                $currentOnTarget += $usePaid;
//                $need            -= $usePaid;
//            }
//
//            // 3.3 nếu > học phí → tách phần dư sang NO-CODE mới
//            $excess = max(0.0, $currentOnTarget - $tuitionFee);
//            if ($excess > 0) {
//                $toMove = $excess;
//                $targetFees = Fee::where('student_id', $student->id)
//                    ->where('course_student_id', $targetPivot->id)
//                    ->where('is_received', true)
//                    ->orderByDesc('id')
//                    ->lockForUpdate()
//                    ->get();
//
//                foreach ($targetFees as $f) {
//                    if ($toMove <= 0) break;
//                    $amt = (float) $f->amount;
//
//                    if ($amt <= $toMove + 1e-9) {
//                        // move cả dòng sang NO-CODE
//                        $f->update(['course_student_id' => $holderNoCodePivot->id]);
//                        $toMove -= $amt;
//                    } else {
//                        // bẻ đôi
//                        $f->update(['amount' => $amt - $toMove]);
//                        Fee::create([
//                            'student_id'        => $student->id,
//                            'course_student_id' => $holderNoCodePivot->id,
//                            'fee_type'          => 1,
//                            'collector_id'      => $f->collector_id ?? optional(auth())->id(),
//                            'amount'            => $toMove,
//                            'payment_date'      => now(),
//                            'is_received'       => true,
//                        ]);
//                        $toMove = 0.0;
//                    }
//                }
//
//                // phần dư sẽ được tính lại vào paid_fee ở bước đồng bộ cuối
//            }
//
//            // 4) đồng bộ Student flags
//            $student->update([
//                'became_student_at' => $student->became_student_at ?: now(),
//                'is_student'        => 1,
//                'is_lead'           => 0,
//                'fee_ranking'       => $availableRank,
//            ]);
//
//            // 5) (tùy khóa) khởi tạo ExamField/Status
//            foreach ($course->examFields as $examField) {
//                for ($i = 1; $i < 5; $i++) {
//                    if ($i == 1 && $examField->is_practical != 1) continue;
//                    if ($i == 2 && $examField->is_practical != 0) continue;
//                    StudentExamField::updateOrCreate([
//                        'student_id'    => $student->id,
//                        'exam_field_id' => $examField->id,
//                        'course_id'     => $course->id,
//                        'type_exam'     => $i,
//                    ]);
//                }
//            }
//            foreach ($course->learningFields()->pluck('learning_fields.id') as $learningFieldId) {
//                StudentStatus::updateOrCreate([
//                    'student_id'        => $student->id,
//                    'learning_field_id' => $learningFieldId,
//                    'course_id'         => $course->id,
//                ]);
//            }
//
//            // 6) cập nhật đếm HV
//            Course::countAndUpdateStudents($course->id);
//
//            // 7) cuối cùng: students.paid_fee = tổng Fee đang nằm ở tất cả NO-CODE
//            $sumNoCode = (float) Fee::where('student_id', $student->id)
//                ->where('is_received', true)
//                ->whereHas('courseStudent', fn($q) => $q->where('course_id', $NO_CODE_ID))
//                ->sum('amount');
//
//            $student->update(['paid_fee' => $sumNoCode]);
//
//            DB::commit();
//            return back()->with('success', 'Thêm học viên vào khóa học thành công!');
//        } catch (\Throwable $e) {
//            DB::rollBack();
//            \Log::error('Lead->addCourse error', [
//                'error' => $e->getMessage(),
//                'file'  => $e->getFile(),
//                'line'  => $e->getLine(),
//            ]);
//            return back()->withErrors(['system' => 'Đã xảy ra lỗi, vui lòng thử lại.'])->withInput();
//        }
//    }



    public function addCourse(AddCourseRequest $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            \Log::info('Bắt đầu thêm học viên vào khóa học', ['request_data' => $data]);
            $courseId = $data['course_id'];
            $course = Course::with('ranking')->find($courseId);
            $student = Student::find($data['student_id']);
            \Log::info('Dữ liệu course & student', [
                'course' => $course?->toArray(),
                'student' => $student?->toArray(),
            ]);

            $exists = $student->courses()
                ->where('course_id', $data['course_id'])
                ->exists();
            if ($exists) {
                \Log::warning('Học viên đã tồn tại trong khóa học', [
                    'student_id' => $student->id,
                    'course_id' => $courseId
                ]);
                return back()->withErrors(['course_id' => 'Khách hàng này đã được thêm vào khóa học này trước đó rồi'])->withInput();
            }
            if ($courseId == 99999999) {
                $student->courses()->attach($data['course_id'], [
                    'tuition_fee' => $data['tuition_fee'],
                    'health_check_date' => $data['health_check_date'] ?? null,
                    'contract_date' => $data['contract_date'] ?? null,
                    'stadium_id' => $data['stadium_id'] ?? null,
                    'learn_teacher_id' => $data['learn_teacher_id'] ?? null,
                    'sale_id' => $data['sale_id'] ?? null,
                    'note' => $data['note'] ?? null,
                ]);
                    \Log::info('Đã attach học viên vào course 99999999', [
                        'student_id' => $student->id,
                        'pivot_data' => $data
                    ]);
                $becameStudent = $student->became_student_at ?: now();
                $student->update([
                    'paid_fee' => $data['paid_fee'],
                    'became_student_at' => $becameStudent,
                    'is_student'        => 1,
                    'is_lead'           => 0,
                ]);
                \Log::info('Đã update thông tin student', $student->toArray());

                Fee::create([
                    'student_id' => $student->id,
                    'course_student_id' => 99999999,
                    'fee_type' => 1,
                    'collector_id' => auth()->id(),
                    'amount' => $data['paid_fee'],
                    'payment_date' => now(),
                    'is_received' => true,
                ]);
                \Log::info('Đã tạo fee', [
                    'student_id' => $student->id,
                    'amount' => $data['paid_fee']
                ]);
            } else {
                $ranking = $course->ranking;
                $courseStudent = CourseStudent::where('student_id', $student->id)
                    ->where('course_id', 99999999)
                    ->first();

                if ($courseStudent) {
                    if ($student->ranking_id === null || $course->ranking_id != $student->ranking_id) {
                        $cloneData = $courseStudent->replicate();
                        $cloneData->save();
                        $newCourseStudent = CourseStudent::find($cloneData->id);
                    } else {
                        $newCourseStudent = null;
                    }

                    $updateData = [
                        'course_id' => $course->id,
                        'contract_date' => \Carbon\Carbon::parse($request->contract_date)->format('Y-m-d'),
                        'teacher_id' => $request->learn_teacher_id ?? null,
                        'stadium_id' => $request->stadium_id,
                        'health_check_date' => \Carbon\Carbon::parse($request->health_check_date)->format('Y-m-d'),
                        'sale_id' => $request->sale_id ?? $student->sale_id,
                        'hours' => $ranking->hours ?? 0,
                        'km' => $ranking->km ?? 0,
                        'status' => 1,
                        'tuition_fee' => $request->tuition_fee ?? $course->tuition_fee,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'note' => $request->note,
                        'updated_at' => now(),
                    ];

                    if ($ranking && $ranking->vehicle_type == 1) {
                        // $request->validate([
                        //     'give_chip_hour' => 'nullable|date_format:H:i',
                        //     'order_chip_hour' => 'nullable|date_format:H:i',
                        // ]);
                        $updateData['gifted_chip_hours'] = $request->give_chip_hour ?? '00:00';
                        $updateData['reserved_chip_hours'] = $request->order_chip_hour ?? '00:00';
                    }

                    $courseStudent->update($updateData);

                    $fee = Fee::where('course_student_id', $newCourseStudent->id)->first();
                    $newAmount = ($fee->amount ?? 0) + ($data['paid_fee'] ?? 0);
                    Fee::updateOrCreate(
                        ['course_student_id' => $newCourseStudent->id],
                        [
                            'amount' => $newAmount ? $newAmount : 0,
                            'student_id' => $student->id ?? null,
                            'updated_at' => now(),
                            'fee_type' => 1,
                            'collector_id' => auth()->id(),
                            'payment_date' => now(),
                            'is_received' => true,
                        ]
                    );

                    $student->update([
                        'paid_fee' => $newAmount,
                    ]);

                    $this->feeTransferService->handleFeeTransfer($student, $course, $courseStudent->id, $newCourseStudent ? $newCourseStudent->id : null, $updateData['tuition_fee']);
                } else {
                    $pivotData = [
                        'contract_date' => \Carbon\Carbon::parse($request->contract_date)->format('Y-m-d'),
                        'teacher_id' => $request->learn_teacher_id ?? null,
                        'stadium_id' => $request->stadium_id,
                        'health_check_date' => \Carbon\Carbon::parse($request->health_check_date)->format('Y-m-d'),
                        'sale_id' => $request->sale_id ?? $student->sale_id,
                        'hours' => $ranking->hours ?? 0,
                        'km' => $ranking->km ?? 0,
                        'status' => 1,
                        'tuition_fee' => $request->tuition_fee ?? $course->tuition_fee,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'note' => $request->note,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if ($ranking && $ranking->vehicle_type == 1) {
                        $pivotData['gifted_chip_hours'] = $request->give_chip_hour ?? '00:00';
                        $pivotData['reserved_chip_hours'] = $request->order_chip_hour ?? '00:00';
                    }

                    $student->courses()->attach($course->id, $pivotData);
                    $student->update([
                        'became_student_at' => $student->became_student_at ?: now(),
                        'is_student'        => 1,
                        'is_lead'           => 0,
                    ]);
                }

                foreach ($course->examFields()->get() as $examField) {
                    for ($i=1; $i < 5; $i++) {
                        if ($i == 1 && $examField->is_practical != 1) {
                            continue;
                        }

                        if ($i == 2 && $examField->is_practical != 0) {
                            continue;
                        }

                        StudentExamField::updateOrCreate([
                            'student_id' => $student->id,
                            'exam_field_id' => $examField->id,
                            'course_id' => $course->id,
                            'type_exam' => $i
                        ]);
                    }
                }

                foreach ($course->learningFields()->pluck('learning_fields.id') as $learningFieldId) {
                    StudentStatus::updateOrCreate([
                        'student_id' => $student->id,
                        'learning_field_id' => $learningFieldId,
                        'course_id' => $course->id,
                    ]);
                }
            }
            Course::countAndUpdateStudents($course->id);

            DB::commit();
            return redirect()->back()->with('success', 'Thêm học viên vào khóa học thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi thêm học viên vào khóa học', [
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return redirect()->back()->withErrors(['system' => 'Đã xảy ra lỗi, vui lòng thử lại.'])->withInput();
        }
    }

    // public function addCourse(AddCourseRequest $request)
    // {
    //     $data = $request->validated();
    //     try {
    //         DB::beginTransaction();

    //         \Log::info('Bắt đầu thêm học viên vào khóa học', ['request_data' => $data]);

    //         $courseId = $data['course_id'];
    //         $course = Course::with('ranking')->find($courseId);
    //         $student = Student::find($data['student_id']);

    //         \Log::info('Dữ liệu course & student', [
    //             'course' => $course?->toArray(),
    //             'student' => $student?->toArray(),
    //         ]);

    //         $exists = $student->courses()
    //             ->where('course_id', $data['course_id'])
    //             ->exists();

    //         if ($exists) {
    //             \Log::warning('Học viên đã tồn tại trong khóa học', [
    //                 'student_id' => $student->id,
    //                 'course_id' => $courseId
    //             ]);
    //             return back()->withErrors(['course_id' => 'Khách hàng này đã được thêm vào khóa học này trước đó rồi'])->withInput();
    //         }

    //         if ($courseId == 99999999) {
    //             $student->courses()->attach($data['course_id'], [
    //                 'tuition_fee' => $data['tuition_fee'],
    //                 'health_check_date' => $data['health_check_date'] ?? null,
    //                 'contract_date' => $data['contract_date'] ?? null,
    //                 'stadium_id' => $data['stadium_id'] ?? null,
    //                 'learn_teacher_id' => $data['learn_teacher_id'] ?? null,
    //                 'sale_id' => $data['sale_id'] ?? null,
    //                 'note' => $data['note'] ?? null,
    //             ]);

    //             \Log::info('Đã attach học viên vào course 99999999', [
    //                 'student_id' => $student->id,
    //                 'pivot_data' => $data
    //             ]);

    //             $becameStudent = $student->became_student_at ?: now();
    //             $student->update([
    //                 'paid_fee' => $data['paid_fee'],
    //                 'became_student_at' => $becameStudent,
    //                 'is_student'        => 1,
    //                 'is_lead'           => 0,
    //             ]);
    //             \Log::info('Đã update thông tin student', $student->toArray());

    //             Fee::create([
    //                 'student_id' => $student->id,
    //                 'course_student_id' => 99999999,
    //                 'fee_type' => 1,
    //                 'collector_id' => auth()->id(),
    //                 'amount' => $data['paid_fee'],
    //                 'payment_date' => now(),
    //                 'is_received' => true,
    //             ]);
    //             \Log::info('Đã tạo fee', [
    //                 'student_id' => $student->id,
    //                 'amount' => $data['paid_fee']
    //             ]);
    //         } else {
    //             $ranking = $course->ranking;
    //             \Log::info('Ranking của course', $ranking?->toArray() ?? []);

    //             $courseStudent = CourseStudent::where('student_id', $student->id)
    //                 ->where('course_id', 99999999)
    //                 ->first();
    //             \Log::info('CourseStudent tìm thấy', $courseStudent?->toArray() ?? []);

    //             // --- đoạn xử lý clone, update, attach... ---
    //             // \Log::info('Đã update courseStudent', $updateData);
    //             // \Log::info('Đã tạo/ update Fee', ['new_amount' => $newAmount]);
    //             // \Log::info('Đã update student paid_fee', ['paid_fee' => $student->paid_fee]);

    //         }

    //         Course::countAndUpdateStudents($course->id);
    //         \Log::info('Đã cập nhật số lượng học viên trong course', [
    //             'course_id' => $course->id,
    //             'student_count' => $course->students()->count()
    //         ]);

    //         DB::commit();
    //         \Log::info('Thêm học viên vào khóa học thành công', [
    //             'student_id' => $student->id,
    //             'course_id'  => $course->id,
    //         ]);
    //         return redirect()->back()->with('success', 'Thêm học viên vào khóa học thành công!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Lỗi thêm học viên vào khóa học', [
    //             'error'   => $e->getMessage(),
    //             'file'    => $e->getFile(),
    //             'line'    => $e->getLine(),
    //             'trace'   => $e->getTraceAsString(),
    //         ]);
    //         return redirect()->back()->withErrors(['system' => 'Đã xảy ra lỗi, vui lòng thử lại.'])->withInput();
    //     }
    // }
}
