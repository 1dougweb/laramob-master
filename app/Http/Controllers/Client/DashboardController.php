<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Property;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the client dashboard.
     */
    public function index()
    {
        $person = Person::where('user_id', Auth::id())->first();
        
        // Verifica se existe uma pessoa vinculada ao usuário
        if (!$person) {
            return view('client.dashboard.index')->with('warning', 'Seu perfil ainda não está configurado completamente.');
        }

        // Inicializa todas as variáveis com valores padrão
        $todoTasksCount = 0;
        $inProgressTasksCount = 0;
        $pendingTasksCount = 0;
        $overdueTasksCount = 0;
        $todayMeetingsCount = 0;
        $nextMeeting = null;
        $clientsCount = 0;
        $activeClientsCount = 0;
        $ownerClientsCount = 0;
        
        try {
            // Tasks data
            $todoTasksCount = (int)Task::where('assigned_to', $person->id)
                ->where('status', 'todo')
                ->whereNull('completed_at')
                ->count();
                
            $inProgressTasksCount = (int)Task::where('assigned_to', $person->id)
                ->where('status', 'in_progress')
                ->whereNull('completed_at')
                ->count();
                
            $pendingTasksCount = $todoTasksCount + $inProgressTasksCount;
            
            $overdueTasksCount = (int)Task::where('assigned_to', $person->id)
                ->whereIn('status', ['todo', 'in_progress'])
                ->whereNull('completed_at')
                ->whereDate('due_date', '<', now())
                ->count();
                
            // Log para debugging
            Log::info('Dashboard task data', [
                'person_id' => $person->id,
                'todoTasksCount' => $todoTasksCount,
                'inProgressTasksCount' => $inProgressTasksCount,
                'pendingTasksCount' => $pendingTasksCount,
                'overdueTasksCount' => $overdueTasksCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar tarefas para dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Total de documentos compartilhados
        $documentCount = $person->documents()->whereNotNull('shared_at')->count();
        
        // Documentos recentes (últimos 5)
        $recentDocuments = $person->documents()
            ->whereNotNull('shared_at')
            ->orderBy('shared_at', 'desc')
            ->take(5)
            ->get();
        
        // Próximos documentos a expirar
        $expiringDocuments = $person->documents()
            ->whereNotNull('shared_at')
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>', now())
            ->orderBy('expiration_date', 'asc')
            ->take(3)
            ->get();
            
        // Imóveis favoritos (últimos 3)
        $favoriteProperties = $person->favoriteProperties()
            ->with(['city', 'district'])
            ->take(3)
            ->get();
            
        // Imóveis em destaque
        $featuredProperties = Property::with(['city', 'district'])
            ->where('is_active', true)
            ->where('status', 'available')
            ->where('is_featured', true)
            ->take(3)
            ->get();
            
        try {
            if ($person->type === 'broker') {
                // Count meetings if the person is a broker
                $todayMeetingsCount = (int)$person->meetings()
                    ->whereDate('scheduled_at', Carbon::today())
                    ->count();
                    
                $nextMeeting = $person->meetings()
                    ->whereDate('scheduled_at', Carbon::today())
                    ->whereTime('scheduled_at', '>=', Carbon::now())
                    ->orderBy('scheduled_at', 'asc')
                    ->first();
                    
                // Count clients if the person is a broker
                $clientsCount = (int)Person::where('broker_id', $person->id)->count();
                $activeClientsCount = (int)Person::where('broker_id', $person->id)
                    ->where('type', 'buyer')
                    ->count();
                $ownerClientsCount = (int)Person::where('broker_id', $person->id)
                    ->where('type', 'owner')
                    ->count();
            }
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados de reuniões/clientes para dashboard', [
                'error' => $e->getMessage()
            ]);
        }
            
        return view('client.dashboard.index', compact(
            'person', 
            'documentCount', 
            'recentDocuments', 
            'expiringDocuments',
            'favoriteProperties',
            'featuredProperties',
            'pendingTasksCount',
            'todoTasksCount',
            'inProgressTasksCount',
            'overdueTasksCount',
            'todayMeetingsCount',
            'nextMeeting',
            'clientsCount',
            'activeClientsCount',
            'ownerClientsCount'
        ));
    }
}
