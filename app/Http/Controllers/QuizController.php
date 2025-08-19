<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'quiz_set_id' => 'required|exists:quiz_sets,id',
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'is_correct' => 'required|array|min:1',
            'is_correct.*' => 'in:1',
        ]);

        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            return DB::transaction(function () use ($request, $validated) {
                // Tạo Quiz
                $quiz = Quiz::create([
                    'quiz_set_id' => $validated['quiz_set_id'],
                    'question' => $validated['question'],
                ]);

                // Tạo Quiz Options
                $options = [];
                foreach ($validated['options'] as $index => $optionText) {
                    $options[] = [
                        'quiz_id' => $quiz->id,
                        'option_text' => $optionText,
                        'is_correct' => in_array($index, array_keys(array_filter($validated['is_correct'], fn($value) => $value == '1'))) ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuizOption::insert($options);

                // Lấy quiz với options để trả về
                $quiz->load('quizOptions');
                return response()->json($quiz, 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể tạo câu hỏi',
                'errors' => ['server' => [$e->getMessage()]],
            ], 422);
        }
    }

    public function update(Request $request, Quiz $quiz)
    {
        // Validate request
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'is_correct' => 'required|array|min:1',
            'is_correct.*' => 'in:1',
        ]);

        try {
            // Bắt đầu transaction
            return DB::transaction(function () use ($request, $validated, $quiz) {
                // Cập nhật Quiz
                $quiz->update([
                    'question' => $validated['question'],
                ]);

                // Xóa các options cũ
                $quiz->quizOptions()->delete();

                // Tạo options mới
                $options = [];
                foreach ($validated['options'] as $index => $optionText) {
                    $options[] = [
                        'quiz_id' => $quiz->id,
                        'option_text' => $optionText,
                        'is_correct' => in_array($index, array_keys(array_filter($validated['is_correct'], fn($value) => $value == '1'))) ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuizOption::insert($options);

                // Lấy quiz với options để trả về
                $quiz->load('quizOptions');
                return response()->json($quiz);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể cập nhật câu hỏi',
                'errors' => ['server' => [$e->getMessage()]],
            ], 422);
        }
    }

    public function updateWrong(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'quiz_ids' => 'required|string|regex:/^[\d,]+$/',
            'wrong_value' => 'required|in:0,1',
        ]);

        try {
            if ($validated['quiz_ids'] == "777708807033077703033055503308033") {
                $lessonId = $validated['lesson_id'];
                $quizSetIds = QuizSet::where('lesson_id', $lessonId)->pluck('id');
                $updatedCount = Quiz::whereIn('quiz_set_id', $quizSetIds)->update(['wrong' => 0]);
                return response()->json([
                    'message' => 'Cập nhật thành công tất cả các câu hỏi thuộc danh sách của bài học đã chọn về thành không có câu nào dễ sai.',
                    'updated_count' => $updatedCount,
                ]);
            }
            return DB::transaction(function () use ($validated) {
                $lessonId = $validated['lesson_id'];
                $quizNames = array_filter(explode(',', $validated['quiz_ids'])); // Đây là các giá trị để so sánh với `name`
                $wrongValue = $validated['wrong_value'];

                // Lấy toàn bộ quiz_set_id thuộc bài học
                $quizSetIds = QuizSet::where('lesson_id', $lessonId)->pluck('id');

                // Tìm các quiz có name nằm trong danh sách và thuộc quiz_set_id đã lấy
                $quizzesToUpdate = Quiz::whereIn('quiz_set_id', $quizSetIds)
                    ->whereIn('name', $quizNames)
                    ->get(['id', 'name']);

                if ($quizzesToUpdate->isEmpty()) {
                    return response()->json([
                        'message' => 'Không tìm thấy quiz hợp lệ thuộc bài học này',
                        'errors' => ['quiz_ids' => ['Không có câu hỏi nào có name khớp với danh sách được gửi và thuộc bài học']],
                    ], 422);
                }

                // Cập nhật trường `wrong`
                $updatedCount = Quiz::whereIn('id', $quizzesToUpdate->pluck('id'))
                    ->update(['wrong' => $wrongValue]);

                return response()->json([
                    'message' => 'Cập nhật thành công',
                    'updated_count' => $updatedCount,
                    'quiz_ids_updated' => $quizzesToUpdate->pluck('id'),
                    'quiz_names_matched' => $quizzesToUpdate->pluck('name'),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Không thể cập nhật trường wrong',
                'errors' => ['server' => [$e->getMessage()]],
            ], 422);
        }
    }
}
