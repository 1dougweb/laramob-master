<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::with(['propertyType', 'city', 'district'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propertyTypes = PropertyType::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $districts = District::where('is_active', true)->get();

        return view('admin.properties.create', compact('propertyTypes', 'cities', 'districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'built_area' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'suites' => 'nullable|integer|min:0',
            'parking' => 'nullable|integer|min:0',
            'purpose' => 'required|in:sale,rent,both',
            'price' => 'nullable|numeric|min:0',
            'rental_price' => 'nullable|numeric|min:0',
            'condominium_fee' => 'nullable|numeric|min:0',
            'iptu' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,sold,rented,reserved,inactive',
            'featured_image' => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'gallery.*' => 'nullable|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'gallery' => 'nullable|array|max:10',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'feature' => 'nullable|array',
            'feature.*.feature_name' => 'nullable|string|max:100',
            'feature.*.feature_icon' => 'nullable|string|max:50',
        ]);

        // Generate the code
        $propertyCode = 'PROP' . date('y') . str_pad(
            Property::whereYear('created_at', date('Y'))->count() + 1, 
            4, 
            '0', 
            STR_PAD_LEFT
        );

        // Create property
        $property = new Property();
        $property->title = $validated['title'];
        $property->slug = Str::slug($validated['title']) . '-' . $propertyCode;
        $property->code = $propertyCode;
        $property->property_type_id = $validated['property_type_id'];
        $property->description = $validated['description'];
        $property->city_id = $validated['city_id'];
        $property->district_id = $validated['district_id'];
        $property->address = $validated['address'];
        $property->area = $validated['area'];
        $property->built_area = $validated['built_area'] ?? null;
        $property->bedrooms = $validated['bedrooms'];
        $property->bathrooms = $validated['bathrooms'];
        $property->suites = $validated['suites'] ?? null;
        $property->parking = $validated['parking'] ?? null;
        $property->purpose = $validated['purpose'];
        $property->price = $validated['price'] ?? null;
        $property->rental_price = $validated['rental_price'] ?? null;
        $property->condominium_fee = $validated['condominium_fee'] ?? null;
        $property->iptu = $validated['iptu'] ?? null;
        $property->status = $validated['status'];
        $property->is_featured = $request->has('is_featured');
        $property->is_active = $request->has('is_active');

        // Process features with icons
        if ($request->has('feature') && is_array($request->feature)) {
            $featuresData = [];
            
            foreach ($request->feature as $feature) {
                if (!empty($feature['feature_name'])) {
                    $featuresData[] = [
                        'name' => $feature['feature_name'],
                        'icon' => $feature['feature_icon'] ?? 'home'
                    ];
                }
            }
            
            $property->features = !empty($featuresData) ? json_encode($featuresData) : null;
        } else {
            $property->features = null;
        }

        // Upload featured image if provided
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('properties', 'public');
            $property->featured_image = $imagePath;
        }

        $property->save();

        // Process gallery images
        if ($request->hasFile('gallery')) {
            $sort = 0;
            foreach ($request->file('gallery') as $image) {
                $imagePath = $image->store('properties/gallery', 'public');
                
                $property->gallery()->create([
                    'image' => $imagePath,
                    'title' => $property->title . ' - Imagem ' . ($sort + 1),
                    'is_featured' => $sort === 0 && !$request->hasFile('featured_image'),
                    'sort_order' => $sort
                ]);
                
                $sort++;
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Imóvel criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load(['propertyType', 'city', 'district']);
        
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $propertyTypes = PropertyType::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $districts = District::where('is_active', true)->get();

        return view('admin.properties.edit', compact('property', 'propertyTypes', 'cities', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string|max:255',
            'area' => 'required|numeric|min:0',
            'built_area' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'suites' => 'nullable|integer|min:0',
            'parking' => 'nullable|integer|min:0',
            'purpose' => 'required|in:sale,rent,both',
            'price' => 'nullable|numeric|min:0',
            'rental_price' => 'nullable|numeric|min:0',
            'condominium_fee' => 'nullable|numeric|min:0',
            'iptu' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,sold,rented,reserved,inactive',
            'featured_image' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery' => 'nullable|array|max:10',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'feature' => 'nullable|array',
            'feature.*.feature_name' => 'nullable|string|max:100',
            'feature.*.feature_icon' => 'nullable|string|max:50',
        ]);

        // Update property
        $property->title = $validated['title'];
        // Don't update the slug to preserve existing URLs
        $property->property_type_id = $validated['property_type_id'];
        $property->description = $validated['description'];
        $property->city_id = $validated['city_id'];
        $property->district_id = $validated['district_id'];
        $property->address = $validated['address'];
        $property->area = $validated['area'];
        $property->built_area = $validated['built_area'] ?? null;
        $property->bedrooms = $validated['bedrooms'];
        $property->bathrooms = $validated['bathrooms'];
        $property->suites = $validated['suites'] ?? null;
        $property->parking = $validated['parking'] ?? null;
        $property->purpose = $validated['purpose'];
        $property->price = $validated['price'] ?? null;
        $property->rental_price = $validated['rental_price'] ?? null;
        $property->condominium_fee = $validated['condominium_fee'] ?? null;
        $property->iptu = $validated['iptu'] ?? null;
        $property->status = $validated['status'];
        $property->is_featured = $request->has('is_featured');
        $property->is_active = $request->has('is_active');

        // Process features with icons
        if ($request->has('feature') && is_array($request->feature)) {
            $featuresData = [];
            
            foreach ($request->feature as $feature) {
                if (!empty($feature['feature_name'])) {
                    $featuresData[] = [
                        'name' => $feature['feature_name'],
                        'icon' => $feature['feature_icon'] ?? 'home'
                    ];
                }
            }
            
            $property->features = !empty($featuresData) ? json_encode($featuresData) : null;
        } else {
            $property->features = null;
        }

        // Upload featured image if provided
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($property->featured_image) {
                Storage::disk('public')->delete($property->featured_image);
            }
            
            $imagePath = $request->file('featured_image')->store('properties', 'public');
            $property->featured_image = $imagePath;
        }

        $property->save();

        // Process gallery images
        if ($request->hasFile('gallery')) {
            $sort = $property->gallery()->max('sort_order') + 1;
            foreach ($request->file('gallery') as $image) {
                $imagePath = $image->store('properties/gallery', 'public');
                
                $property->gallery()->create([
                    'image' => $imagePath,
                    'title' => $property->title . ' - Imagem ' . ($sort + 1),
                    'is_featured' => false,
                    'sort_order' => $sort
                ]);
                
                $sort++;
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', 'Imóvel atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        // Check if property has related records before deleting
        if ($property->contracts()->count() > 0) {
            return redirect()->route('admin.properties.index')
                ->with('error', 'Não é possível excluir este imóvel porque ele possui contratos associados.');
        }

        // Delete featured image if exists
        if ($property->featured_image) {
            Storage::disk('public')->delete($property->featured_image);
        }

        // Delete gallery images
        foreach ($property->gallery as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Imóvel excluído com sucesso.');
    }

    /**
     * Remove an image from the property gallery.
     */
    public function destroyImage($id)
    {
        $image = PropertyImage::findOrFail($id);
        $propertyId = $image->property_id;
        
        // Delete the image file
        Storage::disk('public')->delete($image->image);
        
        // Delete the image record
        $image->delete();
        
        return redirect()->route('admin.properties.edit', $propertyId)
            ->with('success', 'Imagem removida com sucesso.');
    }
} 