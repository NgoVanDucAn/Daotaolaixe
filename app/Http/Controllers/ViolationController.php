<?php

namespace App\Http\Controllers;

use App\Models\LawBookmark;
use App\Models\LawBookmarkType;
use App\Models\LawTopic;
use App\Models\LawVehicleType;
use App\Models\LawViolation;
use App\Models\LawViolationRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = LawViolation::with(['vehicleType', 'topic'])->get();
        return view('admin.violations.index', compact('violations'));
    }

    public function create()
    {
        $vehicleTypes = LawVehicleType::all();
        $topics = LawTopic::all();
        $bookmarkTypes = LawBookmarkType::all();
        $violations = LawViolation::all(['id', 'violation_no', 'description']);
        return view('admin.violations.create', compact('vehicleTypes', 'topics', 'bookmarkTypes', 'violations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'violation_no' => 'required|string|unique:law_violations,violation_no',
            'description' => 'required|string',
            'entities' => 'required|string',
            'fines' => 'required|string',
            'additional_penalties' => 'nullable|string',
            'remedial' => 'nullable|string',
            'other_penalties' => 'nullable|string',
            'image' => 'nullable|string',
            'keyword' => 'nullable|string',
            'type_id' => 'required|exists:law_vehicle_types,id',
            'topic_id' => 'required|exists:law_topics,id',
            'bookmarks' => 'nullable|array',
            'bookmarks.*.bookmark_code' => 'required|string',
            'bookmarks.*.bookmark_type_id' => 'required|exists:law_bookmark_types,id',
            'bookmarks.*.bookmark_description' => 'required|string',
            'related_violation_ids' => 'nullable|array',
            'related_violation_ids.*' => 'exists:law_violations,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $violation = LawViolation::create([
                    'violation_no' => $request->violation_no,
                    'description' => $request->description,
                    'entities' => $request->entities,
                    'fines' => $request->fines,
                    'additional_penalties' => $request->additional_penalties,
                    'remedial' => $request->remedial,
                    'other_penalties' => $request->other_penalties,
                    'image' => $request->image,
                    'keyword' => $request->keyword,
                    'type_id' => $request->type_id,
                    'topic_id' => $request->topic_id,
                ]);

                if ($request->has('bookmarks')) {
                    foreach ($request->bookmarks as $bookmark) {
                        $lawBookmark = LawBookmark::create([
                            'bookmark_code' => $bookmark['bookmark_code'],
                            'bookmark_type_id' => $bookmark['bookmark_type_id'],
                            'bookmark_description' => $bookmark['bookmark_description'],
                        ]);
                        $violation->bookmarks()->attach($lawBookmark->id);
                    }
                }

                if ($request->has('related_violation_ids')) {
                    foreach ($request->related_violation_ids as $relatedId) {
                        LawViolationRelation::create([
                            'violation_id' => $violation->id,
                            'related_violation_id' => $relatedId,
                        ]);
                    }
                }
            });

            return redirect()->route('violations.index')->with('success', 'Tạo vi phạm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tạo vi phạm thất bại: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $violation = LawViolation::with(['vehicleType', 'topic', 'bookmarks.bookmarkType', 'relatedViolations'])->findOrFail($id);
        return view('admin.violations.show', compact('violation'));
    }

    public function edit($id)
    {
        $violation = LawViolation::with(['bookmarks', 'relatedViolations'])->findOrFail($id);
        $vehicleTypes = LawVehicleType::all();
        $topics = LawTopic::all();
        $bookmarkTypes = LawBookmarkType::all();
        $violations = LawViolation::where('id', '!=', $id)->get(['id', 'violation_no', 'description']);
        return view('admin.violations.edit', compact('violation', 'vehicleTypes', 'topics', 'bookmarkTypes', 'violations'));
    }

    public function update(Request $request, $id)
    {
        $violation = LawViolation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'violation_no' => 'required|string|unique:law_violations,violation_no,' . $id,
            'description' => 'required|string',
            'entities' => 'required|string',
            'fines' => 'required|string',
            'additional_penalties' => 'nullable|string',
            'remedial' => 'nullable|string',
            'other_penalties' => 'nullable|string',
            'image' => 'nullable|string',
            'keyword' => 'nullable|string',
            'type_id' => 'required|exists:law_vehicle_types,id',
            'topic_id' => 'required|exists:law_topics,id',
            'bookmarks' => 'nullable|array',
            'bookmarks.*.bookmark_code' => 'required|string',
            'bookmarks.*.bookmark_type_id' => 'required|exists:law_bookmark_types,id',
            'bookmarks.*.bookmark_description' => 'required|string',
            'related_violation_ids' => 'nullable|array',
            'related_violation_ids.*' => 'exists:law_violations,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::transaction(function () use ($request, $violation) {
                $violation->update([
                    'violation_no' => $request->violation_no,
                    'description' => $request->description,
                    'entities' => $request->entities,
                    'fines' => $request->fines,
                    'additional_penalties' => $request->additional_penalties,
                    'remedial' => $request->remedial,
                    'other_penalties' => $request->other_penalties,
                    'image' => $request->image,
                    'keyword' => $request->keyword,
                    'type_id' => $request->type_id,
                    'topic_id' => $request->topic_id,
                ]);

                // Xóa bookmarks cũ và thêm mới
                $violation->bookmarks()->detach();
                if ($request->has('bookmarks')) {
                    foreach ($request->bookmarks as $bookmark) {
                        $lawBookmark = LawBookmark::create([
                            'bookmark_code' => $bookmark['bookmark_code'],
                            'bookmark_type_id' => $bookmark['bookmark_type_id'],
                            'bookmark_description' => $bookmark['bookmark_description'],
                        ]);
                        $violation->bookmarks()->attach($lawBookmark->id);
                    }
                }

                // Xóa relations cũ và thêm mới
                LawViolationRelation::where('violation_id', $violation->id)->delete();
                if ($request->has('related_violation_ids')) {
                    foreach ($request->related_violation_ids as $relatedId) {
                        LawViolationRelation::create([
                            'violation_id' => $violation->id,
                            'related_violation_id' => $relatedId,
                        ]);
                    }
                }
            });

            return redirect()->route('violations.index')->with('success', 'Cập nhật vi phạm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật vi phạm thất bại: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $violation = LawViolation::findOrFail($id);
                $violation->bookmarks()->detach();
                LawViolationRelation::where('violation_id', $id)->orWhere('related_violation_id', $id)->delete();
                $violation->delete();
            });

            return redirect()->route('violations.index')->with('success', 'Xóa vi phạm thành công!');
        } catch (\Exception $e) {
            return redirect()->route('violations.index')->with('error', 'Xóa vi phạm thất bại: ' . $e->getMessage());
        }
    }
}
