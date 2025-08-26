<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $quiz_set_id id xác định việc add bài tập vào bộ câu hỏi nào
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\QuizSet $quizSet
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Assignment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type Kiểu lịch exam: lịch học, kiểm tra, họp
 * @property string $name Tên lịch
 * @property int $status Trạng thái lịch: 0: chưa hoàn thành, 1: đang diễn ra, 2: đã hoàn thành
 * @property string $priority Mức độ ưu tiên
 * @property string|null $date_start Ngày bắt đầu
 * @property string|null $date_end Ngày kết thúc
 * @property int|null $duration Thời lượng
 * @property string|null $description Mô tả
 * @property int|null $learning_field_id
 * @property int|null $exam_field_id
 * @property string|null $exam_fee
 * @property string|null $exam_fee_deadline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $location Địa điểm
 * @property int|null $stadium_id
 * @property int|null $exam_schedule_id
 * @property int|null $vehicle_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \App\Models\ExamField|null $examField
 * @property-read \App\Models\ExamSchedule|null $examSchedule
 * @property-read \App\Models\LearningField|null $learningField
 * @property-read \App\Models\Stadium|null $stadium
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \App\Models\Vehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFeeDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereExamScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereLearningFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereStadiumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendar whereVehicleId($value)
 * @mixin \Eloquent
 */
	class Calendar extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Material> $materials
 * @property-read int|null $materials_count
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chapter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Chapter extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Course|null $course
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel query()
 * @mixin \Eloquent
 */
	class ClassModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $ranking_id
 * @property string $code Mã khóa học
 * @property int|null $shlx_course_id ID của khóa học trên hệ thống sát hạch
 * @property string|null $course_system_code Mã khóa học trên hệ thống sát hạch
 * @property int|null $curriculum_id Loại khóa học như cơ bản nâng cao
 * @property string|null $number_bc Số hồ sơ quản lý khóa học, báo cáo
 * @property \Illuminate\Support\Carbon|null $date_bci Ngày bắt đầu báo cáo khóa học
 * @property \Illuminate\Support\Carbon|null $start_date Ngày bắt đầu khóa học
 * @property \Illuminate\Support\Carbon|null $end_date Ngày kết thúc khóa học
 * @property int|null $number_students Số lượng học viên
 * @property string|null $decision_kg Lưu thông tin quyết định giấy tờ liên quan của khóa học
 * @property int|null $duration Tổng số giờ học của khóa học
 * @property string $km số km cần để hoàn thành khóa học
 * @property string $required_km số km cần để hoàn thành khóa học
 * @property int|null $tuition_fee Học phí khóa học
 * @property int $status Trạng thái khóa học (active, inactive)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Curriculum|null $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamField> $examFields
 * @property-read int|null $exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LearningField> $learningFields
 * @property-read int|null $learning_fields_count
 * @property-read \App\Models\Ranking|null $ranking
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCourseSystemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDateBci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDecisionKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereNumberBc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereNumberStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRankingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRequiredKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereShlxCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTuitionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $contract_date Ngày ký hợp đồng
 * @property string|null $contract_image
 * @property \Illuminate\Support\Carbon|null $graduation_date Ngày tốt nghiệp
 * @property int|null $teacher_id
 * @property string|null $practice_field Địa điểm thực hành
 * @property string|null $note Ghi chú thông tin đặc biệt liên quan đến khóa học và học viên
 * @property \Illuminate\Support\Carbon|null $health_check_date Ngày khám sức khỏe
 * @property int|null $sale_id
 * @property float $hours Số giờ đã học
 * @property string $km
 * @property int $status Trạng thái khóa học (active, inactive)
 * @property int|null $tuition_fee Học phí khóa học
 * @property \Illuminate\Support\Carbon|null $start_date Ngày khai giảng
 * @property \Illuminate\Support\Carbon|null $end_date Ngày bế giảng
 * @property \Illuminate\Support\Carbon|null $cabin_learning_date Ngày học cabin
 * @property int|null $exam_field_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\ExamField|null $examField
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\User|null $teacher
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCabinLearningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereContractDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereContractImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereGraduationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereHealthCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent wherePracticeField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereTuitionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStudent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CourseStudent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name Tên giáo trình
 * @property string $title Loại giáo trình: Cơ bản / Nâng cao
 * @property string|null $description Mô tả về giáo trình: là nội dung hiển thị để giới thiệu về khóa học ở client
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum query()
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curriculum whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Curriculum extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name Tên lĩnh vực thi
 * @property string|null $description Mô tả lĩnh vực thi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSchedule> $examSchedules
 * @property-read int|null $exam_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ExamField extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $start_time
 * @property string $end_time
 * @property int $stadium_id Sân thi
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamField> $examFields
 * @property-read int|null $exam_fields_count
 * @property-read string|null $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankings
 * @property-read int|null $rankings_count
 * @property-read \App\Models\Stadium $stadium
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStadiumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ExamSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name tên bộ đề
 * @property int|null $license_level
 * @property string $type kiểu bộ đề: đề thi thử, đề ôn tập, câu hỏi ôn tập,etc...
 * @property string|null $description mô tả về bộ đề
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lesson_id
 * @property-read \App\Models\Lesson|null $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \App\Models\Ranking|null $ranking
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereLicenseLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ExamSet extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $payment_date Ngày thanh toán
 * @property int $amount Số tiền đã đóng
 * @property int $student_id Tài khoản học viên
 * @property int $is_received Trạng thái thanh toán
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $course_id Khóa học người dùng tham gia
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|Fee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereIsReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Fee extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $bookmark_code
 * @property int $bookmark_type_id
 * @property string $bookmark_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LawBookmarkType $bookmarkType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereBookmarkTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmark whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LawBookmark extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $bookmark_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawBookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereBookmarkName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawBookmarkType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LawBookmarkType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $topic_name
 * @property string|null $subtitle
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereTopicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawTopic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LawTopic extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $vehicle_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawViolation> $violations
 * @property-read int|null $violations_count
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawVehicleType whereVehicleName($value)
 * @mixin \Eloquent
 */
	class LawVehicleType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $description
 * @property string $entities
 * @property string $fines
 * @property string|null $additional_penalties
 * @property string|null $remedial
 * @property string|null $other_penalties
 * @property int $type_id
 * @property int $topic_id
 * @property string|null $image
 * @property string|null $keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $violation_no
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LawBookmark> $bookmarks
 * @property-read int|null $bookmarks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LawViolation> $relatedViolations
 * @property-read int|null $related_violations_count
 * @property-read \App\Models\LawTopic $topic
 * @property-read \App\Models\LawVehicleType $vehicleType
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereAdditionalPenalties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereEntities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereFines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereOtherPenalties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereRemedial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolation whereViolationNo($value)
 * @mixin \Eloquent
 */
	class LawViolation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $violation_id
 * @property int $related_violation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereRelatedViolationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LawViolationRelation whereViolationId($value)
 * @mixin \Eloquent
 */
	class LawViolationRelation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name Tên nguồn
 * @property string|null $description Mô tả về nguồn
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadSource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LeadSource extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name tên lĩnh vực học
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField query()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LearningField extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $curriculum_id Giáo trình id
 * @property string $title Tên bài học
 * @property string|null $description Mô tả chi tiết về bài học
 * @property int $sequence Thứ tự của bài học trong giáo trình
 * @property string $visibility
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Curriculum $curriculum
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSet> $examSets
 * @property-read int|null $exam_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizSet> $quizSets
 * @property-read int|null $quiz_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankings
 * @property-read int|null $rankings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereCurriculumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lesson whereVisibility($value)
 * @mixin \Eloquent
 */
	class Lesson extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $title Tiêu đề tài liệu
 * @property string|null $type Loại tài liệu
 * @property string|null $file_path Đường dẫn tới tài liệu
 * @property string|null $url Link tới tài liệu nếu là tài liệu trực tuyến
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $chapter_id
 * @property string|null $total_time
 * @property string|null $start_time
 * @property string|null $end_time
 * @property-read \App\Models\Chapter $chapter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Simulation> $simulations
 * @property-read int|null $simulations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereChapterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereTotalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUrl($value)
 * @mixin \Eloquent
 */
	class Material extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $chapter_name
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereChapterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $quiz_set_id Bộ câu hỏi
 * @property string $question Câu hỏi
 * @property string|null $name Tên câu hỏi
 * @property string|null $image Ảnh của câu hỏi
 * @property string|null $explanation Nội dung giải thích về câu hỏi đó
 * @property int $mandatory quy định câu hỏi là câu bắt buộc đúng hay không
 * @property int $wrong câu hỏi hay bị sai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tip cách làm nhanh, nhận diện đáp án cho câu hỏi đó
 * @property string|null $tip_image hình ảnh mô tả việc nhận diện đáp án đúng cho câu hỏi đó
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuizOption> $quizOptions
 * @property-read int|null $quiz_options_count
 * @property-read \App\Models\QuizSet $quizSet
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereMandatory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereTipImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Quiz whereWrong($value)
 * @mixin \Eloquent
 */
	class Quiz extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $quiz_id ID câu hỏi
 * @property string $option_text Các lựa chọn của quiz
 * @property int $is_correct Đáp án của quiz
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Quiz $quiz
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereOptionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class QuizOption extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $lesson_id id cho biết thuộc về bài nào
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \App\Models\Lesson $lesson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quiz> $quizzes
 * @property-read int|null $quizzes_count
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class QuizSet extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name tên hạng bằng
 * @property string|null $description mô tả hạng bằng
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamSet> $examSets
 * @property-read int|null $exam_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicles
 * @property-read int|null $vehicles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Ranking extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name Tên bộ đề mô phỏng
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Material> $materials
 * @property-read int|null $materials_count
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Simulation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Simulation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $location
 * @property string|null $google_maps_url
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereGoogleMapsUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stadium whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Stadium extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $student_code Mã học viên
 * @property int|null $card_id Mã số thẻ được đính kèm khi thực hiện sát hạch
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string|null $image
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $dob Date of Birth
 * @property string|null $identity_card Identity Card Number
 * @property string|null $address
 * @property string $status User status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $description Mô tả học viên, có thể lưu các thông tin khác như có quan tâm đến các bằng khác không, mức độ quan tâm
 * @property \Illuminate\Support\Carbon|null $became_student_at
 * @property bool $is_student
 * @property int|null $sale_support người chăm sóc khách hàng
 * @property int|null $lead_source nguồn khách hàng
 * @property int|null $converted_by người xác nhận đóng phí và chuyển đổi tài khoản khách hàng thành học viên
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $trainee_id id của học viên trên hệ thống sát hạch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\User|null $convertedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $courseFees
 * @property-read int|null $course_fees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Fee> $fees
 * @property-read int|null $fees_count
 * @property-read \App\Models\LeadSource|null $leadSource
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lesson> $lessons
 * @property-read int|null $lessons_count
 * @property-read \App\Models\User|null $saleSupport
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentExamField> $studentExamFields
 * @property-read int|null $student_exam_fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentStatus> $studentStatuses
 * @property-read int|null $student_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereBecameStudentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereConvertedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereIsStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereLeadSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSaleSupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereStudentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereTraineeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property int $exam_field_id
 * @property int $attempt_number Số lần đã thi
 * @property int $status lưu lại trạng thái môn thi đó: 0 là chưa hoàn thành, 1 là hoàn thành
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\ExamField $examField
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereAttemptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereExamFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentExamField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class StudentExamField extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property int $learning_field_id
 * @property float $hours tổng số giờ học
 * @property float $km số km chạy được
 * @property float $night_hours số giờ chạy đêm
 * @property float $auto_hours số giờ chạy tự động
 * @property int $status 0: chưa hoàn thành, 1: đã hoàn thành, 2: đã bỏ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @property-read \App\Models\LearningField $learningField
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereAutoHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereLearningFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereNightHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class StudentStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $tip_type
 * @property int|null $quiz_set_id
 * @property int|null $page_id
 * @property string $content
 * @property array $question
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $label_type
 * @property-read \App\Models\Page|null $page
 * @property-read \App\Models\QuizSet|null $quizSet
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereQuizSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereTipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tip whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Tip extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property string $name
 * @property string|null $image
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $type_label
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficSign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class TrafficSign extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $image
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $dob Date of Birth
 * @property string|null $identity_card Identity Card Number
 * @property string|null $address
 * @property string $status User status
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $instructor_id id của giáo viên trên hệ thống sát hạch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $supportedStudents
 * @property-read int|null $supported_students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdentityCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $license_plate biển số
 * @property string $model
 * @property int $ranking_id
 * @property string $type loại xe: xe con, xe 7 chỗ, xe ? chỗ,...
 * @property string $color màu sắc của xe
 * @property string|null $training_license_number số giấy phép tập lái
 * @property string $manufacture_year năm sản xuất
 * @property string|null $description mô tả
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Calendar> $calendars
 * @property-read int|null $calendars_count
 * @property-read \App\Models\Ranking $ranking
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereManufactureYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereRankingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereTrainingLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Vehicle extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $type
 * @property string $time
 * @property int|null $user_id
 * @property int $amount
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $type_vi
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleExpense whereVehicleId($value)
 * @mixin \Eloquent
 */
	class VehicleExpense extends \Eloquent {}
}

