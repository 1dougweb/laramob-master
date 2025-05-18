<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Person;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\Transaction;
use App\Models\Commission;
use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ConsoleTVs\Charts\Facades\Charts;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is already applied in the routes file
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Count total properties by status
        $propertyStats = [
            'total' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'sold' => Property::where('status', 'sold')->count(),
            'rented' => Property::where('status', 'rented')->count(),
            'reserved' => Property::where('status', 'reserved')->count(),
            'inactive' => Property::where('status', 'inactive')->count(),
        ];

        // Count total people by type
        $peopleStats = [
            'total' => Person::count(),
            'employees' => Person::where('type', 'employee')->count(),
            'clients' => Person::where('type', 'client')->count(),
            'owners' => Person::where('type', 'owner')->count(),
            'tenants' => Person::where('type', 'tenant')->count(),
            'brokers' => Person::where('type', 'broker')->count(),
        ];

        // Get recent contacts
        $recentContacts = Contact::with('property')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get contract stats
        $contractStats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'active')->count(),
            'finished' => Contract::where('status', 'finished')->count(),
            'canceled' => Contract::where('status', 'canceled')->count(),
        ];

        // Get financial stats
        $incomeTotal = Transaction::where('type', 'income')->where('status', 'paid')->sum('amount');
        $expenseTotal = Transaction::where('type', 'expense')->where('status', 'paid')->sum('amount');
        $pendingIncome = Transaction::where('type', 'income')->where('status', 'pending')->sum('amount');
        $pendingExpense = Transaction::where('type', 'expense')->where('status', 'pending')->sum('amount');

        $financialStats = [
            'income' => $incomeTotal,
            'expense' => $expenseTotal,
            'balance' => $incomeTotal - $expenseTotal,
            'pendingIncome' => $pendingIncome,
            'pendingExpense' => $pendingExpense,
        ];

        // Get detailed accounts receivable data (pending income by due date)
        $now = Carbon::now();
        $receivables = [
            'overdue' => Transaction::where('type', 'income')
                                    ->where('status', 'pending')
                                    ->where('date', '<', $now->format('Y-m-d'))
                                    ->sum('amount'),
            'next7days' => Transaction::where('type', 'income')
                                     ->where('status', 'pending')
                                     ->whereBetween('date', [$now->format('Y-m-d'), $now->copy()->addDays(7)->format('Y-m-d')])
                                     ->sum('amount'),
            'next30days' => Transaction::where('type', 'income')
                                      ->where('status', 'pending')
                                      ->whereBetween('date', [$now->copy()->addDays(8)->format('Y-m-d'), $now->copy()->addDays(30)->format('Y-m-d')])
                                      ->sum('amount'),
            'future' => Transaction::where('type', 'income')
                                  ->where('status', 'pending')
                                  ->where('date', '>', $now->copy()->addDays(30)->format('Y-m-d'))
                                  ->sum('amount'),
        ];

        // Get detailed accounts payable data (pending expenses by due date)
        $payables = [
            'overdue' => Transaction::where('type', 'expense')
                                    ->where('status', 'pending')
                                    ->where('date', '<', $now->format('Y-m-d'))
                                    ->sum('amount'),
            'next7days' => Transaction::where('type', 'expense')
                                     ->where('status', 'pending')
                                     ->whereBetween('date', [$now->format('Y-m-d'), $now->copy()->addDays(7)->format('Y-m-d')])
                                     ->sum('amount'),
            'next30days' => Transaction::where('type', 'expense')
                                      ->where('status', 'pending')
                                      ->whereBetween('date', [$now->copy()->addDays(8)->format('Y-m-d'), $now->copy()->addDays(30)->format('Y-m-d')])
                                      ->sum('amount'),
            'future' => Transaction::where('type', 'expense')
                                  ->where('status', 'pending')
                                  ->where('date', '>', $now->copy()->addDays(30)->format('Y-m-d'))
                                  ->sum('amount'),
        ];

        // Get transaction categories stats
        $incomeByCategory = Transaction::where('type', 'income')
                                      ->where('status', 'paid')
                                      ->select('category', DB::raw('SUM(amount) as total'))
                                      ->groupBy('category')
                                      ->get()
                                      ->mapWithKeys(function($item) {
                                          return [$item->category => $item->total];
                                      })
                                      ->toArray();

        $expenseByCategory = Transaction::where('type', 'expense')
                                       ->where('status', 'paid')
                                       ->select('category', DB::raw('SUM(amount) as total'))
                                       ->groupBy('category')
                                       ->get()
                                       ->mapWithKeys(function($item) {
                                          return [$item->category => $item->total];
                                       })
                                       ->toArray();

        // Get commission stats
        $commissionStats = [
            'total' => Commission::count(),
            'pending' => Commission::where('status', 'pending')->count(),
            'paid' => Commission::where('status', 'paid')->count(),
            'totalAmount' => Commission::where('status', 'paid')->sum('amount'),
            'pendingAmount' => Commission::where('status', 'pending')->sum('amount'),
        ];

        // Monthly revenue/expense data (last 6 months)
        $lastSixMonths = collect([]);
        $monthLabels = [];
        $incomeData = [];
        $expenseData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabel = $month->format('M/Y');
            $monthLabels[] = $monthLabel;
            
            $income = Transaction::where('type', 'income')
                ->where('status', 'paid')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
                
            $expense = Transaction::where('type', 'expense')
                ->where('status', 'paid')
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');
                
            $incomeData[] = $income;
            $expenseData[] = $expense;
            
            $lastSixMonths->push([
                'month' => $monthLabel,
                'income' => $income,
                'expense' => $expense,
            ]);
        }
        
        // Property status distribution chart data
        $propertyStatusLabels = ['Disponível', 'Vendido', 'Alugado', 'Reservado', 'Inativo'];
        $propertyStatusData = [
            $propertyStats['available'],
            $propertyStats['sold'],
            $propertyStats['rented'],
            $propertyStats['reserved'],
            $propertyStats['inactive']
        ];
        
        // People type distribution chart data
        $peopleTypeLabels = ['Funcionários', 'Clientes', 'Proprietários', 'Inquilinos', 'Corretores'];
        $peopleTypeData = [
            $peopleStats['employees'],
            $peopleStats['clients'],
            $peopleStats['owners'],
            $peopleStats['tenants'],
            $peopleStats['brokers']
        ];
        
        // Blog activity
        $blogStats = [
            'posts' => BlogPost::count(),
            'published' => BlogPost::where('status', 'published')->count(),
            'draft' => BlogPost::where('status', 'draft')->count(),
            'viewCount' => BlogPost::sum('view_count'),
        ];
        
        // Monthly property listings (last 6 months)
        $propertyListingLabels = [];
        $propertyListingData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $propertyListingLabels[] = $month->format('M/Y');
            
            $propertyCount = Property::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
                
            $propertyListingData[] = $propertyCount;
        }

        return view('admin.dashboard', compact(
            'propertyStats',
            'peopleStats',
            'recentContacts',
            'contractStats',
            'financialStats',
            'receivables',
            'payables',
            'incomeByCategory',
            'expenseByCategory',
            'commissionStats',
            'lastSixMonths',
            'monthLabels',
            'incomeData',
            'expenseData',
            'propertyStatusLabels',
            'propertyStatusData',
            'peopleTypeLabels',
            'peopleTypeData',
            'blogStats',
            'propertyListingLabels',
            'propertyListingData'
        ));
    }
}
