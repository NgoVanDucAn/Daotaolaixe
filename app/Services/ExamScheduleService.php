<?php

namespace App\Services;

use App\Models\Course;
use App\Models\ExamSchedule;
use Carbon\Carbon;

class ExamScheduleService
{
    public function findAvailableExamSchedules($courseId, $date, $time, $examFieldIds)
    {
        $course = Course::findOrFail($courseId);
        $rankingId = $course->ranking_id;

        $timeRanges = [
            1 => ['start' => '07:00:00', 'end' => '11:30:00'],
            2 => ['start' => '13:00:00', 'end' => '17:00:00'],
            3 => ['start' => '07:00:00', 'end' => '17:00:00'],
        ];

        if (!isset($timeRanges[$time])) {
            throw new \InvalidArgumentException('Khoảng thời gian không hợp lệ');
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
            // ->whereDoesntHave('calendars', function ($query) use ($startTime, $endTime) {
            //     $query->whereBetween('date_start', [$startTime, $endTime])
            //         ->orWhereBetween('date_end', [$startTime, $endTime])
            //         ->orWhere(function ($q) use ($startTime, $endTime) {
            //             $q->where('date_start', '<=', $startTime)
            //                 ->where('date_end', '>=', $endTime);
            //         });
            // })
            ->get();

        return $examSchedules;
    }
}