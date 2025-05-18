<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Adicionar Pessoa') }}
            </h2>
            <a href="{{ route('admin.people.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Lista') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.people.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Tipo de Pessoa -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Tipo de Pessoa</h3>
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                    <div class="flex items-center space-x-2 p-3 rounded-lg border-2 {{ old('type') == 'employee' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" id="type_employee" name="type" value="employee" {{ old('type') == 'employee' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                        <label for="type_employee" class="text-sm font-medium text-gray-700">Funcionário</label>
                                    </div>
                                    <div class="flex items-center space-x-2 p-3 rounded-lg border-2 {{ old('type') == 'broker' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" id="type_broker" name="type" value="broker" {{ old('type') == 'broker' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                        <label for="type_broker" class="text-sm font-medium text-gray-700">Corretor</label>
                                    </div>
                                    <div class="flex items-center space-x-2 p-3 rounded-lg border-2 {{ old('type') == 'owner' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" id="type_owner" name="type" value="owner" {{ old('type') == 'owner' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                        <label for="type_owner" class="text-sm font-medium text-gray-700">Vendedor/Locador</label>
                                    </div>
                                    <div class="flex items-center space-x-2 p-3 rounded-lg border-2 {{ old('type') == 'client' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" id="type_client" name="type" value="client" {{ old('type') == 'client' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                        <label for="type_client" class="text-sm font-medium text-gray-700">Comprador</label>
                                    </div>
                                    <div class="flex items-center space-x-2 p-3 rounded-lg border-2 {{ old('type') == 'tenant' ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                        <input type="radio" id="type_tenant" name="type" value="tenant" {{ old('type') == 'tenant' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                        <label for="type_tenant" class="text-sm font-medium text-gray-700">Locatário</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Grupo para todos os tipos de pessoa -->
                            <div id="all_types_fields" class="mt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Gerais</h3>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Nome Completo -->
                                    <div>
                                        <x-input-label for="name" :value="__('Nome Completo')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                    <!-- Tipo de Documento -->
                                    <div>
                                        <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento</label>
                                        <select name="document_type" id="document_type" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option value="cpf" {{ old('document_type') == 'cpf' ? 'selected' : '' }}>CPF</option>
                                            <option value="cnpj" {{ old('document_type') == 'cnpj' ? 'selected' : '' }}>CNPJ</option>
                                        </select>
                                    </div>

                                    <!-- Documento -->
                                    <div>
                                        <label id="document_label" for="document" class="block text-sm font-medium text-gray-700 mb-1">{{ old('document_type', 'cpf') == 'cpf' ? 'CPF' : 'CNPJ' }}</label>
                                        <input type="text" name="document" id="document" value="{{ old('document') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                    <!-- Telefone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md phone-mask">
                                    </div>

                                    <!-- WhatsApp -->
                                    <div>
                                        <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <div class="relative flex items-stretch flex-grow">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4 h-4 text-gray-400">
                                                        <path fill="currentColor" d="M224 122.8c-72.7 0-131.8 59.1-131.9 131.8 0 24.9 7 49.2 20.2 70.1l3.1 5-13.3 48.6 49.9-13.1 4.8 2.9c20.2 12 43.4 18.4 67.1 18.4h.1c72.6 0 133.3-59.1 133.3-131.8 0-35.2-15.2-68.3-40.1-93.2-25-25-58-38.7-93.2-38.7zm77.5 188.4c-3.3 9.3-19.1 17.7-26.7 18.8-12.6 1.9-22.4.9-47.5-9.9-39.7-17.2-65.7-57.2-67.7-59.8-2-2.6-16.2-21.5-16.2-41s10.2-29.1 13.9-33.1c3.6-4 7.9-5 10.6-5 2.6 0 5.3 0 7.6.1 2.4.1 5.7-.9 8.9 6.8 3.3 7.9 11.2 27.4 12.2 29.4s1.7 4.3.3 6.9c-7.6 15.2-15.7 14.6-11.6 21.6 15.3 26.3 30.6 35.4 53.9 47.1 4 2 6.3 1.7 8.6-1 2.3-2.6 9.9-11.6 12.5-15.5 2.6-4 5.3-3.3 8.9-2 3.6 1.3 23.1 10.9 27.1 12.9s6.6 3 7.6 4.6c.9 1.9.9 9.9-2.4 19.1zM400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM224 456c-123.7 0-224-100.3-224-224S100.3 8 224 8s224 100.3 224 224-100.3 224-224 224z"/>
                                                    </svg>
                                                </div>
                                                <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md phone-mask" placeholder="(00) 00000-0000">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                    <!-- Nacionalidade e Data de Nascimento -->
                                    <div>
                                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">Nacionalidade</label>
                                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', 'Brasileira') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    
                                    <div>
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Estado Civil e Profissão -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                                        <select name="marital_status" id="marital_status" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option value="">Selecione</option>
                                            <option value="solteiro" {{ old('marital_status') == 'solteiro' ? 'selected' : '' }}>Solteiro(a)</option>
                                            <option value="casado" {{ old('marital_status') == 'casado' ? 'selected' : '' }}>Casado(a)</option>
                                            <option value="divorciado" {{ old('marital_status') == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                            <option value="viuvo" {{ old('marital_status') == 'viuvo' ? 'selected' : '' }}>Viúvo(a)</option>
                                            <option value="separado" {{ old('marital_status') == 'separado' ? 'selected' : '' }}>Separado(a)</option>
                                            <option value="uniao_estavel" {{ old('marital_status') == 'uniao_estavel' ? 'selected' : '' }}>União Estável</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="profession" class="block text-sm font-medium text-gray-700 mb-1">Profissão</label>
                                        <input type="text" name="profession" id="profession" value="{{ old('profession') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                
                                <!-- Foto -->
                                <div class="mt-6">
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                                    <div class="mt-1 flex items-center">
                                        <span class="inline-block h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                        <label for="photo" class="ml-5 cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                            Selecionar Foto
                                            <input type="file" name="photo" id="photo" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="ml-3 text-xs text-gray-500" id="photo-name">Nenhum arquivo selecionado</p>
                                    </div>
                                </div>
                                
                                <!-- Status -->
                                <div class="mt-6">
                                    <x-input-label for="is_active" :value="__('Status')" />
                                    <div class="mt-2">
                                        <label class="inline-flex items-center">
                                            <x-checkbox id="is_active" name="is_active" value="1" :checked="old('is_active', '1') == '1'" />
                                            <span class="ml-2 text-sm text-gray-600">Ativo</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Campos específicos para CORRETOR -->
                            <div id="broker_specific_fields" class="mt-6 hidden border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Corretor</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Taxa de Comissão -->
                                    <div>
                                        <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1">Taxa de Comissão (%)</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="number" step="0.01" min="0" max="100" name="commission_rate" id="commission_rate" value="{{ old('commission_rate') }}" 
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">%</span>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Comissão padrão para os contratos deste corretor</p>
                                    </div>
                                    
                                    <!-- CRECI -->
                                    <div>
                                        <label for="creci" class="block text-sm font-medium text-gray-700 mb-1">CRECI</label>
                                        <input type="text" name="creci" id="creci" value="{{ old('creci') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Campos específicos para CLIENTE/LOCATÁRIO -->
                            <div id="client_tenant_specific_fields" class="mt-6 hidden border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Cliente</h3>
                                
                                <!-- Corretor responsável -->
                                <div>
                                    <label for="broker_id" class="block text-sm font-medium text-gray-700 mb-1">Corretor Responsável</label>
                                    <select name="broker_id" id="broker_id" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Selecione um corretor</option>
                                        @foreach(\App\Models\Person::where('type', 'broker')->where('is_active', true)->orderBy('name')->get() as $broker)
                                            <option value="{{ $broker->id }}" {{ old('broker_id') == $broker->id ? 'selected' : '' }}>
                                                {{ $broker->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Preferências (para locatários/compradores) -->
                                <div class="mt-4">
                                    <label for="preferences" class="block text-sm font-medium text-gray-700 mb-1">Preferências de Imóveis</label>
                                    <textarea name="preferences" id="preferences" rows="3" 
                                        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Ex: Apartamento de 2 quartos, região central, até R$ 300.000">{{ old('preferences') }}</textarea>
                                </div>
                            </div>

                            <!-- Informações Bancárias -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Bancárias</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Banco -->
                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <!-- Agência -->
                                    <div>
                                        <label for="bank_agency" class="block text-sm font-medium text-gray-700 mb-1">Agência</label>
                                        <input type="text" name="bank_agency" id="bank_agency" value="{{ old('bank_agency') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md agency-mask">
                                    </div>

                                    <!-- Conta -->
                                    <div>
                                        <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-1">Conta</label>
                                        <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md account-mask">
                                    </div>
                                </div>

                                <!-- Chave PIX -->
                                <div class="mt-6">
                                    <label for="pix_key" class="block text-sm font-medium text-gray-700 mb-1">Chave PIX</label>
                                    <input type="text" name="pix_key" id="pix_key" value="{{ old('pix_key') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <p class="mt-1 text-xs text-gray-500">Pode ser CPF, CNPJ, e-mail, telefone ou chave aleatória</p>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Endereço</h3>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Endereço -->
                                    <div>
                                        <x-input-label for="address" :value="__('Endereço Completo')" />
                                        <x-textarea id="address" name="address" class="mt-1 block w-full" rows="3">{{ old('address') }}</x-textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações (Máx 500 caracteres)</label>
                                    <textarea name="notes" id="notes" rows="4" maxlength="500" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between items-center">
                                        <span><span id="char-count">0</span>/500 caracteres</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Salvar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/imask@6.4.3/dist/imask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script carregado');
            // Mascaras para telefone
            document.querySelectorAll('.phone-mask').forEach(function(el) {
                IMask(el, {
                    mask: '(00) 00000-0000'
                });
            });

            // Máscara para agência bancária
            document.querySelectorAll('.agency-mask').forEach(function(el) {
                IMask(el, {
                    mask: '0000-0'
                });
            });

            // Máscara para conta bancária
            document.querySelectorAll('.account-mask').forEach(function(el) {
                IMask(el, {
                    mask: '00000000-0'
                });
            });

            // Máscara CPF/CNPJ - abordagem simplificada
            const documentInput = document.getElementById('document');
            const documentTypeSelect = document.getElementById('document_type');
            const documentLabel = document.getElementById('document_label');
            let maskCPF, maskCNPJ;
            
            // Cria as duas máscaras
            maskCPF = IMask(documentInput, {
                mask: '000.000.000-00'
            });
            
            // Função para trocar as máscaras
            function updateMask() {
                let type = documentTypeSelect.value;
                
                if (type === 'cpf') {
                    documentLabel.textContent = 'CPF';
                    maskCPF.updateOptions({
                        mask: '000.000.000-00'
                    });
                    maskCPF.enable();
                } else {
                    documentLabel.textContent = 'CNPJ';
                    maskCPF.updateOptions({
                        mask: '00.000.000/0000-00'
                    });
                    maskCPF.enable();
                }
            }
            
            // Inicializa a máscara correta
            updateMask();
            
            // Listener para quando mudar o tipo
            documentTypeSelect.addEventListener('change', updateMask);

            // Contador de caracteres para observações
            const notesField = document.getElementById('notes');
            const charCount = document.getElementById('char-count');
            
            function updateCharCount() {
                charCount.textContent = notesField.value.length;
            }
            
            notesField.addEventListener('input', updateCharCount);
            updateCharCount(); // Inicializar contador

            // Mostrar nome do arquivo selecionado
            document.getElementById('photo').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
                document.getElementById('photo-name').textContent = fileName;
            });

            // Controle de exibição de campos específicos por tipo de pessoa
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const brokerFields = document.getElementById('broker_specific_fields');
            const clientTenantFields = document.getElementById('client_tenant_specific_fields');
            
            // Estilizar seleção dos tipos de pessoa
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Primeiro, remove a estilização de todos
                    document.querySelectorAll('input[name="type"]').forEach(r => {
                        r.closest('div').classList.remove('border-blue-500', 'bg-blue-50');
                        r.closest('div').classList.add('border-gray-200');
                    });
                    
                    // Adiciona estilização ao selecionado
                    this.closest('div').classList.remove('border-gray-200');
                    this.closest('div').classList.add('border-blue-500', 'bg-blue-50');
                    
                    // Controla campos específicos
                    const selectedType = this.value;
                    
                    // Taxa de comissão é relevante apenas para corretores
                    if (selectedType === 'broker') {
                        brokerFields.classList.remove('hidden');
                    } else {
                        brokerFields.classList.add('hidden');
                    }
                    
                    // Seleção de corretor é relevante apenas para clientes e locatários
                    if (selectedType === 'client' || selectedType === 'tenant') {
                        clientTenantFields.classList.remove('hidden');
                    } else {
                        clientTenantFields.classList.add('hidden');
                    }
                });
            });
            
            // Verificar inicialmente para estilizar o tipo selecionado
            const selectedTypeRadio = document.querySelector('input[name="type"]:checked');
            if (selectedTypeRadio) {
                selectedTypeRadio.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @endpush
</x-app-layout> 