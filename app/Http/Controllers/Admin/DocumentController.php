<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents for a person.
     */
    public function index(Person $person)
    {
        $documents = $person->documents()->orderBy('created_at', 'desc')->get();
        return view('admin.documents.index', compact('person', 'documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Person $person)
    {
        return view('admin.documents.create', compact('person'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request, Person $person)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:contract,identity,address_proof,property,financial,other',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB máximo
            'expiration_date' => 'nullable|date',
            'is_private' => 'boolean',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        // Gera um nome de arquivo único para evitar colisões
        $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Armazena o arquivo no diretório de documentos com o ID da pessoa como subdiretório
        $filePath = $file->storeAs('documents/' . $person->id, $uniqueName, 'private');

        $document = $person->documents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'expiration_date' => $request->expiration_date,
            'is_private' => $request->has('is_private'),
        ]);

        return redirect()->route('admin.people.documents.index', $person)
            ->with('success', 'Documento adicionado com sucesso.');
    }

    /**
     * Display the specified document.
     */
    public function show(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        return view('admin.documents.show', compact('person', 'document'));
    }

    /**
     * Download the specified document.
     */
    public function download(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        if (!Storage::disk('private')->exists($document->file_path)) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        return Storage::disk('private')->download(
            $document->file_path, 
            $document->file_name
        );
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        return view('admin.documents.edit', compact('person', 'document'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:contract,identity,address_proof,property,financial,other',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB máximo
            'expiration_date' => 'nullable|date',
            'is_private' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'expiration_date' => $request->expiration_date,
            'is_private' => $request->has('is_private'),
        ];

        // Atualiza o arquivo se um novo for enviado
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();

            // Remove o arquivo antigo
            if (Storage::disk('private')->exists($document->file_path)) {
                Storage::disk('private')->delete($document->file_path);
            }

            // Gera um nome de arquivo único para evitar colisões
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Armazena o novo arquivo
            $filePath = $file->storeAs('documents/' . $person->id, $uniqueName, 'private');

            // Adiciona os dados do novo arquivo
            $data['file_path'] = $filePath;
            $data['file_name'] = $fileName;
            $data['file_type'] = $fileType;
            $data['file_size'] = $fileSize;
        }

        $document->update($data);

        return redirect()->route('admin.people.documents.index', $person)
            ->with('success', 'Documento atualizado com sucesso.');
    }

    /**
     * Share the document with the client by setting shared_at.
     */
    public function share(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        $document->update([
            'shared_at' => now(),
        ]);

        return back()->with('success', 'Documento compartilhado com o cliente.');
    }

    /**
     * Unshare the document by setting shared_at to null.
     */
    public function unshare(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        $document->update([
            'shared_at' => null,
        ]);

        return back()->with('success', 'Compartilhamento do documento removido.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Person $person, Document $document)
    {
        // Verifica se o documento pertence à pessoa
        if ($document->person_id !== $person->id) {
            return abort(404);
        }

        // Remove o arquivo físico
        if (Storage::disk('private')->exists($document->file_path)) {
            Storage::disk('private')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Documento excluído com sucesso.');
    }
}
