<?php

namespace App\Http\Controllers;

use App\Models\LearningField;
use Illuminate\Http\Request;

class LearningFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $learningFields = LearningField::latest()->paginate(30);
        return view('admin.learning_fields.index', compact('learningFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.learning_fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:learning_fields,name',
            'price' => 'required|numeric|min:0',
            'teaching_mode' => 'required|in:0,1',
            'description' => 'nullable|string',
        ]);

        LearningField::create($request->all());
        return redirect()->route('learning_fields.index')->with('success', 'Lĩnh vực học đã được tạo.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LearningField $learningField)
    {
        return view('admin.learning_fields.edit', compact('learningField'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LearningField $learningField)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:learning_fields,name,' . $learningField->id,
            'price' => 'required|numeric|min:0',
            'teaching_mode' => 'required|in:0,1',
            'description' => 'nullable|string',
        ]);

        $learningField->update($request->all());

        return redirect()->route('learning_fields.index')->with('success', 'Lĩnh vực học đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningField $learningField)
    {
        $learningField->delete();
        return redirect()->route('learning_fields.index')->with('success', 'Lĩnh vực học đã được xóa.');
    }
}
