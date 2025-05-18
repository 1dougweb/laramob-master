@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stat-card {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .chart-container {
        position: relative;
        height: 350px;
        margin-bottom: 20px;
    }
    
    .mini-chart-container {
        position: relative;
        height: 200px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 600;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6c757d;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    
    .bg-gradient-success {
        background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
    }
    
    .bg-gradient-danger {
        background: linear-gradient(87deg, #f5365c 0, #f56036 100%) !important;
    }
    
    .bg-gradient-warning {
        background: linear-gradient(87deg, #fb6340 0, #fbb140 100%) !important;
    }
    
    .bg-gradient-info {
        background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-2xl font-bold">Painel Administrativo</h1>
    
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">
        <div class="stat-card bg-gradient-primary text-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="stat-label text-white">Imóveis</h2>
                    <p class="stat-value">{{ $propertyStats['total'] }}</p>
                </div>
                <div class="text-3xl">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-white">{{ $propertyStats['available'] }} disponíveis</span>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-success text-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="stat-label">Clientes</h2>
                    <p class="stat-value">{{ $peopleStats['total'] }}</p>
                </div>
                <div class="text-3xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-white">{{ $peopleStats['clients'] }} clientes</span>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-info text-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="stat-label">Receitas</h2>
                    <p class="stat-value">R$ {{ number_format($financialStats['income'], 2, ',', '.') }}</p>
                </div>
                <div class="text-3xl">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-white">Balanço: R$ {{ number_format($financialStats['balance'], 2, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="stat-card bg-gradient-warning text-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="stat-label">Contratos</h2>
                    <p class="stat-value">{{ $contractStats['total'] }}</p>
                </div>
                <div class="text-3xl">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-white">{{ $contractStats['active'] }} ativos</span>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Receitas x Despesas (6 meses)</h2>
            <div class="chart-container">
                <canvas id="revenueExpenseChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Distribuição de Imóveis</h2>
            <div class="chart-container">
                <canvas id="propertyStatusChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Second Charts Row -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Tipos de Contatos</h2>
            <div class="chart-container">
                <canvas id="peopleTypeChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Novos Imóveis Cadastrados (6 meses)</h2>
            <div class="chart-container">
                <canvas id="propertyListingChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Financial Details Row -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Contas a Receber</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-red-100 rounded-lg">
                    <p class="text-sm text-gray-500">Vencidas</p>
                    <p class="text-xl font-bold">R$ {{ number_format($receivables['overdue'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <p class="text-sm text-gray-500">Próximos 7 dias</p>
                    <p class="text-xl font-bold">R$ {{ number_format($receivables['next7days'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <p class="text-sm text-gray-500">Próximos 30 dias</p>
                    <p class="text-xl font-bold">R$ {{ number_format($receivables['next30days'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <p class="text-sm text-gray-500">Futuro</p>
                    <p class="text-xl font-bold">R$ {{ number_format($receivables['future'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Contas a Pagar</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-red-100 rounded-lg">
                    <p class="text-sm text-gray-500">Vencidas</p>
                    <p class="text-xl font-bold">R$ {{ number_format($payables['overdue'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <p class="text-sm text-gray-500">Próximos 7 dias</p>
                    <p class="text-xl font-bold">R$ {{ number_format($payables['next7days'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <p class="text-sm text-gray-500">Próximos 30 dias</p>
                    <p class="text-xl font-bold">R$ {{ number_format($payables['next30days'], 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <p class="text-sm text-gray-500">Futuro</p>
                    <p class="text-xl font-bold">R$ {{ number_format($payables['future'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity and Blog Stats -->
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        <div class="bg-white p-4 rounded-lg shadow lg:col-span-2">
            <h2 class="font-bold text-lg mb-4">Contatos Recentes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imóvel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentContacts as $contact)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                                <div class="text-sm text-gray-500">{{ $contact->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $contact->property->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $contact->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $contact->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contact->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $contact->status == 'pending' ? 'Pendente' : 'Respondido' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-bold text-lg mb-4">Blog</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <p class="text-sm text-gray-500">Total de Posts</p>
                    <p class="text-xl font-bold">{{ $blogStats['posts'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <p class="text-sm text-gray-500">Publicados</p>
                    <p class="text-xl font-bold">{{ $blogStats['published'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <p class="text-sm text-gray-500">Rascunhos</p>
                    <p class="text-xl font-bold">{{ $blogStats['draft'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <p class="text-sm text-gray-500">Visualizações</p>
                    <p class="text-xl font-bold">{{ $blogStats['viewCount'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Revenue vs Expense Chart
    var revenueExpenseCtx = document.getElementById('revenueExpenseChart').getContext('2d');
    var revenueExpenseChart = new Chart(revenueExpenseCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [
                {
                    label: 'Receitas',
                    data: {!! json_encode($incomeData) !!},
                    backgroundColor: 'rgba(45, 206, 137, 0.6)',
                    borderColor: 'rgba(45, 206, 137, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Despesas',
                    data: {!! json_encode($expenseData) !!},
                    backgroundColor: 'rgba(245, 54, 92, 0.6)',
                    borderColor: 'rgba(245, 54, 92, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
    
    // Property Status Chart
    var propertyStatusCtx = document.getElementById('propertyStatusChart').getContext('2d');
    var propertyStatusChart = new Chart(propertyStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($propertyStatusLabels) !!},
            datasets: [{
                data: {!! json_encode($propertyStatusData) !!},
                backgroundColor: [
                    'rgba(45, 206, 137, 0.6)',
                    'rgba(94, 114, 228, 0.6)',
                    'rgba(251, 99, 64, 0.6)',
                    'rgba(251, 177, 64, 0.6)',
                    'rgba(136, 136, 136, 0.6)'
                ],
                borderColor: [
                    'rgba(45, 206, 137, 1)',
                    'rgba(94, 114, 228, 1)',
                    'rgba(251, 99, 64, 1)',
                    'rgba(251, 177, 64, 1)',
                    'rgba(136, 136, 136, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    
    // People Type Chart
    var peopleTypeCtx = document.getElementById('peopleTypeChart').getContext('2d');
    var peopleTypeChart = new Chart(peopleTypeCtx, {
        type: 'polarArea',
        data: {
            labels: {!! json_encode($peopleTypeLabels) !!},
            datasets: [{
                data: {!! json_encode($peopleTypeData) !!},
                backgroundColor: [
                    'rgba(17, 205, 239, 0.6)',
                    'rgba(45, 206, 137, 0.6)',
                    'rgba(251, 99, 64, 0.6)',
                    'rgba(94, 114, 228, 0.6)',
                    'rgba(251, 177, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(17, 205, 239, 1)',
                    'rgba(45, 206, 137, 1)',
                    'rgba(251, 99, 64, 1)',
                    'rgba(94, 114, 228, 1)',
                    'rgba(251, 177, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    
    // Property Listing Chart
    var propertyListingCtx = document.getElementById('propertyListingChart').getContext('2d');
    var propertyListingChart = new Chart(propertyListingCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($propertyListingLabels) !!},
            datasets: [{
                label: 'Novos Imóveis',
                data: {!! json_encode($propertyListingData) !!},
                backgroundColor: 'rgba(94, 114, 228, 0.2)',
                borderColor: 'rgba(94, 114, 228, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endsection 