<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $propertyTypes = PropertyType::orderBy('name')->paginate(10);
        return view('admin.property-types.index', compact('propertyTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.property-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:property_types',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        PropertyType::create($request->all());

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PropertyType $propertyType)
    {
        return view('admin.property-types.show', compact('propertyType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropertyType $propertyType)
    {
        return view('admin.property-types.edit', compact('propertyType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropertyType $propertyType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:property_types,name,' . $propertyType->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $propertyType->update($request->all());

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyType $propertyType)
    {
        // Check if this property type has any properties associated with it
        if ($propertyType->properties()->count() > 0) {
            return redirect()->route('admin.property-types.index')
                ->with('error', 'Cannot delete this property type because it has properties associated with it.');
        }

        $propertyType->delete();

        return redirect()->route('admin.property-types.index')
            ->with('success', 'Property type deleted successfully.');
    }
} 