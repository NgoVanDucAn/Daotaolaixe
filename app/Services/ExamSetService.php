<?php

namespace App\Services;

use App\Models\ExamSet;
use App\Models\Quiz;
use Exception;

class ExamSetService
{

    public function index()
    {
        $examSets = ExamSet::with(['quizzes.quizOptions', 'ranking'])->get()->groupBy('license_level')
            ->map(function ($examSetsByLevel) {
                $licenseLevelName = optional($examSetsByLevel->first()->ranking)->name;
                return [
                    'license_level_name' => $licenseLevelName,
                    'exam_sets' => $examSetsByLevel->groupBy('type')->map(function ($examSetsByType) {
                        return $examSetsByType->map(function ($examSet) {
                            return [
                                'exam_set_id' => $examSet->id,
                                'exam_set_name' => $examSet->name,
                                'quiz_count' => $examSet->quizzes->count(),
                                'license_level_name' => $examSet->ranking ? $examSet->ranking->name : null,
                                'quizzes' => $examSet->quizzes->map(function ($quiz) {
                                    return [
                                        'quiz_id' => $quiz->id,
                                        'question' => $quiz->question,
                                        'options' => $quiz->quizOptions->map(function ($option) {
                                            return [
                                                'option_id' => $option->id,
                                                'option_text' => $option->option_text,
                                                'is_correct' => $option->is_correct
                                            ];
                                        })
                                    ];
                                })
                            ];
                        });
                    })
                ];
            });

        return $examSets;
    }

    public function create(array $data)
    {
        try {
            // Lưu bộ đề
            $examSet = ExamSet::create([
                'name' => $data['name'],
                'time_do' => $data['time_do'] ?? 0,
                'license_level' => $data['license_level'],
                'type' => $data['type'],
                'description' => $data['description'],
                'lesson_id' => $data['lessonSelect'],
            ]);

            // Lưu các câu hỏi quiz vào bảng pivot giữa exam_set và quiz
            $quizIds = [];
            if (!empty($data['quiz_ids'])) {
                if (is_array($data['quiz_ids'])) {
                    $quizIds = $data['quiz_ids'];
                } else {
                    $quizIds = array_filter(explode(',', $data['quiz_ids']));
                }
            }
            if (!empty($quizIds)) {
                foreach ($quizIds as $quizId) {
                    $quiz = Quiz::find($quizId);
                    if ($quiz) {
                        $examSet->quizzes()->attach($quiz->id);
                    } else {
                        throw new Exception("Quiz ID $quizId không hợp lệ.");
                    }
                }
            }

            return $examSet;
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            throw new Exception("Lỗi khi tạo bộ đề: " . $e->getMessage());
        }
    }


    public function update(ExamSet $examSet, array $data)
    {
        $examSet->update([
            'name' => $data['name'],
            'license_level' => $data['license_level'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
        ]);
        return $examSet;
    }

    public function delete(ExamSet $examSet)
    {
        $examSet->delete();
    }
}