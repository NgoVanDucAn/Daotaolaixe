<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalendarRequest;
use App\Models\Calendar;
use App\Models\CalendarStudent;
use App\Models\CalendarStudentExamField;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\ExamField;
use App\Models\ExamSchedule;
use App\Models\LearningField;
use App\Models\Stadium;
use App\Models\Student;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseAlls = Course::all();
        $type = $request->input('type', 'study');
        $studentCode = $request->input('student_code');
        $studentName = $request->input('student_name');
        $userName = $request->input('user_name');
        $timeFilter = $request->input('time_filter');
        $courseId = $request->input('course_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $statusConfig = $this->getCalendarStatusConfig($type);

        $query = Calendar::with(['users', 'courseStudents', 'learningField', 'examField', 'courses', 'stadium:id,location,google_maps_url', 'examSchedule.stadium:id,location,google_maps_url'])
            ->where('type', $type);

        if ($type == 'exam') {
            $levelFilter = $request->input('level_filter');
            if (!empty($levelFilter)) {
                $query->where('level', $levelFilter);
            }
        }

        if (!empty($studentCode)) {
            $query->whereHas('courseStudents', function ($q) use ($studentCode) {
                $q->where('student_code', 'like', "%$studentCode%");
            });
        }

        // Lọc theo tên học viên
        if (!empty($studentName)) {
            $query->whereHas('courseStudents', function ($q) use ($studentName) {
                $q->where('name', 'like', "%$studentName%");
            });
        }

        // Lọc theo tên người dùng
        if (!empty($userName)) {
            $query->whereHas('users', function ($q) use ($userName) {
                $q->where('name', 'like', "%$userName%");
            });
        }

        if (!empty($courseId)) {
            $query->whereHas('courses', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        // Lọc theo khoảng thời gian
        if (!empty($startDate) || !empty($endDate)) {
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('date_start', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ]);
            } else {
                $date = !empty($startDate) ? $startDate : $endDate;
                $query->whereDate('date_start', Carbon::parse($date));
            }
        } else if (!empty($timeFilter)) {
            $today = Carbon::now();
            switch ($timeFilter) {
                case '90':
                    $query->whereBetween('date_start', [$today->copy()->subDays(90), $today]);
                    break;
                case '30':
                    $query->whereBetween('date_start', [$today->copy()->subDays(30), $today]);
                    break;
                case '7':
                    $query->whereBetween('date_start', [$today->copy()->subDays(7), $today]);
                    break;
                case '71':
                    $query->whereBetween('date_start', [$today, $today->copy()->addDays(7)]);
                    break;
                case '301':
                    $query->whereBetween('date_start', [$today, $today->copy()->addDays(30)]);
                    break;
                case '901':
                    $query->whereBetween('date_start', [$today, $today->copy()->addDays(90)]);
                    break;
            }
        }

        $calendars = $query->orderBy('date_start', 'asc')->get();

        if ($type === 'exam') {
            foreach ($calendars as $calendar) {
                $calendar->exam_field_data = $calendar->exam_fields_of_calendar->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                })->toArray();
            }

            $groupedByDate = $calendars->groupBy(fn($c) => $c->date_start->format('Y-m-d'))
            ->map(function ($group) {
                return [
                    'calendars_by_time' => $group->groupBy('time'),
                    'count' => $group->count(),
                    'status_3_count' => $group->where('status', 3)->count(),
                    'status_4_count' => $group->where('status', 4)->count(),
                    'status_10_count' => $group->where('status', 10)->count(),
                    'status_1_2_count' => $group->whereIn('status', [1, 2])->count(),
                ];
            });
        } else {
            $groupedCalendars = $calendars->groupBy(function ($calendar) {
                return \Carbon\Carbon::parse($calendar->date_start)->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'calendars' => $group,
                    'count' => $group->count(),
                    'status_3_count' => $group->where('status', 3)->count(),
                    'status_4_count' => $group->where('status', 4)->count(),
                    'status_10_count' => $group->where('status', 10)->count(),
                    'status_1_2_count' => $group->whereIn('status', [1, 2])->count(),
                ];
            });
        }

        $perPage = 30;
        $currentPage = $request->input('page', 1);
        $totalDays = $groupedCalendars->count();
        $dates = $groupedCalendars->forPage($currentPage, $perPage);

        $paginatedCalendars = new LengthAwarePaginator(
            $dates,
            $totalDays,
            $perPage,
            $currentPage,
            [
                'path' => url()->current(),
                'query' => array_merge(
                    $request->except('page'),
                    ['type' => $type]
                )
            ]
        );
        $calendarTypes = [
            'study' => 'học',
            'exam' => 'thi',
            'work' => 'công việc',
            'meeting' => 'họp',
            'call' => 'gọi',
        ];

        return view('admin.calendars.index', compact('paginatedCalendars', 'calendarTypes', 'type', 'statusConfig', 'courseAlls'));
    }

    public function index2(Request $request, $type = null, $level_filter = null)
    {
        $stadiums = Stadium::all();
        $teachers = User::Role('instructor')->with('roles')->get();
        $studentAlls = Student::where('is_student', '=', 1)->get();
        $statusConfig = $this->getCalendarStatusConfig($type);

        // ---- Eager-load quan hệ cần thiết
        $baseRelations = [
            'users',
            'learningField',
            'examField',
            'stadium:id,location,google_maps_url',

            // Cặp course_student + student + course
            'calendarStudents.courseStudent.course',
            'calendarStudents.courseStudent.student',
        ];

        // Nếu là lịch thi thì cần cả các môn thi theo học viên trong lịch
        if ($type === 'exam') {
            $baseRelations[] = 'calendarStudents.examFields'; // -> pivot: exam_status/remarks/attempt_number
        }

        // Để lấy kết quả học (giờ, km…) theo course_student_id
        $baseRelations[] = 'calendarStudents.courseStudent.studentStatuses';

        $query = Calendar::with($baseRelations)
            ->where('type', $type)
            ->where('level', $level_filter);

        if ($type === 'exam' && $request->filled('calendar_id')) {
            $query->whereKey((int) $request->calendar_id);
        }

        $query->when($request->filled('stadium_id'), function ($q) use ($request) {
            $q->where('stadium_id', $request->stadium_id);
        });

        $query->when($request->filled('student_id'), function ($q) use ($request) {
            $q->whereHas('calendarStudents.courseStudent.student', fn($s) =>
            $s->where('id', $request->student_id)
            );
        });

        $query->when($request->filled('student_name'), function ($q) use ($request) {
            $q->whereHas('calendarStudents.courseStudent.student', fn($s) =>
            $s->where('name', 'like', '%'.$request->student_name.'%')
            );
        });

        // --- Lọc ngày/ca như bạn đang có (giữ nguyên) ---
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
        $end   = $request->filled('end_date')   ? Carbon::parse($request->end_date)->endOfDay()   : null;

        if ($start && $end) {
            $query->whereBetween('date_start', [$start, $end]);
        } elseif ($start || $end) {
            $query->whereDate('date_start', ($start ?? $end)->toDateString());
        }

        if ($request->filled('time')) {
            $time = (int) $request->time;
            if (in_array($time, [1,2,3], true)) {
                $query->where('time', $time);
            }
        } elseif ($request->filled('time_filter')) {
            $today = Carbon::now();
            $map = [
                '90'  => [$today->copy()->subDays(90), $today],
                '30'  => [$today->copy()->subDays(30), $today],
                '7'   => [$today->copy()->subDays(7),  $today],
                '71'  => [$today, $today->copy()->addDays(7)],
                '301' => [$today, $today->copy()->addDays(30)],
                '901' => [$today, $today->copy()->addDays(90)],
            ];
            if (isset($map[$request->time_filter])) {
                $query->whereBetween('date_start', $map[$request->time_filter]);
            }
        }

        $timeOrder = [1=>1,2=>2,3=>3];

        $calendars = $query->orderBy('date_start', 'desc')->get();

        // ======= TÍNH KẾT QUẢ THEO HỌC VIÊN TRONG TỪNG LỊCH =======
        foreach ($calendars as $calendar) {
            foreach ($calendar->calendarStudents as $calStu) {
                // ĐƯỜNG DẪN KHÓA HỌC/HỌC VIÊN (sẵn)
                $cs = $calStu->courseStudent;

                if ($type === 'exam') {
                    // Các hàng trong bảng nối calendar_student_exam_field
                    // $calStu->examFields là tập các môn thi của học viên trong lịch này
                    $statuses = collect($calStu->examFields)->pluck('pivot.exam_status')->filter(fn($v) => $v !== null);

                    if ($statuses->isEmpty() || $statuses->every(fn($s) => (int)$s === 0)) {
                        $overall = 0; // chưa có kết quả
                    } elseif ($statuses->contains(2)) {
                        $overall = 2; // chỉ cần 1 môn trượt -> trượt
                    } elseif ($statuses->every(fn($s) => (int)$s === 1)) {
                        $overall = 1; // tất cả đều đạt -> đạt
                    } else {
                        $overall = 0; // còn 0 xen lẫn 1 -> coi là chưa có kết quả
                    }

                    // Gắn cho từng học viên trong lịch
                    $calStu->overall_exam_status = $overall;
                } elseif ($type === 'study') {
                    // Lấy record student_status tương ứng môn học của lịch (1 môn/học phần)
                    $learningFieldId = $calendar->learning_field_id;
                    $studyStatus = optional($cs->studentStatuses)
                        ->firstWhere('learning_field_id', $learningFieldId);

                    // Gắn để view dùng (giờ/km…)
                    $calStu->study_status = $studyStatus; // có thể null nếu chưa khởi tạo
                }
            }

            // Để tiện render badge chung ở cột “Kết quả” (rowspan),
            // ta gom unique overall của **tất cả học viên** trong lịch này.
            if ($type === 'exam') {
                $calendar->overall_statuses = $calendar->calendarStudents
                    ->pluck('overall_exam_status')
                    ->filter(fn($v) => $v !== null)
                    ->unique()
                    ->values()
                    ->all();
            }
        }
        // ==========================================================

        // (Giữ nguyên phần sort & group như bạn đang dùng)
        $totalStudentCount = $calendars->flatMap->calendarStudents->pluck('course_student_id')->unique()->count();

        if ($type === 'exam') {
            foreach ($calendars as $calendar) {
                $calendar->exam_field_data = $calendar->exam_fields_of_calendar->map(fn($item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                ])->toArray();
            }
            $calendars = $calendars->sortBy([
                fn($a, $b) => $b->date_start <=> $a->date_start,
                fn($a, $b) => ($timeOrder[$a->time] ?? 99) <=> ($timeOrder[$b->time] ?? 99),
                fn($a, $b) => $a->stadium_id <=> $b->stadium_id,
            ]);
        } else {
            $calendars = $calendars->sortBy([
                fn($a, $b) => $b->date_start <=> $a->date_start,
                fn($a, $b) => $a->stadium_id <=> $b->stadium_id,
            ]);
        }

        $grouped = $calendars
            ->groupBy(fn($c) => $c->date_start->format('Y-m-d'))
            ->sortKeysDesc()
            ->map(function ($itemsOfDay) use ($timeOrder, $type) {
                if ($type === 'exam') {
                    return [
                        'calendars_by_time' => $itemsOfDay
                            ->groupBy(fn($c) => $c->time)
                            ->sortBy(fn($_, $time) => $timeOrder[$time])
                            ->map(function ($itemsOfTime) {
                                return [
                                    'calendars_by_stadium' => $itemsOfTime
                                        ->groupBy(fn($c) => $c->stadium_id)
                                        ->sortKeys()
                                        ->map(function ($itemsOfStadium) {
                                            return [
                                                'stadium' => $itemsOfStadium->first()->stadium,
                                                'calendars' => $itemsOfStadium,
                                                'student_count' => $itemsOfStadium
                                                    ->flatMap->calendarStudents
                                                    ->pluck('course_student_id')
                                                    ->unique()
                                                    ->count(),
                                            ];
                                        }),
                                ];
                            }),
                        'count' => $itemsOfDay->count(),
                        'student_count' => $itemsOfDay
                            ->flatMap->calendarStudents
                            ->pluck('course_student_id')
                            ->unique()
                            ->count(),
                    ];
                }
                // study/work/meeting/call
                return [
                    'calendars_by_stadium' => $itemsOfDay
                        ->groupBy(fn($c) => $c->stadium_id)
                        ->sortKeys()
                        ->map(function ($itemsOfStadium) {
                            return [
                                'stadium' => $itemsOfStadium->first()->stadium,
                                'calendars' => $itemsOfStadium,
                                'student_count' => $itemsOfStadium
                                    ->flatMap->calendarStudents
                                    ->pluck('course_student_id')
                                    ->unique()
                                    ->count(),
                            ];
                        }),
                    'count' => $itemsOfDay->count(),
                    'student_count' => $itemsOfDay
                        ->flatMap->calendarStudents
                        ->pluck('course_student_id')
                        ->unique()
                        ->count(),
                ];
            });

        $grouped = $grouped->sortKeysDesc();

        $perPage = 30;
        $currentPage = $request->input('page', 1);
        $paginatedCalendars = new LengthAwarePaginator(
            $grouped->forPage($currentPage, $perPage)->all(),
            $grouped->count(),
            $perPage,
            $currentPage,
            [
                'path' => url()->current(),
                'query' => array_merge($request->except('page'), ['type' => $type])
            ]
        );

        $calendarTypes = [
            'study' => 'học',
            'exam' => 'thi',
            'work' => 'công việc',
            'meeting' => 'họp',
            'call' => 'gọi',
        ];

        $viewName = match ($request->route()->getName()) {
            'calendars.study-lt' => 'admin.calendars.study-lt',
            'calendars.study-th' => 'admin.calendars.study-th',
            'calendars.study-th-detail' => 'admin.calendars.study-th-detail',
            'calendars.study-lt-detail' => 'admin.calendars.study-lt-detail',
            'calendars.exam-tn' => 'admin.calendars.exam-tn',
            'calendars.exam-tn-detail' => 'admin.calendars.exam-tn-detail',
            'calendars.exam-sh' => 'admin.calendars.exam-sh',
            'calendars.exam-sh-detail' => 'admin.calendars.exam-sh-detail',
            'calendars.exam-hmlt' => 'admin.calendars.exam-hmlt',
            'calendars.exam-hmlt-detail' => 'admin.calendars.exam-hmlt-detail',
            'calendars.exam-hmth' => 'admin.calendars.exam-hmth',
            'calendars.exam-hmth-detail' => 'admin.calendars.exam-hmth-detail',
            'calendars.work' => 'admin.calendars.work',
            'calendars.meeting' => 'admin.calendars.meeting',
            'calendars.call' => 'admin.calendars.call',
            default => 'admin.calendars.index',
        };

        return view($viewName, compact(
            'teachers','studentAlls','paginatedCalendars','calendarTypes',
            'type','statusConfig','stadiums','totalStudentCount'
        ));
    }


    public function getInforByVehicleType(Request $request)
    {
        $vehicleTypeRequest = $request->input('vehicle_type');

        if (!$vehicleTypeRequest) {
            return response()->json(['error' => 'vehicle_type is required'], 400);
        }
        $learningField = [];
        if ($vehicleTypeRequest == 1) {
            $vehicleType = 0;
            $learningField = LearningField::where('applies_to_all_rankings', 1)->get();
            $examField = ExamField::whereIn('applies_to_all_rankings', [1, 3])->get();
        } else {
            $vehicleType = 1;
            $learningField = LearningField::all();
            $examField = ExamField::where('applies_to_all_rankings', '!=', 3)->get();
        }
        $courses = Course::where(function ($query) use ($vehicleType) {
            $query->whereHas('ranking', function ($subQuery) use ($vehicleType) {
                $subQuery->where('vehicle_type', $vehicleType);
            })
            ->orWhere('id', 99999999);
        })
        ->with([
            'students' => function ($query) {
                $query->withPivot('id'); // load id của bảng course_student
            },
            'students.ranking' => function ($query) {
                $query->select('id', 'name', 'vehicle_type');
            }
        ])
        ->get();

        $courses->each(function ($course) use ($vehicleType) {
            if ($course->id == 99999999) {
                $course->students = $course->students->filter(function ($student) use ($vehicleType) {
                    return is_null($student->ranking) || $student->ranking->vehicle_type == $vehicleType;
                })->values();
                $course->students->each(function ($student) use ($course) {
                    if ($student->ranking) {
                        $student->course_name = $student->ranking->name . ' chưa xếp';
                    } else {
                        $student->course_name = $course->code;
                    }
                });
            }
        });

        $results = [];
        foreach ($courses as $course) {
            foreach ($course->students as $student) {
                $results[] = [
                    'id' => $student->pivot->id,
                    'label' => $student->name . ' - ' . ($course->id == 99999999 ? $student->course_name : $course->code),
                ];
            }
        }

        return response()->json([
            'students' => $results,
            'learning_fields' => $learningField,
            'exam_fields' => $examField,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        $stadiums = Stadium::all();
        $users = User::all();
        $teachers = $users->filter(fn($user) => $user->hasRole('instructor'));
        $salespersons = $users->filter(fn($user) => $user->hasRole('salesperson'));
        $studentsAll = Student::all();
        $students = $studentsAll->where('is_student', '1');
        return view('admin.calendars.create', compact('courses','users', 'teachers', 'salespersons', 'studentsAll', 'students', 'stadiums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalendarRequest $request)
    {
        // dd($request->all());
        try {
            $validatedData = $request->validated();
            if ($validatedData['type'] === 'exam' && $validatedData['date'] && $validatedData['time']) {
                $date = $validatedData['date'];
                $time = $validatedData['time'];
                $timeRanges = [
                    1 => ['start' => '07:00:00', 'end' => '11:30:00'],
                    2 => ['start' => '13:00:00', 'end' => '17:00:00'],
                    3 => ['start' => '07:00:00', 'end' => '17:00:00'],
                ];
                $validatedData['date_start'] = Carbon::parse("{$date} {$timeRanges[$time]['start']}")->format('Y-m-d H:i:s');
                $validatedData['date_end'] = Carbon::parse("{$date} {$timeRanges[$time]['end']}")->format('Y-m-d H:i:s');
                try {
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                        $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                    }
                    $validatedData['date_start'] = Carbon::parse("{$date} {$timeRanges[$time]['start']}")->format('Y-m-d H:i:s');
                    $validatedData['date_end'] = Carbon::parse("{$date} {$timeRanges[$time]['end']}")->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Định dạng ngày không hợp lệ: ' . $e->getMessage());
                }
            }
            if ($validatedData['type'] === 'exam' || $validatedData['type'] === 'study') {
                $stadiumID = $validatedData['type'] === 'exam' ? $validatedData['exam_schedule_id'] : $validatedData['stadium_id'];

                $exists = Calendar::where('type', $validatedData['type'])
                ->where('level', $validatedData['exam_course_type'])
                ->whereDate('date_start', $validatedData['date_start'])
                ->whereDate('date_end', $validatedData['date_end'])
                ->where('stadium_id', $stadiumID)
                ->exists();

                if ($exists) {
                    return back()->withErrors(['duplicate' => 'Đã tồn tại lịch cho sân này vào buổi này và ngày này.'])->withInput();
                }
            }

            if ($validatedData['type'] === 'study' && $validatedData['exam_course_type'] == 4 && $validatedData['vehicle_type'] == 2) {
                if (!empty($validatedData['vehicle_id'])) {
                    $vehicleConflict = Calendar::where('type', 'study')
                    ->where('vehicle_id', $validatedData['vehicle_id'])
                    ->where(function ($query) use ($validatedData) {
                        $query->whereBetween('date_start', [$validatedData['date_start'], $validatedData['date_end']])
                            ->orWhereBetween('date_end', [$validatedData['date_start'], $validatedData['date_end']])
                            ->orWhere(function ($q) use ($validatedData) {
                                $q->where('date_start', '<=', $validatedData['date_start'])
                                    ->where('date_end', '>=', $validatedData['date_end']);
                        });
                    })
                    ->exists();

                    if ($vehicleConflict) {
                        throw ValidationException::withMessages([
                            'vehicle_id' => 'Xe đã được sử dụng trong khoảng thời gian này cho một lịch học khác.',
                        ]);
                    }
                }
            }

            $possibleStudentFields = [
                'learn_student_id',
                'exam_student_id',
                'student_participants',
            ];
            $studentIds = [];
            foreach ($possibleStudentFields as $field) {
                if (!empty($validatedData[$field])) {
                    if (is_array($validatedData[$field])) {
                        $studentIds = $validatedData[$field];
                    } else {
                        $studentIds = [$validatedData[$field]];
                    }
                    break;
                }
            }

            foreach ($studentIds as $studentId) {
                $studentConflict = $this->checkForOverlappingSchedule(
                    $studentId,
                    $validatedData['date_start'],
                    $validatedData['date_end']
                );

                if ($studentConflict) {
                    throw ValidationException::withMessages([
                        'student_id' => 'Học viên đã có lịch trong khoảng thời gian này.',
                    ]);
                }
            }
            DB::beginTransaction();

            $dateStart = Carbon::parse($validatedData['date_start']);
            $dateEnd = Carbon::parse($validatedData['date_end']);
            $durationInMinutes = $dateEnd->diffInMinutes($dateStart);
            $status = 1;
            $calendar = Calendar::create([
                'type' => $validatedData['type'],
                'name' => $validatedData['name'] ?? '',
                'status' => $status,
                'date_start' => $validatedData['date_start'],
                'date_end' => $validatedData['date_end'],
                'duration' => $durationInMinutes,
                'description' => $validatedData['description'] ?? null,
            ]);
            if ($validatedData['type'] === 'study') {
                if ($validatedData['vehicle_type'] == 1) {
                    if($validatedData['exam_course_type'] == 4) {
                        $validatedData['learning_id'] = LearningField::where('applies_to_all_rankings', 1)->where('is_practical', 1)->value('id');
                    } else if ($validatedData['exam_course_type'] == 3) {
                        $validatedData['learning_id'] = LearningField::where('applies_to_all_rankings', 1)->where('is_practical', 0)->value('id');
                    }
                }
                $calendar->learning_field_id = $validatedData['learning_id'];
                $calendar->level = $validatedData['exam_course_type'];
                $calendar->vehicle_id = $validatedData['vehicle_select'] ?? null;
                $calendar->stadium_id = $validatedData['stadium_id'] ?? null;
                $calendar->save();
                $calendar->courseStudents()->attach($validatedData['learn_student_id']);
                $calendar->users()->attach($validatedData['learn_teacher_id'], ['role' => 2]);
            } elseif ($validatedData['type'] === 'exam') {
                if ($validatedData['vehicle_type'] == 2) {
                    if($validatedData['exam_course_type'] == 5) {
                        $validatedData['exam_id'] = LearningField::where('applies_to_all_rankings', 3)->where('is_practical', 1)->value('id');
                    } else if ($validatedData['exam_course_type'] == 6) {
                        $validatedData['exam_id'] = LearningField::where('applies_to_all_rankings', 1)->where('is_practical', 0)->value('id');
                    }
                }
                $calendar->level = $validatedData['exam_course_type'];
                $calendar->date =  $validatedData['date'];
                $calendar->time =  $validatedData['time'];
                $calendar->stadium_id = $validatedData['exam_schedule_id'];
                $calendar->save();

                foreach ($validatedData['exam_student_id'] as $studentId) {
                    $studentData = $validatedData['students'][$studentId];
                    $pickup = isset($studentData['pickup']) ? true : false;
                    $calendar->courseStudents()->attach($studentId, [
                        'exam_number' => $studentData['exam_number'],
                        'pickup' => $pickup,
                    ]);

                    $calendarStudentId = CalendarStudent::where('calendar_id', $calendar->id)
                    ->where('course_student_id', $studentId)
                    ->value('id');

                    foreach ($validatedData['exam_id'] as $examFieldId) {
                        CalendarStudentExamField::create([
                            'calendar_student_id' => $calendarStudentId,
                            'exam_field_id' => $examFieldId,
                            'attempt_number' => $validatedData['lanthi'],
                            'exam_status' => 0,
                        ]);
                    }
                }
            } elseif ($validatedData['type'] === 'work') {
                // Lưu các mối quan hệ giữa lịch và công việc
                $calendar->users()->attach($validatedData['work_assigned_to'], ['role' => 1]);
                if (!empty($validatedData['work_support'])) {
                    $calendar->users()->attach($validatedData['work_support'], ['role' => 4]);
                }
            } elseif ($validatedData['type'] === 'meeting') {
                // Lưu các mối quan hệ giữa lịch và cuộc họp
                $calendar->users()->attach($validatedData['meeting_assigned_to'], ['role' => 1]);
                if (!empty($validatedData['meeting_support'])) {
                    $calendar->users()->attach($validatedData['meeting_support'], ['role' => 4]);
                }
                if (!empty($validatedData['user_participants'])) {
                    $calendar->users()->attach($validatedData['user_participants'], ['role' => 5]);
                }
                if (!empty($validatedData['student_participants'])) {
                    $calendar->students()->attach($validatedData['student_participants']);
                }
            } elseif ($validatedData['type'] === 'call') {
                // Lưu các mối quan hệ giữa lịch và cuộc gọi
                $calendar->users()->attach($validatedData['call_sale_id'], ['role' => 3]);
                $calendar->students()->attach($validatedData['call_student_id']);
            }
            //Trong bảng calendar_user thì role như sau: Người phụ trách=1, Giáo viên=2, Người thực hiện=3, Người hỗ trợ=4, Người tham gia=5
            DB::commit();
            return redirect()->back()->with('success', 'Lịch đã được tạo thành công!');
        } catch (ValidationException $e) {
            Log::error('Lỗi validate khi tạo lịch', [
                'error_messages' => $e->errors(),
                'type' => $validatedData['type'] ?? null,
                'date_start' => $validatedData['date_start'] ?? null,
                'date_end' => $validatedData['date_end'] ?? null,
                'student_ids' => $studentIds ?? [],
                'vehicle_id' => $validatedData['vehicle_id'] ?? null,
                'user_id' => auth()->id() ?? null,
                'stack_trace' => $e->getTraceAsString(),
                'input_data' => collect($request->all())->except(['password', '_token'])->toArray(),
            ]);

            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lỗi khi tạo lịch', [
                'error_message' => $e->getMessage(),
                'type' => $validatedData['type'] ?? null,
                'date_start' => $validatedData['date_start'] ?? null,
                'date_end' => $validatedData['date_end'] ?? null,
                'student_ids' => $studentIds ?? [],
                'vehicle_id' => $validatedData['vehicle_id'] ?? null,
                'user_id' => auth()->id() ?? null,
                'stack_trace' => $e->getTraceAsString(),
                'input_data' => collect($request->all())->except(['password', '_token'])->toArray(),
            ]);

            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi trong quá trình tạo lịch. Vui lòng thử lại.']);
        }
    }

    public function checkForOverlappingSchedule($studentId, $dateStart, $dateEnd)
    {
        return Calendar::whereHas('courseStudents', function ($query) use ($studentId) {
            $query->where('course_student_id', $studentId);
        })
        ->where(function ($query) use ($dateStart, $dateEnd) {
            $query->whereBetween('date_start', [$dateStart, $dateEnd])
                  ->orWhereBetween('date_end', [$dateStart, $dateEnd])
                  ->orWhere(function ($q) use ($dateStart, $dateEnd) {
                      $q->where('date_start', '<=', $dateStart)
                        ->where('date_end', '>=', $dateEnd);
                  });
        })
        ->exists();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $calendar = Calendar::with([
            'courses:id,code',
            'users:id,name' => fn($query) => $query->withPivot('role', 'hours', 'km', 'night_hours', 'auto_hours'),
            'students:id,name,student_code' => fn($query) => $query->withPivot('score', 'correct_answers', 'exam_status', 'attempt_number', 'exam_number', 'exam_fee_paid_at', 'remarks', 'hours', 'km', 'night_hours', 'auto_hours'),
            'examField:id,name',
            'learningField:id,name',
            'vehicle:id,license_plate,model',
            'stadium:id,location',
            'examSchedule:id,stadium_id,date,time,start_time,end_time'
        ])->findOrFail($id);

        return response()->json($calendar);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calendar $calendar)
    {
        $courses = Course::all();
        $stadiums = Stadium::all();
        $users = User::all();
        $teachers = $users->filter(fn($user) => $user->hasRole('instructor'));
        $salespersons = $users->filter(fn($user) => $user->hasRole('salesperson'));
        $studentsAll = Student::all();
        $students = $studentsAll->where('is_student', '1');
        $courseStudentAlls = CourseStudent::all();
        $learningFields = $firstCourse?->learningFields ?? collect();
        $examFields = $firstCourse?->examFields ?? collect();
        $examFieldOfCalendar = $calendar->exam_fields_of_calendar->pluck('id')->toArray();
        $calendar->load(['calendarStudents', 'calendarStudents.courseStudent.course', 'calendarStudents.courseStudent.student', 'calendarStudents.courseStudent.calendarExam', 'users', 'stadium', 'vehicle', 'learningField']);
        // dd($calendar);
        return view('admin.calendars.edit', compact('calendar', 'courses', 'users', 'teachers', 'salespersons', 'studentsAll', 'students', 'stadiums', 'learningFields', 'examFields', 'examFieldOfCalendar', 'courseStudentAlls'));
    }

    public function update(StoreCalendarRequest $request, Calendar $calendar)
    {
        Log::info('Form data:', $request->all());
        $validatedData = $request->validated();
        // Kiểm tra xung đột xe nếu là lịch học
        if ($validatedData['type'] === 'study' && !empty($validatedData['vehicle_select'])) {
            $vehicleConflict = Calendar::where('type', 'study')
                ->where('vehicle_id', $validatedData['vehicle_select'])
                ->where('id', '!=', $calendar->id)
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('date_start', [$validatedData['date_start'], $validatedData['date_end']])
                        ->orWhereBetween('date_end', [$validatedData['date_start'], $validatedData['date_end']])
                        ->orWhere(function ($q) use ($validatedData) {
                            $q->where('date_start', '<=', $validatedData['date_start'])
                                ->where('date_end', '>=', $validatedData['date_end']);
                        });
                })
                ->exists();

            if ($vehicleConflict) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Xe đã được sử dụng trong khoảng thời gian này cho một lịch học khác.',
                ]);
            }
        }

        // Xử lý thời gian cho lịch thi
        if ($validatedData['type'] === 'exam' && $validatedData['date'] && $validatedData['time']) {
            $date = $validatedData['date'];
            $time = $validatedData['time'];
            $timeRanges = [
                1 => ['start' => '07:00:00', 'end' => '11:30:00'],
                2 => ['start' => '13:00:00', 'end' => '17:00:00'],
                3 => ['start' => '07:00:00', 'end' => '17:00:00'],
            ];
            $validatedData['date_start'] = Carbon::parse("{$date} {$timeRanges[$time]['start']}")->format('Y-m-d H:i:s');
            $validatedData['date_end'] = Carbon::parse("{$date} {$timeRanges[$time]['end']}")->format('Y-m-d H:i:s');
        }

        // Kiểm tra xung đột lịch của học viên
        $possibleStudentFields = ['learn_student_id', 'exam_student_id', 'student_participants', 'call_student_id'];
        $studentIds = [];
        foreach ($possibleStudentFields as $field) {
            if (!empty($validatedData[$field])) {
                $studentIds = is_array($validatedData[$field]) ? $validatedData[$field] : [$validatedData[$field]];
                break;
            }
        }

        foreach ($studentIds as $studentId) {
            $studentConflict = Calendar::where('id', '!=', $calendar->id)
                ->whereHas('students', fn($query) => $query->where('student_id', $studentId))
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('date_start', [$validatedData['date_start'], $validatedData['date_end']])
                        ->orWhereBetween('date_end', [$validatedData['date_start'], $validatedData['date_end']])
                        ->orWhere(function ($q) use ($validatedData) {
                            $q->where('date_start', '<=', $validatedData['date_start'])
                                ->where('date_end', '>=', $validatedData['date_end']);
                        });
                })
                ->exists();

            if ($studentConflict) {
                throw ValidationException::withMessages([
                    'student_id' => 'Học viên đã có lịch trong khoảng thời gian này.',
                ]);
            }
        }
        try {
            DB::beginTransaction();

            $dateStart = Carbon::parse($validatedData['date_start']);
            $dateEnd = Carbon::parse($validatedData['date_end']);
            $durationInMinutes = $dateEnd->diffInMinutes($dateStart);

            // Cập nhật thông tin cơ bản của lịch
            $calendar->update([
                'type' => $validatedData['type'],
                'name' => $validatedData['name'] ?? '',
                'date_start' => $validatedData['date_start'],
                'date_end' => $validatedData['date_end'],
                'duration' => $durationInMinutes,
                'description' => $validatedData['description'] ?? null,
            ]);

            if ($validatedData['type'] === 'study') {
                $calendar->update([
                    'learning_field_id' => $validatedData['learning_id'],
                    'vehicle_id' => $validatedData['vehicle_select'] ?? null,
                    'stadium_id' => $validatedData['stadium_id'] ?? null,
                ]);
                // Đồng bộ students
                $calendar->students()->sync($validatedData['learn_student_id']);
                // Đồng bộ users
                $calendar->users()->sync([$validatedData['learn_teacher_id'] => ['role' => 2]]);
                // Đồng bộ courses
                $calendar->courses()->sync($validatedData['learn_course_id']);
            } elseif ($validatedData['type'] === 'exam') {
                $calendar->update([
                    'level' => $validatedData['exam_course_type'],
                    'date' => $validatedData['date'],
                    'time' => $validatedData['time'],
                    'exam_schedule_id' => $validatedData['exam_schedule_id'],
                    'exam_fee' => $validatedData['exam_fee'],
                    'exam_fee_deadline' => $validatedData['exam_fee_deadline'],
                ]);
                // Đồng bộ courses
                $calendar->courses()->sync($validatedData['exam_course_id']);
                // Đồng bộ students
                $studentsToSync = [];
                foreach ($validatedData['exam_student_id'] as $studentId) {
                    $studentData = $validatedData['students'][$studentId];
                    $studentsToSync[$studentId] = [
                        'exam_number' => $studentData['exam_number'],
                        'pickup' => $studentData['pickup'] ?? 0,
                    ];
                }
                $calendar->students()->sync($studentsToSync);

                // Xử lý các môn thi cho từng học sinh
                foreach ($validatedData['exam_student_id'] as $studentId) {
                    $calendarStudentId = CalendarStudent::where('calendar_id', $calendar->id)
                        ->where('student_id', $studentId)
                        ->value('id');

                    if (!$calendarStudentId) {
                        DB::rollBack();
                        return redirect()->back()->withErrors(['error' => 'Không thể tìm thấy calendarStudentId cho học sinh ' . $studentId]);
                    }

                    CalendarStudentExamField::where('calendar_student_id', $calendarStudentId)->delete();
                    foreach ($validatedData['exam_id'] as $examFieldId) {
                        CalendarStudentExamField::create([
                            'calendar_student_id' => $calendarStudentId,
                            'exam_field_id' => $examFieldId,
                            'attempt_number' => 1,
                            'exam_status' => 0,
                        ]);
                    }
                }
            } elseif ($validatedData['type'] === 'work') {
                $usersToSync = [$validatedData['work_assigned_to'] => ['role' => 1]];
                if (!empty($validatedData['work_support'])) {
                    $usersToSync[$validatedData['work_support']] = ['role' => 4];
                }
                $calendar->users()->sync($usersToSync);
            } elseif ($validatedData['type'] === 'meeting') {
                $usersToSync = [$validatedData['meeting_assigned_to'] => ['role' => 1]];
                if (!empty($validatedData['meeting_support'])) {
                    $usersToSync[$validatedData['meeting_support']] = ['role' => 4];
                }
                if (!empty($validatedData['user_participants'])) {
                    foreach ((array)$validatedData['user_participants'] as $userId) {
                        $usersToSync[$userId] = ['role' => 5];
                    }
                }
                $calendar->users()->sync($usersToSync);
                $calendar->students()->sync($validatedData['student_participants'] ?? []);
            } elseif ($validatedData['type'] === 'call') {
                $calendar->users()->sync([$validatedData['call_sale_id'] => ['role' => 3]]);
                $calendar->students()->sync($validatedData['call_student_id']);
            }

            DB::commit();
            return redirect()->route('calendars.index')->with('success', 'Lịch đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi cập nhật lịch: ' . $e->getMessage(), [
                'calendar_id' => $calendar->id,
                'exception' => $e,
            ]);
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi trong quá trình cập nhật lịch. Vui lòng thử lại.']);
        }
    }

    public function addStudent(Request $request, Calendar $calendar)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'student_exam_number' => 'required_if:type,exam|string|max:50',
            'student_exam_fee_paid_at' => 'required_if:type,exam|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $request->all();

        // Kiểm tra xung đột lịch cho học viên
        $studentConflict = $this->checkUpdateForOverlappingSchedule(
            $validatedData['student_id'],
            $calendar->date_start,
            $calendar->date_end
        );

        if ($studentConflict) {
            throw ValidationException::withMessages([
                'student_id' => 'Học viên đã có lịch trong khoảng thời gian này.',
            ]);
        }

        try {
            DB::beginTransaction();

            $data = ['role' => 'student'];
            if ($calendar->type === 'exam') {
                $data['exam_number'] = $validatedData['student_exam_number'];
                $data['exam_fee_paid_at'] = $validatedData['student_exam_fee_paid_at'];

                // Thêm học viên và các exam_field nếu có
                $calendar->students()->syncWithoutDetaching([$validatedData['student_id'] => $data]);

                $calendarStudentId = CalendarStudent::where('calendar_id', $calendar->id)
                    ->where('student_id', $validatedData['student_id'])
                    ->value('id');

                if (!empty($validatedData['exam_field_id'])) {
                    CalendarStudentExamField::create([
                        'calendar_student_id' => $calendarStudentId,
                        'exam_field_id' => $validatedData['exam_field_id'],
                        'attempt_number' => 1,
                        'exam_status' => 0,
                    ]);
                }
            } else {
                $calendar->students()->syncWithoutDetaching([$validatedData['student_id'] => $data]);
            }

            DB::commit();
            return response()->json(['message' => 'Thêm học viên thành công'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['error' => 'Đã xảy ra lỗi khi thêm học viên.'], 500);
        }
    }

    public function removeStudent(Request $request, Calendar $calendar, Student $student)
    {
        try {
            DB::beginTransaction();

            // Xóa học viên và các bản ghi liên quan trong calendar_student_exam_field
            CalendarStudentExamField::whereIn('calendar_student_id', function ($query) use ($calendar, $student) {
                $query->select('id')
                    ->from('calendar_student')
                    ->where('calendar_id', $calendar->id)
                    ->where('student_id', $student->id);
            })->delete();

            $calendar->students()->detach($student->id);

            DB::commit();
            return response()->json(['message' => 'Xóa học viên thành công'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['error' => 'Đã xảy ra lỗi khi xóa học viên.'], 500);
        }
    }

    public function getAvailableStudents(Calendar $calendar)
    {
        $courseIds = $calendar->courses()->pluck('courses.id')->toArray();

        if (empty($courseIds)) {
            return response()->json(['error' => 'Lịch không thuộc khóa học nào'], 400);
        }

        $students = Student::whereHas('courses', function ($query) use ($courseIds) {
            $query->whereIn('courses.id', $courseIds); // Lọc học viên thuộc các khóa học
        })
        ->whereDoesntHave('calendars', function ($query) use ($calendar) {
            $query->where('calendar_id', $calendar->id); // Kiểm tra học viên chưa có trong lịch
        })
        ->get(['id', 'name', 'student_code']);

        if ($students->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy học viên phù hợp'], 200);
        }

        return response()->json($students, 200);
    }

    public function checkUpdateForOverlappingSchedule($studentId, $dateStart, $dateEnd, $excludeCalendarId = null)
    {
        $query = Calendar::whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->where(function ($query) use ($dateStart, $dateEnd) {
            $query->whereBetween('date_start', [$dateStart, $dateEnd])
                  ->orWhereBetween('date_end', [$dateStart, $dateEnd])
                  ->orWhere(function ($q) use ($dateStart, $dateEnd) {
                      $q->where('date_start', '<=', $dateStart)
                        ->where('date_end', '>=', $dateEnd);
                  });
        });

        if ($excludeCalendarId) {
            $query->where('id', '!=', $excludeCalendarId);
        }

        return $query->exists();
    }

    public function getStudents(Calendar $calendar, Request $request)
    {
        $courseStudentId = $request->input('course_student_id');
        $calendar->load(['stadium:id,location,google_maps_url']);
        if ($calendar->type === 'study') {
            $calendar->load('learningField:id,name');
            $fieldName = $calendar->learningField?->name;
        } elseif ($calendar->type === 'exam') {
            $fieldName = $calendar->exam_fields_of_calendar->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            })->toArray();
        } else {
            $fieldName = null;
        }

        $studentsQuery = $calendar->calendarStudents()->with(['courseStudent.student:id,student_code,name', 'courseStudent.course:id,code', 'examFields.examField:id,name']);

        if ($courseStudentId) {
            $studentsQuery->where('course_student_id', $courseStudentId);
        }

        $students = $studentsQuery->get();
        $studentsData = $students->map(function ($cs) {
            return [
                'student_id' => $cs->courseStudent->student->id,
                'student_code' => $cs->courseStudent->student->student_code,
                'student_name' => $cs->courseStudent->student->name,
                'course_id' => $cs->courseStudent->course->id ?? null,
                'course_code' => $cs->courseStudent->course->code ?? null,
                'study_results' => [
                    'score' => $cs->score,
                    'correct_answers' => $cs->correct_answers,
                    'exam_status' => $cs->exam_status,
                    'hours' => $cs->hours,
                    'km' => $cs->km,
                    'night_hours' => $cs->night_hours,
                    'auto_hours' => $cs->auto_hours,
                    'exam_number' => $cs->exam_number,
                    'remarks' => $cs->remarks,
                    'attempt_number' => $cs->attempt_number,
                ],
                'exam_results' => $cs->examFields->map(function ($exam) {
                    return [
                        'field_name' => $exam->examField->name,
                        'attempt_number' => $exam->attempt_number,
                        'answer_ratio' => $exam->answer_ratio,
                        'exam_all_status' => $exam->exam_all_status,
                        'exam_status' => $exam->exam_status,
                        'remarks' => $exam->remarks,
                    ];
                }),
            ];
        });

        return response()->json([
            'calendar' => [
                'name' => $calendar->name,
                'date_start' => $calendar->date_start,
                'date_end' => $calendar->date_end,
                'duration' => $calendar->duration,
                'location' => $calendar->stadium->location,
                'url_location' => $calendar->stadium->google_maps_url,
                'type' => $calendar->type,
                'field_name' => $fieldName ?? '',
            ],
            'students' => $studentsData,
        ]);
    }

    public function storeResults(Request $request, Calendar $calendar)
    {
        try {
            DB::beginTransaction();
            // Lấy danh sách khóa học liên quan đến lịch hiện tại
            $relatedCourseIds = DB::table('calendar_course')
            ->where('calendar_id', $calendar->id)
            ->pluck('course_id');
            if ($calendar->type === 'exam') {
                foreach ($request->students as $studentId => $data) {
                    $pivot = $calendar->students()->where('student_id', $studentId)->first();
                    if (!$pivot) continue;
                    $calendarStudentId = $pivot->pivot->id;
                    $examStatuses = [];
                    foreach ($data as $examFieldId => $result) {
                        // Đếm số lần sinh viên đã thi môn này trong các khóa học đó
                        $attemptQuery = DB::table('calendar_student_exam_field as csef')
                            ->join('calendar_student as cs', 'csef.calendar_student_id', '=', 'cs.id')
                            ->join('calendars as c', 'cs.calendar_id', '=', 'c.id')
                            ->join('calendar_course as cc', 'cc.calendar_id', '=', 'c.id')
                            ->where('cs.student_id', $studentId)
                            ->where('c.type', 'exam')
                            ->where('csef.exam_field_id', $examFieldId)
                            ->whereIn('cc.course_id', $relatedCourseIds)
                            ->where('c.date_start', '<', $calendar->date_start)
                            ->distinct('cs.id');

                        $attemptNumber  = $attemptQuery->count() + 1;

                        DB::table('calendar_student_exam_field')->updateOrInsert(
                            [
                                'calendar_student_id' => $calendarStudentId,
                                'exam_field_id' => $examFieldId,
                            ],
                            [
                                'exam_status' => $result['exam_status'],
                                'remarks' => $result['remarks'] ?? null,
                                'attempt_number' => $attemptNumber,
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );

                        $examStatuses[] = $result['exam_status'] ?? 0;

                        foreach ($relatedCourseIds as $courseId) {
                            $this->updateStudentExamAttemptNumber($studentId, $examFieldId, $courseId);
                        }
                    }
                    if (in_array(null, $examStatuses, true) || count($examStatuses) == 0) {
                        DB::table('calendar_student_exam_field')
                            ->where('calendar_student_id', $calendarStudentId)
                            ->update(['exam_all_status' => 0]);
                    } elseif (in_array(2, $examStatuses)) {
                        DB::table('calendar_student_exam_field')
                            ->where('calendar_student_id', $calendarStudentId)
                            ->update(['exam_all_status' => 2]);
                    } else {
                        DB::table('calendar_student_exam_field')
                            ->where('calendar_student_id', $calendarStudentId)
                            ->update(['exam_all_status' => 1]);
                    }
                }
            }else if ($calendar->type === 'study') {
                $learningField = optional($calendar->learningField);
                $aggregated = [
                    'hours' => 0,
                    'km' => 0,
                    'night_hours' => 0,
                    'auto_hours' => 0,
                ];

                foreach ($request->students as $studentId => $data) {
                    $pivotData = collect($data)
                        ->filter(fn($value) => $value !== null && $value !== '')
                        ->toArray();

                        $learningFieldId = $calendar->learning_field_id;

                    if ($calendar->students()->where('student_id', $studentId)->exists()) {
                        $calendar->students()->updateExistingPivot($studentId, $pivotData);
                    }

                    if ($learningField->teachingMode == 0) {
                        $aggregated['hours'] += (float) ($pivotData['hours'] ?? 0);
                        $aggregated['km'] += (float) ($pivotData['km'] ?? 0);
                        $aggregated['night_hours'] += (float) ($pivotData['night_hours'] ?? 0);
                        $aggregated['auto_hours'] += (float) ($pivotData['auto_hours'] ?? 0);
                    }


                    foreach ($relatedCourseIds as $courseId) {
                        $this->updateStudentStudyTotals($studentId, $learningFieldId, $courseId);
                    }
                }
                $teacher = $calendar->users()->where('role', 2)->first();
                if ($teacher) {
                    $teacherId = $teacher->id;

                    if ($learningField->teachingMode == 0) {
                        // Lưu tổng cộng của tất cả học viên
                        $aggregated['price_at_result'] = $learningField->price;
                        $calendar->users()->syncWithoutDetaching([
                            $teacherId => $aggregated,
                        ]);
                    } elseif ($learningField->teachingMode == 1) {
                        // Lấy kết quả của học viên đầu tiên trong request
                        $firstStudentData = collect($request->students)->first();

                        $teacherData = collect($firstStudentData)
                            ->only(['hours', 'km', 'night_hours', 'auto_hours'])
                            ->map(fn($v) => (float) $v)
                            ->toArray();

                        $teacherData['price_at_result'] = $learningField->price;

                        $calendar->users()->syncWithoutDetaching([
                            $teacherId => $teacherData,
                        ]);
                    }
                }
            } else {
                foreach ($request->students as $studentId => $data) {
                    $pivotData = collect($data)
                        ->filter(fn($value) => $value !== null && $value !== '')
                        ->toArray();

                    if ($calendar->students()->where('student_id', $studentId)->exists()) {
                        $calendar->students()->updateExistingPivot($studentId, $pivotData);
                    }
                }
            }

            $user = auth()->user();
            /** @var User|null $user */
            if ($calendar->type === 'exam' || $calendar->type === 'study') {
                if ($user && $user->hasRole('instructor')) {
                    $calendar->status = 3;
                } else {
                    $calendar->status = 10;
                }
            } else {
                $calendar->status = 3;
            }
            $calendar->save();
            DB::commit();
            return response()->json(['message' => 'Cập nhật kết quả thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật kết quả!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function updateStudentStudyTotals(int $courseStudentId, int $learningFieldId)
    {
        // Lấy ra course_id và student_id từ course_students để dùng cho logic phụ thuộc
        $cs = DB::table('course_students')->where('id', $courseStudentId)->first();
        if (!$cs) {
            Log::warning("Không tìm thấy course_student_id={$courseStudentId}");
            return;
        }

        // Tính totals theo course_student_id thay vì student_id + course_id
        $totals = DB::table('calendar_course_student as ccs')
            ->join('calendars as c', 'ccs.calendar_id', '=', 'c.id')
            ->join('calendar_course as cc', 'cc.calendar_id', '=', 'c.id')
            ->where('ccs.course_student_id', $courseStudentId)
            ->where('c.type', 'study')
            ->where('c.learning_field_id', $learningFieldId)
            ->where('cc.course_id', $cs->course_id)
            ->selectRaw('
            COALESCE(SUM(ccs.hours), 0) as total_hours,
            COALESCE(SUM(ccs.km), 0) as total_km,
            COALESCE(SUM(ccs.night_hours), 0) as total_night_hours,
            COALESCE(SUM(ccs.auto_hours), 0) as total_auto_hours
        ')
            ->first();

        // Tính status (đủ/thiếu) dựa trên yêu cầu của khóa học + môn
        $status = 0;
        $student = DB::table('students')->where('id', $cs->student_id)->first();
        if ($student && strtolower($student->status ?? '') === 'active') {
            $required = DB::table('course_learning_field')
                ->where('course_id', $cs->course_id)
                ->where('learning_field_id', $learningFieldId)
                ->first();
            if ($required) {
                $status = ($totals->total_hours >= ($required->hours ?? 0)
                    && $totals->total_km >= ($required->km ?? 0)) ? 1 : 0;
            }
        } else {
            $status = 2; // nghỉ/không active
        }

        // Upsert bằng course_student_id (mới)
        DB::table('student_statuses')->updateOrInsert(
            [
                'course_student_id'  => $courseStudentId,
                'learning_field_id'  => $learningFieldId,
            ],
            [
                'hours'       => $totals->total_hours,
                'km'          => $totals->total_km,
                'night_hours' => $totals->total_night_hours,
                'auto_hours'  => $totals->total_auto_hours,
                'status'      => $status,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );
        Log::info("Cập nhật tổng giờ/km cho student_id={$cs->student_id}, learning_field_id={$learningFieldId}, course_id={$cs->course_id}", (array)$totals);
    }

    private function updateStudentExamAttemptNumber(int $courseStudentId, int $examFieldId)
    {
        $cs = DB::table('course_students')->where('id', $courseStudentId)->first();
        if (!$cs) {
            Log::warning("Không tìm thấy course_student_id={$courseStudentId}");
            return;
        }

        // Lấy tất cả calendar_student (pivot cũ) tương ứng course_student_id hiện diện trong lịch thi
        $calendarStudentIds = DB::table('calendar_course_student as ccs')
            ->join('calendars as c', 'c.id', '=', 'ccs.calendar_id')
            ->join('calendar_course as cc', 'cc.calendar_id', '=', 'c.id')
            ->where('ccs.course_student_id', $courseStudentId)
            ->where('c.type', 'exam')
            ->where('cc.course_id', $cs->course_id)
            ->pluck('ccs.id'); // chính là id của calendar_course_student

        $hasPassed = DB::table('calendar_student_exam_field')
            ->whereIn('calendar_student_id', $calendarStudentIds)
            ->where('exam_field_id', $examFieldId)
            ->where('exam_all_status', 1)
            ->exists();

        $examCount = DB::table('calendar_student_exam_field')
            ->whereIn('calendar_student_id', $calendarStudentIds)
            ->where('exam_field_id', $examFieldId)
            ->whereIn('exam_all_status', [1, 2])
            ->count();

        DB::table('student_exam_fields')->updateOrInsert(
            [
                'course_student_id' => $courseStudentId,
                'exam_field_id'     => $examFieldId,
            ],
            [
                'attempt_number' => $examCount,
                'status'         => $hasPassed ? 1 : 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );

//        Log::info("Cập nhật attempt_number = {$examCount} cho student_id = {$studentId}, exam_field_id = {$examFieldId}, course_id = {$courseId}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return redirect()->route('admin.calendars.index')->with('success', 'Lịch đã bị xóa.');
    }

    public function inforCourse(String $type, String $id, Request $request)
    {
        if ($type == 'exam') {
            $startTime = $request->query('date');
            $endTime = $request->query('time');
        } else {
            $startTime = $request->query('date_start');
            $endTime = $request->query('date_end');
        }

        if (!$startTime || $startTime === 'null' || strtolower($startTime) === 'null' || !$endTime || $endTime === 'null' || strtolower($endTime) === 'null') {
            if ($type == 'exam') {
                $examType = $request->query('examType');
                $course = Course::with(['examFields', 'students:id,name,student_code'])->findOrFail($id);
                if ($request->has('examType') && $request->query('examType') !== null && $request->query('examType') !== '' && $request->query('examType') !== 'undefined' && $examType != 1 && $examType != 2) {
                    $examTypeSearch = $examType == 6 ? 0 : ($examType == 5 ? 1 : null);
                    $filtered = $course->examFields->where('is_practical', (int) $examTypeSearch)->values();
                    $course->setRelation('examFields', $filtered);
                }
                $availableStudents = $course->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'student_code' => $student->student_code,
                    ];
                })->toArray();
                $course->examFields->each->makeHidden('pivot');
                return response()->json([
                    'course' => $course,
                    'available_students' => $availableStudents,
                    'option_message' => 'Vui lòng nhập đầy đủ thời gian bắt đầu và kết thúc.'
                ], 422);
            }
            if ($type == 'study') {
                $learnType = $request->query('learnType');
                $course = Course::with(['learningFields', 'students:id,name,student_code'])->findOrFail($id);
                if ($request->has('learnType') && $request->query('learnType') !== null && $request->query('learnType') !== '' && $request->query('learnType') !== 'undefined') {
                    $filtered = $course->learningFields->where('is_practical', (int) $learnType)->values();
                    $course->setRelation('learningFields', $filtered);
                }
                $availableStudents = $course->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'student_code' => $student->student_code,
                    ];
                })->toArray();
                $course->learningFields->each->makeHidden('pivot');
                return response()->json([
                    'course' => $course,
                    'available_students' => $availableStudents,
                    'option_message' => 'Vui lòng nhập đầy đủ thời gian bắt đầu và kết thúc.'
                ], 422);
            }
        }

        if($type == 'exam'){
            $course = Course::with(['examFields'])->findOrFail($id);
            $examType = $request->query('examType');

            if ($request->has('examType') && $request->query('examType') !== null && $request->query('examType') !== '' && $request->query('examType') !== 'undefined' && $examType != 1 && $examType != 2) {
                $examTypeSearch = $examType == 6 ? 0 : ($examType == 5 ? 1 : null);
                $filtered = $course->examFields->where('is_practical', (int) $examTypeSearch)->values();
                $course->setRelation('examFields', $filtered);
            }
            $course->examFields->each->makeHidden('pivot');

            $availableStudents = $course->students()
            ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->where('date', $startTime)
                      ->where(function ($q) use ($endTime) {
                          // Nếu chọn buổi Sáng (1) hoặc Chiều (2), kiểm tra lịch trùng buổi đó hoặc Cả ngày (3)
                          if ($endTime == 1 || $endTime == 2) {
                              $q->where('time', $endTime)
                                ->orWhere('time', 3);
                          }
                          // Nếu chọn Cả ngày (3), kiểm tra lịch ở bất kỳ buổi nào (1, 2, 3)
                          elseif ($endTime == 3) {
                              $q->whereIn('time', [1, 2, 3]);
                          }
                      });
            })->get(['students.id', 'students.name', 'students.student_code']);

            return response()->json([
                'course' => $course,
                'available_students' => $availableStudents,
            ]);
        }else if ($type == 'study') {
            $learnType = $request->query('learnType');
            $course = Course::with('learningFields')->findOrFail($id);
            if ($request->has('learnType') && $request->query('learnType') !== null && $request->query('learnType') !== '' && $request->query('learnType') !== 'undefined') {
                $filtered = $course->learningFields->where('is_practical', (int) $learnType)->values();
                $course->setRelation('learningFields', $filtered);
            }
            $course->learningFields->each->makeHidden('pivot');

            $availableStudents = $course->students()
            ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('date_start', [$startTime, $endTime])
                    ->orWhereBetween('date_end', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('date_start', '<=', $startTime)
                            ->where('date_end', '>=', $endTime);
                    });
                });
            })->get(['students.id', 'students.name', 'students.student_code']);

            return response()->json([
                'course' => $course,
                'available_students' => $availableStudents,
            ]);
        } else if ($type == 'meeting') {
            $startTime = Carbon::parse($startTime);
            $endTime = Carbon::parse($endTime);
            $availableStudents = Student::whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('date_start', [$startTime, $endTime])
                    ->orWhereBetween('date_end', [$startTime, $endTime])
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('date_start', '<=', $startTime)
                            ->where('date_end', '>=', $endTime);
                    });
                });
            })->get(['students.id', 'students.name', 'students.student_code']);
            return response()->json([
                'available_students' => $availableStudents,
            ]);
        }
    }

    public function getCalendarStatusConfig(string $type)
    {
        $config = [
            'study' => [
                'transitions' => [
                    1 => [1, 2, 4],
                    2 => [2],
                    3 => [],
                    4 => [],
                ],
                'labels' => [
                    1 => 'Đang chờ',
                    2 => 'Đang diễn ra',
                    3 => 'Hoàn Thành',
                    4 => 'Đã huỷ',
                    10 => 'Đã xác nhận',
                ],
            ],
            'exam' => [
                'transitions' => [
                    1 => [1, 2, 4],
                    2 => [2],
                    3 => [3],
                    4 => [],
                ],
                'labels' => [
                    1 => 'Đang chờ',
                    2 => 'Đang diễn ra',
                    3 => 'Hoàn Thành',
                    4 => 'Thi lại',
                    10 => 'Đã xác nhận',
                ],
            ],
            'work' => [
                'transitions' => [
                    1 => [1, 2, 4],
                    2 => [2],
                    3 => [],
                    4 => [],
                ],
                'labels' => [
                    1 => 'Chưa bắt đầu',
                    2 => 'Đang tiến hành',
                    3 => 'Hoàn Thành',
                    4 => 'Hoãn lại',
                ],
            ],
            'meeting' => [
                'transitions' => [
                    1 => [1, 2, 4],
                    2 => [2],
                    3 => [],
                    4 => [],
                ],
                'labels' => [
                    1 => 'Đã lên lịch',
                    2 => 'Đang diễn ra',
                    3 => 'Hoàn Thành',
                    4 => 'Đã hủy',
                ],
            ],
            'call' => [
                'transitions' => [
                    1 => [1, 2, 3],
                    2 => [],
                    3 => [],
                ],
                'labels' => [
                    1 => 'Đã lên kế hoạch',
                    2 => 'Đã thực hiện',
                    3 => 'Không thực hiện',
                ],
            ],
        ];

        if (!array_key_exists($type, $config)) {
            return ['error' => 'Invalid calendar type'];
        }

        return $config[$type] ?? [
            'transitions' => [],
            'labels' => [],
        ];
    }

    public function updateStatus(Request $request, Calendar $calendar)
    {
        $newStatus = $request->input('status');
        // Kiểm tra trạng thái hợp lệ
        $statusConfig = $this->getCalendarStatusConfig($calendar->type);
        if (!in_array($newStatus, $statusConfig['transitions'][$calendar->status] ?? [])) {
            return back()->with('error', 'Không thể chuyển trạng thái này!');
        }

        // Cập nhật trạng thái
        $calendar->status = $newStatus;
        $calendar->save();

        // Redirect trở lại với thông báo thành công
        return back()->with('success', 'Trạng thái đã được cập nhật!');
    }

    public function approveSchedule($id)
    {
        $calendar = Calendar::find($id);

        if (!$calendar) {
            return redirect()->back()->with('error', 'Lịch không tồn tại.');
        }
        if ($calendar->status == 3) {
            $calendar->status = 10;
            $calendar->save();
            return redirect()->back()->with('success', 'Bạn đã duyệt thành công kết quả cho lịch ' . $calendar->name . ' vào ngày ' . \Carbon\Carbon::parse($calendar->date_start)->format('d-m-Y'));
        }
        return redirect()->back()->with('error', 'Lịch này không thể duyệt ngay.');
    }
}
