<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Contrato') }}: #{{ $contract->id }}
            </h2>
            <a href="{{ route('admin.contracts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
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

                    <form action="{{ route('admin.contracts.update', $contract) }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Informações básicas -->
                            
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Informações Básicas</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Propriedade -->
                                    <div>
                                        <label for="property_id" class="block text-sm font-medium text-gray-700 mb-1">Propriedade</label>
                                        <select id="property_id" name="property_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="">Selecione uma propriedade</option>
                                            @foreach($properties as $property)
                                                <option value="{{ $property->id }}" {{ (old('property_id') ?? $contract->property_id) == $property->id ? 'selected' : '' }}>
                                                    {{ $property->title }} - {{ $property->address }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Cliente -->
                                    <div>
                                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Cliente</label>
                                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name') ?? $contract->client_name }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                            <!-- Detalhes do contrato -->
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Detalhes do Contrato</h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Tipo de Contrato -->
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Contrato</label>
                                        <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="">Selecione o tipo</option>
                                            <option value="sale" {{ (old('type') ?? $contract->type) == 'sale' ? 'selected' : '' }}>Venda</option>
                                            <option value="rental" {{ (old('type') ?? $contract->type) == 'rental' ? 'selected' : '' }}>Aluguel</option>
                                            <option value="lease" {{ (old('type') ?? $contract->type) == 'lease' ? 'selected' : '' }}>Arrendamento</option>
                                        </select>
                                    </div>

                                    <!-- Valor -->
                                    <div>
                                        <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                                        <input type="text" name="value" id="value" value="{{ old('value') ?? number_format($contract->value, 2, ',', '.') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="0,00">
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="pending" {{ (old('status') ?? $contract->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                                            <option value="active" {{ (old('status') ?? $contract->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                            <option value="expired" {{ (old('status') ?? $contract->status) == 'expired' ? 'selected' : '' }}>Expirado</option>
                                            <option value="cancelled" {{ (old('status') ?? $contract->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                    <!-- Data Início -->
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') ?? $contract->start_date->format('Y-m-d') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <!-- Data Fim -->
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Fim (opcional para venda)</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') ?? ($contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <!-- Upload de Documento -->
                                <div class="mt-6">
                                    <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">Documento do Contrato</label>
                                    
                                    <div class="mb-3" x-data="{ fileName: '{{ $contract->document_file ? basename($contract->document_file) : '' }}', previewUrl: '{{ $contract->document_file ? 'exists' : '' }}' }">
                                        @if($contract->document_file)
                                            <div class="mb-3 flex items-center">
                                                <span class="mr-2">Documento atual:</span>
                                                <a href="{{ Storage::url($contract->document_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span>Ver documento</span>
                                                </a>
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center justify-center w-full">
                                            <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6" 
                                                     x-show="!previewUrl">
                                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <p class="text-sm text-gray-500" x-text="fileName || '{{ __('Clique para selecionar ou arraste um documento') }}'"></p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ __('PDF, DOC, DOCX, JPG, JPEG, PNG (máx. 10MB)') }}</p>
                                                </div>
                                                <div class="h-full w-full flex items-center justify-center" x-show="previewUrl">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <p class="text-sm font-medium text-blue-600" x-text="fileName ? (fileName.length > 30 ? fileName.substring(0, 27) + '...' : fileName) : 'Documento pronto para upload'"></p>
                                                    </div>
                                                </div>
                                                <input type="file" name="document_file" id="document_file" 
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden"
                                                    @change="
                                                        const file = $event.target.files[0];
                                                        if (file) {
                                                            const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/jpg'];
                                                            if (!validTypes.includes(file.type)) {
                                                                alert('{{ __('O arquivo selecionado não é um tipo suportado. Use PDF, DOC, DOCX, JPG, JPEG ou PNG.') }}');
                                                                $event.target.value = '';
                                                                return;
                                                            }
                                                            if (file.size > 10 * 1024 * 1024) {
                                                                alert('{{ __('O arquivo excede o tamanho máximo de 10MB.') }}');
                                                                $event.target.value = '';
                                                                return;
                                                            }
                                                            fileName = file.name;
                                                            previewUrl = 'preview';
                                                        }
                                                    " x-ref="fileInput">
                                            </label>
                                        </div>
                                        <div class="text-xs text-center mt-2" x-show="fileName && previewUrl !== 'exists'">
                                            <span class="text-gray-600">{{ __('Novo arquivo selecionado:') }}</span>
                                            <span class="font-medium text-blue-600" x-text="fileName"></span>
                                            <button type="button" @click="fileName = ''; previewUrl = '{{ $contract->document_file ? 'exists' : '' }}'; $refs.fileInput.value = ''" 
                                                    class="ml-2 text-red-500 hover:text-red-700">
                                                <span>{{ __('Remover') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-100 p-3 rounded border border-gray-200 flex items-center mt-4" x-show="fileName && previewUrl !== 'exists'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900" x-text="fileName"></span>
                                    </div>
                                    
                                    <div class="bg-gray-100 p-3 rounded border border-gray-200 flex items-center mt-4" x-show="previewUrl === 'exists' && fileName">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900" x-text="fileName"></span>
                                        <a href="{{ Storage::url($contract->document_file) }}" target="_blank" class="ml-3 text-blue-600 hover:text-blue-800 flex items-center text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Visualizar</span>
                                        </a>
                                    </div>
                                    
                                    @error('document_file')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-2">{{ __('Deixe em branco para manter o documento atual.') }}</p>
                                </div>

                            <!-- Observações -->
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Informações Adicionais</h3>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                                    <textarea name="notes" id="notes" rows="5" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('notes') ?? $contract->notes }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Atualizar Contrato') }}
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</x-app-layout> 