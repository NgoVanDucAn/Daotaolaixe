<?php

namespace App\Http\Controllers\Api\Law;

use App\Http\Controllers\Controller;
use App\Models\LawBookmark;
use App\Models\LawViolation;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function store(Request $request, $violationId)
    {
        $request->validate([
            'bookmark_code' => 'required|string|max:50',
            'bookmark_type_id' => 'required|exists:bookmark_types,id',
            'bookmark_description' => 'nullable|string',
        ]);

        $violation = LawViolation::findOrFail($violationId);

        $bookmark = $violation->bookmarks()->create($request->only(
            'bookmark_code', 'bookmark_type_id', 'bookmark_description'
        ));

        return response()->json(['message' => 'Đã thêm bookmark!', 'data' => $bookmark]);
    }

    public function update(Request $request, $id)
    {
        $bookmark = LawBookmark::findOrFail($id);
        $bookmark->update($request->only('bookmark_code', 'bookmark_type_id', 'bookmark_description'));

        return response()->json(['message' => 'Cập nhật bookmark thành công!', 'data' => $bookmark]);
    }

    public function destroy(Request $request, $id)
    {
        $bookmark = LawBookmark::findOrFail($id);
        if ($request->boolean('super')) {
            $bookmark->violations()->detach();
        } else {
            if ($bookmark->violations()->exists()) {
                return response()->json([
                    'message' => 'Không thể xóa bookmark vì đang được sử dụng trong vi phạm pháp luật.'
                ], 400);
            }
        }
        $bookmark->delete();
        return response()->json(['message' => 'Đã xóa bookmark thành công!']);
    }
}
