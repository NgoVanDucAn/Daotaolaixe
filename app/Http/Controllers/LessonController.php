<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Assignment;
use App\Models\Curriculum;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizSet;
use App\Models\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::withCount('quizSets')->latest()->paginate(30);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $curriculums = Curriculum::all();
        $rankings = Ranking::all();
        return view('admin.lessons.create', compact('curriculums', 'rankings'));
    }

    public function show($id)
    {
        $lesson = Lesson::with([
            'curriculum',
            'quizSets.quizzes.quizOptions',
            'quizSets.assignments'
        ])->findOrFail($id);

        return view('admin.lessons.show', compact('lesson'));
    }

    //lưu ý khi xử lý để lưu bài học cần thêm cả các phần bài tập và asm để đồng bộ hệ thống
    public function store(LessonRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $lesson = Lesson::create($validated);
            $lesson->rankings()->sync($request->rankings);

            foreach ($request->input('quiz_sets', []) as $quizSetData) {
                $quizSet = $lesson->quizSets()->create([
                    'lesson_id' => $lesson->id,
                    'name' => $quizSetData['title'] ?? 'Quiz Set ' . ($lesson->quizSets()->count() + 1),
                    'description' => $quizSetData['description'],
                ]);
    
                // Tạo asm cho mỗi bộ quiz
                $quizSet->assignments()->create([
                    'title' => "Bài tập cho {$quizSet->name} - {$quizSet->description}",
                    'description' => "Thực hiện {$quizSet->name} của bài {$lesson->id} để hoàn thành bài tập",
                ]);

                // Tạo Quizzes cho mỗi Quiz Set
                foreach ($quizSetData['quizzes'] as $quizData) {
                    $imagePath = null;
                    $imagePathTip = null;
                    if (!empty($quizData['image']) && $quizData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $timestamp = now()->timestamp;
                        $extension = $quizData['image']->getClientOriginalExtension();
                        //đổi tên file ảnh kết hợp timestamp và uuid để tránh trùng cho tên ảnh
                        $fileName = $timestamp . '_' . Str::uuid() . '.' . $extension;
                        
                        //lưu trong storage/app/public/quizzes (Hiển thị ảnh không được thử lệnh sinh symbolic link của laravel - php artisan storage:link)
                        //Truy cập và tìm kiếm trong source file "storage/app/public" và cấu hình lại nếu cần bảo mật hình ảnh bằng cách cho phép các ip được phép truy cập
                        $imagePath = $quizData['image']->storeAs('quizzes', $fileName, 'public');
                    }

                    if (!empty($quizData['tip_image']) && $quizData['tip_image'] instanceof \Illuminate\Http\UploadedFile) {
                        $timestamp = now()->timestamp;
                        $extension = $quizData['tip_image']->getClientOriginalExtension();
                        //đổi tên file ảnh kết hợp timestamp và uuid để tránh trùng cho tên ảnh
                        $fileName = $timestamp . '_' . Str::uuid() . '.' . $extension;
                        
                        //lưu trong storage/app/public/quizzes_tip (Hiển thị ảnh không được thử lệnh sinh symbolic link của laravel - php artisan storage:link)
                        //Truy cập và tìm kiếm trong source file "storage/app/public" và cấu hình lại nếu cần bảo mật hình ảnh bằng cách cho phép các ip được phép truy cập
                        $imagePathTip = $quizData['tip_image']->storeAs('quizzes_tip', $fileName, 'public');
                    }

                    $quiz = $quizSet->quizzes()->create([
                        'question' => $quizData['question'],
                        'name' => $quizData['name'],
                        'image' => $imagePath,
                        'tip' => $quizData['tip'],
                        'tip_image' => $imagePathTip,
                        'mandatory' => $quizData['mandatory'] ?? 0,
                        'explanation' => $quizData['explanation'],
                    ]);

                    // Tạo Quiz Options cho mỗi Quiz
                    foreach ($quizData['options'] as $option) {
                        $quiz->quizOptions()->create([
                            'option_text' => $option['option'],
                            'is_correct' => !empty($option['is_correct']) ? filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN) : false,
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('lessons.index')->with('success', 'Bài học và quiz đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo bài học và quiz: ' . $e->getMessage() . ' tại dòng ' . $e->getLine() . ' trong file ' . $e->getFile());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo bài học và quiz. Vui lòng thử lại.');
        }
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load([
            'curriculum',
            'quizSets.quizzes.quizOptions',
            'quizSets.assignments'
        ]);
    
        $curriculums = Curriculum::all();
    
        return view('admin.lessons.edit', compact('lesson', 'curriculums'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sequence' => 'required|integer',
        ]);

        $lesson->update($request->all());
        $lesson->rankings()->sync($request->rankings);

        return redirect()->route('lessons.index');
    }

    public function destroy($id)
    {
        // try {
        //     $lesson = Lesson::findOrFail($id);
        //     $lesson->delete();
        //     return redirect()->route('lessons.index')->with('success', 'Bài học đã được xóa thành công.');
        // } catch (\Exception $e) {
        //     Log::error('Lỗi khi xóa bài học: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa bài học.');
        // }
        try {
            // Tìm Lesson theo ID
            $lesson = Lesson::findOrFail($id);
    
            // Lấy tất cả các QuizSet thuộc về Lesson này và xóa chúng
            $lesson->quizSets()->delete();
    
            // Trả về thông báo thành công
            return redirect()->route('lessons.index')->with('success', 'Tất cả QuizSets của bài học đã được xóa thành công.');
        } catch (\Exception $e) {
            // Ghi log lỗi
            Log::error('Lỗi khi xóa QuizSets của bài học: ' . $e->getMessage());
    
            // Trả về thông báo lỗi
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa QuizSets của bài học.');
        }
    }

    public function cloneToChapter(Request $request, $chapterId)
    {
        $nameMap = $request->input('names');

        if (!is_array($nameMap) || empty($nameMap)) {
            return response()->json(['message' => 'Dữ liệu không hợp lệ'], 400);
        }

        return $this->cloneQuestionsWithOptionsToChapter($nameMap, $chapterId);
    }

    public function cloneQuestionsWithOptionsToChapter(array $nameMap, int $chapterId)
    {
        DB::beginTransaction();

        try {
            $lesson = Lesson::where('title', '600 câu hỏi oto')->first();
    
            if (!$lesson) {
                return response()->json(['message' => 'Không tìm thấy lesson "600 câu hỏi ô tô"'], 404);
            }
    
            $quizSetIds = $lesson->quizSets()->pluck('id');
    
            if ($quizSetIds->isEmpty()) {
                return response()->json(['message' => 'Lesson không có quiz sets'], 404);
            }
    
            $quizzes = Quiz::with('quizOptions')
                ->whereIn('quiz_set_id', $quizSetIds)
                ->whereIn('name', array_keys($nameMap))
                ->get();
    
            if ($quizzes->isEmpty()) {
                return response()->json(['message' => 'Không tìm thấy câu hỏi phù hợp'], 404);
            }
    
            $clonedCount = 0;
    
            foreach ($quizzes as $quiz) {
                $newQuiz = $quiz->replicate();
                $newQuiz->quiz_set_id = $chapterId;
    
                if (isset($nameMap[$quiz->name])) {
                    $newQuiz->name = $nameMap[$quiz->name];
                }
    
                $newQuiz->save();
    
                foreach ($quiz->quizOptions as $option) {
                    $newOption = $option->replicate();
                    $newOption->quiz_id = $newQuiz->id;
                    $newOption->save();
                }
    
                $clonedCount++;
            }
    
            DB::commit();
    
            return response()->json([
                'message' => "Đã clone $clonedCount câu hỏi với tên mới.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
    
}
