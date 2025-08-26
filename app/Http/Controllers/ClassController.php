<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = ClassModel::with(['course', 'students', 'users'])->withCount(['students as student_count', 'users as teacher_count'])->latest()->paginate(30);
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $students = Student::where('is_student', true)->get();
        $users = User::Role('instructor')->with('roles')->get();
        return view('admin.classes.create', compact('courses', 'students', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassRequest $request)
    {
        $class = ClassModel::create($request->validated());
        $class->students()->sync($request->students ?? []);
        $class->users()->sync($request->users ?? []);

        return redirect()->route('classes.index')->with('success', 'Class created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = ClassModel::with(['students','users', 'course.curriculum.rankGp'])->findOrFail($id);
        $addStudents = Student::where('is_student', true)
        ->whereDoesntHave('classes', function ($query) use ($id) {
            $query->where('classes.id', $id);
        })
        ->get();

        return view('admin.classes.show', compact('class', 'addStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = ClassModel::findOrFail($id);

        $class->students()->detach();

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Lớp học đã được xóa.');
    }

    public function addStudent(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classes,id',
            'student_id' => 'required|array',
            'student_id.*' => 'exists:students,id',
        ]);

        $classroom = ClassModel::findOrFail($request->classroom_id);
        $classroom->students()->attach($request->student_id);

        return redirect()->back()->with('success', 'Thêm học viên thành công!');
    }

    public function removeStudent(ClassModel $class, Student $student)
    {
        // Kiểm tra xem sinh viên có thuộc lớp này không
        if (!$class->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Sinh viên không tồn tại trong lớp.');
        }

        // Xóa quan hệ giữa sinh viên và lớp học
        $class->students()->detach($student->id);

        return redirect()->back()->with('success', 'Xóa sinh viên khỏi lớp thành công!');
    }
}
