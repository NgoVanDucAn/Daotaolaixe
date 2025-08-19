<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Material;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $chapters = Chapter::with('materials')->orderBy('order')->get();

        $formattedChapters = $chapters->map(function ($chapter) {
            $chapterArray = $chapter->toArray();
            $chapterArray['materials'] = collect($chapterArray['materials'])->map(function ($material) {
                $material['image'] = $material['url'];
                unset($material['url']);
                return $material;
            })->values();
            return $chapterArray;
        });

        return response()->json([
            'message' => 'Danh sách chương và tài liệu',
            'data' => $formattedChapters
        ]);
    }

    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'chapters' => 'required|array',
            'chapters.*.name' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.order' => 'required|integer',
            'chapters.*.materials' => 'required|array',
            'chapters.*.materials.*.name' => 'required|string|max:255',
            'chapters.*.materials.*.title' => 'nullable|string|max:255',
            'chapters.*.materials.*.type' => 'nullable|string|max:50',
            'chapters.*.materials.*.file_path' => 'nullable|string',
            'chapters.*.materials.*.url' => 'nullable|url',
            'chapters.*.materials.*.total_time' => 'nullable|string',
            'chapters.*.materials.*.start_time' => 'nullable|string',
            'chapters.*.materials.*.end_time' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $results = [];

            foreach ($data['chapters'] as $chapterData) {
                $materialsData = $chapterData['materials'];
                unset($chapterData['materials']);

                $chapter = Chapter::create($chapterData);

                foreach ($materialsData as $materialData) {
                    $materialData['chapter_id'] = $chapter->id;
                    Material::create($materialData);
                }

                $results[] = $chapter->load('materials');
            }

            DB::commit();

            return response()->json([
                'message' => 'Tạo nhiều chương và tài liệu thành công!',
                'data' => $results
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Đã xảy ra lỗi!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateSimulations(Request $request)
    {
        $rules = $request->input('rules', []); // ví dụ: [2,1,2,1,2,2]

        if (empty($rules)) {
            return response()->json(['message' => 'Vui lòng cung cấp rules.'], 400);
        }

        $chapters = Chapter::with('materials')->orderBy('order')->get();

        if (count($rules) !== $chapters->count()) {
            return response()->json(['message' => 'Số lượng rules không khớp với số chương.'], 400);
        }

        $results = [];

        DB::beginTransaction();
        try {
            for ($i = 0; $i < 15; $i++) {
                $simulation = Simulation::create([
                    'name' => 'Bộ đề mô phỏng ' . ($i + 1),
                    'order' => $i + 1,
                ]);

                $materialsForThisSimulation = [];

                foreach ($chapters as $index => $chapter) {
                    $count = (int) $rules[$index];
                    $selectedMaterials = $chapter->materials->shuffle()->take($count);
                    $materialsForThisSimulation = array_merge($materialsForThisSimulation, $selectedMaterials->pluck('id')->toArray());
                }

                $simulation->materials()->attach($materialsForThisSimulation);

                $results[] = [
                    'simulation_name' => $simulation->name,
                    'material_ids' => $materialsForThisSimulation,
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Tạo 15 bộ đề mô phỏng thành công',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi tạo bộ đề',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function allSimulations()
    {
        $simulations = Simulation::with(['materials' => function ($query) {
            $query->select(
                'materials.id',
                'materials.name',
                'materials.title',
                'materials.type',
                'materials.file_path',
                'materials.url',
                'materials.total_time',
                'materials.start_time',
                'materials.end_time'
            );
        }])
        ->orderBy('order')
        ->get();

        $simulations->each(function ($simulation) {
            $simulation->materials->transform(function ($material) {
                return [
                    'id' => $material->id,
                    'name' => $material->name,
                    'title' => $material->title,
                    'type' => $material->type,
                    'file_path' => $material->file_path,
                    'image' => $material->url,
                    'total_time' => $material->total_time,
                    'start_time' => $material->start_time,
                    'end_time' => $material->end_time,
                ];
            });
        });

        return response()->json([
            'message' => 'Danh sách bộ đề mô phỏng',
            'data' => $simulations
        ]);
    }
}
