<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Models\Course;
use App\Models\Fee;
use App\Models\Student;
use App\Models\CourseStudent;
use App\Models\Stadium;
use App\Models\User;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $collectors = User::all();
        $students = Student::where('is_student', 1)->get();
        $courses = Course::all();
        $teachers = User::Role('instructor')->with('roles')->get();
        $stadiums = Stadium::all();
        $courseStudents = CourseStudent::with('course')->get();
        $query = Fee::with('student', 'courseStudent.course')->orderBy('payment_date', 'desc');
        
        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('is_received')) {
            $query->where('is_received', $request->is_received);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $fees = $query->get();
        $totalAmount = $fees->sum('amount');
        $feesGrouped = $fees->groupBy(function ($fee) {
            return \Carbon\Carbon::parse($fee->payment_date)->toDateString();
        })->map(function ($dailyFees) {
            $sortedByHour = $dailyFees->sortBy(function ($fee) {
                return \Carbon\Carbon::parse($fee->payment_date)->format('H:i');
            });
            return $sortedByHour->groupBy(function ($fee) {
                return \Carbon\Carbon::parse($fee->payment_date)->format('H:i');
            });
        });
        return view('admin.fees.index', compact('fees', 'feesGrouped', 'students', 'courseStudents', 'courses', 'teachers', 'stadiums', 'collectors', 'totalAmount'));
    }

    // public function create()
    // {
    //     $students = Student::all();
    //     $courses = Course::all();
    //     $stadiums = Stadium::all();
    //     $teachers = User::Role('instructor')->with('roles')->get();
    //     $courseStudents = CourseStudent::with('course')->get();
    //     return view('admin.fees.create', compact('students', 'courseStudents', 'courses', 'teachers', 'stadiums'));
    // }

    public function store(FeeRequest $request)
    {
        $validated = $request->validated();
        $courseStudentId = $validated['course_student_id'] ?? null;

        if (!$courseStudentId) {
            return back()->with('error', 'Thiếu mã đăng ký khóa học!');
        }

        $courseStudent = CourseStudent::find($courseStudentId);

        if (!$courseStudent) {
            return back()->with('error', 'Không tìm thấy học viên đã đăng ký khóa học!');
        }

        Fee::create([
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'fee_type' => $validated['fee_type'],
            'collector_id' => $validated['collector_id'],
            'student_id' => $courseStudent->student_id,
            'course_student_id' => $courseStudent->id,
            'is_received' => $validated['is_received'] ?? 0,
            'note' => $validated['note'] ?? null,
        ]);

        return back()->with('success', 'Thông tin phí của học viên đã được lưu thành công!');
    }

    public function edit($id)
    {
        $collectors = User::all();
        $fee = Fee::with('collector')->findOrFail($id);
        $courseStudents = CourseStudent::all();
        return view('admin.fees.edit', compact('fee', 'courseStudents', 'collectors'));
    }

    public function update(FeeRequest $request, $id)
    {
        $validated = $request->validated();
        $fee = Fee::findOrFail($id);
        $courseStudent = null;
        if (!empty($validated['course_student_id'])) {
            $courseStudent = CourseStudent::find($validated['course_student_id']);
            if (!$courseStudent) {
                return back()->with('error', 'Không tìm thấy học viên đã đăng ký khóa học!');
            }
        }

        $fee->update([
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'fee_type' => $validated['fee_type'],
            'collector_id' => $validated['collector_id'],
            'course_student_id' => optional($courseStudent)->id,
            'student_id' => optional($courseStudent)->student_id ?? $fee->student_id,
            'is_received' => $validated['is_received'] ?? 0,
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('fees.index')->with('success', 'Cập nhật phí thành công.');
    }

    public function destroy($id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();

        return redirect()->route('fees.index')->with('success', 'Fee deleted successfully.');
    }
}
