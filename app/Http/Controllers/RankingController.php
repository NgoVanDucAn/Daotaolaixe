<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Ranking;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ranks = Ranking::latest()->paginate(30);
        $lessons = Lesson::all();
        return view('admin.rankings.index', compact('ranks', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lessons = Lesson::all();
        return view('admin.rankings.create', compact('lessons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rankings,name',
            'fee_ranking' => 'required|numeric|min:0',
            'vehicle_type' => 'required|in:0,1',
            'description' => 'nullable|string',
            'lessons' => 'required|array',
            'lessons.*' => 'exists:lessons,id',
        ]);
        $ranking = Ranking::create($request->all());
        $ranking->lessons()->sync($request->lessons);
    
        return redirect()->route('rankings.index')->with('success', 'Tạo hạng bằng thành công');
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
    public function edit(Ranking $ranking)
    {
        $lessonAlls = Lesson::all();
        $ranking->load('lessons');
        return response()->json([
            'ranking' => [
                'id' => $ranking->id,
                'name' => $ranking->name,
                'fee_ranking' => $ranking->fee_ranking,
                'vehicle_type' => $ranking->vehicle_type,
                'description' => $ranking->description,
                'lessons' => $ranking->lessons->pluck('id')->toArray(),
            ],
            'lessonAlls' => $lessonAlls->map(fn ($lessonAlls) => [
                'id' => $lessonAlls->id,
                'title' => $lessonAlls->title,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ranking $ranking)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rankings,name,' . $ranking->id,
            'fee_ranking' => 'required|numeric|min:0',
            'vehicle_type' => 'required|in:0,1',
            'description' => 'nullable|string',
            'lessons' => 'required|array',
            'lessons.*' => 'exists:lessons,id',
        ]);
    
        // Cập nhật ranking
        $ranking->update($request->all());
    
        // Cập nhật các lessons
        $ranking->lessons()->sync($request->lessons);
    
        return redirect()->route('rankings.index')->with('success', 'Cập nhật hạng bằng thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ranking $ranking)
    {
        $ranking->delete();
        return redirect()->route('rankings.index')->with('success', 'Xóa hạng bằng thành công.');
    }
}
