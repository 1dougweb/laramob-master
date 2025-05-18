<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $districts = District::with('city')->orderBy('name')->paginate(10);
        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::where('is_active', true)->orderBy('name')->get();
        return view('admin.districts.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'is_active' => 'boolean',
        ]);

        District::create($request->all());

        return redirect()->route('admin.districts.index')
            ->with('success', 'Bairro criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        $district->load('city');
        return view('admin.districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        $cities = City::where('is_active', true)->orderBy('name')->get();
        return view('admin.districts.edit', compact('district', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'is_active' => 'boolean',
        ]);

        $district->update($request->all());

        return redirect()->route('admin.districts.index')
            ->with('success', 'Bairro atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        // Check if this district has any properties associated with it
        if ($district->properties()->count() > 0) {
            return redirect()->route('admin.districts.index')
                ->with('error', 'Cannot delete this district because it has properties associated with it.');
        }

        $district->delete();

        return redirect()->route('admin.districts.index')
            ->with('success', 'Bairro deletado com sucesso.');
    }
} 