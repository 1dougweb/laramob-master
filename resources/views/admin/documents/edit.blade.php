<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Documento') }}: {{ $document->title }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.people.documents.show', [$person, $document]) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Ver Detalhes') }}
                </a>
                <a href="{{ route('admin.people.documents.index', $person) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    {{ __('Voltar para Lista') }}
                </a>
            </div>
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

                    <form action="{{ route('admin.people.documents.update', [$person, $document]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Título do Documento -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título do Documento *</label>
                                <input type="text" name="title" id="title" value="{{ old('title') ?? $document->title }}" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-xs text-gray-500">Ex: Contrato de Locação, RG, Matrícula do Imóvel, etc.</p>
                            </div>

                            <!-- Tipo de Documento -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Documento *</label>
                                <select name="type" id="type" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Selecione um tipo</option>
                                    <option value="contract" {{ (old('type') ?? $document->type) == 'contract' ? 'selected' : '' }}>Contrato</option>
                                    <option value="identity" {{ (old('type') ?? $document->type) == 'identity' ? 'selected' : '' }}>Documentos de Identidade</option>
                                    <option value="address_proof" {{ (old('type') ?? $document->type) == 'address_proof' ? 'selected' : '' }}>Comprovante de Endereço</option>
                                    <option value="property" {{ (old('type') ?? $document->type) == 'property' ? 'selected' : '' }}>Documentação de Imóvel</option>
                                    <option value="financial" {{ (old('type') ?? $document->type) == 'financial' ? 'selected' : '' }}>Financeiro</option>
                                    <option value="other" {{ (old('type') ?? $document->type) == 'other' ? 'selected' : '' }}>Outro</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data de Expiração -->
                            <div>
                                <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Expiração</label>
                                <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') ?? ($document->expiration_date ? $document->expiration_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-xs text-gray-500">Deixe em branco se o documento não expira</p>
                            </div>

                            <!-- Arquivo atual -->
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Arquivo atual</label>
                                <div class="mt-1 flex items-center">
                                    <div class="px-4 py-2 bg-gray-100 rounded-md text-sm">
                                        <span class="font-medium">{{ $document->file_name }}</span>
                                        <span class="ml-2 text-gray-500">({{ $document->formatted_size }})</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enviado em {{ $document->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Novo arquivo (opcional) -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Novo arquivo (opcional)</label>
                            <div class="mt-1 flex items-center">
                                <label for="file" class="cursor-pointer border border-gray-300 rounded-md py-2 px-3 bg-white shadow-sm hover:bg-gray-50 text-sm text-gray-700">
                                    Selecionar Arquivo
                                    <input type="file" name="file" id="file" class="sr-only">
                                </label>
                                <span class="ml-3 text-sm text-gray-500" id="file-name">Nenhum arquivo selecionado</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Formatos aceitos: PDF, DOC, DOCX, JPG, JPEG, PNG. Tamanho máximo: 10MB. Deixe em branco para manter o arquivo atual.</p>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') ?? $document->description }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Informações adicionais sobre o documento</p>
                        </div>

                        <!-- Visibilidade -->
                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_private" id="is_private" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ (old('is_private') ?? $document->is_private) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_private" class="font-medium text-gray-700">Documento Privado</label>
                                    <p class="text-gray-500">Marque esta opção para manter o documento privado (visível apenas para administradores)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status do compartilhamento -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <h3 class="text-base font-medium text-gray-900 mb-2">Status do compartilhamento</h3>
                            
                            @if($document->shared_at)
                                <div class="flex items-center mb-3">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-2">
                                        Compartilhado
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        em {{ $document->shared_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Este documento está atualmente compartilhado com o cliente.
                                    <a href="{{ route('admin.people.documents.unshare', [$person, $document]) }}" class="text-red-600 hover:text-red-800 font-medium ml-2">
                                        Remover compartilhamento
                                    </a>
                                </p>
                            @else
                                <div class="flex items-center mb-3">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 mr-2">
                                        Não compartilhado
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Este documento não está compartilhado com o cliente.
                                    <a href="{{ route('admin.people.documents.share', [$person, $document]) }}" class="text-blue-600 hover:text-blue-800 font-medium ml-2">
                                        Compartilhar agora
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Atualizar Documento') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar nome do arquivo selecionado
            document.getElementById('file').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
                document.getElementById('file-name').textContent = fileName;
            });
        });
    </script>
    @endpush
</x-app-layout> 