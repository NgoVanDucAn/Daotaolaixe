<?php

namespace App\Http\Controllers;

use App\Models\TrafficSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrafficSignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signs = TrafficSign::latest()->paginate(30);
        return view('admin.traffic_signs.index', compact('signs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.traffic_signs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:traffic_signs',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('question/bienbao', $filename, 'public');
            $data['image'] = 'bienbao/' . $filename;
        }

        TrafficSign::create($data);

        return redirect()->route('traffic-signs.index')->with('success', 'Tạo biển báo thành công!');
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
    public function edit(TrafficSign $trafficSign)
    {
        return view('admin.traffic_signs.edit', compact('trafficSign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrafficSign $trafficSign)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:traffic_signs,code,' . $trafficSign->id,
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($trafficSign->image) {
                Storage::disk('public')->delete('question/' . $trafficSign->image);
            }
            $image = $request->file('image');
            $filename = $image->getClientOriginalName();
            $image->storeAs('question/bienbao', $filename, 'public');
            $data['image'] = 'bienbao/' . $filename;
        }

        $trafficSign->update($data);

        return redirect()->route('traffic-signs.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrafficSign $trafficSign)
    {
        if ($trafficSign->image) {
            Storage::disk('public')->delete('question/' . $trafficSign->image);
        }

        $trafficSign->delete();

        return back()->with('success', 'Xóa thành công!');
    }
}
