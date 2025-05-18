<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\City;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $featuredProperties = Property::where('is_featured', true)
            ->where('is_active', true)
            ->with(['propertyType', 'city', 'district'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $recentProperties = Property::where('is_active', true)
            ->with(['propertyType', 'city', 'district'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $propertyTypes = PropertyType::where('is_active', true)->get();
        $cities = City::where('is_active', true)->get();

        return view('welcome', compact('featuredProperties', 'recentProperties', 'propertyTypes', 'cities'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
}
