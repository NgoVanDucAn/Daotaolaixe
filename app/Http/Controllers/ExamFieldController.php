<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamField;

class ExamFieldController extends Controller
{
    public function index()
    {
        $examFields = ExamField::latest()->paginate(30);
        return view('admin.exam_fields.index', compact('examFields'));
    }

    public function create()
    {
        return view('admin.exam_fields.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:exam_fields,name',
            'description' => 'nullable',
        ]);

        ExamField::create($request->all());

        return redirect()->route('exam_fields.index')->with('success', 'Lĩnh vực thi đã được tạo.');
    }

    public function show(ExamField $examField)
    {
        return view('admin.exam_fields.show', compact('examField'));
    }

    public function edit(ExamField $examField)
    {
        return view('admin.exam_fields.edit', compact('examField'));
    }

    public function update(Request $request, ExamField $examField)
    {
        $request->validate([
            'name' => 'required|unique:exam_fields,name,' . $examField->id,
            'description' => 'nullable',
        ]);

        $examField->update($request->all());

        return redirect()->route('exam_fields.index')->with('success', 'Lĩnh vực thi đã được cập nhật.');
    }

    public function destroy(ExamField $examField)
    {
        $examField->delete();
        return redirect()->route('exam_fields.index')->with('success', 'Lĩnh vực thi đã được xóa.');
    }
}
