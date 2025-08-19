<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamSet;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('quizSets.quizzes.quizOptions', 'rankings')->get();

        $data = [];

        foreach ($lessons as $lesson) {
            $lessonData = [
                'lesson_id' => $lesson->id,
                'lesson_name' => $lesson->title,
                'ranks' => $lesson->rankings->map(fn($rank) => [
                    'rank_id' => $rank->id,
                    'rank_name' => $rank->name,
                    'description' => $rank->description
                ])->toArray(),
                'quiz_sets' => [],
            ];

            $mandatoryQuizzes = [];

            foreach ($lesson->quizSets as $quizSet) {
                // Tách câu hỏi có `mandatory = 1`
                $mandatory = $quizSet->quizzes->where('mandatory', 1);

                // các câu hỏi bình thường gộp chung với câu hỏi điểm liệt để hiển thị ở các bộ câu hỏi
                $normalQuizzes = $quizSet->quizzes;

                // Gom các câu hỏi `mandatory = 1` vào danh sách chung của lesson
                if ($mandatory->isNotEmpty()) {
                    $mandatoryQuizzes = array_merge($mandatoryQuizzes, $mandatory->toArray());
                }

                // Thêm quizSet với câu hỏi bình thường
                $lessonData['quiz_sets'][] = [
                    'quiz_set_id' => $quizSet->id,
                    'quiz_set_name' => $quizSet->name . ": " . $quizSet->description,
                    'quizzes' => $normalQuizzes->map(fn($quiz) => [
                        'quiz_id' => $quiz->id,
                        'name' => $quiz->name,
                        'question' => $quiz->question,
                        'image' => !empty($quiz->image) ? "https://cdn.dtlx.app/question/" . $quiz->image : null,
                        'explanation' => $quiz->explanation,
                        'mandatory' => $quiz->mandatory,
                        'wrong' => $quiz->wrong,
                        'tip' => $quiz->tip,
                        'tip_image' => !empty($quiz->tip_image) ? "https://cdn.dtlx.app/question/" . $quiz->tip_image : null,
                        'options' => $quiz->quizOptions->map(fn($option) => [
                            'option_id' => $option->id,
                            'option_text' => $option->option_text,
                            'is_correct' => $option->is_correct
                        ])->values()->toArray()
                    ])->values()->toArray()
                ];
            }

            // Nếu có câu hỏi `mandatory = 1`, tạo thêm 1 quizSet mới cho nó
            if (!empty($mandatoryQuizzes)) {
                $newQuizSetId = $lesson->id . '_' . (count($lesson->quizSets) + 1);
                $lessonData['quiz_sets'][] = [
                    'quiz_set_id' => $newQuizSetId,
                    'quiz_set_name' => "Chương " . (count($lesson->quizSets) + 1) . ": - Câu hỏi bắt buộc",
                    'quizzes' => array_map(fn($quiz) => [
                        'quiz_id' => $quiz['id'],
                        'name' => $quiz['name'],
                        'question' => $quiz['question'],
                        'image' => !empty($quiz['image']) ? "https://cdn.dtlx.app/question/" . $quiz['image'] : null,
                        'explanation' => $quiz['explanation'],
                        'mandatory' => $quiz['mandatory'],
                        'tip' => $quiz['tip'],
                        'tip_image' => !empty($quiz['tip_image']) ? "https://cdn.dtlx.app/question/" . $quiz['tip_image'] : null,
                        'options' => array_map(fn($option) => [
                            'option_id' => $option['id'],
                            'option_text' => $option['option_text'],
                            'is_correct' => $option['is_correct']
                        ], $quiz['quiz_options'] ?? [])
                    ], $mandatoryQuizzes)
                ];
            }

            $data[] = $lessonData;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function examSets()
    {
        // Lấy tất cả exam_sets với quizzes liên quan (eager load mối quan hệ nhiều-nhiều)
        $examSets = ExamSet::with('quizzes')->get();

        // Dữ liệu cần trả về
        $data = [];

        foreach ($examSets as $examSet) {
            $examSetData = [
                'exam_set_id' => $examSet->id,
                'license_level' => $examSet->license_level,
                'required_point' => count($examSet->quizzes),
                'time_do' => $examSet->time_do,
                'exam_set_name' => $examSet->name,
                'type' => $examSet->type,
                'quizzes' => $examSet->quizzes->map(fn($quiz) => [
                    'quiz_id' => $quiz->id,
                ])->toArray()
            ];

            $data[] = $examSetData;
        }

        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function newMotobike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson' => 'required|integer',
            'content' => 'required|array',
            'content.*.name' => 'required|string|max:255',
            'content.*.description' => 'required|string',
            'content.*.quiz_name' => 'required|array',
            'content.*.quiz_name.*' => 'integer',
            'mandatory' => 'required|array',
            'mandatory.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $jsonData = $request->all();
        $lessonIdCar = $jsonData['lesson_car'];
        $lessonId = $jsonData['lesson'];
        $lesson = Lesson::with('quizSets.quizzes.quizOptions')->find($lessonIdCar);
        
        if (!$lesson) {
            return response()->json(['error' => 'Không tìm thấy bộ câu hỏi'], 404);
        }

        try {
            DB::beginTransaction();
            $newName = 0;
            foreach ($jsonData['content'] as $chapter) {
                $quizSet = QuizSet::create([
                    'name' => $chapter['name'],
                    'description' => $chapter['description'],
                    'lesson_id' => $lessonId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $existingQuizzes = $lesson->quizSets->flatMap->quizzes->whereIn('name', $chapter['quiz_name']);

                foreach ($existingQuizzes as $existingQuiz) {
                    $newName += 1;
                    $newQuiz = Quiz::create([
                        'quiz_set_id' => $quizSet->id,
                        'question' => $existingQuiz->question,
                        'name' => $newName,
                        'image' => $existingQuiz->image,
                        'explanation' => $existingQuiz->explanation,
                        'is_mandatory' => in_array($existingQuiz->name, $jsonData['mandatory']) ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($existingQuiz->quizOptions as $option) {
                        QuizOption::create([
                            'quiz_id' => $newQuiz->id,
                            'option_text' => $option->option_text,
                            'is_correct' => $option->is_correct,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Đã ghi thành công dữ liệu cho bộ câu hỏi xe máy'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }
}
