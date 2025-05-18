<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Person::query();
        
        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by search term if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
            });
        }
        
        $people = $query->orderBy('name')->paginate(10);
        
        return view('admin.people.index', compact('people'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.people.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:people,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:20',
            'document_type' => 'nullable|in:cpf,cnpj',
            'marital_status' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:100',
            'type' => 'required|in:employee,broker,owner,client,tenant',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_agency' => 'nullable|string|max:20',
            'bank_account' => 'nullable|string|max:20',
            'pix_key' => 'nullable|string|max:100',
            'broker_id' => 'nullable|exists:people,id,type,broker',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'type.required' => 'O tipo de pessoa é obrigatório.',
            'type.in' => 'O tipo de pessoa selecionado é inválido.',
            'document_type.in' => 'O tipo de documento deve ser CPF ou CNPJ.',
            'birth_date.date' => 'A data de nascimento informada não é válida.',
            'photo.image' => 'O arquivo deve ser uma imagem.',
            'photo.max' => 'A imagem não pode ser maior que 2MB.',
            'commission_rate.numeric' => 'A taxa de comissão deve ser um número.',
            'commission_rate.min' => 'A taxa de comissão não pode ser negativa.',
            'commission_rate.max' => 'A taxa de comissão não pode ser maior que 100%.',
            'notes.max' => 'As observações não podem ter mais de 500 caracteres.',
            'broker_id.exists' => 'O corretor selecionado não existe ou não é um corretor válido.',
        ]);

        $data = $request->except('photo');
        $data['is_active'] = $request->has('is_active');
        
        // Handle null values for broker_id
        if (($request->type !== 'client' && $request->type !== 'tenant') || empty($request->broker_id)) {
            $data['broker_id'] = null;
        }
        
        $person = new Person($data);
        
        if ($request->hasFile('photo')) {
            $person->photo = $request->file('photo')->store('people', 'public');
        }
        
        $person->save();

        $typeLabels = [
            'employee' => 'Funcionário',
            'broker' => 'Corretor',
            'owner' => 'Vendedor/Locador',
            'client' => 'Comprador',
            'tenant' => 'Locatário',
        ];

        $typeLabel = $typeLabels[$person->type] ?? $person->type;

        return redirect()->route('admin.people.index')
            ->with('success', $typeLabel . ' cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        // Load related data
        if ($person->broker_id) {
            $person->load('broker');
        }
        
        if ($person->type === 'broker') {
            $person->load('commissions', 'brokerContracts.property');
        } elseif ($person->type === 'owner') {
            $person->load('properties');
        } elseif ($person->type === 'client' || $person->type === 'tenant') {
            $person->load('contracts.property');
        }
        
        return view('admin.people.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return view('admin.people.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:people,email,' . $person->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:20',
            'document_type' => 'nullable|in:cpf,cnpj',
            'marital_status' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:100',
            'type' => 'required|in:employee,broker,owner,client,tenant',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_agency' => 'nullable|string|max:20',
            'bank_account' => 'nullable|string|max:20',
            'pix_key' => 'nullable|string|max:100',
            'broker_id' => 'nullable|exists:people,id,type,broker',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'type.required' => 'O tipo de pessoa é obrigatório.',
            'type.in' => 'O tipo de pessoa selecionado é inválido.',
            'document_type.in' => 'O tipo de documento deve ser CPF ou CNPJ.',
            'birth_date.date' => 'A data de nascimento informada não é válida.',
            'photo.image' => 'O arquivo deve ser uma imagem.',
            'photo.max' => 'A imagem não pode ser maior que 2MB.',
            'commission_rate.numeric' => 'A taxa de comissão deve ser um número.',
            'commission_rate.min' => 'A taxa de comissão não pode ser negativa.',
            'commission_rate.max' => 'A taxa de comissão não pode ser maior que 100%.',
            'notes.max' => 'As observações não podem ter mais de 500 caracteres.',
            'broker_id.exists' => 'O corretor selecionado não existe ou não é um corretor válido.',
        ]);

        $data = $request->except(['photo', 'remove_photo']);
        $data['is_active'] = $request->has('is_active');
        
        // Handle null values for broker_id
        if (($request->type !== 'client' && $request->type !== 'tenant') || empty($request->broker_id)) {
            $data['broker_id'] = null;
        }
        
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($person->photo && Storage::disk('public')->exists($person->photo)) {
                Storage::disk('public')->delete($person->photo);
            }
            $data['photo'] = $request->file('photo')->store('people', 'public');
        } elseif ($request->has('remove_photo') && $person->photo) {
            Storage::disk('public')->delete($person->photo);
            $data['photo'] = null;
        }
        
        $person->update($data);

        $typeLabels = [
            'employee' => 'Funcionário',
            'broker' => 'Corretor',
            'owner' => 'Vendedor/Locador',
            'client' => 'Comprador',
            'tenant' => 'Locatário',
        ];

        $typeLabel = $typeLabels[$person->type] ?? $person->type;

        return redirect()->route('admin.people.index')
            ->with('success', $typeLabel . ' atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            // Check if person has clients assigned to them (for brokers)
            if ($person->type === 'broker' && $person->clients()->exists()) {
                return redirect()->route('admin.people.index')
                    ->with('error', 'Este corretor possui clientes associados e não pode ser excluído.');
            }
            
            if ($person->photo && Storage::disk('public')->exists($person->photo)) {
                Storage::disk('public')->delete($person->photo);
            }
            
            $person->delete();
            
            $typeLabels = [
                'employee' => 'Funcionário',
                'broker' => 'Corretor',
                'owner' => 'Vendedor/Locador',
                'client' => 'Comprador',
                'tenant' => 'Locatário',
            ];
    
            $typeLabel = $typeLabels[$person->type] ?? $person->type;
            
            return redirect()->route('admin.people.index')
                ->with('success', $typeLabel . ' excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('admin.people.index')
                ->with('error', 'Erro ao excluir: esta pessoa está associada a outros registros.');
        }
    }
} 