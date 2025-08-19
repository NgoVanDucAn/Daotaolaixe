<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamSetRequest;
use App\Http\Requests\UpdateExamSetRequest;
use Illuminate\Support\Str;
use App\Models\ExamSet;
use App\Models\Lesson;
use App\Models\Ranking;
use App\Services\ExamSetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSetController extends Controller
{

    private ExamSetService $examSetService;

    public function __construct(ExamSetService $examSetService)
    {
        $this->examSetService = $examSetService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $examSets = $this->examSetService->index();
        return view('admin.exam_sets.index', compact('examSets'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('admin.exam_sets.create');
    }
    public function _create()
    {
        $rankings = Ranking::all();
        $lessons = Lesson::with('quizSets.quizzes')->get();

        foreach ($lessons as $lesson) {
            $lessonData = [
                'lesson_id' => $lesson->id,
                'lesson_name' => $lesson->title,
                'quiz_sets' => [],
            ];

            $mandatoryQuizzes = []; // Lưu tất cả câu hỏi `mandatory = 1` của lesson này

            foreach ($lesson->quizSets as $quizSet) {
                // Tách câu hỏi có `mandatory = 1`
                $mandatory = $quizSet->quizzes->where('mandatory', 1);
                $normalQuizzes = $quizSet->quizzes->where('mandatory', 0);

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
                        'question' => $quiz->question
                    ])->values()->toArray()
                ];
            }

            // Nếu có câu hỏi `mandatory = 1`, tạo thêm 1 quizSet mới cho nó
            if (!empty($mandatoryQuizzes)) {
                $newQuizSetId = $lesson->id . '_' . (count($lesson->quizSets) + 1); // ID theo format: lessonId_sốChươngMới
                $lessonData['quiz_sets'][] = [
                    'quiz_set_id' => $newQuizSetId,
                    'quiz_set_name' => "Chương " . (count($lesson->quizSets) + 1) . " - Câu hỏi điểm liệt",
                    'quizzes' => array_map(fn($quiz) => [
                        'quiz_id' => $quiz['id'],
                        'question' => $quiz['question']
                    ], $mandatoryQuizzes)
                ];
            }

            $data[] = $lessonData;
        }

        return view('admin.exam_sets.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->examSetService->create($request->all());
        return redirect()->route('exam_sets.index');
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
    public function edit(String $id)
    {
        $rankings = Ranking::all();
        $examSet = ExamSet::with('quizzes')->findOrFail($id);

        $lesson = Lesson::with('quizSets.quizzes')->findOrFail($examSet->lesson_id);

        //lọc bỏ những câu đã thuộc về bộ đề hiện tại và phân làm 2 arr câu hỏi bắt buộc và không bắt buộc
        $mandatoryQuizzes = [];
        $optionalQuizzes = [];
        foreach ($lesson->quizSets as $quizSet) {
            foreach ($quizSet->quizzes as $quiz) {
                if (!$examSet->quizzes->contains('id', $quiz->id)) {
                    if ($quiz->mandatory == 1) {
                        $mandatoryQuizzes[] = $quiz;
                    } else {
                        $optionalQuizzes[] = $quiz;
                    }
                }
            }
        }
        return view('admin.exam_sets.edit', compact('rankings', 'examSet', 'mandatoryQuizzes', 'optionalQuizzes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamSet $examSet)
    {
        $this->examSetService->update($examSet, $request->all());
        return redirect()->route('exam_sets.index')->with('success', 'Cập nhật thông tin bộ đề thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamSet $examSet)
    {
        $this->examSetService->delete($examSet);
        return redirect()->route('exam_sets.index');
    }

    public function addQuestion(Request $request, ExamSet $examSet)
    {
        $validated = $request->validate([
            'quiz_id' => 'nullable|exists:quizzes,id',
            'quiz_mandatory_id' => 'nullable|exists:quizzes,id',
        ]);

        if ($validated['quiz_id']) {
            $examSet->quizzes()->attach($validated['quiz_id']);
        }
        if ($validated['quiz_mandatory_id']) {
            $examSet->quizzes()->attach($validated['quiz_mandatory_id']);
        }

        return redirect()->route('exam_sets.edit', $examSet->id)->with('success', 'Thêm câu hỏi vào bộ đề thành công');
    }

    public function removeQuestion($examSetId, $quizId)
    {
        $examSet = ExamSet::findOrFail($examSetId);
        $examSet->quizzes()->detach($quizId);

        return redirect()->back()->with('success', 'Câu hỏi đã được gỡ khỏi bộ đề.');
    }

    protected function prepareChapters(Lesson $lesson)
    {
        $chapters = [];
        $mandatoryQuizzes = [];

        foreach ($lesson->quizSets as $quizSet) {
            $normalQuizzes = $quizSet->quizzes->where('mandatory', 0);
            $mandatory = $quizSet->quizzes->where('mandatory', 1);

            if ($mandatory->isNotEmpty()) {
                $mandatoryQuizzes = array_merge($mandatoryQuizzes, $mandatory->toArray());
            }

            $chapters[] = collect($normalQuizzes);
        }

        // Thêm chương cuối cùng cho câu hỏi điểm liệt (nếu có)
        if (!empty($mandatoryQuizzes)) {
            $chapters[] = collect($mandatoryQuizzes);
        }

        return $chapters;
    }

    public function editor_data()
    {

        // Lấy dữ liệu cần thiết cho trình soạn thảo
        $rankings = Ranking::all();
        $lessons = Lesson::with('quizSets.quizzes')->get();
        $lessonRanking = DB::table('lesson_ranking')->get();
        $curriculums = DB::table('curriculums')->orderBy('id')->get();
        $quizSets = DB::table('quiz_sets')->orderBy('lesson_id', 'asc')->orderBy('id', 'asc')->get();
        $quizzes = DB::table('quizzes')->orderBy('quiz_set_id', 'asc')->orderBy('id', 'asc')->get();

        return response()->json([
            'rankings' => $rankings,
            'lessons' => $lessons,
            'lessonRanking' => $lessonRanking,
            'curriculums' => $curriculums,
            'quizSets' => $quizSets,
            'quizzes' => $quizzes
        ]);
    }

    public function fakeExamSets()
    {
        $rankings = Ranking::all();

        foreach ($rankings as $ranking) {
            $existingSets = ExamSet::where('license_level', $ranking->id)
                ->where('type', 'Đề ôn tập')
                ->exists();

            if ($existingSets) {
                continue;
            }

            $lessonRanking = DB::table('lesson_ranking')
                ->where('ranking_id', $ranking->id)
                ->first();

            if (!$lessonRanking) {
                continue;
            }

            $lessonId = $lessonRanking->lesson_id;
            $lesson = Lesson::with('quizSets.quizzes')->find($lessonId);
            if (!$lesson) continue;

            // Chia chương trước
            $chapters = $this->prepareChapters($lesson);
            // Định nghĩa số câu cần lấy từ từng chương
            $rank = mb_strtolower(trim($ranking->name));
            $chapterQuizCount = match ($rank) {
                'hạng a1', 'hạng a2', 'hạng b1' => [11, 3, 4, 2, 2, 1],
                'hạng b' => [8, 1, 1, 1, 9, 9, 1],
                'hạng b2' => [8, 1, 1, 1, 9, 9, 1],
                'hạng c1' => [9, 2, 2, 1, 10, 10, 1],
                'hạng c' => [9, 2, 2, 1, 14, 11, 1],
                default => [9, 2, 2, 1, 16, 14, 1]
            };
            $timeDo = match ($rank) {
                'hạng a', 'hạng a1', 'hạng a2', 'hạng b1' => 1140,
                'hạng b', 'hạng b2' => 1200,
                'hạng c1' => 1320,
                'hạng c' => 1440,
                default => 1560
            };

            for ($i = 0; $i < 5; $i++) {
                $examSet = ExamSet::create([
                    'license_level' => $ranking->id,
                    'name' => "Đề số " . ($i + 1) . " cho " . mb_strtoupper($ranking->name),
                    'type' => "Đề ôn tập",
                    'lesson_id' => $lessonId,
                    'time_do' => $timeDo,
                ]);

                $quizIds = collect();

                foreach ($chapterQuizCount as $index => $count) {
                    $chapterQuizzes = collect($chapters[$index] ?? []);
                    $availableCount = $chapterQuizzes->count();
                    if ($availableCount >= $count && $count > 0) {
                        $quizIds = $quizIds->merge($chapterQuizzes->pluck('id')->random($count));
                    }
                }

                $examSet->quizzes()->sync($quizIds->toArray());
            }
        }

        return response()->json(['success' => true, 'message' => 'Fake bộ đề thành công!']);
    }
}
