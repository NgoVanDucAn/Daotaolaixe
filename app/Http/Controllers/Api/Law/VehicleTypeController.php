<?php

namespace App\Http\Controllers\Api\Law;

use App\Http\Controllers\Controller;
use App\Models\LawVehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
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
            'vehicle_name' => 'required|string|max:255',
        ]);

        $vehicleType = LawVehicleType::create($request->only('vehicle_name'));

        return response()->json(['message' => 'Thêm loại phương tiện thành công!', 'data' => $vehicleType]);
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
        $vehicleType = LawVehicleType::findOrFail($id);
        $vehicleType->update($request->only('vehicle_name'));

        return response()->json(['message' => 'Cập nhật thành công!', 'data' => $vehicleType]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vehicleType = LawVehicleType::withCount('violations')->findOrFail($id);

        if ($vehicleType->violations_count > 0) {
            return response()->json(['message' => 'Không thể xóa: Đã được sử dụng trong các vi phạm.'], 400);
        }

        $vehicleType->delete();

        return response()->json(['message' => 'Xóa thành công.']);
    }
}
