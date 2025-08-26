<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TipsController extends Controller
{
    public function getAllTips()
    {
        try {
            // Lấy tất cả mẹo với quan hệ quizSet và page
            $tips = Tip::with(['quizSet', 'page'])->get();

            // Lấy lesson_ids dựa trên từ khóa
            $keywordsCar = ['ô tô', 'oto', 'xe hơi', 'xe con', 'xe khách'];
            $keywordsBike = ['xe máy', 'mô tô', 'xe gắn máy'];

            $carLessonIds = Lesson::where(function ($query) use ($keywordsCar) {
                foreach ($keywordsCar as $word) {
                    $query->orWhere('title', 'like', "%$word%");
                }
            })->pluck('id')->toArray();

            $bikeLessonIds = Lesson::where(function ($query) use ($keywordsBike) {
                foreach ($keywordsBike as $word) {
                    $query->orWhere('title', 'like', "%$word%");
                }
            })->pluck('id')->toArray();

            // Khởi tạo tips dạng mảng
            $groupedTips = [
                'tips' => [
                    [
                        'type' => 'Ô tô',
                        'tip_type' => 1,
                        'lesson_ids' => $carLessonIds,
                        'programs' => [],
                    ],
                    [
                        'type' => 'Xe máy',
                        'tip_type' => 2,
                        'lesson_ids' => $bikeLessonIds,
                        'programs' => [],
                    ],
                    [
                        'type' => 'Mô phỏng',
                        'tip_type' => 3,
                        'programs' => [],
                    ],
                ],
            ];

            // Tạo mảng để theo dõi các chương đã xử lý
            $processedPrograms = [
                '1' => [], // quiz_set_id cho Ô tô
                '2' => [], // page_id cho Xe máy
                '3' => [], // page_id cho Mô phỏng
            ];

            foreach ($tips as $tip) {
                $tipType = $tip->tip_type;
                $programKey = $tipType == 1 || $tipType == 2 ? $tip->quiz_set_id : $tip->page_id;

                // Bỏ qua nếu không có chương hợp lệ
                if (!$programKey) {
                    continue;
                }

                // Tạo chương nếu chưa tồn tại
                if (!isset($processedPrograms[$tipType][$programKey])) {
                    if ($tipType == 3) {
                        $page = $tip->page;
                        $program = [
                            'chapter_id' => $page->id,
                            'chapter_name' => $page->chapter_name ?? 'Chưa đặt tên',
                            'title' => $page->title ?? 'Chưa có tiêu đề',
                            'tips' => [],
                        ];
                    } else {
                        $quizSet = $tip->quizSet;
                        $program = [
                            'quiz_set_id' => $quizSet->id,
                            'name' => $quizSet->name ?? 'Chưa đặt tên',
                            'description' => $quizSet->description ?? 'Chưa có mô tả',
                            'tips' => [],
                        ];
                    }
                    $processedPrograms[$tipType][$programKey] = $program;
                }

                // Thêm mẹo vào chương
                $processedPrograms[$tipType][$programKey]['tips'][] = [
                    'id' => $tip->id,
                    'content' => $tip->content,
                    'question' => $tip->question,
                    'created_at' => $tip->created_at->toIso8601String(),
                    'updated_at' => $tip->updated_at->toIso8601String(),
                ];
            }

            // Gán các chương vào groupedTips
            $groupedTips['tips'][0]['programs'] = array_values($processedPrograms['1']);
            $groupedTips['tips'][1]['programs'] = array_values($processedPrograms['2']);
            $groupedTips['tips'][2]['programs'] = array_values($processedPrograms['3']);

            return response()->json($groupedTips, 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách mẹo: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi lấy danh sách mẹo.',
            ], 500);
        }
    }
}
