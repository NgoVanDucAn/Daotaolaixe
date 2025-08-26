<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipRequest;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\QuizSet;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Helpers\StringFormatter;

class TipsController extends Controller
{
    public function ajaxCreate(Request $request)
    {
        $request->validate([
            'chapter_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $page = Page::create([
            'chapter_name' => $request->chapter_name,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'page' => $page
        ]);
    }

    public function index(Request $request)
    {
        $quizSetIdsType1 = Tip::where('tip_type', 1)->whereNotNull('quiz_set_id')->pluck('quiz_set_id')->unique();
        $quizSetIdsType2 = Tip::where('tip_type', 2)->whereNotNull('quiz_set_id')->pluck('quiz_set_id')->unique();
        
        $quizSetsType1 = QuizSet::whereIn('id', $quizSetIdsType1)->get();
        $quizSetsType2 = QuizSet::whereIn('id', $quizSetIdsType2)->get();
        
        $pageIds = Tip::where('tip_type', 3)->whereNotNull('page_id')->pluck('page_id')->unique();
        $pages = Page::whereIn('id', $pageIds)->get();

        $groupedTips = [
            1 => [],
            2 => [],
            3 => [],
        ];

        foreach ([1 => $quizSetsType1, 2 => $quizSetsType2, 3 => $pages] as $typeTip => $programs) {
            foreach ($programs as $program) {
                $groupKey = $typeTip == 3 ? $program->id : $program->id;
                
                $baseQuery = Tip::where(function ($q) use ($typeTip, $groupKey) {
                    if ($typeTip == 3) {
                        $q->where('tip_type', 3)->where('page_id', $groupKey);
                    } else {
                        $q->where('tip_type', $typeTip)->where('quiz_set_id', $groupKey);
                    }
                });

                if ($search = $request->query('search')) {
                    $baseQuery->where(function ($q) use ($search) {
                        $q->where('content', 'like', "%{$search}%")
                          ->orWhere('question', 'like', "%{$search}%");
                    });
                }

                if ($typeTipFilter = $request->query('type_tip')) {
                    $baseQuery->where('tip_type', $typeTipFilter);
                }

                if ($programId = $request->query('program_id')) {
                    $baseQuery->where(function ($q) use ($programId) {
                        $q->where('quiz_set_id', $programId)
                          ->orWhere('page_id', $programId);
                    });
                }

                $totalCount = (clone $baseQuery)->count();

                $query = Tip::with(['quizSet', 'page'])->where(function ($q) use ($typeTip, $groupKey) {
                    if ($typeTip == 3) {
                        $q->where('tip_type', 3)->where('page_id', $groupKey);
                    } else {
                        $q->where('tip_type', $typeTip)->where('quiz_set_id', $groupKey);
                    }
                });

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('content', 'like', "%{$search}%")
                          ->orWhere('question', 'like', "%{$search}%");
                    });
                }

                if ($typeTipFilter) {
                    $query->where('tip_type', $typeTipFilter);
                }

                if ($programId) {
                    $query->where(function ($q) use ($programId) {
                        $q->where('quiz_set_id', $programId)
                          ->orWhere('page_id', $programId);
                    });
                }

                $pageParam = "page_program_{$typeTip}_{$groupKey}";
                $tips = $query->paginate(30, ['*'], $pageParam);

