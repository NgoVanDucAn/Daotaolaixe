<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        $rankings = Ranking::with('lessons', 'examSets')->get()->map(function ($ranking) {
            return [
                'id' => $ranking->id,
                'name' => $ranking->name,
                'description' => $ranking->description,
                'lessons' => $ranking->lessons->map(function ($lesson) {
                    return [
                        'lesson_id' => $lesson->id,
                    ];
                }),
                'examSets' => $ranking->examSets->map(function ($examSets) {
                    return [
                        'examSet_id' => $examSets->id,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $rankings
        ]);
    }

    public function show($id)
    {
        $ranking = Ranking::find($id);

        if (!$ranking) {
            return response()->json([
                'success' => false,
                'message' => 'Ranking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ranking
        ]);
    }
}
