<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\District;
use App\Models\Contact;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties with search filters.
     */
    public function index(Request $request)
    {
        $query = Property::where('is_active', true)
            ->with(['propertyType', 'city', 'district']);

        // Apply filters if they exist
        if ($request->has('property_type_id') && $request->property_type_id) {
            $query->where('property_type_id', $request->property_type_id);
        }

        if ($request->has('city_id') && $request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->has('purpose') && $request->purpose) {
            if ($request->purpose === 'both') {
                $query->whereIn('purpose', ['sale', 'rent', 'both']);
            } else {
                $query->whereIn('purpose', [$request->purpose, 'both']);
            }
        }

        if ($request->has('min_price') && $request->min_price) {
            if ($request->purpose === 'rent') {
                $query->where('rental_price', '>=', $request->min_price);
            } else {
                $query->where('price', '>=', $request->min_price);
            }
        }

        if ($request->has('max_price') && $request->max_price) {
            if ($request->purpose === 'rent') {
                $query->where('rental_price', '<=', $request->max_price);
            } else {
                $query->where('price', '<=', $request->max_price);
            }
        }

        if ($request->has('bedrooms') && $request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->has('bathrooms') && $request->bathrooms) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        if ($request->has('code') && $request->code) {
            $query->where('code', $request->code);
        }

        // Get properties with pagination
        $properties = $query->orderBy('created_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        // Get filter options
        $propertyTypes = PropertyType::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();
        $districts = District::where('is_active', true);
        
        if ($request->has('city_id') && $request->city_id) {
            $districts->where('city_id', $request->city_id);
        }
        
        $districts = $districts->get();

        return view('properties.index', compact(
            'properties',
            'propertyTypes',
            'cities',
            'districts'
        ));
    }

    /**
     * Display the specified property.
     */
    public function show($slug)
    {
        $property = Property::where('slug', $slug)
            ->where('is_active', true)
            ->with(['propertyType', 'city', 'district', 'gallery', 'owner'])
            ->firstOrFail();

        // Get similar properties
        $similarProperties = Property::where('id', '!=', $property->id)
            ->where('is_active', true)
            ->where(function ($query) use ($property) {
                $query->where('property_type_id', $property->property_type_id)
                    ->orWhere('district_id', $property->district_id);
            })
            ->with(['propertyType', 'city', 'district'])
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Store a contact request for a property.
     */
    public function contact(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $contact = new Contact($validated);
        $contact->property_id = $id;
        $contact->save();

        return redirect()->back()->with('success', 'Your message has been sent successfully. We will contact you soon.');
    }
}
