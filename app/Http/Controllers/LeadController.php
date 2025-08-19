<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCourseRequest;
use App\Http\Requests\LeadStoreRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Student;
use App\Models\User;
use App\Models\LeadSource;
use App\Models\Ranking;
use App\Models\Stadium;
use App\Models\StudentExamField;
use App\Models\StudentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class LeadController extends Controller
{
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

    public function addCourse(AddCourseRequest $request)
    {
        $data = $request->validated();
        dd($data);
        try {
            DB::beginTransaction();
            
            $courseId = $data['course_id'];
            $course = Student::find($courseId);
            $student = Student::find($data['student_id']);

            $exists = $student->courses()
                ->where('course_id', $data['course_id'])
                ->exists();
            if ($exists) {
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
                $becameStudent = $student->became_student_at ?: now();
                $student->update([
                    'became_student_at' => $becameStudent,
                    'is_student'        => 1,
                    'is_lead'           => 0,
                ]);
            } else {
                $ranking = $course->ranking;
                $courseStudent = CourseStudent::where('student_id', $student->id)
                    ->where('course_id', 99999999)
                    ->first();

                if ($courseStudent) {
                    if ($course->ranking_id != $student->ranking_id) {
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
                        $request->validate([
                            'give_chip_hour' => 'nullable|date_format:H:i',
                            'order_chip_hour' => 'nullable|date_format:H:i',
                        ]);
                        $updateData['gifted_chip_hours'] = $request->give_chip_hour ?? '00:00';
                        $updateData['reserved_chip_hours'] = $request->order_chip_hour ?? '00:00';
                    }

                    $courseStudent->update($updateData);
                    $this->handleFeeTransfer($student, $course, $courseStudent->id, $newCourseStudent ? $newCourseStudent->id : null, $updateData['tuition_fee']);
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
            \Log::error('Lỗi thêm học viên vào khóa học', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại.');
        }
    }
}
