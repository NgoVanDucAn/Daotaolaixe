<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurriculumRequest;
use App\Http\Requests\CurriculumUpdateRequest;
use App\Models\Curriculum;
use App\Models\RankGp;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        $curriculums = Curriculum::with(['courses', 'lessons.quizSets.assignments'])->latest()->paginate(30);
        return view('admin.curriculums.index', compact('curriculums'));
    }

    public function create()
    {
        return view('admin.curriculums.create');
    }

    public function store(CurriculumRequest $request)
    {
        Curriculum::create($request->validated());
        return redirect()->route('curriculums.index')->with('success', 'Thêm giáo trình thành công.');
    }

    public function show(Curriculum $curriculum)
    {
        $curriculum->load(['courses', 'lessons.quizSets.assignments']);
        return view('admin.curriculums.show', compact('curriculum'));
    }

    public function edit(Curriculum $curriculum)
    {
        return view('admin.curriculums.edit', compact('curriculum'));
    }

    public function update(CurriculumRequest $request, Curriculum $curriculum)
    {
        $curriculum->update($request->validated());
        return redirect()->route('curriculums.index')->with('success', 'Rank GPS updated successfully.');
    }

    public function destroy(Curriculum $curriculum)
    {
        $curriculum->delete();
        return redirect()->route('curriculums.index')->with('success', 'Rank GPS deleted successfully.');
    }
}
