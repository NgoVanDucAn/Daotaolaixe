<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\ExamField;
use App\Models\Fee;
use App\Models\LeadSource;
use App\Models\Ranking;
use App\Models\Stadium;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Normalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function removeVietnameseAccents($str) {
        $str = Normalizer::normalize($str, Normalizer::FORM_D);
        $str = preg_replace('/\pM/u', '', $str);
        $str = mb_strtolower(preg_replace('/[^a-zA-Z\s]/', '', $str));

        $parts = preg_split('/\s+/', trim($str));

        $initials = '';
        $initials = implode('', array_map(fn($part) => mb_substr($part, 0, 1), array_slice($parts, 0, -1)));
        $lastName = end($parts);
        $studentCode = $lastName . $initials;
        return $studentCode;
    }

    /**
     * Display a listing of the resource.
     */

    // public function index(Request $request)
    // {
    //     $query = Student::where('is_student', 1)->with(['convertedBy','saleSupport', 'leadSource', 'courses.ranking', 'fees', 'studentStatuses.learningField', 'studentExamFields.examField', 'calendars.examSchedule.stadium', 'calendars' => fn($q) => $q->whereIn('type', ['study', 'exam'])->where('date_end', '>', Carbon::now())->orderBy('date_start', 'asc')]);

    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->where(function ($sub) use ($q) {
    //             $sub->where('name', 'like', "%$q%")
    //                 ->orWhere('student_code', 'like', "%$q%")
    //                 ->orWhere('phone', 'like', "%$q%")
    //                 ->orWhere('email', 'like', "%$q%");
    //         });
    //     }

    //     if ($request->filled('course_id')) {
    //         $query->whereHas('courses', function ($q) use ($request) {
    //             $q->where('courses.id', $request->course_id);
    //         });
    //     }

    //     if ($request->filled('lead_source_id')) {
    //         $query->where('lead_source', $request->lead_source_id);
    //     }

    //     if ($request->filled('sale_support_id')) {
    //         $query->where('sale_support', $request->sale_support_id);
    //     }

    //     if ($request->filled('status')) {
    //         if ($request->status == '0') {
    //             // Những học viên không có bất kỳ studentStatus nào có status = 1 hoặc 2
    //             $query->whereDoesntHave('studentStatuses', function ($q) {
    //                 $q->whereIn('status', [1, 2]);
    //             });
    //         } else {
    //             $query->whereHas('studentStatuses', function ($q) use ($request) {
    //                 $q->where('status', $request->status);
    //             });
    //         }
    //     }

    //     if ($request->filled('created_from')) {
    //         $query->whereDate('created_at', '>=', $request->created_from);
    //     }
    //     if ($request->filled('created_to')) {
    //         $query->whereDate('created_at', '<=', $request->created_to);
    //     }

    //     $totalStudents = $query->count();
    //     $students = $query->orderBy('created_at', 'desc')->paginate(30);

    //     $teachers = User::Role('instructor')->with('roles')->get();
    //     $courseAlls = Course::all();
    //     $stadiums = Stadium::all();
    //     $exams = ExamField::all();
    //     $users = User::all();
    //     $studentsAll = Student::all();
    //     $leadSources = LeadSource::all();
    //     $courseFees = [];
    //     $remainingFees = [];
    //     $totalHours = [];
    //     $totalKm = [];
    //     $examsResults = [];

    //     foreach ($students as $student) {
    //         $student->progress_by_course = [];
    //         foreach ($student->courses as $course) {
    //             $courseId = $course->id;
    //             $feesForCourse = $student->fees->where('course_id', $course->id);
    //             $courseFees[$student->id][$course->id] = $feesForCourse->sum('amount');
    //             $coursePivot = $student->courses->where('id', $course->id)->first();
    //             $tuitionFee = $coursePivot && $coursePivot->pivot ? $coursePivot->pivot->tuition_fee : 0;
    //             $remainingFees[$student->id][$course->id] = $tuitionFee - $courseFees[$student->id][$course->id];
    //             $courseStatuses = $student->studentStatuses->where('course_id', $courseId);
    //             $totalHours[$student->id][$course->id] = $courseStatuses->sum('hours');
    //             $totalKm[$student->id][$course->id] = $courseStatuses->sum('km');
    //             $examsResults[$student->id][$course->id] = $student->studentExamFields->where('course_id', $courseId)->values();
    //             $contractImage = $course->pivot->contract_image ?? null;
    //         }
    //     }
    //     return view('admin.students.index', compact('students', 'courseFees', 'remainingFees', 'courseAlls', 'stadiums', 'teachers', 'exams', 'users', 'studentsAll', 'totalHours', 'totalKm', 'examsResults', 'leadSources', 'totalStudents'));
    // }

    public function index(Request $request)
    {
        $query = Student::where('is_student', 1)
            ->with([
                'convertedBy','saleSupport','leadSource',
                'courses','courses.ranking','fees',
                'courseStudents' => function ($q) {
                    $q->with([
                        'studentStatuses.learningField',
                        'studentExamFields.examField',
                        'calendars' => function ($q2) {
                            $q2->whereIn('type', ['study','exam'])
                                ->where('date_end','>', Carbon::now())
                                ->orderBy('date_start','asc');
                        },
                        'calendars.examSchedule.stadium',
                    ]);
                },
            ]);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->q);
            });
        }

        if ($request->course_id) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }

        if ($request->ranking_id) {
            $query->whereHas('courses.ranking', function ($q) use ($request) {
                $q->where('rankings.id', $request->ranking_id);
            });
        }

        if ($request->lead_source_id) {
            $query->where('lead_source_id', $request->lead_source_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $totalStudents = $query->count();
        $students = $query->orderBy('id', 'desc')->paginate(30)->withQueryString();
        if ($request->course_id) {
            $filteredCourseId = $request->course_id;
            foreach ($students as $student) {
                $filteredCourses = $student->courses->where('id', $filteredCourseId)->values();
                $student->setRelation('courses', $filteredCourses);
            }
        }

        $teachers = User::Role('instructor')->with('roles')->get();
        $stadiums = Stadium::all();
        $rankings = Ranking::all();
        $courseAlls = Course::all();
        $leadSources = LeadSource::all();
        $exams = ExamField::all();
        return view('admin.students.index', compact('students', 'rankings', 'courseAlls', 'leadSources', 'totalStudents', 'exams', 'teachers', 'stadiums'));
    }

    // public function indexMoto(Request $request)
    // {
    //     $query = Student::where('is_student', 1)
    //         ->with([
    //             'ranking',
    //             'convertedBy',
    //             'saleSupport',
    //             'leadSource',
    //             'courses.ranking',
    //             'fees',
    //             'studentStatuses.learningField',
    //             'studentExamFields.examField',
    //             'calendars.examSchedule.stadium',
    //             'calendars' => fn($q) => $q->whereIn('type', ['study', 'exam'])
    //                                     ->where('date_end', '>', Carbon::now())
    //                                     ->orderBy('date_start', 'asc')
    //         ]);

    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->where(function ($sub) use ($q) {
    //             $sub->where('name', 'like', "%$q%")
    //                 ->orWhere('student_code', 'like', "%$q%")
    //                 ->orWhere('phone', 'like', "%$q%")
    //                 ->orWhere('email', 'like', "%$q%");
    //         });
    //     }

    //     if ($request->filled('course_id')) {
    //         $query->whereHas('courses', function ($q) use ($request) {
    //             $q->where('courses.id', $request->course_id);
    //         });
    //     }

    //     if ($request->filled('lead_source_id')) {
    //         $query->where('lead_source', $request->lead_source_id);
    //     }

    //     if ($request->filled('sale_support_id')) {
    //         $query->where('sale_support', $request->sale_support_id);
    //     }

    //     if ($request->filled('status')) {
    //         if ($request->status == '0') {
    //             $query->whereDoesntHave('studentStatuses', function ($q) {
    //                 $q->whereIn('status', [1, 2]);
    //             });
    //         } else {
    //             $query->whereHas('studentStatuses', function ($q) use ($request) {
    //                 $q->where('status', $request->status);
    //             });
    //         }
    //     }

    //     if ($request->filled('created_from')) {
    //         $query->whereDate('created_at', '>=', $request->created_from);
    //     }

    //     if ($request->filled('created_to')) {
    //         $query->whereDate('created_at', '<=', $request->created_to);
    //     }

    //     $totalStudents = $query->count();
    //     $students = $query->orderBy('created_at', 'desc')->paginate(30);

    //     $teachers = User::role('instructor')->with('roles')->get();
    //     $courseAlls = Course::all();
    //     $stadiums = Stadium::all();
    //     $exams = ExamField::all();
    //     $users = User::all();
    //     $studentsAll = Student::all();
    //     $leadSources = LeadSource::all();

    //     $courseFees = [];
    //     $remainingFees = [];
    //     $totalHours = [];
    //     $totalKm = [];
    //     $examsResults = [];

    //     foreach ($students as $student) {
    //         $motoCourses = $student->courses->filter(function ($course) {
    //             return optional($course->ranking)->vehicle_type === 0;
    //         });
    //         $student->setRelation('courses', $motoCourses);
    //         if ($motoCourses->isNotEmpty()) {
    //             $nearestCourse = null;
    //             $minDiff = null;

    //             foreach ($motoCourses as $course) {
    //                 $courseId = $course->id;

    //                 $feesForCourse = $student->fees->where('course_id', $courseId);
    //                 $courseFees[$student->id][$courseId] = $feesForCourse->sum('amount');

    //                 $tuitionFee = $course->pivot->tuition_fee ?? 0;
    //                 $remainingFees[$student->id][$courseId] = $tuitionFee - $courseFees[$student->id][$courseId];

    //                 $courseStatuses = $student->studentStatuses->where('course_id', $courseId);
    //                 $totalHours[$student->id][$courseId] = $courseStatuses->sum('hours');
    //                 $totalKm[$student->id][$courseId] = $courseStatuses->sum('km');

    //                 $examsResults[$student->id][$courseId] = $student->studentExamFields->where('course_id', $courseId)->values();

    //                 if (!is_null($course->start_date)) {
    //                     $diff = abs(Carbon::now()->diffInSeconds($course->start_date));
    //                     if (is_null($minDiff) || $diff < $minDiff) {
    //                         $minDiff = $diff;
    //                         $nearestCourse = $course;
    //                     }
    //                 }
    //             }

    //             // Gán ID khóa học mô tô gần nhất
    //             $student->nearest_moto_course_id = $nearestCourse?->id;
    //         } else {
    //             $student->nearest_moto_course_id = null;
    //         }
    //     }
    //     // dd($students);
    //     return view('admin.students.index-moto', compact('students', 'courseFees', 'remainingFees', 'courseAlls', 'stadiums', 'teachers', 'exams', 'users', 'studentsAll', 'totalHours', 'totalKm', 'examsResults', 'leadSources', 'totalStudents'));
    // }

    public function indexMoto(Request $request)
    {
        $query = Student::withVehicleType(0)
        ->with([
            'courseStudents.teacher',
            // 'courseStudents.course',
            // 'courseStudents.calendars' => function ($q) {
            //     $q->whereIn('type', ['study', 'exam'])
            //       ->where('date_end', '>', Carbon::now())
            //       ->orderBy('date_start', 'asc');
            // },
            // 'courseStudents.calendars.examSchedule.stadium',
        ]);

        if ($request->filled('course_id')) {
            $courseId = $request->course_id;
            $query->whereHas('courseStudents', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
            $query->with([
                'courseStudents' => function ($q) use ($courseId) {
                    $q->where('course_id', $courseId)
                      ->with([
                          'course.ranking',
                          'calendars' => function ($q2) {
                              $q2->whereIn('type', ['study', 'exam'])
                                 ->where('date_end', '>', Carbon::now())
                                 ->orderBy('date_start', 'asc');
                          },
                          'calendars.examSchedule.stadium',
                      ]);
                },
            ]);
        } else {
            $query->with([
                'courseStudents.course.ranking',
                'courseStudents.calendars' => function ($q) {
                    $q->whereIn('type', ['study', 'exam'])
                      ->where('date_end', '>', Carbon::now())
                      ->orderBy('date_start', 'asc');
                },
                'courseStudents.calendars.examSchedule.stadium',
            ]);
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $columns = ['name', 'student_code', 'identity_card'];
            $query->where(function ($q) use ($columns, $keyword) {
                foreach ($columns as $index => $column) {
                    if ($index === 0) {
                        $q->where($column, 'like', "%{$keyword}%");
                    } else {
                        $q->orWhere($column, 'like', "%{$keyword}%");
                    }
                }
                $q->orWhereHas('courseStudents.course', function ($q2) use ($keyword) {
                    $q2->where('code', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('teacher_id')) {
            $teacherId = $request->teacher_id;
            $query->whereHas('courseStudents', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            });
        }

        if ($request->filled('created_from') || $request->filled('created_to')) {
            $from = $request->filled('created_from') ? Carbon::parse($request->created_from)->startOfDay() : null;
            $to = $request->filled('created_to') ? Carbon::parse($request->created_to)->endOfDay() : null;

            $query->where(function ($q) use ($from, $to) {
                $q->whereHas('courseStudents', function ($subQ) use ($from, $to) {
                    if ($from) {
                        $subQ->whereDate('contract_date', '>=', $from);
                    }
                    if ($to) {
                        $subQ->whereDate('contract_date', '<=', $to);
                    }
                });

                $q->orWhere(function ($subQ) use ($from, $to) {
                    if ($from) {
                        $subQ->whereDate('date_of_profile_set', '>=', $from);
                    }
                    if ($to) {
                        $subQ->whereDate('date_of_profile_set', '<=', $to);
                    }
                });
            });
        }

        if ($request->filled('stadium_id')) {
            $query->whereHas('courseStudents', function ($q) use ($request) {
                $q->where('stadium_id', $request->stadium_id);
            });
        }

        $totalStudents = $query->count();
        $students = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $teachers = User::role('instructor')->with('roles')->get();
        $courseAlls = Course::with('ranking')
            ->whereHas('ranking', function ($q) {
                $q->where('vehicle_type', 0);
            })
        ->get();
        $stadiums = Stadium::all();
        $exams = ExamField::all();
        $users = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales = User::role('salesperson')->with('roles')->get();
        $rankings = Ranking::all();

        $courseFees = [];
        $remainingFees = [];
        $totalHours = [];
        $totalKm = [];
        $examsResults = [];


        // Eager load để tránh N+1
        $query->with([
            'fees',
            'courseStudents' => function ($q) {
                $q->with([
                    'course.ranking',
                    'studentStatuses',      // tổng giờ/km
                    'studentExamFields',    // kết quả thi
                    'calendars' => function ($q2) {
                        $q2->whereIn('type', ['study', 'exam'])
                            ->where('date_end', '>', Carbon::now())
                            ->orderBy('date_start', 'asc');
                    },
                    'calendars.examSchedule.stadium',
                ]);
            },
        ]);

        // Tính toán
        foreach ($students as $student) {
            foreach ($student->courseStudents as $cs) {
                $course = $cs->course;
                if (!$course || optional($course->ranking)->vehicle_type !== 0) {
                    continue; // chỉ lấy khóa mô tô
                }

                $courseId = $cs->course_id;

                // 1) Học phí đã đóng
                // Khuyến nghị: dùng course_student_id cho nhất quán với thiết kế mới
                $feesForCourse = $student->fees->where('course_student_id', $cs->id);
                // (Nếu bảng fees chưa chuyển, tạm dùng course_id)
                // $feesForCourse = $student->fees->where('course_id', $courseId);

                $courseFees[$student->id][$courseId] = $feesForCourse->sum('amount');

                // 2) Học phí còn lại
                $tuitionFee = $cs->tuition_fee ?? 0;
                $remainingFees[$student->id][$courseId] = $tuitionFee - ($courseFees[$student->id][$courseId] ?? 0);

                // 3) Giờ/Km từ student_statuses (theo course_student_id)
                $totalHours[$student->id][$courseId] = $cs->studentStatuses->sum('hours');
                $totalKm[$student->id][$courseId]     = $cs->studentStatuses->sum('km');

                // 4) Kết quả thi từ student_exam_fields (theo course_student_id)
                $examsResults[$student->id][$courseId] = $cs->studentExamFields->values();
                // Nếu cần tách theo type_exam:
                // $examsResults[$student->id][$courseId]['lt'] = $cs->studentExamFields->where('type_exam',1)->values();
                // $examsResults[$student->id][$courseId]['th'] = $cs->studentExamFields->where('type_exam',2)->values();
                // $examsResults[$student->id][$courseId]['tn'] = $cs->studentExamFields->where('type_exam',3)->values();
            }
        }
        return view('admin.students.index-moto', compact(
            'students', 'courseFees', 'remainingFees', 'courseAlls', 'stadiums', 'teachers',
            'exams', 'users', 'studentsAll', 'totalHours', 'totalKm', 'examsResults',
            'leadSources', 'totalStudents', 'sales', 'rankings'
        ));
    }

    // public function indexCar(Request $request)
    // {
    //     $query = Student::where('is_student', 1)
    //         ->with([
    //             'ranking',
    //             'convertedBy',
    //             'saleSupport',
    //             'leadSource',
    //             'courses.ranking',
    //             'fees',
    //             'studentStatuses.learningField',
    //             'studentExamFields.examField',
    //             'calendars.examSchedule.stadium',
    //             'calendars' => fn($q) => $q->whereIn('type', ['study', 'exam'])
    //                                     ->where('date_end', '>', Carbon::now())
    //                                     ->orderBy('date_start', 'asc')
    //         ]);

    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->where(function ($sub) use ($q) {
    //             $sub->where('name', 'like', "%$q%")
    //                 ->orWhere('student_code', 'like', "%$q%")
    //                 ->orWhere('phone', 'like', "%$q%")
    //                 ->orWhere('email', 'like', "%$q%");
    //         });
    //     }

    //     if ($request->filled('course_id')) {
    //         $query->whereHas('courses', function ($q) use ($request) {
    //             $q->where('courses.id', $request->course_id);
    //         });
    //     }

    //     if ($request->filled('lead_source_id')) {
    //         $query->where('lead_source', $request->lead_source_id);
    //     }

    //     if ($request->filled('sale_support_id')) {
    //         $query->where('sale_support', $request->sale_support_id);
    //     }

    //     if ($request->filled('status')) {
    //         if ($request->status == '0') {
    //             $query->whereDoesntHave('studentStatuses', function ($q) {
    //                 $q->whereIn('status', [1, 2]);
    //             });
    //         } else {
    //             $query->whereHas('studentStatuses', function ($q) use ($request) {
    //                 $q->where('status', $request->status);
    //             });
    //         }
    //     }

    //     if ($request->filled('created_from')) {
    //         $query->whereDate('created_at', '>=', $request->created_from);
    //     }

    //     if ($request->filled('created_to')) {
    //         $query->whereDate('created_at', '<=', $request->created_to);
    //     }

    //     $totalStudents = $query->count();
    //     $students = $query->orderBy('created_at', 'desc')->paginate(30);

    //     $teachers = User::role('instructor')->with('roles')->get();
    //     $courseAlls = Course::all();
    //     $stadiums = Stadium::all();
    //     $exams = ExamField::all();
    //     $users = User::all();
    //     $studentsAll = Student::all();
    //     $leadSources = LeadSource::all();

    //     $courseFees = [];
    //     $remainingFees = [];
    //     $totalHours = [];
    //     $totalKm = [];
    //     $examsResults = [];

    //     foreach ($students as $student) {
    //         $motoCourses = $student->courses->filter(function ($course) {
    //             return optional($course->ranking)->vehicle_type === 1;
    //         });
    //         $student->setRelation('courses', $motoCourses);
    //         if ($motoCourses->isNotEmpty()) {
    //             $nearestCourse = null;
    //             $minDiff = null;

    //             foreach ($motoCourses as $course) {
    //                 $courseId = $course->id;

    //                 $feesForCourse = $student->fees->where('course_id', $courseId);
    //                 $courseFees[$student->id][$courseId] = $feesForCourse->sum('amount');

    //                 $tuitionFee = $course->pivot->tuition_fee ?? 0;
    //                 $remainingFees[$student->id][$courseId] = $tuitionFee - $courseFees[$student->id][$courseId];

    //                 $courseStatuses = $student->studentStatuses->where('course_id', $courseId);
    //                 $totalHours[$student->id][$courseId] = $courseStatuses->sum('hours');
    //                 $totalKm[$student->id][$courseId] = $courseStatuses->sum('km');

    //                 $examsResults[$student->id][$courseId] = $student->studentExamFields->where('course_id', $courseId)->values();

    //                 if (!is_null($course->start_date)) {
    //                     $diff = abs(Carbon::now()->diffInSeconds($course->start_date));
    //                     if (is_null($minDiff) || $diff < $minDiff) {
    //                         $minDiff = $diff;
    //                         $nearestCourse = $course;
    //                     }
    //                 }
    //             }

    //             // Gán ID khóa học mô tô gần nhất
    //             $student->nearest_moto_course_id = $nearestCourse?->id;
    //         } else {
    //             $student->nearest_moto_course_id = null;
    //         }
    //     }
    //     // dd($students);
    //     return view('admin.students.index-car', compact('students', 'courseFees', 'remainingFees', 'courseAlls', 'stadiums', 'teachers', 'exams', 'users', 'studentsAll', 'totalHours', 'totalKm', 'examsResults', 'leadSources', 'totalStudents'));
    // }

    public function indexCar(Request $request)
    {
        $query = Student::withVehicleType(1)
            ->with([
                'convertedBy',
                'saleSupport',
                'leadSource',
                'fees',
                'courseStudents' => function ($q) {
                    $q->with([
                        'course.ranking',
                        'studentStatuses.learningField',   // ✅ load từ CourseStudent
                        'studentExamFields.examField',     // ✅ load từ CourseStudent
                        'calendars' => function ($q2) {
                            $q2->whereIn('type', ['study', 'exam'])
                                ->where('date_end', '>', Carbon::now())
                                ->orderBy('date_start', 'asc');
                        },
                        'calendars.examSchedule.stadium',
                    ]);
                },
            ]);

        if ($request->filled('course_id')) {
            $courseId = $request->course_id;
            $query->whereHas('courseStudents', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
            $query->with([
                'courseStudents' => function ($q) use ($courseId) {
                    $q->where('course_id', $courseId)
                      ->with([
                          'course.ranking',
                          'calendars' => function ($q2) {
                              $q2->whereIn('type', ['study', 'exam'])
                                 ->where('date_end', '>', Carbon::now())
                                 ->orderBy('date_start', 'asc');
                          },
                          'calendars.examSchedule.stadium',
                      ]);
                },
            ]);
        } else {
            $query->with([
                'courseStudents.course.ranking',
                'courseStudents.calendars' => function ($q) {
                    $q->whereIn('type', ['study', 'exam'])
                      ->where('date_end', '>', Carbon::now())
                      ->orderBy('date_start', 'asc');
                },
                'courseStudents.calendars.examSchedule.stadium',
            ]);
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $columns = ['name', 'student_code', 'identity_card'];
            $query->where(function ($q) use ($columns, $keyword) {
                foreach ($columns as $index => $column) {
                    if ($index === 0) {
                        $q->where($column, 'like', "%{$keyword}%");
                    } else {
                        $q->orWhere($column, 'like', "%{$keyword}%");
                    }
                }
                $q->orWhereHas('courseStudents.course', function ($q2) use ($keyword) {
                    $q2->where('code', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->filled('teacher_id')) {
            $teacherId = $request->teacher_id;
            $query->whereHas('courseStudents', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            });
        }

        if ($request->filled('created_from') || $request->filled('created_to')) {
            $from = $request->filled('created_from') ? Carbon::parse($request->created_from)->startOfDay() : null;
            $to = $request->filled('created_to') ? Carbon::parse($request->created_to)->endOfDay() : null;

            $query->where(function ($q) use ($from, $to) {
                $q->whereHas('courseStudents', function ($subQ) use ($from, $to) {
                    if ($from) {
                        $subQ->whereDate('contract_date', '>=', $from);
                    }
                    if ($to) {
                        $subQ->whereDate('contract_date', '<=', $to);
                    }
                });

                $q->orWhere(function ($subQ) use ($from, $to) {
                    if ($from) {
                        $subQ->whereDate('date_of_profile_set', '>=', $from);
                    }
                    if ($to) {
                        $subQ->whereDate('date_of_profile_set', '<=', $to);
                    }
                });
            });
        }

        if ($request->filled('stadium_id')) {
            $query->whereHas('courseStudents', function ($q) use ($request) {
                $q->where('stadium_id', $request->stadium_id);
            });
        }

        $totalStudents = $query->count();
        $students = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $teachers = User::role('instructor')->with('roles')->get();
        $courseAlls = Course::with('ranking')
            ->whereHas('ranking', function ($q) {
                $q->where('vehicle_type', 1);
            })
        ->get();
        $stadiums = Stadium::all();
        $exams = ExamField::all();
        $users = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales = User::role('salesperson')->with('roles')->get();
        $rankings = Ranking::all();
        $courseFees = [];
        $remainingFees = [];
        $totalHours = [];
        $totalKm = [];
        $examsResults = [];

        // Eager load để tránh N+1
        $query->with([
            'fees',
            'courseStudents' => function ($q) {
                $q->with([
                    'course.ranking',
                    'studentStatuses',      // tổng giờ/km
                    'studentExamFields',    // kết quả thi
                    'calendars' => function ($q2) {
                        $q2->whereIn('type', ['study', 'exam'])
                            ->where('date_end', '>', Carbon::now())
                            ->orderBy('date_start', 'asc');
                    },
                    'calendars.examSchedule.stadium',
                ]);
            },
        ]);

        foreach ($students as $student) {
            foreach ($student->courseStudents as $cs) {
                $course = $cs->course;
                if (!$course || optional($course->ranking)->vehicle_type !== 0) {
                    continue; // lọc theo mô tô (0). Với ô tô thì đổi 1.
                }

                $courseId = $cs->course_id;

                // 1) Học phí đã đóng (khuyến nghị dùng course_student_id cho nhất quán)
                $feesForCourse = $student->fees
                    ->where('course_student_id', $cs->id);     // ✅ ưu tiên cách này
                // ->where('course_id', $courseId);        // ❌ chỉ dùng nếu bảng fees của bạn chưa chuyển

                $courseFees[$student->id][$courseId] = $feesForCourse->sum('amount');

                // 2) Học phí còn lại
                $tuitionFee = $cs->tuition_fee ?? 0;
                $remainingFees[$student->id][$courseId] = $tuitionFee - ($courseFees[$student->id][$courseId] ?? 0);

                // 3) Giờ / Km từ student_statuses (đã chuyển sang course_student_id)
                $totalHours[$student->id][$courseId] = $cs->studentStatuses->sum('hours');
                $totalKm[$student->id][$courseId]     = $cs->studentStatuses->sum('km');

                // 4) Kết quả thi từ student_exam_fields (đã chuyển sang course_student_id)
                $examsResults[$student->id][$courseId] = $cs->studentExamFields->values();
                // Nếu cần tách theo type_exam:
                // $examsResults[$student->id][$courseId]['lt'] = $cs->studentExamFields->where('type_exam',1)->values();
                // $examsResults[$student->id][$courseId]['th'] = $cs->studentExamFields->where('type_exam',2)->values();
                // $examsResults[$student->id][$courseId]['tn'] = $cs->studentExamFields->where('type_exam',3)->values();
            }
        }


        return view('admin.students.index-car', compact(
            'students', 'courseFees', 'remainingFees', 'courseAlls', 'stadiums', 'teachers',
            'exams', 'users', 'studentsAll', 'totalHours', 'totalKm', 'examsResults',
            'leadSources', 'totalStudents', 'sales', 'rankings'
        ));
    }

    public function studyDetails(Student $student, $courseId)
    {
        // Lấy bản ghi course_student của học viên trong khóa chỉ định
        $cs = \App\Models\CourseStudent::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->firstOrFail();

        // Lịch học gắn với course_student này (type = study)
        $studyCalendars = $cs->calendars()   // <- cần có quan hệ calendars() trong CourseStudent (ở dưới)
        ->where('type', 'study')
            ->with(['learningField:id,name'])
            ->orderBy('date_start')
            ->get();

        // Tổng hợp kết quả học theo từng môn từ bảng student_statuses (đã chuyển sang course_student_id)
        $statuses = \App\Models\StudentStatus::where('course_student_id', $cs->id)
            ->get()
            ->keyBy('learning_field_id');

        // Gom nhóm theo tên môn học (learning_field)
        $grouped = $studyCalendars
            ->groupBy(fn ($calendar) => optional($calendar->learningField)->name ?? 'Chưa xác định')
            ->map(function ($items) use ($statuses) {
                $first = $items->first();
                $learningFieldId = $first?->learning_field_id;
                $statusRow = $learningFieldId ? ($statuses->get($learningFieldId) ?? null) : null;

                return [
                    'learning_field_name' => optional($first?->learningField)->name ?? 'Chưa xác định',
                    'total_hours'         => $statusRow->hours        ?? 0,
                    'total_km'            => $statusRow->km           ?? 0,
                    'total_night_hours'   => $statusRow->night_hours  ?? 0,
                    'total_auto_hours'    => $statusRow->auto_hours   ?? 0,
                    'status'              => $statusRow->status       ?? null,
                    'items' => $items->map(function ($calendar) {
                        return [
                            'calendar_id' => $calendar->id,
                            'date_start'  => $calendar->date_start,
                            'date_end'    => $calendar->date_end,
                            // dữ liệu từ pivot calendar_course_student
                            'hours'       => $calendar->pivot->hours,
                            'km'          => $calendar->pivot->km,
                            'night_hours' => $calendar->pivot->night_hours ?? null,
                            'auto_hours'  => $calendar->pivot->auto_hours  ?? null,
                            'remarks'     => $calendar->pivot->remarks,
                        ];
                    })->values(),
                ];
            });

        return response()->json([
            'data' => $grouped,
        ]);
    }


    public function examDetails(Student $student, $courseId, $examFieldId)
    {
        $calendarDetails = DB::table('calendar_student')
            ->join('calendars', 'calendar_student.calendar_id', '=', 'calendars.id')
            ->join('calendar_course', 'calendar_course.calendar_id', '=', 'calendars.id')
            ->where('calendar_student.student_id', $student->id)
            ->where('calendar_course.course_id', $courseId)
            ->where('calendars.exam_field_id', $examFieldId)
            ->where('calendars.type', 'exam')
            ->where('calendar_student.exam_status', '!=', 0)
            ->select(
                'calendar_student.correct_answers',
                'calendar_student.remarks',
                'calendar_student.attempt_number',
                'calendar_student.exam_status',
                'calendars.date_start',
                'calendars.name as calendar_name'
            )
            ->orderBy('calendars.date_start', 'asc')
            ->get();

        return response()->json([
            'data' => $calendarDetails->map(function ($item, $index) {
                return [
                    'attempt' => $index + 1,
                    'calendar_name' => $item->calendar_name,
                    'correct_answers' => $item->correct_answers,
                    'remarks' => $item->remarks,
                    'attempt_number' => $item->attempt_number ? $item->attempt_number : '-',
                    'exam_status' => match ($item->exam_status) {
                        1 => 'Đạt',
                        2 => 'Không đạt',
                        default => 'Chưa rõ',
                    },
                    'date' => $item->date_start ? \Carbon\Carbon::parse($item->date_start)->format('d/m/Y H:i') : '-',
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $saleSupports = User::role('salesperson')->get();
        $leadSources = LeadSource::all();
        $rankings = Ranking::all();

        return view('admin.students.create', compact('saleSupports', 'leadSources', 'rankings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $data['became_student_at'] = now();
        $data['is_student'] = 1;
        $data['converted_by'] = auth()->id();
        if (empty($data['password']) || $data['password'] == 'password') {
            $data['password'] = '123456';
            $data['password_confirmation'] = '123456';
        }
        if (empty($data['paid_fee'])) {
            $data['paid_fee'] = 0;
        }
        if ($data['password'] != $data['password_confirmation']) {
            $validator = Validator::make($data, []);
            $validator->after(function ($validator) {
                $validator->errors()->add('password', 'Mật khẩu và xác nhận mật khẩu không khớp.');
            });
            return back()->withErrors($validator)->withInput();
        }
        if (!empty($request->image) && $request->image instanceof \Illuminate\Http\UploadedFile) {
            $timestamp = now()->timestamp;
            $extension = $request->image->getClientOriginalExtension();
            //đổi tên file ảnh kết hợp timestamp và uuid để tránh trùng cho tên ảnh
            $fileName = $timestamp . '_' . Str::uuid() . '.' . $extension;

            $imagePath = $request->image->storeAs('students', $fileName, 'public');
            $data['image'] = $imagePath;
        }
        unset($data['password_confirmation']);

        try {
            DB::beginTransaction();
            $student = Student::create($data);
            $student->student_code = $student->student_code . $student->id;
            $student->save();

            $virtualCourseId = 99999999;
            $virtualCourse = Course::find($virtualCourseId);
            if (!$virtualCourse) {
                Course::create([
                    'id'         => $virtualCourseId,
                    'code'       => 'No Code',
                    'ranking_id' => null,
                    'status'     => 1,
                    'km'         => 0,
                    'required_km'=> 0,
                    'min_automatic_car_hours' => 0,
                    'min_night_hours'         => 0,
                    'tuition_fee'=> 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $courseStudent = CourseStudent::create([
                'student_id'    => $student->id,
                'course_id'     => $virtualCourseId,
                'contract_date' => $data['date_of_profile_set'] ?? null,
                'tuition_fee' => $data['fee_ranking'] ?? null,
                'created_at'    => $student->created_at,
                'updated_at'    => $student->created_at,
            ]);

            Fee::create([
                'student_id' => $student->id,
                'course_student_id' => $courseStudent->id,
                'fee_type' => 1,
                'collector_id' => auth()->id(),
                'amount' => $student->paid_fee,
                'payment_date' => now(),
                'is_received' => true,
            ]);

            DB::commit();

            return redirect()->route('students.index')->with('success', 'Thêm mới học viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('students.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $student = Student::where('is_student', 1)->with(['convertedBy','saleSupport', 'leadSource'])->find($id);

    //     if (!$student) {
    //         return response()->json(['error' => 'Học viên không tồn tại!'], 404);
    //     }

    //     return response()->json([
    //         'id'       => $student->id,
    //         'student_code' => $student->student_code,
    //         'card_id'  => $student->card_id ?? null,
    //         'trainee_id'  => $student->trainee_id ?? null,
    //         'name'     => $student->name,
    //         'email'    => $student->email,
    //         'phone'    => $student->phone ?? null,
    //         'address'  => $student->address ?? null,
    //         'gender'  => $student->gender ?? null,
    //         'dob'  => $student->dob ?? null,
    //         'identity_card'  => $student->identity_card ?? null,
    //         'description'  => $student->description ?? null,
    //         'became_student_at'  => $student->became_student_at ?? null,
    //         'sale_support'  => $student->saleSupport->name ?? null,
    //         'lead_source'  => $student->leadSource->name ?? null,
    //         'converted_by'  => $student->convertedBy->name ?? null,
    //     ]);
    // }

    public function show($id)
    {
        $teachers    = User::role('instructor')->with('roles')->get();
        $courseAlls  = Course::all();
        $rankings    = Ranking::all();
        $stadiums    = Stadium::all();
        $exams       = ExamField::all();
        $users       = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales       = User::role('salesperson')->with('roles')->get();

        // Eager load TẤT CẢ quan hệ cần dùng qua CourseStudent
        $student = Student::where('is_student', 1)
            ->with([
                'convertedBy', 'saleSupport', 'leadSource',
                'courses.ranking', 'fees',
                'courseStudents' => function ($q) {
                    $q->with([
                        'course', // + 'course.ranking' nếu cần
                        'studentStatuses.learningField',
                        'studentExamFields.examField',
                        'calendars' => function ($q2) {
                            $q2->whereIn('type', ['study', 'exam'])
                                ->where('date_end', '>', Carbon::now())
                                ->orderBy('date_start', 'asc');
                        },
                        'calendars.examSchedule.stadium',
                    ]);
                },
            ])
            ->findOrFail($id);

        $courseFees       = [];
        $remainingFees    = [];
        $totalHours       = [];
        $totalKm          = [];
        $totalNightHours  = [];
        $totalAutoHours   = [];
        $examsResults     = [];

        // TÍNH TOÁN QUA CourseStudent (đúng với schema mới)
        foreach ($student->courseStudents as $cs) {
            $courseId = $cs->course_id;
            if (!$courseId) { continue; }

            // 1) Học phí đã đóng (ưu tiên theo course_student_id)
            $feesForCourse = $student->fees->where('course_student_id', $cs->id);
            $courseFees[$student->id][$courseId] = $feesForCourse->sum('amount');

            // 2) Học phí còn lại
            $tuitionFee = $cs->tuition_fee ?? 0;
            $remainingFees[$student->id][$courseId] = $tuitionFee - ($courseFees[$student->id][$courseId] ?? 0);

            // 3) Tổng giờ/km từ student_statuses (đã chuyển cột sang course_student_id)
            $totalHours[$student->id][$courseId]      = $cs->studentStatuses->sum('hours');
            $totalKm[$student->id][$courseId]         = $cs->studentStatuses->sum('km');
            $totalNightHours[$student->id][$courseId] = $cs->studentStatuses->sum('night_hours');
            $totalAutoHours[$student->id][$courseId]  = $cs->studentStatuses->sum('auto_hours');

            // 4) Kết quả thi từ student_exam_fields (đã chuyển cột sang course_student_id)
            $exams = $cs->studentExamFields;
            $examsResults[$student->id][$courseId]['lt'] = $exams->where('type_exam', 1)->values();
            $examsResults[$student->id][$courseId]['th'] = $exams->where('type_exam', 2)->values();
            $examsResults[$student->id][$courseId]['tn'] = $exams->where('type_exam', 3)->values();

            // (Nếu bạn cần ảnh hợp đồng: ưu tiên lấy từ CourseStudent nếu cột nằm ở đó)
            // $contractImage = $cs->contract_image ?? null;
        }

        return view('admin.students.show', compact(
            'rankings', 'student', 'studentsAll', 'teachers', 'courseAlls',
            'stadiums', 'sales', 'courseFees', 'remainingFees', 'totalHours',
            'totalKm', 'totalNightHours', 'totalAutoHours', 'examsResults'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, Course $course)
    {
        $saleSupports = User::role('salesperson')->get();
        $leadSources = LeadSource::all();
        $courseAlls = Course::all();
        $stadiums = Stadium::all();
        $exams = ExamField::all();
        $users = User::all();
        $teachers = User::Role('instructor')->with('roles')->get();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $courseFees = [];
        $remainingFees = [];
        $totalHours = [];
        $totalKm = [];
        $examsResults = [];
        $rankings = Ranking::all();
        $courseStatuses = $student->courseStudents()
        ->where('course_id', $course->id)
        ->with(['calendars' => function ($query) {
            $query->where('status', 10);
        }])
        ->get()
        ->pluck('calendars')
        ->flatten();
        $totalHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->hours;
        });
        $totalKm = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->km;
        });
        $totalAutoHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->auto_hours;
        });
        $totalNightHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->night_hours;
        });
        return view('admin.students.edit', compact('teachers', 'student', 'exams', 'saleSupports', 'leadSources','totalKm', 'totalAutoHours', 'totalNightHours', 'rankings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $oldImagePath = $student->image;
        $newImagePath = null;

        try {
            DB::beginTransaction();

            $imagePath = $oldImagePath;
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = now()->timestamp . '_' . uniqid() . '.' . $extension;

                try {
                    $newImagePath = $request->file('image')->storeAs('students', $fileName, 'public');
                } catch (\Exception $e) {
                    throw new \Exception('Upload ảnh thất bại: ' . $e->getMessage());
                }

                $imagePath = $newImagePath;
            }

            $rankingId = $request->ranking_id;

            if (!is_null($rankingId) && $rankingId !== 'null') {
                $ranking = Ranking::find($rankingId);
                if (!$ranking) {
                    throw ValidationException::withMessages(['ranking_id' => 'Ranking không tồn tại.']);
                }
            } else {
                $rankingId = null;
            }

            $name = $request->name;
            $data = [
                'name' => $name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender ?? $student->gender,
                'dob' => $request->dob ?? $student->dob,
                'identity_card' => $request->identity_card ?? $student->identity_card,
                'address' => $request->address ?? $student->address,
                'card_id' => $request->card_id ?? $student->card_id,
                'status' => $request->status,
                'ranking_id' => $rankingId,
                'date_of_profile_set' => $request->date_of_profile_set ? Carbon::parse($request->date_of_profile_set) : $student->date_of_profile_set,
                'image' => $imagePath,
                'fee_ranking' => $request->fee_ranking,
                'paid_fee' => $request->paid_fee ?? 0,
            ];
            if ($student->name !== $name) {
                $nameWithoutAccents = $this->removeVietnameseAccents($name);
                $data['student_code'] = $nameWithoutAccents . $student->id;
            }
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }
            $student->update($data);

            if ($student->paid_fee != $data['paid_fee']) {
                $courseStudent = CourseStudent::where('student_id', $student->id)
                    ->where('course_id', 99999999)
                    ->first();

                if ($courseStudent) {
                    Fee::updateOrCreate(
                        [
                            'student_id'        => $student->id,
                            'course_student_id' => $courseStudent->id,
                            'fee_type'          => 1, // giữ nguyên loại phí
                        ],
                        [
                            'collector_id' => auth()->id(),
                            'amount'       => $data['paid_fee'],
                            'payment_date' => now(),
                            'is_received'  => true,
                        ]
                    );
                }
            }

            if ($student->fee_ranking != $data['fee_ranking']) {
                CourseStudent::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'course_id'  => 99999999,
                    ],
                    [
                        'tuition_fee' => $data['fee_ranking'],
                    ]
                );
            }

            DB::commit();

            if ($newImagePath && $oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            return redirect()->route('students.index')->with('success', 'Cập nhật thông tin học viên thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($newImagePath) && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }

            \Log::error('Lỗi khi cập nhật học viên: ' . $e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'update_error' => 'Đã xảy ra lỗi trong quá trình cập nhật. Vui lòng thử lại.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $student->delete();
            return redirect()->back()->with('success', 'Xóa học viên thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa học viên: ' . $e->getMessage());
        }
    }

    public function updateCard(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Học viên không tồn tại!'], 404);
        }

        try {
            $request->validate([
                'card_id' => 'required|unique:students,card_id',
            ], [
                'card_id.required' => 'Mã thẻ không được để trống.',
                'card_id.unique' => 'Mã thẻ đã tồn tại. Vui lòng nhập mã khác.',
            ]);

            $student->card_id = $request->card_id;
            $student->save();

            return response()->json(['success' => true, 'message' => 'Mã thẻ được cập nhật thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function getCourses($id)
    {
        $student = Student::findOrFail($id);
        $courses = $student->courses()->select('courses.id', 'courses.code')->get();
        $courses->makeHidden('pivot');
        return response()->json($courses);
    }

    public function availableCourses(Student $student)
    {
        $enrolledCourseIds = $student->courses()->pluck('courses.id');
        $availableCourses = Course::whereNotIn('id', $enrolledCourseIds)->get(['id', 'code']);

        return response()->json($availableCourses);
    }

    public function showAll(Student $student, Course $course)
    {
        // 1) Chỉ load những gì còn cần trực tiếp từ Student (không load statuses/exams ở đây)
        $student->load([
            'convertedBy',
            'saleSupport',
            'leadSource',
            'fees',
            'courses', // để lấy pivot tuition_fee, contract_image...
        ]);

        // 2) Lấy đúng bản ghi course_student và eager load mọi thứ liên quan đến course_student
        $courseStudent = $student->courseStudents()
            ->where('course_id', $course->id)
            ->with([
                // lịch
                'calendars' => function ($q) {
                    $q->whereIn('type', ['study', 'exam'])
                        ->orderBy('date_start', 'asc');
                },
                'calendars.examSchedule.stadium',
                'calendars.users',                 // nếu cần hiển thị instructor

                // dữ liệu *gắn với course_student_id*
                'studentStatuses.learningField',
                'studentExamFields.examField',
            ])
            ->first();

        // 3) Map danh sách lịch (giữ nguyên logic cũ)
        $calendars = collect($courseStudent?->calendars ?? [])->map(function ($calendar) {
            if ($calendar->type === 'exam') {
                $calendar->fields = $calendar->examFieldsOfCalendar;
                $stadium = $calendar->examSchedule?->stadium;
                $calendar->stadium_location = $stadium?->location;
                $calendar->stadium_google_maps_url = $stadium?->google_maps_url;

                foreach ($calendar->fields as $field) {
                    $pivotData = DB::table('calendar_student_exam_field')
                        ->where('calendar_student_id', $calendar->pivot->id ?? null)
                        ->where('exam_field_id', $field->id)
                        ->first();

                    $field->attempt_number  = $pivotData->attempt_number  ?? null;
                    $field->exam_all_status = $pivotData->exam_all_status ?? null;
                    $field->exam_status     = $pivotData->exam_status     ?? null;
                    $field->remarks         = $pivotData->remarks         ?? null;
                }
            } elseif ($calendar->type === 'study') {
                $calendar->fields = $calendar->learningField ? collect([$calendar->learningField]) : collect();
                $calendar->stadium_location = $calendar->stadium?->location;
                $calendar->stadium_google_maps_url = $calendar->stadium?->google_maps_url;
            } else {
                $calendar->fields = collect();
                $calendar->stadium_location = null;
                $calendar->stadium_google_maps_url = null;
            }

            $calendar->instructor = $calendar->users->first();
            return $calendar;
        });

        // 4) Dữ liệu bổ sung (giữ nguyên)
        $teachers    = User::role('instructor')->with('roles')->get();
        $courseAlls  = Course::all();
        $stadiums    = Stadium::all();
        $exams       = ExamField::all();
        $users       = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales       = User::role('salesperson')->with('roles')->get();

        // 5) Học phí đã đóng (dùng course_student_id)
        $feesForCourse = $student->fees->where('course_student_id', $courseStudent?->id);
        $courseFees    = $feesForCourse->sum('amount');

        // 6) Học phí còn lại (giữ nguyên cách lấy từ pivot courses của Student)
        $coursePivot  = $student->courses->where('id', $course->id)->first();
        $tuitionFee   = $coursePivot && $coursePivot->pivot ? $coursePivot->pivot->tuition_fee : 0;
        $remainingFees = $tuitionFee - $courseFees;

        // 7) Giờ/km đã hoàn thành (status = 10) – vẫn từ pivot calendar_student
        $completedCalendars = collect($courseStudent?->calendars ?? [])->filter(
            fn($calendar) => $calendar->pivot?->status == 10
        );
        $totalHours      = $completedCalendars->sum(fn($c) => $c->pivot->hours);
        $totalKm         = $completedCalendars->sum(fn($c) => $c->pivot->km);
        $totalAutoHours  = $completedCalendars->sum(fn($c) => $c->pivot->auto_hours);
        $totalNightHours = $completedCalendars->sum(fn($c) => $c->pivot->night_hours);

        // 8) Tổng giờ/km tất cả buổi (bất kể trạng thái)
        $allCalendars       = collect($courseStudent?->calendars ?? []);
        $totalHoursAlls     = $allCalendars->sum(fn($c) => $c->pivot->hours);
        $totalKmAlls        = $allCalendars->sum(fn($c) => $c->pivot->km);
        $totalAutoHoursAlls = $allCalendars->sum(fn($c) => $c->pivot->auto_hours);
        $totalNightHoursAlls= $allCalendars->sum(fn($c) => $c->pivot->night_hours);

        // 9) Kết quả thi — lấy trực tiếp từ courseStudent đã eager load (tránh query thêm)
        $examsResults = $courseStudent ? $courseStudent->studentExamFields->values() : collect();

        // 10) Hợp đồng (nếu có)
        $contractImage = $coursePivot->pivot->contract_image ?? null;

        $rankings = Ranking::all();
        return view('admin.students.show_all', compact(
            'rankings',
            'feesForCourse',
            'student',
            'course',
            'coursePivot',
            'courseStudent',
            'courseFees',
            'remainingFees',
            'totalHoursAlls',
            'totalKmAlls',
            'totalAutoHoursAlls',
            'totalNightHoursAlls',
            'totalHours',
            'totalKm',
            'totalAutoHours',
            'totalNightHours',
            'examsResults',
            'contractImage',
            'courseAlls',
            'stadiums',
            'teachers',
            'exams',
            'users',
            'studentsAll',
            'leadSources',
            'calendars',
            'sales'
        ));
    }


    public function editCouseInShowAllOld(Student $student, Course $course)
    {
        $student->load([
            'convertedBy',
            'saleSupport',
            'leadSource',
            'courses.ranking',
            'fees',
            'studentStatuses.learningField',
            'studentExamFields.examField',
            'courseStudents.calendars' => function ($q) {
                $q->whereIn('type', ['study', 'exam'])
                  ->orderBy('date_start', 'asc');
            },
        ]);

        $calendars = $student->courseStudents
            ->flatMap(function ($courseStudent) {
                return $courseStudent->calendars;
            })
            ->filter(fn($c) => in_array($c->type, ['study', 'exam']))
            ->sortBy('date_start')
            ->values()
            ->map(function ($calendar) {
                // Gán danh sách môn học / môn thi
                if ($calendar->type === 'exam') {
                    $calendar->fields = $calendar->examFieldsOfCalendar;
                    $stadium = $calendar->examSchedule?->stadium;
                    $calendar->stadium_location = $stadium?->location;
                    $calendar->stadium_google_maps_url = $stadium?->google_maps_url;

                    foreach ($calendar->fields as $field) {
                        $pivotData = DB::table('calendar_student_exam_field')
                            ->where('calendar_student_id', $calendar->pivot->id ?? null)
                            ->where('exam_field_id', $field->id)
                            ->first();

                        $field->attempt_number = $pivotData->attempt_number ?? null;
                        $field->exam_all_status = $pivotData->exam_all_status ?? null;
                        $field->exam_status = $pivotData->exam_status ?? null;
                        $field->remarks = $pivotData->remarks ?? null;
                    }
                } elseif ($calendar->type === 'study') {
                    $calendar->fields = $calendar->learningField ? collect([$calendar->learningField]) : collect();
                    $calendar->stadium_location = $calendar->stadium?->location;
                    $calendar->stadium_google_maps_url = $calendar->stadium?->google_maps_url;
                } else {
                    $calendar->fields = collect();
                    $calendar->stadium_location = null;
                    $calendar->stadium_google_maps_url = null;
                }

                // Gán giáo viên duy nhất (nếu có)
                $calendar->instructor = $calendar->users->first();

                return $calendar;
            });

        $teachers = User::role('instructor')->with('roles')->get();
        $courseAlls = Course::all();
        $stadiums = Stadium::all();
        $exams = ExamField::all();
        $users = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales = User::role('salesperson')->with('roles')->get();

        // 1. Học phí đã đóng
        $feesForCourse = $student->fees->where('course_student_id', $course->id);
        $courseFees = $feesForCourse->sum('amount');

        // 2. Học phí còn lại
        $coursePivot = $student->courses->where('id', $course->id)->first();
        $tuitionFee = $coursePivot && $coursePivot->pivot ? $coursePivot->pivot->tuition_fee : 0;
        $remainingFees = $tuitionFee - $courseFees;

        // 3. Giờ và km từ bảng calendar_student
        $courseStatuses = $student->courseStudents
        ->flatMap(function ($courseStudent) use ($course) {
            return $courseStudent->calendars->filter(function ($calendar) use ($course) {
                return $calendar->pivot?->status == 10 &&
                    $calendar->courses->contains('id', $course->id);
            });
        });
        $totalHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->hours;
        });
        $totalKm = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->km;
        });
        $totalAutoHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->auto_hours;
        });
        $totalNightHours = $courseStatuses->sum(function ($calendar) {
            return $calendar->pivot->night_hours;
        });

        $courseStatusesAlls = $student->courseStudents
        ->flatMap(function ($courseStudent) use ($course) {
            return $courseStudent->calendars->filter(function ($calendar) use ($course) {
                return $calendar->courses->contains('id', $course->id);
            });
        });
        $totalHoursAlls = $courseStatusesAlls->sum(function ($calendar) {
            return $calendar->pivot->hours;
        });
        $totalKmAlls = $courseStatusesAlls->sum(function ($calendar) {
            return $calendar->pivot->km;
        });
        $totalAutoHoursAlls = $courseStatusesAlls->sum(function ($calendar) {
            return $calendar->pivot->auto_hours;
        });
        $totalNightHoursAlls = $courseStatusesAlls->sum(function ($calendar) {
            return $calendar->pivot->night_hours;
        });

        // 4. Kết quả thi
        $examsResults = $student->studentExamFields->where('course_id', $course->id)->values();

        // 5. Hợp đồng (nếu có)
        $contractImage = $coursePivot->pivot->contract_image ?? null;
        //  dd($student);
        $rankings = Ranking::all();
        return view('admin.students.edit_course', compact(
            'rankings',
            'student',
            'course',
            'coursePivot',
            'courseFees',
            'remainingFees',
            'totalHoursAlls',
            'totalKmAlls',
            'totalAutoHoursAlls',
            'totalNightHoursAlls',
            'totalHours',
            'totalKm',
            'totalAutoHours',
            'totalNightHours',
            'examsResults',
            'contractImage',
            'courseAlls',
            'stadiums',
            'teachers',
            'exams',
            'users',
            'studentsAll',
            'leadSources',
            'calendars',
            'sales'
        ));
    }

    public function editCouseInShowAll(Student $student, Course $course)
    {
        $student->load([
            'convertedBy',
            'saleSupport',
            'leadSource',
            'fees',
            'studentStatuses.learningField',
            'studentExamFields.examField',
        ]);

        // Lấy bản ghi course_student tương ứng
        $courseStudent = $student->courseStudents()
            ->where('course_id', $course->id)
            ->with(['calendars' => function ($q) {
                $q->whereIn('type', ['study', 'exam'])->orderBy('date_start', 'asc');
            }])
            ->first();

        // Xử lý danh sách lịch học / lịch thi
        $calendars = collect($courseStudent?->calendars ?? [])->map(function ($calendar) {
            if ($calendar->type === 'exam') {
                $calendar->fields = $calendar->examFieldsOfCalendar;
                $stadium = $calendar->examSchedule?->stadium;
                $calendar->stadium_location = $stadium?->location;
                $calendar->stadium_google_maps_url = $stadium?->google_maps_url;

                foreach ($calendar->fields as $field) {
                    $pivotData = DB::table('calendar_student_exam_field')
                        ->where('calendar_student_id', $calendar->pivot->id ?? null)
                        ->where('exam_field_id', $field->id)
                        ->first();

                    $field->attempt_number = $pivotData->attempt_number ?? null;
                    $field->exam_all_status = $pivotData->exam_all_status ?? null;
                    $field->exam_status = $pivotData->exam_status ?? null;
                    $field->remarks = $pivotData->remarks ?? null;
                }
            } elseif ($calendar->type === 'study') {
                $calendar->fields = $calendar->learningField ? collect([$calendar->learningField]) : collect();
                $calendar->stadium_location = $calendar->stadium?->location;
                $calendar->stadium_google_maps_url = $calendar->stadium?->google_maps_url;
            } else {
                $calendar->fields = collect();
                $calendar->stadium_location = null;
                $calendar->stadium_google_maps_url = null;
            }

            $calendar->instructor = $calendar->users->first();

            return $calendar;
        });

        // Dữ liệu bổ sung
        $teachers = User::role('instructor')->with('roles')->get();
        $courseAlls = Course::all();
        $stadiums = Stadium::all();
        $exams = ExamField::all();
        $users = User::all();
        $studentsAll = Student::all();
        $leadSources = LeadSource::all();
        $sales = User::role('salesperson')->with('roles')->get();

        // 1. Học phí đã đóng
        $feesForCourse = $student->fees->where('course_student_id', $courseStudent->id);
        $courseFees = $feesForCourse->sum('amount');

        // 2. Học phí còn lại
        $coursePivot = $student->courses->where('id', $course->id)->first();
        $tuitionFee = $coursePivot && $coursePivot->pivot ? $coursePivot->pivot->tuition_fee : 0;
        $remainingFees = $tuitionFee - $courseFees;

        // 3. Giờ và km đã hoàn thành (status = 10)
        $completedCalendars = collect($courseStudent?->calendars ?? [])->filter(fn($calendar) =>
            $calendar->pivot?->status == 10
        );

        $totalHours = $completedCalendars->sum(fn($c) => $c->pivot->hours);
        $totalKm = $completedCalendars->sum(fn($c) => $c->pivot->km);
        $totalAutoHours = $completedCalendars->sum(fn($c) => $c->pivot->auto_hours);
        $totalNightHours = $completedCalendars->sum(fn($c) => $c->pivot->night_hours);

        // 4. Tổng giờ và km tất cả (bất kể trạng thái)
        $allCalendars = collect($courseStudent?->calendars ?? []);
        $totalHoursAlls = $allCalendars->sum(fn($c) => $c->pivot->hours);
        $totalKmAlls = $allCalendars->sum(fn($c) => $c->pivot->km);
        $totalAutoHoursAlls = $allCalendars->sum(fn($c) => $c->pivot->auto_hours);
        $totalNightHoursAlls = $allCalendars->sum(fn($c) => $c->pivot->night_hours);

        // 5. Kết quả thi
        $examsResults = $student->studentExamFields->where('course_id', $course->id)->values();

        // 6. Hợp đồng (nếu có)
        $contractImage = $coursePivot->pivot->contract_image ?? null;
        $rankings = Ranking::all();
        return view('admin.students.edit_course', compact(
            'rankings',
            'feesForCourse',
            'student',
            'course',
            'coursePivot',
            'courseStudent',
            'courseFees',
            'remainingFees',
            'totalHoursAlls',
            'totalKmAlls',
            'totalAutoHoursAlls',
            'totalNightHoursAlls',
            'totalHours',
            'totalKm',
            'totalAutoHours',
            'totalNightHours',
            'examsResults',
            'contractImage',
            'courseAlls',
            'stadiums',
            'teachers',
            'exams',
            'users',
            'studentsAll',
            'leadSources',
            'calendars',
            'sales'
        ));
    }
}
