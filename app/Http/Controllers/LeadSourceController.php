<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->name;
        $leadSources = LeadSource::when($name, function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->paginate(30);
        return view('admin.lead_sources.index', compact('leadSources'));
    }
    
    public function create()
    {
        return view('admin.lead_sources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:lead_sources,name|max:255',
            'description' => 'nullable|string',
        ]);

        LeadSource::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('leadSource.index')
            ->with('success', 'Lead Source created successfully.');
    }

    public function edit(LeadSource $leadSource)
    {
        return view('admin.lead_sources.edit', compact('leadSource'));
    }

    public function update(Request $request, LeadSource $leadSource)
    {
        $request->validate([
            'name' => 'required|unique:lead_sources,name,' . $leadSource->id . '|max:255',
            'description' => 'nullable|string',
        ]);

        $leadSource->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('leadSource.index')
            ->with('success', 'Lead Source updated successfully.');
    }

    public function destroy(LeadSource $leadSource)
    {
        $leadSource->delete();

        return redirect()->route('leadSource.index')
            ->with('success', 'Lead Source deleted successfully.');
    }
}
