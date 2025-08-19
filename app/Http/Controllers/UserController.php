<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\LearningField;
use App\Models\Ranking;
use App\Models\Student;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as ModelsRole;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $query = User::query();

        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', '!=', 'student');

            if ($request->filled('role')) {
                $q->where('name', $request->role);
            }
        });

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        //IDE ngáo báo vậy thôi dùng var cũng không hết
        $users = $query->latest()->paginate(30)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->back()->with('success', 'Trạng thái tài khoản đã được cập nhật.');
    }

    public function indexForSale(Request $request)
    {
        $saleId = $request->input('sale_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $saleAlls = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesperson');
        })->get();
        
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'salesperson');
        })
        ->when($saleId, fn($q) => $q->where('id', $saleId))
        ->when($startDate || $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereHas('supportedStudents.courses', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->where('contract_date', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('contract_date', '<=', $endDate);
                }
            });
        })
        ->with('supportedStudents.courses')
        ->withCount([
            'supportedStudents as total_students',
            'supportedStudents as successful_students' => function ($query) {
                $query->where('is_student', 1);
            },
            'supportedStudents as in_lead' => function ($query) {
                $query->where('is_lead', 1);
            }
        ])
        ->addSelect([
            'total_courses_through_students' => function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('course_students')
                    ->join('students', 'course_students.student_id', '=', 'students.id')
                    ->whereColumn('students.sale_support', 'users.id');
            }
        ])
        ->latest()
        ->paginate(30);

        $countLead = Student::query()
        ->where('is_lead', 1)
        ->when($saleId, fn($q) => $q->where('sale_support', $saleId))
        ->when($startDate || $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereHas('courses', function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->where('contract_date', '>=', $startDate);
                }
                if ($endDate) {
                    $q->where('contract_date', '<=', $endDate);
                }
            });
        })
        ->count();

        $totalContractSigned = DB::table('course_students')
        ->join('students', 'course_students.student_id', '=', 'students.id')
        ->when($saleId, function ($query) use ($saleId) {
            $query->where('students.sale_support', $saleId);
        })
        ->when($startDate, function ($query) use ($startDate) {
            $query->where('course_students.contract_date', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->where('course_students.contract_date', '<=', $endDate);
        })
        ->where('students.is_student', 1)
        ->count();

        return view('admin.users.indexSale', compact('users', 'countLead', 'totalContractSigned', 'saleAlls'));
    }

    public function indexForInstructor(Request $request)
    {
        $teachers = User::Role('instructor')->with('roles')->get();
        $teacherId = $request->teacher_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $query  = User::whereHas('roles', function ($query) {
            $query->where('name', 'instructor');
        })
        ->when($teacherId, function ($query) use ($teacherId) {
            $query->where('id', $teacherId);
        })
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        });
        $countUsers = $query->count();
        $users= $query->with('calendars.learningField', 'vehicle', 'rankings')
        ->latest()->paginate(30);
        $learningFeilds = LearningField::where('price', '!=', 0)->get();
        foreach ($users as $user) {
            $totalHour = 0;
            $totalMoney = 0;
            $confirmedHour = 0;
            $confirmedMoney = 0;
            $unconfirmedHour = 0;
            $unconfirmedMoney = 0;
            $teachingHoursByField = [];
            foreach ($user->calendars as $calendar) {
                if ($calendar->type == 'study') {
                    $hour = $calendar->pivot->hours;
                    $price = $calendar->pivot->price_at_result;
                    $fieldId = optional($calendar->learningField)->id;

                    if ($fieldId) {
                        if (!isset($teachingHoursByField[$fieldId])) {
                            $teachingHoursByField[$fieldId] = 0;
                        }
                        $teachingHoursByField[$fieldId] += $hour;
                    }

                    if ($price != 0) {
                        if ($calendar->status == 10) {
                            $confirmedHour += $hour;
                            $confirmedMoney += $hour * $price;
                        } else {
                            $unconfirmedHour += $hour;
                            $unconfirmedMoney += $hour * $price;
                        }
                        $totalHour += $hour;
                        $totalMoney += $hour * $price;
                    }
                }
            }
            $user->total_hour = $totalHour;
            $user->total_money = $totalMoney;
            $user->confirmed_hour = $confirmedHour;
            $user->confirmed_money = $confirmedMoney;
            $user->unconfirmed_hour = $unconfirmedHour;
            $user->unconfirmed_money = $unconfirmedMoney;
            $user->teaching_hours_by_field = $teachingHoursByField;
            $user->selected_ranking_names = $user->rankings->pluck('name')->toArray();
        }
        
        // dd($user);
        return view('admin.users.indexInstructor', compact('teachers', 'users', 'learningFeilds', 'countUsers'));
    }

    public function create()
    {
        $roles = ModelsRole::where('name', '!=', 'student')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function createSale()
    {
        return view('admin.users.create_sale');
    }

    public function createInstructor()
    {
        $rankings = Ranking::all();
        $vehicles = Vehicle::select('id', 'license_plate', 'model')->get();
        return view('admin.users.create_instructor', compact('rankings', 'vehicles'));
    }

    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->gender = $validated['gender'];
            $user->dob = $validated['dob'];
            $user->identity_card = $validated['identity_card'];
            $user->address = $validated['address'];
            $user->password = 123456;
            if (!empty($validated['image']) && $validated['image'] instanceof \Illuminate\Http\UploadedFile) {
                $timestamp = now()->timestamp;
                $extension = $validated['image']->getClientOriginalExtension();
                //đổi tên file ảnh kết hợp timestamp và uuid để tránh trùng cho tên ảnh
                $fileName = $timestamp . '_' . Str::uuid() . '.' . $extension;
                
                $imagePath = $validated['image']->storeAs('users', $fileName, 'public');
                $user->image = $imagePath;
            }
            if (trim($validated['role']) == 'instructor' && isset($validated['vehicle_id'])) {
                $user->vehicle_id = $validated['vehicle_id'];
            }
            $user->save();
            if (trim($validated['role']) == 'instructor' && isset($validated['ranking_ids']) && is_array($validated['ranking_ids'])) {
                $user->rankings()->sync($validated['ranking_ids']);
            }
            $user->assignRole($validated['role']);

            $previousUrl = url()->previous();
            $path = parse_url($previousUrl, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            $module = $segments[0];
            if ($module) {
                if ($module == 'sale') {
                    return redirect()->route('sale.index')->with('success', 'Thêm mới thành công nhân viên sale');
                } else if ($module == 'instructor') {
                    return redirect()->route('instructor.index')->with('success', 'Thêm mới thành công giáo viên');
                } else {
                    return redirect()->route('users.index')->with('success', 'Thêm mới thành công người dùng quản trị');
                }
            }

            return redirect()->route('users.index')->with('success', 'Thêm mới thành công người dùng quản trị');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $users = User::role('salesperson')->get();
        $roles = $user->getRoleNames();
        return view('admin.users.show', compact('user', 'roles'));
    }

    public function showSale(User $user)
    {
        $users = User::role('salesperson')->get();
        $roles = $user->getRoleNames();
        $leads = Student::where('is_lead', 1)->get();
        return view('admin.users.show', compact('user', 'roles', 'users', 'leads'));
    }

    public function showInstructor(User $user)
    {
        $roles = $user->getRoleNames();
        return view('admin.users.show', compact('user', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = ModelsRole::where('name', '!=', 'student')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function editSale(User $user)
    {
        $roles = $user->getRoleNames();
        $users = User::role('salesperson')->get();
        $leads = Student::where('is_lead', 1)->get();
        return view('admin.users.edit_sale', compact('user', 'roles', 'users', 'leads'));
    }

    public function editInstructor(User $user)
    {
        $rankings = Ranking::all();
        $vehicles = Vehicle::select('id', 'license_plate', 'model')->get();
        $selectedRankingIds = $user->rankings->pluck('id')->toArray();
        return view('admin.users.edit_instructor', compact('user', 'rankings', 'vehicles', 'selectedRankingIds'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        $oldImagePath = $user->image;
        $newImagePath = null;

        try {
            DB::beginTransaction();
            $imagePath = $oldImagePath;
            if ($request->hasFile('image')) {
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = now()->timestamp . '_' . uniqid() . '.' . $extension;

                try {
                    $newImagePath = $request->file('image')->storeAs('users', $fileName, 'public');
                } catch (\Exception $e) {
                    throw new \Exception('Upload ảnh thất bại: ' . $e->getMessage());
                }

                $imagePath = $newImagePath;
            }
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'identity_card' => $request->identity_card,
                'address' => $request->address,
                'image' => $imagePath,
            ];
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            if ($request->filled('license_number')) {
                $data['license_number'] = $request->license_number;
            }
            
            if ($request->filled('vehicle_id')) {
                $data['vehicle_id'] = $request->vehicle_id;
            }
            $user->update($data);
            if (isset($request->ranking_ids) && is_array($request->ranking_ids)) {
                $user->rankings()->sync($request->ranking_ids);
            }
            DB::commit();
            
            if ($newImagePath && $oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $previousUrl = url()->previous();
            $path = parse_url($previousUrl, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            $module = $segments[0];
            if ($module) {
                if ($module == 'sale') {
                    return redirect()->route('sale.index')->with('success', 'cập nhật thành công thông tin nhân viên sale');
                } else if ($module == 'instructor') {
                    return redirect()->route('instructor.index')->with('success', 'cập nhật thành công thông tin giáo viên');
                } else {
                    return redirect()->route('users.index')->with('success', 'cập nhật thành công thông tin người dùng quản trị');
                }
            }

            return redirect()->route('users.index')->with('success', 'cập nhật thành công thông tin người dùng quản trị');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($newImagePath) && Storage::disk('public')->exists($newImagePath)) {
                Storage::disk('public')->delete($newImagePath);
            }

            \Log::error('Lỗi khi cập nhật học viên: ' . $e->getMessage());

            return redirect()->back()->withErrors([
                'update_error' => 'Đã xảy ra lỗi trong quá trình cập nhật. Vui lòng thử lại.',
            ]);
        }
    }

    public function destroy(User $user)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }
        $user->delete();
        return redirect()->back()->with('success', 'Xóa tài khoản người dùng quản trị thành công. Bạn có thể khôi phục lại tài khoản người dùng quản trị tại thùng rác.');
    }

    public function forceDelete(User $user)
    {
        $user->forceDelete();
        return redirect()->route('users.index')->with('success', 'Xóa tài khoản người dùng quản trị thành công.');
    }

    public function restore(User $user)
    {
        $user->restore();
        return redirect()->route('users.index')->with('success', 'Khôi phục tài khoản người dùng quản trị thành công.');
    }

    public function getAvailableUsers(Request $request)
    {
        $type = $request['type'];
        if ($type == 'exam') {
            $date = $request['date'];
            $time = (int) $request['time'];

            if (!$date || !$time) {
                return response()->json(['error' => 'Vui lòng cung cấp ngày và giờ'], 400);
            }
            $timeRanges = [
                1 => ['start' => '07:00:00', 'end' => '11:30:00'],
                2 => ['start' => '13:00:00', 'end' => '17:00:00'],
                3 => ['start' => '07:00:00', 'end' => '17:00:00'],
            ];

            if (!isset($timeRanges[$time])) {
                return response()->json(['error' => 'Khoảng thời gian không hợp lệ'], 400);
            }
            try {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                    $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                }
                $startTime = Carbon::parse("{$date} {$timeRanges[$time]['start']}")->format('Y-m-d H:i:s');
                $endTime = Carbon::parse("{$date} {$timeRanges[$time]['end']}")->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                throw new \InvalidArgumentException('Định dạng ngày không hợp lệ: ' . $e->getMessage());
            }

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'instructor');
            })
            ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('date_start', [$startTime, $endTime])
                    ->orWhereBetween('date_end', [$startTime, $endTime])
                    ->orWhere(function($q2) use ($startTime, $endTime) {
                        $q2->where('date_start', '<=', $startTime)
                            ->where('date_end', '>=', $endTime);
                    });
                });
            })
            ->get(['id', 'name']);

            return response()->json([
                'teachers' => $users
            ]);
        } else {
            $startTime = $request['start_time'];
            $endTime = $request['end_time'];

            $users = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'student');
            })
            ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->whereBetween('date_start', [$startTime, $endTime])
                    ->orWhereBetween('date_end', [$startTime, $endTime])
                    ->orWhere(function($q2) use ($startTime, $endTime) {
                        $q2->where('date_start', '<=', $startTime)
                            ->where('date_end', '>=', $endTime);
                    });
                });
            })
            ->get(['id', 'name']);
        
            $teachers = $users->filter(function($user) {
                return $user->hasRole('instructor');
            })->values();
        
            $sales = $users->filter(function($user) {
                return $user->hasRole('salesperson');
            })->values();

            return response()->json([
                'users' => $users,
                'teachers' => $teachers,
                'sales' => $sales,
            ]);
        }
    }
}
