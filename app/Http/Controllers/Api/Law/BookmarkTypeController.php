<?php

namespace App\Http\Controllers\Api\Law;

use App\Http\Controllers\Controller;
use App\Models\LawBookmarkType;
use Illuminate\Http\Request;

class BookmarkTypeController extends Controller
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
            'bookmark_name' => 'required|string|max:255',
        ]);

        $bookmarkType = LawBookmarkType::create($request->only('bookmark_name'));

        return response()->json(['message' => 'Thêm loại bookmark thành công!', 'data' => $bookmarkType]);
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
        $bookmarkType = LawBookmarkType::findOrFail($id);
        $bookmarkType->update($request->only('bookmark_name'));

        return response()->json(['message' => 'Cập nhật thành công!', 'data' => $bookmarkType]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bookmarkType = LawBookmarkType::withCount('bookmarks')->findOrFail($id);

        if ($bookmarkType->bookmarks_count > 0) {
            return response()->json(['message' => 'Không thể xóa: Đã được dùng trong các bookmark.'], 400);
        }

        $bookmarkType->delete();

        return response()->json(['message' => 'Xóa thành công.']);
    }
}
