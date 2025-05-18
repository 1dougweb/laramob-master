<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contas a Pagar') }}
            </h2>
            <a href="{{ route('admin.accounts-payable.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>{{ __('Nova Conta a Pagar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-red-100 p-4 rounded-lg">
                        <h3 class="text-red-800 font-bold text-lg">{{ __('Total a Pagar (Pendente)') }}</h3>
                        <p class="text-2xl text-red-600 font-bold">R$ {{ number_format($totalPending, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-200 p-4 rounded-lg">
                        <h3 class="text-red-800 font-bold text-lg">{{ __('Total Vencido') }}</h3>
                        <p class="text-2xl text-red-700 font-bold">R$ {{ number_format($totalOverdue, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <h3 class="text-blue-800 font-bold text-lg">{{ __('Total Pago') }}</h3>
                        <p class="text-2xl text-blue-600 font-bold">R$ {{ number_format($totalPaid, 2, ',', '.') }}</p>
                    </div>
                </div>
                
                <!-- Filter Form -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-gray-700 font-bold mb-2">{{ __('Filtros') }}</h3>
                    <form action="{{ route('admin.accounts-payable.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Todos') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('Pago') }}</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>{{ __('Cancelado') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Data Inicial') }}</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Data Final') }}</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Categoria') }}</label>
                            <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">{{ __('Todas') }}</option>
                                <option value="tax" {{ request('category') == 'tax' ? 'selected' : '' }}>{{ __('Imposto') }}</option>
                                <option value="salary" {{ request('category') == 'salary' ? 'selected' : '' }}>{{ __('Salário') }}</option>
                                <option value="maintenance" {{ request('category') == 'maintenance' ? 'selected' : '' }}>{{ __('Manutenção') }}</option>
                                <option value="utility" {{ request('category') == 'utility' ? 'selected' : '' }}>{{ __('Utilidade') }}</option>
                                <option value="supplier" {{ request('category') == 'supplier' ? 'selected' : '' }}>{{ __('Fornecedor') }}</option>
                                <option value="service" {{ request('category') == 'service' ? 'selected' : '' }}>{{ __('Serviço') }}</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>{{ __('Outros') }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-search mr-2"></i>{{ __('Filtrar') }}
                            </button>
                            <a href="{{ route('admin.accounts-payable.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                <i class="fas fa-times mr-2"></i>{{ __('Limpar') }}
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Payables List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">{{ __('Descrição') }}</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">{{ __('Fornecedor/Pessoa') }}</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">{{ __('Categoria') }}</th>
                                <th class="py-2 px-4 border-b text-right text-sm font-semibold text-gray-700">{{ __('Valor') }}</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-700">{{ __('Vencimento') }}</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-semibold text-gray-700">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($payables as $payable)
                                <tr class="{{ $payable->status == 'pending' && $payable->due_date < now() ? 'bg-red-50' : '' }}">
                                    <td class="py-2 px-4 text-sm">
                                        <div class="font-medium text-gray-900">{{ $payable->description }}</div>
                                        <div class="text-gray-500 text-xs">{{ $payable->document_number }}</div>
                                    </td>
                                    <td class="py-2 px-4 text-sm">
                                        @if($payable->person)
                                            <span class="font-medium text-gray-900">{{ $payable->person->name }}</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payable->category == 'tax' ? 'bg-red-100 text-red-800' : 
                                          ($payable->category == 'salary' ? 'bg-blue-100 text-blue-800' : 
                                          ($payable->category == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                          'bg-gray-100 text-gray-800')) }}">
                                            {{ __($payable->category) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm text-right">
                                        <span class="font-medium text-red-600">R$ {{ number_format($payable->amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td class="py-2 px-4 text-sm text-center">
                                        <span class="{{ $payable->status == 'pending' && $payable->due_date < now() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                            {{ $payable->due_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $payable->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                          ($payable->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                          'bg-red-100 text-red-800') }}">
                                            {{ __($payable->status == 'paid' ? 'Pago' : ($payable->status == 'pending' ? 'Pendente' : 'Cancelado')) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-sm text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($payable->status == 'pending')
                                                <button onclick="showPaymentModal({{ $payable->id }})" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.accounts-payable.show', $payable->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.accounts-payable.edit', $payable->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.accounts-payable.destroy', $payable->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta conta a pagar?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 px-4 text-center text-gray-500">
                                        {{ __('Nenhuma conta a pagar encontrada.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $payables->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center" style="z-index: 50;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Registrar Pagamento') }}</h3>
                <button onclick="hidePaymentModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="paymentForm" action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Data do Pagamento') }}</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ __('Valor Pago') }}</label>
                    <input type="number" name="amount_paid" step="0.01" min="0.01" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="hidePaymentModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Confirmar Pagamento') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function showPaymentModal(id) {
            document.getElementById('paymentForm').action = "{{ route('admin.accounts-payable.register-payment', ':id') }}".replace(':id', id);
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }
        
        function hidePaymentModal() {
            document.getElementById('paymentModal').classList.remove('flex');
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout> 