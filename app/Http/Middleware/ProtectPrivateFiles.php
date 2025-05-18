<?php

namespace App\Http\Middleware;

use App\Models\Document;
use App\Models\Person;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProtectPrivateFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // O caminho do arquivo deve seguir o padrão: /documents/{person_id}/{file_uuid}.ext
        $path = $request->path();
        
        // Verificar se o caminho começa com private/documents/
        if (!str_starts_with($path, 'private/documents/')) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        // Extrair person_id do caminho
        $parts = explode('/', $path);
        if (count($parts) < 3) {
            return response()->json(['error' => 'Caminho inválido'], 400);
        }
        
        $personId = (int) $parts[2];
        
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Administradores têm acesso a todos os documentos
        if ($user->hasRole('admin')) {
            return $next($request);
        }
        
        // Verificar se o usuário é o dono do documento
        $person = Person::where('user_id', $user->id)->first();
        
        if (!$person) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        // O usuário tem acesso aos seus próprios documentos
        if ($person->id === $personId) {
            // Para arquivos privados, verificar se o documento está compartilhado
            $fileName = basename($path);
            $document = Document::where('file_path', 'like', "%{$fileName}")
                ->where('person_id', $personId)
                ->first();
            
            if ($document && ($document->shared_at || !$document->is_private)) {
                return $next($request);
            }
        }
        
        return response()->json(['error' => 'Acesso negado'], 403);
    }
}
