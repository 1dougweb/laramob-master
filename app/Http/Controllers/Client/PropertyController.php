<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Person;
use App\Models\City;
use App\Models\District;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering
        $search = $request->input('search');
        $purpose = $request->input('purpose');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minArea = $request->input('min_area');
        $maxArea = $request->input('max_area');
        $bedrooms = $request->input('bedrooms');
        $bathrooms = $request->input('bathrooms');
        $cityId = $request->input('city_id');
        $districtId = $request->input('district_id');
        $propertyTypeId = $request->input('property_type_id');

        // Build query
        $query = Property::with(['city', 'district', 'propertyType'])
            ->where('is_active', true)
            ->where('status', 'available');

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($purpose) {
            $query->where('purpose', $purpose);
        }

        if ($minPrice) {
            if ($purpose === 'rent') {
                $query->where('rental_price', '>=', $minPrice);
            } else {
                $query->where('price', '>=', $minPrice);
            }
        }

        if ($maxPrice) {
            if ($purpose === 'rent') {
                $query->where('rental_price', '<=', $maxPrice);
            } else {
                $query->where('price', '<=', $maxPrice);
            }
        }

        if ($minArea) {
            $query->where('area', '>=', $minArea);
        }

        if ($maxArea) {
            $query->where('area', '<=', $maxArea);
        }

        if ($bedrooms) {
            $query->where('bedrooms', '>=', $bedrooms);
        }

        if ($bathrooms) {
            $query->where('bathrooms', '>=', $bathrooms);
        }

        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        if ($propertyTypeId) {
            $query->where('property_type_id', $propertyTypeId);
        }

        // Get properties with pagination
        $properties = $query->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Get filter data for dropdowns
        $cities = City::orderBy('name')->get();
        $districts = $cityId ? District::where('city_id', $cityId)->orderBy('name')->get() : District::orderBy('name')->get();
        $propertyTypes = PropertyType::orderBy('name')->get();

        // Get current client person
        $person = Auth::user()->person ?? null;

        return view('client.properties.index', compact(
            'properties',
            'cities',
            'districts',
            'propertyTypes',
            'person',
            'search',
            'purpose',
            'minPrice',
            'maxPrice',
            'minArea',
            'maxArea',
            'bedrooms',
            'bathrooms',
            'cityId',
            'districtId',
            'propertyTypeId'
        ));
    }

    /**
     * Display the specified property.
     */
    public function show($slug)
    {
        $property = Property::with(['city', 'district', 'propertyType', 'gallery'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get similar properties
        $similarProperties = Property::with(['city', 'district'])
            ->where('is_active', true)
            ->where('status', 'available')
            ->where('id', '!=', $property->id)
            ->where(function($query) use ($property) {
                $query->where('district_id', $property->district_id)
                    ->orWhere('property_type_id', $property->property_type_id);
            })
            ->take(4)
            ->get();

        // Get current client person
        $person = Auth::user()->person ?? null;

        return view('client.properties.show', compact('property', 'similarProperties', 'person'));
    }

    /**
     * Contact about a property.
     */
    public function contact(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        // Create a contact entry
        $property->contacts()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'new',
            'person_id' => Auth::user()->person->id ?? null,
        ]);

        return redirect()->back()->with('success', 'Sua mensagem foi enviada com sucesso! Um corretor entrará em contato em breve.');
    }

    /**
     * Toggle favorite status for a property.
     */
    public function toggleFavorite($id)
    {
        $property = Property::findOrFail($id);
        $person = Person::where('user_id', Auth::id())->firstOrFail();

        // Check if property is already favorited
        if ($person->favoriteProperties()->where('property_id', $property->id)->exists()) {
            $person->favoriteProperties()->detach($property->id);
            $message = 'Propriedade removida dos favoritos.';
        } else {
            $person->favoriteProperties()->attach($property->id);
            $message = 'Propriedade adicionada aos favoritos.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display a listing of favorite properties.
     */
    public function favorites()
    {
        try {
            // Use cache para melhorar performance
            $userId = Auth::id();
            $cacheKey = "user_favorites_{$userId}";
            
            // Se os dados estiverem em cache, use-os
            if (\Cache::has($cacheKey)) {
                $data = \Cache::get($cacheKey);
                return view('client.properties.favorites', $data);
            }
            
            // Se não estiver em cache, busque os dados
            $person = Person::where('user_id', $userId)->first();
            
            if (!$person) {
                $data = [
                    'properties' => collect([]),
                    'person' => null,
                    'error_message' => 'Usuário sem perfil associado'
                ];
                
                // Cache por 5 minutos
                \Cache::put($cacheKey, $data, now()->addMinutes(5));
                return view('client.properties.favorites', $data);
            }
            
            // Otimize a consulta carregando apenas o necessário
            $properties = $person->favoriteProperties()
                ->with(['city:id,name', 'district:id,name,city_id', 'propertyType:id,name'])
                ->paginate(12);
            
            $data = compact('properties', 'person');
            
            // Cache por 5 minutos
            \Cache::put($cacheKey, $data, now()->addMinutes(5));
                
            return view('client.properties.favorites', $data);
        } catch (\Exception $e) {
            // Log e exibir o erro para debug
            \Log::error('Erro ao carregar favoritos: ' . $e->getMessage());
            return view('client.properties.favorites', [
                'properties' => collect([]),
                'person' => null,
                'error_message' => 'Erro: ' . $e->getMessage()
            ]);
        }
    }
} 