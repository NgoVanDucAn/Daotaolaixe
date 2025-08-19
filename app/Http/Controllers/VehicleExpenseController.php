<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleExpenseRequest;
use App\Http\Requests\UpdateVehicleExpenseRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleExpense;
use Illuminate\Http\Request;

class VehicleExpenseController extends Controller
{
    public function index(Request $request)
    {
        $licensePlate = $request->input('license_plate');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');
        $users = User::all();
        $vehicles = Vehicle::all();
        $expenses = VehicleExpense::with('vehicle', 'user')
        ->when($licensePlate, function ($query) use ($licensePlate) {
            $query->whereHas('vehicle', function ($q) use ($licensePlate) {
                $q->where('id', $licensePlate);
            });
        })
        ->when($type, function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('time', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('time', '<=', $endDate);
        })
        ->when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->latest()->paginate(30);
        return view('admin.vehicle_expenses.index', compact('expenses','vehicles', 'users'));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $users = User::all();
        return view('admin.vehicle_expenses.create', compact('vehicles', 'users'));
    }

    public function store(StoreVehicleExpenseRequest $request)
    {
        VehicleExpense::create($request->validated());
        return redirect()->route('vehicle-expenses.index')->with('success', 'Chi phí đã được thêm.');
    }

    public function show(VehicleExpense $vehicleExpense)
    {
        return view('admin.vehicle_expenses.show', compact('vehicleExpense'));
    }

    public function edit(VehicleExpense $vehicleExpense)
    {
        $vehicles = Vehicle::all();
        $users = User::all();
        return view('admin.vehicle_expenses.edit', compact('vehicleExpense', 'vehicles', 'users'));
    }

    public function update(StoreVehicleExpenseRequest $request, VehicleExpense $vehicleExpense)
    {
        $vehicleExpense->update($request->validated());
        return redirect()->route('vehicle-expenses.index')->with('success', 'Chi phí đã được cập nhật.');
    }

    public function destroy(VehicleExpense $vehicleExpense)
    {
        $vehicleExpense->delete();
        return redirect()->route('vehicle-expenses.index')->with('success', 'Chi phí đã bị xóa.');
    }
}
