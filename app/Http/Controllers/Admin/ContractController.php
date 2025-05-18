<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contracts = Contract::with(['property', 'property.city', 'property.district'])
            ->latest()
            ->paginate(10);
        
        return view('admin.contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::select('id', 'title', 'address')
            ->where('status', 'available')
            ->get();
        
        return view('admin.contracts.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_name' => 'required|string|max:255',
            'type' => 'required|string|in:sale,rental,lease',
            'value' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:pending,active,expired,cancelled',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'property_id.required' => 'O campo propriedade é obrigatório.',
            'client_name.required' => 'O campo nome do cliente é obrigatório.',
            'type.required' => 'O campo tipo de contrato é obrigatório.',
            'value.required' => 'O campo valor é obrigatório.',
            'start_date.required' => 'O campo data de início é obrigatório.',
            'property_id.exists' => 'A propriedade selecionada é inválida.',
            'type.in' => 'O tipo de contrato selecionado é inválido.',
            'end_date.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'status.in' => 'O status selecionado é inválido.',
            'document_file.mimes' => 'O documento deve ser do tipo: pdf, doc, docx, jpg, jpeg, png.',
            'document_file.max' => 'O documento não pode ser maior que 10MB.',
        ]);

        // Convert currency format to decimal
        $validated['value'] = (float) str_replace(['.', ','], ['', '.'], $validated['value']);

        try {
            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('document_file')) {
                $validated['document_file'] = $request->file('document_file')->store('contracts', 'public');
            }

            // Create the contract
            $contract = Contract::create($validated);

            // Update property status if the contract is active
            if ($validated['status'] === 'active') {
                $property = Property::findOrFail($validated['property_id']);
                
                if ($validated['type'] === 'sale') {
                    $property->status = 'sold';
                } elseif ($validated['type'] === 'rental' || $validated['type'] === 'lease') {
                    $property->status = 'rented';
                }
                
                $property->save();
            }

            DB::commit();

            return redirect()->route('admin.contracts.index')
                ->with('success', 'Contrato criado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Remove uploaded file if exists
            if (isset($validated['document_file']) && Storage::disk('public')->exists($validated['document_file'])) {
                Storage::disk('public')->delete($validated['document_file']);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao criar contrato: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load(['property', 'property.city', 'property.district']);
        
        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $properties = Property::select('id', 'title', 'address')
            ->where(function ($query) use ($contract) {
                $query->where('status', 'available')
                    ->orWhere('id', $contract->property_id);
            })
            ->get();
        
        return view('admin.contracts.edit', compact('contract', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'client_name' => 'required|string|max:255',
            'type' => 'required|string|in:sale,rental,lease',
            'value' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string|in:pending,active,expired,cancelled',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'property_id.required' => 'O campo propriedade é obrigatório.',
            'client_name.required' => 'O campo nome do cliente é obrigatório.',
            'type.required' => 'O campo tipo de contrato é obrigatório.',
            'value.required' => 'O campo valor é obrigatório.',
            'start_date.required' => 'O campo data de início é obrigatório.',
            'property_id.exists' => 'A propriedade selecionada é inválida.',
            'type.in' => 'O tipo de contrato selecionado é inválido.',
            'end_date.after_or_equal' => 'A data de fim deve ser igual ou posterior à data de início.',
            'status.in' => 'O status selecionado é inválido.',
            'document_file.mimes' => 'O documento deve ser do tipo: pdf, doc, docx, jpg, jpeg, png.',
            'document_file.max' => 'O documento não pode ser maior que 10MB.',
        ]);

        // Convert currency format to decimal
        $validated['value'] = (float) str_replace(['.', ','], ['', '.'], $validated['value']);

        try {
            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('document_file')) {
                // Delete old file if exists
                if ($contract->document_file && Storage::disk('public')->exists($contract->document_file)) {
                    Storage::disk('public')->delete($contract->document_file);
                }
                $validated['document_file'] = $request->file('document_file')->store('contracts', 'public');
            }

            // Get old property status
            $oldStatus = $contract->status;
            $oldPropertyId = $contract->property_id;

            // Update the contract
            $contract->update($validated);

            // Check if property changed or status changed
            if ($oldPropertyId != $validated['property_id'] || $oldStatus != $validated['status']) {
                // Restore old property status if property changed or contract is no longer active
                if ($oldStatus === 'active' && ($oldPropertyId != $validated['property_id'] || $validated['status'] !== 'active')) {
                    $oldProperty = Property::findOrFail($oldPropertyId);
                    $oldProperty->status = 'available';
                    $oldProperty->save();
                }

                // Update new property status if contract is active
                if ($validated['status'] === 'active') {
                    $property = Property::findOrFail($validated['property_id']);
                    
                    if ($validated['type'] === 'sale') {
                        $property->status = 'sold';
                    } elseif ($validated['type'] === 'rental' || $validated['type'] === 'lease') {
                        $property->status = 'rented';
                    }
                    
                    $property->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.contracts.index')
                ->with('success', 'Contrato atualizado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Remove newly uploaded file if exists
            if (isset($validated['document_file']) && 
                $validated['document_file'] !== $contract->getOriginal('document_file') && 
                Storage::disk('public')->exists($validated['document_file'])) {
                Storage::disk('public')->delete($validated['document_file']);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao atualizar contrato: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        try {
            DB::beginTransaction();

            // If contract was active, update property status
            if ($contract->status === 'active') {
                $property = Property::findOrFail($contract->property_id);
                $property->status = 'available';
                $property->save();
            }

            // Delete associated file if exists
            if ($contract->document_file && Storage::disk('public')->exists($contract->document_file)) {
                Storage::disk('public')->delete($contract->document_file);
            }

            // Delete the contract
            $contract->delete();

            DB::commit();

            return redirect()->route('admin.contracts.index')
                ->with('success', 'Contrato excluído com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erro ao excluir contrato: ' . $e->getMessage());
        }
    }
} 