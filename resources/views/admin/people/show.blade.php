<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Pessoa') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.people.edit', $person) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Editar') }}
                </a>
                <a href="{{ route('admin.people.documents.index', $person) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Documentos') }}
                </a>
                <a href="{{ route('admin.people.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row md:space-x-8">
                        <!-- Informações básicas -->
                        <div class="w-full md:w-1/3 mb-6 md:mb-0">
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <div class="flex items-center mb-6">
                                    @if($person->photo)
                                        <img src="{{ Storage::url($person->photo) }}" alt="{{ $person->name }}" class="w-24 h-24 rounded-full object-cover">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-3xl">{{ substr($person->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h3 class="text-xl font-medium text-gray-900">{{ $person->name }}</h3>
                                        <p class="text-sm text-gray-500">ID: {{ $person->id }}</p>
                                        <div class="mt-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $person->type == 'employee' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $person->type == 'broker' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $person->type == 'owner' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $person->type == 'client' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                {{ $person->type == 'tenant' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            ">
                                                @if($person->type == 'employee')
                                                    Funcionário
                                                @elseif($person->type == 'broker')
                                                    Corretor
                                                @elseif($person->type == 'owner')
                                                    Vendedor/Locador
                                                @elseif($person->type == 'client')
                                                    Comprador
                                                @elseif($person->type == 'tenant')
                                                    Locatário
                                                @else
                                                    {{ $person->type }}
                                                @endif
                                            </span>
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $person->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $person->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @if($person->email)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">E-mail</span>
                                        <span class="block mt-1">{{ $person->email }}</span>
                                    </div>
                                    @endif

                                    @if($person->document)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">{{ $person->document_type == 'cnpj' ? 'CNPJ' : 'CPF' }}</span>
                                        <span class="block mt-1">{{ $person->document }}</span>
                                    </div>
                                    @endif

                                    @if($person->marital_status)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Estado Civil</span>
                                        <span class="block mt-1">
                                            @if($person->marital_status == 'solteiro')
                                                Solteiro(a)
                                            @elseif($person->marital_status == 'casado')
                                                Casado(a)
                                            @elseif($person->marital_status == 'divorciado')
                                                Divorciado(a)
                                            @elseif($person->marital_status == 'viuvo')
                                                Viúvo(a)
                                            @elseif($person->marital_status == 'separado')
                                                Separado(a)
                                            @elseif($person->marital_status == 'uniao_estavel')
                                                União Estável
                                            @else
                                                {{ $person->marital_status }}
                                            @endif
                                        </span>
                                    </div>
                                    @endif

                                    @if($person->phone)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Telefone</span>
                                        <span class="block mt-1">{{ $person->phone }}</span>
                                    </div>
                                    @endif

                                    @if($person->mobile)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">WhatsApp</span>
                                        <span class="block mt-1">{{ $person->mobile }}</span>
                                    </div>
                                    @endif

                                    @if($person->nationality)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Nacionalidade</span>
                                        <span class="block mt-1">{{ $person->nationality }}</span>
                                    </div>
                                    @endif

                                    @if($person->birth_date)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Data de Nascimento</span>
                                        <span class="block mt-1">{{ $person->birth_date->format('d/m/Y') }}</span>
                                    </div>
                                    @endif

                                    @if($person->profession)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Profissão</span>
                                        <span class="block mt-1">{{ $person->profession }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($person->type == 'broker' && $person->commission_rate)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Taxa de Comissão</span>
                                        <span class="block mt-1">{{ number_format($person->commission_rate, 2) }}%</span>
                                    </div>
                                    @endif
                                    
                                    @if(($person->type == 'client' || $person->type == 'tenant') && isset($person->broker))
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Corretor Responsável</span>
                                        <span class="block mt-1">{{ $person->broker->name }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($person->address)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Endereço</span>
                                        <span class="block mt-1">{{ $person->address }}</span>
                                    </div>
                                    @endif

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Cadastrado em</span>
                                        <span class="block mt-1">{{ $person->created_at->format('d/m/Y H:i') }}</span>
                                    </div>

                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Última atualização</span>
                                        <span class="block mt-1">{{ $person->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informações Bancárias -->
                            @if($person->bank_name || $person->bank_agency || $person->bank_account || $person->pix_key)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Bancárias</h3>
                                <div class="space-y-3">
                                    @if($person->bank_name)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Banco</span>
                                        <span class="block mt-1">{{ $person->bank_name }}</span>
                                    </div>
                                    @endif

                                    @if($person->bank_agency)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Agência</span>
                                        <span class="block mt-1">{{ $person->bank_agency }}</span>
                                    </div>
                                    @endif

                                    @if($person->bank_account)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Conta</span>
                                        <span class="block mt-1">{{ $person->bank_account }}</span>
                                    </div>
                                    @endif

                                    @if($person->pix_key)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 block">Chave PIX</span>
                                        <span class="block mt-1">{{ $person->pix_key }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            @if($person->notes)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                                <div class="prose max-w-none">
                                    {{ $person->notes }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Informações relacionadas -->
                        <div class="w-full md:w-2/3">
                            <div class="space-y-6">
                                <!-- Propriedades -->
                                @if($person->type == 'owner' || $person->type == 'broker')
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        {{ $person->type == 'owner' ? 'Propriedades como Dono' : 'Propriedades como Corretor' }}
                                    </h3>
                                    
                                    @if($person->type == 'owner' && $person->properties && $person->properties->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Endereço</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($person->properties as $property)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $property->code }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $property->title }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $property->address }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $property->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                                                    {{ $property->status == 'sold' ? 'bg-blue-100 text-blue-800' : '' }}
                                                                    {{ $property->status == 'rented' ? 'bg-purple-100 text-purple-800' : '' }}
                                                                    {{ $property->status == 'reserved' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ $property->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                                                ">
                                                                    {{ $property->status }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('admin.properties.show', $property) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @elseif($person->type == 'broker' && isset($person->brokerContracts) && $person->brokerContracts->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriedade</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($person->brokerContracts as $contract)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->id }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contract->property->title }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contract->client_name }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contract->type }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $contract->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                                    {{ $contract->status == 'expired' ? 'bg-red-100 text-red-800' : '' }}
                                                                    {{ $contract->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ $contract->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}
                                                                ">
                                                                    {{ $contract->status }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('admin.contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Nenhuma propriedade encontrada.</p>
                                    @endif
                                </div>
                                @endif

                                <!-- Contratos -->
                                @if($person->type == 'client' || $person->type == 'tenant')
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contratos</h3>
                                    
                                    @if($person->contracts && $person->contracts->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriedade</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($person->contracts as $contract)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->id }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contract->property->title }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contract->type }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {{ number_format($contract->value, 2, ',', '.') }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    {{ $contract->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                                    {{ $contract->status == 'expired' ? 'bg-red-100 text-red-800' : '' }}
                                                                    {{ $contract->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                                    {{ $contract->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}
                                                                ">
                                                                    {{ $contract->status }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('admin.contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Nenhum contrato encontrado.</p>
                                    @endif
                                </div>
                                @endif

                                <!-- Comissões (para corretores) -->
                                @if($person->type == 'broker')
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Comissões</h3>
                                    
                                    @if(isset($person->commissions) && $person->commissions->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrato</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentual</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Pagamento</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($person->commissions as $commission)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $commission->id }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commission->contract_id }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">R$ {{ number_format($commission->amount, 2, ',', '.') }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $commission->percentage }}%</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $commission->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ $commission->status == 'paid' ? 'Pago' : 'Pendente' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $commission->payment_date ? $commission->payment_date->format('d/m/Y') : 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">Nenhuma comissão encontrada.</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 