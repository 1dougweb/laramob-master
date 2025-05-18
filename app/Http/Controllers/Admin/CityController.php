<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::orderBy('name')->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'is_active' => 'boolean',
        ]);

        City::create($request->all());

        return redirect()->route('admin.cities.index')
            ->with('success', 'Cidade criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city->load('districts');
        return view('admin.cities.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'is_active' => 'boolean',
        ]);

        $city->update($request->all());

        return redirect()->route('admin.cities.index')
            ->with('success', 'Cidade atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        // Check if this city has any districts or properties associated with it
        if ($city->districts()->count() > 0 || $city->properties()->count() > 0) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Não é possível excluir esta cidade porque ela possui bairros ou imóveis associados.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'Cidade excluída com sucesso.');
    }
} 