<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamScheduleRequest;
use App\Http\Requests\UpdateExamScheduleRequest;
use App\Models\Course;
use App\Models\ExamField;
use App\Models\ExamSchedule;
use App\Models\Ranking;
use App\Models\Stadium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\ExamScheduleService;

class ExamScheduleController extends Controller
{

    public function __construct(protected ExamScheduleService $examScheduleService) {}
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $startDate = request('start_date');
    $endDate = request('end_date');
    $stadiumId = request('stadium_id');
    $examSchedules = ExamSchedule::with(['rankings', 'examFields', 'stadium'])
        ->when($stadiumId, fn($q) => $q->where('stadium_id', $stadiumId))
        ->when($startDate || $endDate, function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->orWhere(function ($subQ) use ($startDate) {
                        $subQ->where('start_time', '<=', $startDate)
                             ->where('end_time', '>=', $startDate);
                    });
                }
                if ($endDate) {
                    $q->orWhere(function ($subQ) use ($endDate) {
                        $subQ->where('start_time', '<=', $endDate)
                             ->where('end_time', '>=', $endDate);
                    });
                }
            });
        })
        ->latest()
        ->paginate(30);
    $rankings = Ranking::all();
    $examFields = ExamField::all();
    $stadiums = Stadium::all();

    return view('admin.exam_schedules.index', compact('examSchedules', 'rankings', 'examFields', 'stadiums'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rankings = Ranking::all();
        $examFields = ExamField::all();
        $stadiums = Stadium::all();
        return view('admin.exam_schedules.create', compact('rankings', 'examFields', 'stadiums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamScheduleRequest $request)
    {
        try {
            DB::beginTransaction();
    
            $data = $request->validated();
    
            $schedule = ExamSchedule::create([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'stadium_id' => $data['stadium_id'],
                'description' => $data['description'],
            ]);
    
            $schedule->rankings()->sync($data['ranking_ids']);
            $schedule->examFields()->sync($data['exam_field_ids']);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Tạo kế hoạch thi thành công!'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Lỗi cập nhật kế hoạch thi: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Đã xảy ra lỗi khi tạo kế hoạch thi.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamSchedule $examSchedule)
    {
        $rankings = Ranking::all();
        $examFields = ExamField::all();
        $stadiums = Stadium::all();
        $examSchedule->load(['rankings', 'examFields']);

        return view('admin.exam_schedules.edit', compact('examSchedule', 'rankings', 'examFields', 'stadiums'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamScheduleRequest $request, ExamSchedule $examSchedule)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $examSchedule->update([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'stadium_id' => $data['stadium_id'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'],
            ]);

            $examSchedule->rankings()->sync($data['ranking_ids']);
            $examSchedule->examFields()->sync($data['exam_field_ids']);

            DB::commit();

            return redirect()->route('exam-schedules.index')->with('success', 'Cập nhật kế hoạch thi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lỗi cập nhật kế hoạch thi: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật kế hoạch thi. Vui lòng thử lại.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->rankings()->detach();
        $examSchedule->examFields()->detach();
        $examSchedule->delete();

        return redirect()->route('exam-schedules.index')->with('success', 'Đã xóa kế hoạch thi!');
    }

    public function getAvailableExamSchedules_Old(Request $request)
    {
        // Lấy ranking_id của khóa học đã chọn
        $course = Course::findOrFail($request['course_id']);
        $rankingId = $course->ranking_id;
        $date = $request['date'];
        $time = $request['time'];
        $examFieldIds = $request->input('exam_field_ids', []); // Lấy mảng exam_field_ids

        $timeRanges = [
            1 => ['start' => '07:00:00', 'end' => '11:30:00'],
            2 => ['start' => '13:00:00', 'end' => '17:00:00'],
            3 => ['start' => '07:00:00', 'end' => '17:00:00'],
        ];

        if (!isset($timeRanges[$time])) {
            return response()->json(['error' => 'Khoảng thời gian không hợp lệ'], 400);
        }

        $startTime = Carbon::parse("{$date} {$timeRanges[$time]['start']}")->format('Y-m-d H:i:s');
        $endTime = Carbon::parse("{$date} {$timeRanges[$time]['end']}")->format('Y-m-d H:i:s');

        // Lấy các kế hoạch thi phù hợp với ranking và có các môn thi đó
        $examSchedules = ExamSchedule::with(['stadium', 'examFields'])
            ->whereHas('rankings', function ($q) use ($rankingId) {
                $q->where('rankings.id', $rankingId);
            })
            ->whereHas('examFields', function ($q) use ($examFieldIds) {
                $q->whereIn('exam_fields.id', $examFieldIds)
                  ->groupBy('exam_schedule_id')
                  ->havingRaw('COUNT(DISTINCT exam_fields.id) = ?', [count($examFieldIds)]);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<=', $startTime)
                    ->where('end_time', '>=', $endTime);
            })
            ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
                $query->whereBetween('date_start', [$startTime, $endTime])
                    ->orWhereBetween('date_end', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('date_start', '<=', $startTime)
                            ->where('date_end', '>=', $endTime);
                    });
            })
            ->get();

        return response()->json([
            'exam_schedules' => $examSchedules,
        ]);
    }

    public function getAvailableExamSchedules(Request $request)
    {
        try {
            $courseId = $request->input('course_id');
            $date = $request->input('date');
            $time = $request->input('time');
            $examFieldIds = $request->input('exam_field_ids', []);

            $examSchedules = $this->examScheduleService->findAvailableExamSchedules($courseId, $date, $time, $examFieldIds);

            return response()->json([
                'exam_schedules' => $examSchedules,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi không xác định'], 500);
        }
    }
}
