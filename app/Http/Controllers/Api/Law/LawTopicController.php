<?php

namespace App\Http\Controllers\Api\Law;

use App\Http\Controllers\Controller;
use App\Models\LawTopic;
use Illuminate\Http\Request;

class LawTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
        ]);

        $topic = LawTopic::create($request->only('topic_name', 'subtitle'));

        return response()->json(['message' => 'Thêm chủ đề thành công!', 'data' => $topic]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $topic = LawTopic::findOrFail($id);
        $topic->update($request->only('topic_name', 'subtitle'));

        return response()->json(['message' => 'Cập nhật chủ đề thành công!', 'data' => $topic]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $topic = LawTopic::withCount('violations')->findOrFail($id);

        if ($topic->violations_count > 0) {
            return response()->json(['message' => 'Không thể xóa: Chủ đề đã chứa vi phạm.'], 400);
        }

        $topic->delete();

        return response()->json(['message' => 'Xóa chủ đề thành công.']);
    }
}
