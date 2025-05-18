<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents shared with the client.
     */
    public function index()
    {
        // Buscar pessoa associada ao usuário atual
        $person = Person::where('user_id', Auth::id())->first();
        
        if (!$person) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Não foi encontrado um perfil associado à sua conta.');
        }

        // Obter documentos compartilhados com este cliente
        $documents = $person->documents()
            ->whereNotNull('shared_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.documents.index', compact('documents', 'person'));
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        // Buscar pessoa associada ao usuário atual
        $person = Person::where('user_id', Auth::id())->first();
        
        if (!$person || $document->person_id !== $person->id || $document->shared_at === null) {
            return abort(404);
        }

        return view('client.documents.show', compact('document', 'person'));
    }

    /**
     * Download the specified document.
     */
    public function download(Document $document)
    {
        // Buscar pessoa associada ao usuário atual
        $person = Person::where('user_id', Auth::id())->first();
        
        if (!$person || $document->person_id !== $person->id || $document->shared_at === null) {
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
}
