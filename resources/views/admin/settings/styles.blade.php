<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configurações de Estilo') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex text-sm text-gray-500 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-500">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Configurações de Estilo</span>
            </div>

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

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="style-settings-form" action="{{ route('admin.settings.save') }}" method="POST" class="space-y-8" enctype="multipart/form-data" data-ajax="true">
                        @csrf
                        <input type="hidden" name="group" value="style">

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Left Column: Color Settings -->
                            <div class="lg:col-span-2 space-y-6">
                                <!-- Color Scheme Section -->
                                <div class="border-b pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="fi fi-rr-palette mr-2 text-blue-500"></i>
                                        Esquema de Cores
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Primary Color -->
                                        <div>
                                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Cor Primária</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="primary_color" id="primary_color" 
                                                        value="{{ $settings['primary_color'] ?? '#3b82f6' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="primary_color_text" id="primary_color_text" 
                                                    value="{{ $settings['primary_color'] ?? '#3b82f6' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['primary_color'] ?? '#3b82f6' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor principal para botões e elementos de destaque</p>
                                        </div>

                                        <!-- Secondary Color -->
                                        <div>
                                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Cor Secundária</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="secondary_color" id="secondary_color" 
                                                        value="{{ $settings['secondary_color'] ?? '#1e40af' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="secondary_color_text" id="secondary_color_text" 
                                                    value="{{ $settings['secondary_color'] ?? '#1e40af' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['secondary_color'] ?? '#1e40af' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor complementar para elementos secundários</p>
                                        </div>

                                        <!-- Success Color -->
                                        <div>
                                            <label for="success_color" class="block text-sm font-medium text-gray-700 mb-2">Cor de Sucesso</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="success_color" id="success_color" 
                                                        value="{{ $settings['success_color'] ?? '#10b981' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="success_color_text" id="success_color_text" 
                                                    value="{{ $settings['success_color'] ?? '#10b981' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['success_color'] ?? '#10b981' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Utilizada em mensagens de confirmação e sucesso</p>
                                        </div>

                                        <!-- Danger Color -->
                                        <div>
                                            <label for="danger_color" class="block text-sm font-medium text-gray-700 mb-2">Cor de Erro/Perigo</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="danger_color" id="danger_color" 
                                                        value="{{ $settings['danger_color'] ?? '#ef4444' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="danger_color_text" id="danger_color_text" 
                                                    value="{{ $settings['danger_color'] ?? '#ef4444' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['danger_color'] ?? '#ef4444' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Utilizada em botões de exclusão e mensagens de erro</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Typography Section -->
                                <div class="border-b pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="fi fi-rr-text mr-2 text-blue-500"></i>
                                        Tipografia
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">Fonte Principal</label>
                                            <select name="font_family" id="font_family" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="figtree" {{ ($settings['font_family'] ?? 'figtree') == 'figtree' ? 'selected' : '' }}>Figtree</option>
                                                <option value="inter" {{ ($settings['font_family'] ?? '') == 'inter' ? 'selected' : '' }}>Inter</option>
                                                <option value="roboto" {{ ($settings['font_family'] ?? '') == 'roboto' ? 'selected' : '' }}>Roboto</option>
                                                <option value="open-sans" {{ ($settings['font_family'] ?? '') == 'open-sans' ? 'selected' : '' }}>Open Sans</option>
                                                <option value="montserrat" {{ ($settings['font_family'] ?? '') == 'montserrat' ? 'selected' : '' }}>Montserrat</option>
                                                <option value="nunito" {{ ($settings['font_family'] ?? '') == 'nunito' ? 'selected' : '' }}>Nunito</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">Fonte utilizada em todo o sistema</p>
                                        </div>

                                        <div>
                                            <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">Cor do Texto</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="text_color" id="text_color" 
                                                        value="{{ $settings['text_color'] ?? '#4b5563' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="text_color_text" id="text_color_text" 
                                                    value="{{ $settings['text_color'] ?? '#4b5563' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['text_color'] ?? '#4b5563' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor padrão para textos e parágrafos</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- UI Elements Section -->
                                <div class="border-b pb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="fi fi-rr-apps mr-2 text-blue-500"></i>
                                        Elementos de Interface
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="border_radius" class="block text-sm font-medium text-gray-700 mb-2">Arredondamento de Bordas</label>
                                            <select name="border_radius" id="border_radius" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="none" {{ ($settings['border_radius'] ?? '') == 'none' ? 'selected' : '' }}>Sem arredondamento</option>
                                                <option value="small" {{ ($settings['border_radius'] ?? '') == 'small' ? 'selected' : '' }}>Pequeno (2px)</option>
                                                <option value="default" {{ ($settings['border_radius'] ?? 'default') == 'default' ? 'selected' : '' }}>Médio (4px)</option>
                                                <option value="large" {{ ($settings['border_radius'] ?? '') == 'large' ? 'selected' : '' }}>Grande (8px)</option>
                                                <option value="full" {{ ($settings['border_radius'] ?? '') == 'full' ? 'selected' : '' }}>Arredondado</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">Define o arredondamento de bordas para botões e cards</p>
                                        </div>

                                        <div>
                                            <label for="button_style" class="block text-sm font-medium text-gray-700 mb-2">Estilo dos Botões</label>
                                            <select name="button_style" id="button_style" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="default" {{ ($settings['button_style'] ?? 'default') == 'default' ? 'selected' : '' }}>Padrão</option>
                                                <option value="rounded" {{ ($settings['button_style'] ?? '') == 'rounded' ? 'selected' : '' }}>Arredondado</option>
                                                <option value="pill" {{ ($settings['button_style'] ?? '') == 'pill' ? 'selected' : '' }}>Pílula</option>
                                                <option value="square" {{ ($settings['button_style'] ?? '') == 'square' ? 'selected' : '' }}>Quadrado</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">Define o estilo dos botões no sistema</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar Settings -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="fi fi-rr-menu-burger mr-2 text-blue-500"></i>
                                        Barra Lateral
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="sidebar_style" class="block text-sm font-medium text-gray-700 mb-2">Estilo da Barra Lateral</label>
                                            <select name="sidebar_style" id="sidebar_style" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="light" {{ ($settings['sidebar_style'] ?? 'light') == 'light' ? 'selected' : '' }}>Claro</option>
                                                <option value="dark" {{ ($settings['sidebar_style'] ?? '') == 'dark' ? 'selected' : '' }}>Escuro</option>
                                                <option value="colored" {{ ($settings['sidebar_style'] ?? '') == 'colored' ? 'selected' : '' }}>Colorido</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">Define o estilo visual da barra lateral</p>
                                        </div>

                                        <div>
                                            <label for="sidebar_color" class="block text-sm font-medium text-gray-700 mb-2">Cor da Barra Lateral</label>
                                            <div class="flex items-center space-x-3">
                                                <div class="relative">
                                                    <input type="color" name="sidebar_color" id="sidebar_color" 
                                                        value="{{ $settings['sidebar_color'] ?? '#ffffff' }}"
                                                        class="h-10 w-16 cursor-pointer border border-gray-300 rounded">
                                                </div>
                                                <input type="text" name="sidebar_color_text" id="sidebar_color_text" 
                                                    value="{{ $settings['sidebar_color'] ?? '#ffffff' }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="button" class="color-preview-btn p-2 rounded" 
                                                    style="background-color: {{ $settings['sidebar_color'] ?? '#ffffff' }}; width: 40px; height: 40px;"></button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Cor de fundo da barra lateral (somente para estilo claro)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Preview -->
                            <div class="border rounded-lg shadow p-4 h-full bg-white" id="preview-panel">
                                <h3 class="font-medium text-lg mb-4 flex items-center">
                                    <i class="fi fi-rr-eye mr-2 text-blue-500"></i>
                                    Pré-visualização
                                </h3>
                                
                                <div class="mb-8 border rounded-lg overflow-hidden shadow-sm">
                                    <!-- Header -->
                                    <div class="bg-gray-100 p-4 border-b">
                                        <div class="flex items-center">
                                            <div class="font-bold text-lg" id="preview-site-name">Seu Site</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Body with elements -->
                                    <div class="p-4">
                                        <h2 class="text-xl font-semibold mb-2" id="preview-heading">Título da Página</h2>
                                        <p class="mb-4" id="preview-text">Este é um exemplo de texto para demonstrar a tipografia e cores definidas.</p>
                                        
                                        <!-- Buttons -->
                                        <div class="flex space-x-3 mb-4">
                                            <button type="button" id="preview-primary-btn" class="px-4 py-2 text-white rounded">
                                                Botão Primário
                                            </button>
                                            <button type="button" id="preview-secondary-btn" class="px-4 py-2 text-white rounded">
                                                Botão Secundário
                                            </button>
                                        </div>
                                        
                                        <!-- Form Elements -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium mb-2" id="preview-label">Campo de Texto</label>
                                            <input type="text" value="Exemplo de entrada" id="preview-input" class="w-full px-3 py-2 border rounded">
                                        </div>
                                        
                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <div class="h-6 w-6 rounded-full mr-2" id="preview-success-icon"></div>
                                                <span id="preview-success-text">Mensagem de sucesso</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <div class="flex items-center">
                                                <div class="h-6 w-6 rounded-full mr-2" id="preview-danger-icon"></div>
                                                <span id="preview-danger-text">Mensagem de erro</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <button type="button" id="save-preview" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded mb-2">
                                        Salvar Configurações
                                    </button>
                                    <p class="text-xs text-gray-500 text-center">
                                        As alterações serão aplicadas em tempo real no preview, mas só serão salvas ao clicar no botão acima.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t">
                            <button type="submit" id="submit-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors flex items-center">
                                <i class="fi fi-rr-disk mr-2"></i>
                                Salvar Configurações
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
            // Color inputs sync and preview
            const colorInputs = {
                'primary_color': { text: 'primary_color_text', preview: ['preview-primary-btn'] },
                'secondary_color': { text: 'secondary_color_text', preview: ['preview-secondary-btn'] },
                'success_color': { text: 'success_color_text', preview: ['preview-success-icon'] },
                'danger_color': { text: 'danger_color_text', preview: ['preview-danger-icon'] },
                'text_color': { text: 'text_color_text', preview: ['preview-text', 'preview-label'] },
                'sidebar_color': { text: 'sidebar_color_text', preview: [] }
            };
            
            // Initialize previews
            updateAllPreviews();
            
            // Set up event listeners for color inputs
            Object.keys(colorInputs).forEach(inputId => {
                const colorInput = document.getElementById(inputId);
                const textInput = document.getElementById(colorInputs[inputId].text);
                
                if (colorInput && textInput) {
                    // Sync color picker with text input
                    colorInput.addEventListener('input', function() {
                        textInput.value = this.value;
                        updatePreview(inputId, this.value);
                    });
                    
                    // Sync text input with color picker
                    textInput.addEventListener('input', function() {
                        colorInput.value = this.value;
                        updatePreview(inputId, this.value);
                    });
                }
            });
            
            // Border radius and button style previews
            document.getElementById('border_radius').addEventListener('change', updateAllPreviews);
            document.getElementById('button_style').addEventListener('change', updateAllPreviews);
            document.getElementById('font_family').addEventListener('change', updateAllPreviews);
            
            // Preview save button
            document.getElementById('save-preview').addEventListener('click', function() {
                saveSettings();
            });

            // Formulário principal
            document.getElementById('submit-button').addEventListener('click', function(e) {
                e.preventDefault();
                saveSettings();
            });
            
            // Função de submissão AJAX
            function saveSettings() {
                const form = document.getElementById('style-settings-form');
                const formData = new FormData(form);
                
                // Mostrar indicador de carregamento
                const saveBtn = document.getElementById('save-preview');
                const submitBtn = document.getElementById('submit-button');
                const originalSaveBtnText = saveBtn.innerHTML;
                const originalSubmitBtnText = submitBtn.innerHTML;
                
                saveBtn.innerHTML = 'Salvando...';
                saveBtn.disabled = true;
                submitBtn.innerHTML = 'Salvando...';
                submitBtn.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensagem de sucesso
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                        messageDiv.innerHTML = data.message;
                        document.body.appendChild(messageDiv);
                        
                        // Remover a mensagem após 3 segundos
                        setTimeout(() => {
                            messageDiv.remove();
                        }, 3000);
                        
                        // Recarregar a folha de estilo
                        const stylesheetLink = document.querySelector('link[href*="dynamic-styles"]');
                        if (stylesheetLink) {
                            const href = stylesheetLink.getAttribute('href').split('?')[0];
                            stylesheetLink.setAttribute('href', href + '?t=' + new Date().getTime());
                        }
                    } else {
                        // Mostrar mensagem de erro
                        alert('Erro ao salvar as configurações: ' + (data.message || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao salvar as configurações. Veja o console para mais detalhes.');
                })
                .finally(() => {
                    // Restaurar botões
                    saveBtn.innerHTML = originalSaveBtnText;
                    saveBtn.disabled = false;
                    submitBtn.innerHTML = originalSubmitBtnText;
                    submitBtn.disabled = false;
                });
            }
            
            // Function to update preview elements
            function updatePreview(inputId, value) {
                if (!colorInputs[inputId] || !colorInputs[inputId].preview) return;
                
                colorInputs[inputId].preview.forEach(previewId => {
                    const previewElement = document.getElementById(previewId);
                    if (!previewElement) return;
                    
                    switch(previewId) {
                        case 'preview-primary-btn':
                            previewElement.style.backgroundColor = value;
                            break;
                        case 'preview-secondary-btn':
                            previewElement.style.backgroundColor = value;
                            break;
                        case 'preview-text':
                        case 'preview-label':
                            previewElement.style.color = value;
                            break;
                        case 'preview-success-icon':
                            previewElement.style.backgroundColor = value;
                            break;
                        case 'preview-danger-icon':
                            previewElement.style.backgroundColor = value;
                            break;
                        default:
                            break;
                    }
                });
            }
            
            // Update all preview elements based on current values
            function updateAllPreviews() {
                // Update colors
                Object.keys(colorInputs).forEach(inputId => {
                    const colorInput = document.getElementById(inputId);
                    if (colorInput) {
                        updatePreview(inputId, colorInput.value);
                    }
                });
                
                // Update border radius
                const borderRadiusSelect = document.getElementById('border_radius');
                if (borderRadiusSelect) {
                    let radius = '0.375rem'; // default
                    
                    switch (borderRadiusSelect.value) {
                        case 'none':
                            radius = '0';
                            break;
                        case 'small':
                            radius = '0.25rem';
                            break;
                        case 'large':
                            radius = '0.5rem';
                            break;
                        case 'full':
                            radius = '9999px';
                            break;
                    }
                    
                    document.getElementById('preview-primary-btn').style.borderRadius = radius;
                    document.getElementById('preview-secondary-btn').style.borderRadius = radius;
                    document.getElementById('preview-input').style.borderRadius = radius;
                }
                
                // Update font family
                const fontFamilySelect = document.getElementById('font_family');
                if (fontFamilySelect) {
                    let fontFamily = 'Figtree, sans-serif';
                    
                    switch (fontFamilySelect.value) {
                        case 'inter':
                            fontFamily = 'Inter, sans-serif';
                            break;
                        case 'roboto':
                            fontFamily = 'Roboto, sans-serif';
                            break;
                        case 'open-sans':
                            fontFamily = 'Open Sans, sans-serif';
                            break;
                        case 'montserrat':
                            fontFamily = 'Montserrat, sans-serif';
                            break;
                        case 'nunito':
                            fontFamily = 'Nunito, sans-serif';
                            break;
                    }
                    
                    const previewElements = document.querySelectorAll('#preview-heading, #preview-text, #preview-label, #preview-primary-btn, #preview-secondary-btn');
                    previewElements.forEach(el => {
                        el.style.fontFamily = fontFamily;
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>