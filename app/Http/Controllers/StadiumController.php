<?php

namespace App\Http\Controllers;

use App\Models\Stadium;
use Illuminate\Http\Request;

class StadiumController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->location;
        $stadiumsAll = Stadium::all();
        $stadiums = Stadium::when($location, function ($query) use ($location) {
            $query->where('id', $location);
        })->paginate(30);
        
        return view('admin.stadiums.index', compact('stadiums', 'stadiumsAll'));
    }

    public function create()
    {
        return view('admin.stadiums.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'google_maps_url' => 'nullable|url',
            'note' => 'nullable',
        ]);

        Stadium::create($request->all());

        return redirect()->route('stadiums.index')->with('success', 'Sân đã được thêm thành công!');
    }

    public function edit(Stadium $stadium)
    {
        return view('admin.stadiums.edit', compact('stadium'));
    }

    public function update(Request $request, Stadium $stadium)
    {
        $request->validate([
            'location' => 'required',
            'google_maps_url' => 'nullable|url',
            'note' => 'nullable',
        ]);

        $stadium->update($request->all());

        return redirect()->route('stadiums.index')->with('success', 'Sân đã được cập nhật thành công!');
    }

    public function destroy(Stadium $stadium)
    {
        $stadium->delete();

        return redirect()->route('stadiums.index')->with('success', 'Sân đã được xóa!');
    }
}