                if ($totalCount > 0) {
                    $groupedTips[$typeTip][$groupKey] = [
                        'program' => $program,
                        'tips' => $tips,
                        'total_count' => $totalCount,
                    ];
                }
            }

            $baseQuery = Tip::where('tip_type', $typeTip)
                ->whereNull($typeTip == 3 ? 'page_id' : 'quiz_set_id');

            if ($search = $request->query('search')) {
                $baseQuery->where(function ($q) use ($search) {
                    $q->where('content', 'like', "%{$search}%")
                      ->orWhere('question', 'like', "%{$search}%");
                });
            }

            if ($typeTipFilter = $request->query('type_tip')) {
                $baseQuery->where('tip_type', $typeTipFilter);
            }

            if ($programId = $request->query('program_id')) {
                $baseQuery->where(function ($q) use ($programId) {
                    $q->where('quiz_set_id', $programId)
                      ->orWhere('page_id', $programId);
                });
            }

            $totalCount = (clone $baseQuery)->count();

            $query = Tip::with(['quizSet', 'page'])->where('tip_type', $typeTip)
                ->whereNull($typeTip == 3 ? 'page_id' : 'quiz_set_id');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('content', 'like', "%{$search}%")
                      ->orWhere('question', 'like', "%{$search}%");
                });
            }

            if ($typeTipFilter) {
                $query->where('tip_type', $typeTipFilter);
            }

            if ($programId) {
                $query->where(function ($q) use ($programId) {
                    $q->where('quiz_set_id', $programId)
                      ->orWhere('page_id', $programId);
                });
            }

            $noProgramTips = $query->paginate(30, ['*'], "page_program_{$typeTip}_no_program");
            if ($totalCount > 0) {
                $groupedTips[$typeTip]['no_program'] = [
                    'program' => null,
                    'tips' => $noProgramTips,
                    'total_count' => $totalCount,
                ];
            }
        }

        $search = $request->query('search', '');
        $selectedTypeTip = $request->query('type_tip', '');
        $selectedProgramId = $request->query('program_id', '');

        return view('admin.tips.index', compact(
            'groupedTips',
            'search',
            'selectedTypeTip',
            'selectedProgramId',
            'quizSetsType1',
            'quizSetsType2',
            'pages'
        ));
    }

    public function create()
    {
        $keywordsCar = ['ô tô', 'oto', 'xe hơi', 'xe con', 'xe khách'];
        $keywordsBike = ['xe máy', 'mô tô', 'xe gắn máy'];

        $quizSetsType1 = QuizSet::whereHas('lesson', function ($query) use ($keywordsCar) {
            $query->where(function ($subQuery) use ($keywordsCar) {
                foreach ($keywordsCar as $word) {
                    $subQuery->orWhere('title', 'like', "%$word%");
                }
            });
        })->get();
        
        $quizSetsType2 = QuizSet::whereHas('lesson', function ($query) use ($keywordsBike) {
            $query->where(function ($subQuery) use ($keywordsBike) {
                foreach ($keywordsBike as $word) {
                    $subQuery->orWhere('title', 'like', "%$word%");
                }
            });
        })->get();

        $pages = Page::all();

        return view('admin.tips.create', compact('quizSetsType1', 'quizSetsType2', 'pages'));
    }

    public function store(StoreTipRequest $request)
    {
        try {
            foreach ($request->input('tips') as $tip) {
                // Kiểm tra nếu dữ liệu cần thiết chưa được cung cấp
                $questions = array_filter(array_map('trim', explode("\n", $tip['question'])));
                if (empty($questions)) {
                    throw new \Exception('Câu hỏi không được để trống!');
                }
    
                // Kiểm tra các giá trị có hợp lệ hay không
                $quizSetId = (in_array($tip['type_tip'], [1, 2]) && isset($tip['quiz_set_id'])) ? $tip['quiz_set_id'] : null;
                $pageId = ($tip['type_tip'] == 3 && isset($tip['page_id'])) ? $tip['page_id'] : null;
    
                // Tạo mới bản ghi Tip
                Tip::create([
                    'tip_type' => $tip['type_tip'],
                    'quiz_set_id' => $quizSetId,
                    'page_id' => $pageId,
                    'content' => $tip['content_question'],
                    'question' => $questions,
                ]);
            }
    
            return redirect()->route('tips.create')->with('success', 'Thêm mẹo thành công!');
        } catch (\Exception $e) {
            // Log chi tiết lỗi để dễ dàng chẩn đoán
            Log::error('Lỗi khi lưu mẹo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu mẹo!')->withInput();
        }
    }

    public function edit(Tip $tip)
    {
        $keywordsCar = ['ô tô', 'oto', 'xe hơi', 'xe con', 'xe khách'];
        $keywordsBike = ['xe máy', 'mô tô', 'xe gắn máy'];

        $quizSetsType1 = QuizSet::whereHas('lesson', function ($query) use ($keywordsCar) {
            $query->where(function ($subQuery) use ($keywordsCar) {
                foreach ($keywordsCar as $word) {
                    $subQuery->orWhere('title', 'like', "%$word%");
                }
            });
        })->get();
        
        $quizSetsType2 = QuizSet::whereHas('lesson', function ($query) use ($keywordsBike) {
            $query->where(function ($subQuery) use ($keywordsBike) {
                foreach ($keywordsBike as $word) {
                    $subQuery->orWhere('title', 'like', "%$word%");
                }
            });
        })->get();

        $pages = Page::all();

        return view('admin.tips.edit', compact(
            'tip',
            'quizSetsType1',
            'quizSetsType2',
            'pages'
        ));
    }

    public function update(Request $request, Tip $tip)
    {
        $keywordsCar = ['ô tô', 'oto', 'xe hơi', 'xe con', 'xe khách'];
        $keywordsBike = ['xe máy', 'mô tô', 'xe gắn máy'];

        // Validation rules
        $rules = [
            'tip_type' => 'required|in:1,2,3',
            'content' => 'required|string',
            'question' => 'required|array|min:1',
            'question.*' => 'required|string', // Mỗi câu hỏi là chuỗi, không rỗng
        ];

        if ($request->tip_type == 1) {
            $rules['quiz_set_id'] = ['required', function ($attribute, $value, $fail) use ($keywordsCar) {
                $exists = QuizSet::whereHas('lesson', function ($query) use ($keywordsCar) {
                    $query->where(function ($subQuery) use ($keywordsCar) {
                        foreach ($keywordsCar as $word) {
                            $subQuery->orWhere('title', 'like', "%$word%");
                        }
                    });
                })->where('id', $value)->exists();
                if (!$exists) {
                    $fail('Chương không hợp lệ cho Ô tô.');
                }
            }];
            $rules['page_id'] = 'nullable';
        } elseif ($request->tip_type == 2) {
            $rules['quiz_set_id'] = ['required', function ($attribute, $value, $fail) use ($keywordsBike) {
                $exists = QuizSet::whereHas('lesson', function ($query) use ($keywordsBike) {
                    $query->where(function ($subQuery) use ($keywordsBike) {
                        foreach ($keywordsBike as $word) {
                            $subQuery->orWhere('title', 'like', "%$word%");
                        }
                    });
                })->where('id', $value)->exists();
                if (!$exists) {
                    $fail('Chương không hợp lệ cho Xe máy.');
                }
            }];
            $rules['page_id'] = 'nullable';
        } elseif ($request->tip_type == 3) {
            $rules['page_id'] = 'required|exists:pages,id';
            $rules['quiz_set_id'] = 'nullable';
        }

        // Thay đổi dữ liệu question để validate mảng
        $input = $request->all();
        if (is_string($request->question)) {
            $input['question'] = array_filter(array_map('trim', explode("\n", $request->question)));
        }

        $validator = Validator::make($input, $rules, [
            'tip_type.required' => 'Vui lòng chọn loại mẹo.',
            'tip_type.in' => 'Loại mẹo không hợp lệ.',
            'content.required' => 'Nội dung mẹo không được để trống.',
            'question.required' => 'Danh sách câu hỏi không được để trống.',
            'question.*.required' => 'Mỗi câu hỏi không được để trống.',
            'question.*.string' => 'Mỗi câu hỏi phải là chuỗi.',
            'quiz_set_id.required' => 'Vui lòng chọn chương.',
            'page_id.required' => 'Vui lòng chọn chương cho Mô phỏng.',
            'page_id.exists' => 'Chương không tồn tại.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tip->update([
                'tip_type' => $request->tip_type,
                'quiz_set_id' => $request->tip_type != 3 ? $request->quiz_set_id : null,
                'page_id' => $request->tip_type == 3 ? $request->page_id : null,
                'content' => $request->content,
                'question' => $input['question'],
            ]);

            return redirect()->route('tips.index')->with('success', 'Cập nhật mẹo thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật mẹo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật mẹo. Vui lòng thử lại.')->withInput();
        }
    }

    public function destroy(Tip $tip)
    {
        try {
            $tip->delete();
            return redirect()->route('tips.index')->with('success', 'Xóa mẹo thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xóa mẹo: ' . $e->getMessage());
            return redirect()->route('tips.index')->with('error', 'Có lỗi xảy ra khi xóa mẹo. Vui lòng thử lại.');
        }
    }

    public function updateContent()
    {
        $tips = Tip::all();

        $formattedTips = $tips->map(function ($tip) {
            $originalContent = $tip->content;
            $formattedContent = $this->formatTipContent($tip->content);
    
            // Cập nhật luôn vào DB
            $tip->update([
                'content' => $formattedContent,
            ]);

            $displayTip = new \stdClass();
            $displayTip->id = $tip->id;
            $displayTip->original_content = $originalContent;
            $displayTip->formatted_content = $formattedContent;
    
            return $displayTip;
        });

        return view('admin.tips.format', compact('formattedTips'))
        ->with('success', 'Tất cả chuỗi trong bảng tips đã được định dạng và cập nhật!');
    }

    private function formatTipContent(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        // Thêm dấu phẩy (,) vào danh sách các dấu
        $parts = preg_split('/([:.\-\(\)\,\s]+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $result = [];
        $capitalizeNextWord = true;
        $lastPart = '';

        foreach ($parts as $index => $part) {
            if (in_array($part, [':', '.', '-', '(', ')', ','])) {
                if ($part === ':') {
                    if ($lastPart === ' ') {
                        array_pop($result); // Xóa khoảng trắng trước :
                    }
                    $result[] = $part;
                    if (isset($parts[$index + 1]) && $parts[$index + 1] !== ' ') {
                        $result[] = ' '; // Thêm khoảng trắng sau :
                    }
                    $capitalizeNextWord = true;
                } elseif ($part === ',') {
                    if ($lastPart === ' ') {
                        array_pop($result); // Xóa khoảng trắng trước ,
                    }
                    $result[] = $part;
                    if (isset($parts[$index + 1]) && $parts[$index + 1] !== ' ') {
                        $result[] = ' '; // Thêm khoảng trắng sau ,
                    }
                    $capitalizeNextWord = true;
                } elseif ($part === '-') {
                    if ($lastPart !== ' ') {
                        $result[] = ' '; // Thêm khoảng trắng trước -
                    }
                    $result[] = $part;
                    if (isset($parts[$index + 1]) && $parts[$index + 1] !== ' ') {
                        $result[] = ' '; // Thêm khoảng trắng sau -
                    }
                    $capitalizeNextWord = true;
                } elseif ($part === '(') {
                    $result[] = $part;
                    if (isset($parts[$index + 1]) && $parts[$index + 1] === ' ') {
                        $parts[$index + 1] = ''; // Bỏ qua khoảng trắng sau (
                    }
                    $capitalizeNextWord = true;
                } elseif ($part === ')') {
                    if ($lastPart === ' ') {
                        array_pop($result); // Xóa khoảng trắng trước )
                    }
                    $result[] = $part;
                    $capitalizeNextWord = true;
                } else {
                    $result[] = $part;
                    $capitalizeNextWord = true;
                }
            } elseif (trim($part) === '') {
                $result[] = $part; // Giữ khoảng trắng nếu không bị xóa
            } else {
                if ($capitalizeNextWord && $part) {
                    // Viết hoa chữ cái đầu tiên của $part
                    $result[] = mb_strtoupper(mb_substr($part, 0, 1, 'UTF-8'), 'UTF-8') . mb_strtolower(mb_substr($part, 1, null, 'UTF-8'), 'UTF-8');
                    $capitalizeNextWord = false;
                } else {
                    $result[] = $part;
                }
            }
            $lastPart = $part;
        }

        return implode('', $result);
    }
}
