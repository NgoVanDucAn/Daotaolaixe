<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrafficSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Exception;

class TrafficSignController extends Controller
{
    public function indexGroupedByType(): JsonResponse
    {
        try {
            $grouped = TrafficSign::all()->groupBy('type');
            $result = [];
    
            foreach ($grouped as $type => $items) {
                $formattedItems = $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'type' => $item->type,
                        'code' => $item->code,
                        'name' => $item->name,
                        'description' => $item->description,
                        'image' => !empty($item->image)
                            ? "https://cdn.dtlx.app/question/" . $item->image
                            : null,
                        'type_label' => $item->type_label,
                    ];
                });
    
                $result[] = [
                    'type' => $type,
                    'type_label' => TrafficSign::getTypeOptions()[$type] ?? 'Không xác định',
                    'items' => $formattedItems->values(),
                ];
            }
    
            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể lấy dữ liệu biển báo.',
            ], 500);
        }
    }

    public function bulkStore(Request $request)
    {
        $signs = $request->all();

        if (!is_array($signs)) {
            return response()->json(['message' => 'Dữ liệu phải là mảng các bản ghi.'], 422);
        }

        $validatedSigns = [];
        $errors = [];

        // Kiểm tra từng bản ghi trước khi bắt đầu transaction
        foreach ($signs as $index => $sign) {
            $validator = Validator::make($sign, [
                'type' => 'required|numeric|between:1,6',
                'code' => 'required|string|max:255|unique:traffic_signs,code',
                'name' => 'required|string|max:255',
                'image' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors[$index] = $validator->errors()->toArray();
            } else {
                $validatedSigns[] = $validator->validated();
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ. Không có bản ghi nào được lưu.',
                'errors' => $errors,
            ], 422);
        }

        // Dữ liệu hợp lệ hết → tiến hành lưu
        DB::beginTransaction();
        try {
            foreach ($validatedSigns as $data) {
                TrafficSign::create($data);
            }
            DB::commit();

            return response()->json([
                'message' => 'Tạo tất cả bản ghi thành công.',
                'count' => count($validatedSigns),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình lưu.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
