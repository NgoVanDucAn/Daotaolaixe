<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Fee;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class FeeTransferService
{
    /**
     * Handle fee transfer logic
     *
     * @param Student $student
     * @param Course $course
     * @param int $courseStudentId
     * @param int|null $newCourseStudentId
     * @param float $tuitionFee
     * @return void
     * @throws \RuntimeException
     */
    public function handleFeeTransfer(Student $student, Course $course, $courseStudentId, $newCourseStudentId, $tuitionFee)
    {
        // Nếu ai đó sau này dùng function này thì vui lòng sử dụng transaction ở function gọi nó để sử dụng để đảm bảo bảo toàn dữ liệu nhé
        if (DB::transactionLevel() === 0) {
            throw new \RuntimeException('handleFeeTransfer phải được gọi bên trong một transaction.');
        }

        $paidFee = floatval($student->paid_fee);
        $tuitionFee = floatval($tuitionFee);

        $oldFee = Fee::where('student_id', $student->id)
            ->whereHas('courseStudent', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->first();

        if ($paidFee > 0 && $oldFee) {
            if ($student->ranking_id == $course->ranking_id) {
                $oldFee->update([
                    'course_student_id' => $courseStudentId,
                ]);
                $student->update([
                    'fee_ranking' => 0,
                    'ranking_id' => null,
                    'paid_fee' => 0,
                    'date_of_profile_set' => null,
                ]);
            } else {
                if ($paidFee > $tuitionFee) {
                    $remain = max(0, $paidFee - $tuitionFee);
                    $student->update(['paid_fee' => $remain]);
                    $oldFee->update([
                        'course_student_id' => $newCourseStudentId,
                        'amount' => $remain,
                    ]);
                    Fee::create([
                        'student_id' => $student->id,
                        'course_student_id' => $courseStudentId,
                        'fee_type' => 1,
                        'collector_id' => $oldFee->collector_id ?? auth()->id(),
                        'amount' => $tuitionFee,
                        'payment_date' => now(),
                        'is_received' => true,
                    ]);
                } else {
                    $remain = 0;
                }
                $student->update([
                    'paid_fee' => $remain,
                ]);
            }
        } else {
            if ($student->ranking_id == $course->ranking_id) {
                $student->update([
                    'fee_ranking' => 0,
                    'ranking_id' => null,
                    'date_of_profile_set' => null,
                ]);
            }
        }
    }
}