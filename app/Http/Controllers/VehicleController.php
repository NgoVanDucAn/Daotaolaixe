<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Calendar;
use App\Models\LearningField;
use App\Models\Ranking;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $licensePlate = $request->input('license_plate');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $vehiclesAll = Vehicle::all();
        $vehicles = Vehicle::with([
            'ranking',
            'calendars.learningField',
            'calendars.calendarStudents',
        ])
        ->when($licensePlate, function ($query) use ($licensePlate) {
            $query->where('id', $licensePlate);
        })
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(30);
    
        $practicalFields = LearningField::where('is_practical', 1)->get();
        return view('admin.vehicles.index', compact('vehicles', 'practicalFields', 'vehiclesAll'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rankings = Ranking::all();
        return view('admin.vehicles.create', compact('rankings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        Vehicle::create($request->validated());
        return redirect()->route('vehicles.index')->with('success', 'Xe đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $rankings = Ranking::all();
        return view('admin.vehicles.edit', compact('vehicle', 'rankings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return redirect()->route('vehicles.index')->with('success', 'Thông tin xe đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Xe đã được xóa!');
    }

    public function getAvailableVehicles(Request $request)
    {
        $validated = $request->validate([
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ]);

        $start = $validated['start_time'];
        $end = $validated['end_time'];

        $availableVehicles = Vehicle::whereDoesntHave('calendars', function ($query) use ($start, $end) {
            $query->where('type', 'study')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('date_start', [$start, $end])
                        ->orWhereBetween('date_end', [$start, $end])
                        ->orWhere(function ($sub) use ($start, $end) {
                            $sub->where('date_start', '<=', $start)
                                ->where('date_end', '>=', $end);
                        });
                });
        })->get();

        return response()->json([
            'vehicles' => $availableVehicles,
        ]);
    }
}
