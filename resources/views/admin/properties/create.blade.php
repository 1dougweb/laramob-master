    <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Adicionar Propriedade') }}
            </h2>
            <a href="{{ route('admin.properties.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                {{ __('Voltar para Lista') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Informações Básicas') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Título') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                                    @error('title')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Tipo de Imóvel') }} <span class="text-red-500">*</span></label>
                                    <select name="property_type_id" id="property_type_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('property_type_id') border-red-500 @enderror">
                                        <option value="">{{ __('Selecione um tipo') }}</option>
                                        @foreach($propertyTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('property_type_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Descrição') }} <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" required
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Localização') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Cidade') }} <span class="text-red-500">*</span></label>
                                    <select name="city_id" id="city_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('city_id') border-red-500 @enderror">
                                        <option value="">{{ __('Selecione uma cidade') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }} - {{ $city->state }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="district_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bairro') }} <span class="text-red-500">*</span></label>
                                    <select name="district_id" id="district_id" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('district_id') border-red-500 @enderror">
                                        <option value="">{{ __('Selecione um bairro') }}</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" data-city="{{ $district->city_id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Endereço') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Detalhes do Imóvel') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="area" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Área (m²)') }} <span class="text-red-500">*</span></label>
                                    <input type="number" name="area" id="area" step="0.01" min="0" value="{{ old('area') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('area') border-red-500 @enderror">
                                    @error('area')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="built_area" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Área Construída (m²)') }}</label>
                                    <input type="number" name="built_area" id="built_area" step="0.01" min="0" value="{{ old('built_area') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('built_area') border-red-500 @enderror">
                                    @error('built_area')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Quartos') }} <span class="text-red-500">*</span></label>
                                    <input type="number" name="bedrooms" id="bedrooms" min="0" value="{{ old('bedrooms') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bedrooms') border-red-500 @enderror">
                                    @error('bedrooms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Banheiros') }} <span class="text-red-500">*</span></label>
                                    <input type="number" name="bathrooms" id="bathrooms" min="0" value="{{ old('bathrooms') }}" required
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bathrooms') border-red-500 @enderror">
                                    @error('bathrooms')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="suites" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Suítes') }}</label>
                                    <input type="number" name="suites" id="suites" min="0" value="{{ old('suites') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('suites') border-red-500 @enderror">
                                    @error('suites')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="parking" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Vagas de Garagem') }}</label>
                                    <input type="number" name="parking" id="parking" min="0" value="{{ old('parking') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('parking') border-red-500 @enderror">
                                    @error('parking')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recursos') }}</h3>
                            <div class="border rounded-md p-4" 
                                 x-data="{ 
                                    features: [{ name: '', icon: 'home' }],
                                    addFeature() {
                                        this.features.push({ name: '', icon: 'home' });
                                        this.$nextTick(() => {
                                            const inputs = document.querySelectorAll('.feature-name-input');
                                            inputs[inputs.length - 1].focus();
                                        });
                                    },
                                    removeFeature(index) {
                                        if (this.features.length > 1) {
                                            if (confirm('{{ __('Tem certeza que deseja remover este recurso?') }}')) {
                                                this.features.splice(index, 1);
                                            }
                                        }
                                    }
                                 }">
                                <div class="space-y-3">
                                    <template x-for="(feature, index) in features" :key="index">
                                        <div class="grid grid-cols-3 gap-4 mb-3 pb-3 border-b last:border-b-0">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nome do Recurso') }}</label>
                                                <input type="text" 
                                                       x-model="feature.name" 
                                                       :name="`feature[${index}][feature_name]`" 
                                                       placeholder="Ex: Piscina, Jardim, Ar-condicionado"
                                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 feature-name-input">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ícone') }}</label>
                                                <div x-data="{ 
                                                    open: false, 
                                                    search: '',
                                                    icons: {
                                                        'home': 'Casa',
                                                        'building': 'Prédio',
                                                        'sun': 'Sol',
                                                        'stars': 'Brilhos',
                                                        'wifi': 'Wi-Fi',
                                                        'flame': 'Fogo',
                                                        'gamepad':'Jogos',
                                                        'graduation-cap': 'Chapéu',
                                                        'settings-sliders': 'Ajustes',
                                                        'badge-check': 'Verificado',
                                                        'flask': 'Béquer',
                                                        'bell': 'Sino',
                                                        'bolt': 'Raio',
                                                        'cake-birthday': 'Bolo',
                                                        'camera': 'Câmera',
                                                        'sack-dollar': 'Dinheiro',
                                                        'clock': 'Relógio',
                                                        'cloud': 'Nuvem',
                                                        'settings': 'Engrenagem',
                                                        'cube': 'Cubo',
                                                        'computer': 'Computador',
                                                        'mobile': 'Celular',
                                                        'globe': 'Globo',
                                                        'heart': 'Coração',
                                                        'key': 'Chave',
                                                        'bulb': 'Lâmpada',
                                                        'bolt': 'Raio',
                                                        'marker': 'Localização',
                                                        'lock': 'Cadeado',
                                                        'map': 'Mapa',
                                                        'moon': 'Lua',
                                                        'star': 'Estrela',
                                                        'truck-moving': 'Caminhão',
                                                        'video-camera': 'Filmadora',
                                                        'grid': 'Grade',
                                                        'drafting-compass': 'Planta Baixa',
                                                        'gift': 'Presente',
                                                        'shield': 'Segurança',
                                                        'scale': 'Balança',
                                                        'file-invoice': 'Documento',
                                                        'shopping-cart': 'Compras',
                                                        'briefcase': 'Negócios',
                                                        'truck-container': 'Mudança',
                                                        'scissors': 'Reforma',
                                                        'file-signature': 'Contrato',
                                                        'books': 'Biblioteca',
                                                        'table': 'Móveis',
                                                        'tags': 'Etiqueta de Preço',
                                                        'comments': 'Atendimento',
                                                        'checkbox': 'Aprovado',
                                                        'menu-burger': 'Menu',
                                                        'trash': 'Lixeira',
                                                        'inbox': 'Mensagens',
                                                        'megaphone': 'Anúncio',
                                                        'chart-pie': 'Estatísticas',
                                                        'flag': 'Bandeira',
                                                        'users': 'Usuários',
                                                        'user': 'Perfil',
                                                        'newspaper': 'Notícias',
                                                        'checkbox': 'Aprovado',
                                                        'utensils': 'Cozinha',
                                                        'bath': 'Banheira',
                                                        'seedling': 'Jardim',
                                                        'warehouse': 'Garagem',
                                                        'swimming-pool': 'Piscina',
                                                        'elevator': 'Elevador',
                                                        'gym': 'Academia',
                                                        'car': 'Estacionamento',
                                                        'fence': 'Cerca',
                                                        'home-location': 'Varanda',
                                                        'temperature-frigid': 'Ar-condicionado',
                                                        'chair': 'Mobiliado',
                                                        'camera-security': 'Câmera de Segurança',
                                                        'child': 'Playground',
                                                        'grill': 'Churrasqueira',
                                                        'washer': 'Lavanderia',
                                                        'dog': 'Aceita Pets',
                                                        'wheelchair': 'Acessibilidade',
                                                        'solar-panel': 'Painéis Solares',
                                                        'smart-home': 'Casa Inteligente'
                                                    },
                                                    get filteredIcons() {
                                                        if (!this.search) return Object.entries(this.icons);
                                                        return Object.entries(this.icons).filter(([key, label]) => 
                                                            label.toLowerCase().includes(this.search.toLowerCase())
                                                        );
                                                    }
                                                }" class="relative">
                                                    <div 
                                                        @click="open = !open" 
                                                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-white flex items-center justify-between cursor-pointer hover:bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                                    >
                                                        <div class="flex items-center">
                                                            <i :class="`fi fi-rr-${feature.icon} mr-2 text-gray-500`" style="font-size: 1.25rem;"></i>
                                                            <span x-text="icons[feature.icon] || 'Selecione um ícone'"></span>
                                                        </div>
                                                        <svg class="w-5 h-5 ml-2 -mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    
                                                    <!-- Dropdown menu -->
                                                    <div 
                                                        x-show="open" 
                                                        @click.away="open = false"
                                                        class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg max-h-60 overflow-y-auto"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                    >
                                                        <div class="sticky top-0 p-2 bg-white border-b">
                                                            <input 
                                                                type="text" 
                                                                x-model="search"
                                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                                placeholder="Pesquisar ícones..."
                                                            >
                                                        </div>
                                                        <ul class="py-1 overflow-auto text-base">
                                                            <template x-for="[value, label] in filteredIcons" :key="value">
                                                                <li 
                                                                    @click="feature.icon = value; open = false"
                                                                    class="flex items-center px-3 py-2 cursor-pointer hover:bg-blue-50 hover:text-blue-600"
                                                                    :class="{ 'bg-blue-50 text-blue-600': feature.icon === value }"
                                                                >
                                                                    <i :class="`fi fi-rr-${value} mr-3`" :class="{ 'text-blue-500': feature.icon === value }" 
                                                                       class="text-gray-500" style="font-size: 1.25rem;"></i>
                                                                    <span x-text="label"></span>
                                                                </li>
                                                            </template>
                                                            <li x-show="filteredIcons.length === 0" class="px-3 py-2 text-sm text-gray-500">
                                                                Nenhum ícone encontrado
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <!-- Campo oculto para armazenar o valor -->
                                                    <input type="hidden" :name="`feature[${index}][feature_icon]`" x-model="feature.icon">
                                                </div>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" 
                                                        @click="removeFeature(index)" 
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                    {{ __('Remover') }}
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="button" 
                                            @click="addFeature()" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Adicionar Recurso') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Preços e Disponibilidade') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Finalidade') }} <span class="text-red-500">*</span></label>
                                    <select name="purpose" id="purpose" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('purpose') border-red-500 @enderror">
                                        <option value="sale" {{ old('purpose') == 'sale' ? 'selected' : '' }}>{{ __('Para Venda') }}</option>
                                        <option value="rent" {{ old('purpose') == 'rent' ? 'selected' : '' }}>{{ __('Para Aluguel') }}</option>
                                    </select>
                                    @error('purpose')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Preço de Venda (R$)') }}</label>
                                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                                    @error('price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="rental_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Preço do Aluguel (R$)') }}</label>
                                    <input type="number" name="rental_price" id="rental_price" step="0.01" min="0" value="{{ old('rental_price') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('rental_price') border-red-500 @enderror">
                                    @error('rental_price')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="condominium_fee" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Taxa de Condomínio (R$)') }}</label>
                                    <input type="number" name="condominium_fee" id="condominium_fee" step="0.01" min="0" value="{{ old('condominium_fee') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('condominium_fee') border-red-500 @enderror">
                                    @error('condominium_fee')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="iptu" class="block text-sm font-medium text-gray-700 mb-1">{{ __('IPTU (R$)') }}</label>
                                    <input type="number" name="iptu" id="iptu" step="0.01" min="0" value="{{ old('iptu') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('iptu') border-red-500 @enderror">
                                    @error('iptu')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }} <span class="text-red-500">*</span></label>
                                    <select name="status" id="status" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>{{ __('Disponível') }}</option>
                                        <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>{{ __('Vendido') }}</option>
                                        <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>{{ __('Alugado') }}</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>{{ __('Reservado') }}</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Mídia') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Imagem Destacada -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <h4 class="font-medium text-gray-800 mb-3">{{ __('Imagem Destacada') }}</h4>
                                    
                                    <div class="mb-3" x-data="{ fileName: '', previewUrl: '' }">
                                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('Selecione uma imagem') }}
                                        </label>
                                        
                                        <div class="flex items-center justify-center w-full">
                                            <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6" 
                                                     x-show="!previewUrl">
                                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <p class="text-sm text-gray-500" x-text="fileName || '{{ __('Clique para selecionar ou arraste uma imagem') }}'"></p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ __('PNG, JPG, JPEG ou WebP (máx. 2MB)') }}</p>
                                                </div>
                                                <div class="h-full w-full flex items-center justify-center" x-show="previewUrl">
                                                    <img :src="previewUrl" class="h-full object-contain" />
                                                </div>
                                                <input type="file" name="featured_image" id="featured_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden"
                                                    @change="
                                                        const file = $event.target.files[0];
                                                        if (file) {
                                                            const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/avif'];
                                                            if (!validImageTypes.includes(file.type)) {
                                                                alert('{{ __('O arquivo selecionado não é uma imagem suportada. Use PNG, JPG, JPEG, WebP ou AVIF.') }}');
                                                                $event.target.value = '';
                                                                return;
                                                            }
                                                            fileName = file.name;
                                                            const reader = new FileReader();
                                                            reader.onload = (e) => {
                                                                previewUrl = e.target.result;
                                                            };
                                                            reader.readAsDataURL(file);
                                                        }
                                                    " x-ref="fileInput">
                                            </label>
                                        </div>
                                        <div class="text-xs text-center mt-2" x-show="fileName">
                                            <span class="text-gray-600">{{ __('Arquivo selecionado:') }}</span>
                                            <span class="font-medium text-blue-600" x-text="fileName"></span>
                                            <button type="button" @click="fileName = ''; previewUrl = ''; $refs.fileInput.value = ''" 
                                                    class="ml-2 text-red-500 hover:text-red-700">
                                                <span>{{ __('Remover') }}</span>
                                            </button>
                                        </div>
                                        @error('featured_image')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-xs mt-2">{{ __('Imagem principal do imóvel que será exibida em destaque.') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Galeria de Fotos -->
                                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <h4 class="font-medium text-gray-800 mb-3">{{ __('Galeria de Fotos') }}</h4>
                                    
                                    <div x-data="{ 
                                        files: [], 
                                        previews: [],
                                        handleFileSelect(event) {
                                            const maxFiles = 10;
                                            const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                                            const validFiles = Array.from(event.target.files).filter(file => 
                                                validImageTypes.includes(file.type)
                                            );
                                            
                                            if (validFiles.length < Array.from(event.target.files).length) {
                                                alert('{{ __("Alguns arquivos foram ignorados por não serem imagens suportadas (PNG, JPG, JPEG ou WebP).") }}');
                                            }
                                            
                                            if (validFiles.length > maxFiles) {
                                                alert('{{ __("Apenas as primeiras 10 imagens serão processadas.") }}');
                                                this.files = validFiles.slice(0, maxFiles);
                                            } else {
                                                this.files = validFiles;
                                            }
                                            
                                            this.previews = [];
                                            this.files.forEach(file => {
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    this.previews.push({
                                                        name: file.name,
                                                        url: e.target.result
                                                    });
                                                };
                                                reader.readAsDataURL(file);
                                            });
                                        }
                                    }">
                                        <label for="gallery" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('Selecione múltiplas imagens') }}
                                        </label>
                                        
                                        <div class="flex items-center justify-center w-full">
                                            <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="previews.length === 0">
                                                    <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <p class="text-sm text-gray-500">{{ __('Clique para selecionar ou arraste várias imagens') }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ __('PNG, JPG, JPEG ou WebP (máx. 2MB cada)') }}</p>
                                                </div>
                                                <div class="h-full w-full flex items-center justify-center" x-show="previews.length > 0">
                                                    <div class="text-center">
                                                        <p class="text-sm font-medium text-blue-600">
                                                            <span x-text="previews.length"></span> {{ __('imagens selecionadas') }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ __('Clique para alterar a seleção') }}</p>
                                                    </div>
                                                </div>
                                                <input type="file" name="gallery[]" id="gallery" accept="image/jpeg,image/png,image/jpg,image/webp" multiple class="hidden"
                                                       @change="handleFileSelect($event)">
                                            </label>
                                        </div>
                                        
                                        <div class="mt-3" x-show="previews.length > 0">
                                            <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Pré-visualização:') }}</p>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                <template x-for="(preview, index) in previews" :key="index">
                                                    <div class="relative rounded-md overflow-hidden h-24 border border-gray-200">
                                                        <img :src="preview.url" class="w-full h-full object-cover" />
                                                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                                            <p class="text-white text-xs px-2 truncate max-w-full" x-text="preview.name"></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <button type="button" @click="files = []; previews = []; $refs.galleryInput.value = ''" 
                                                    class="mt-2 text-sm text-red-500 hover:text-red-700">
                                                {{ __('Limpar seleção') }}
                                            </button>
                                        </div>
                                        
                                        @error('gallery.*')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-gray-500 text-xs mt-2">{{ __('Selecione múltiplas imagens para a galeria do imóvel (máximo 10 imagens).') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Configurações') }}</h3>
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                                        {{ __('Imóvel Destacado') }}
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                        {{ __('Imóvel Ativo') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Criar Imóvel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Filter districts based on city selection
            const citySelect = $('#city_id');
            const districtSelect = $('#district_id');
            
            if (citySelect.length && districtSelect.length) {
                const districtOptions = Array.from(districtSelect[0].options);
                
                citySelect.on('change', function() {
                    const selectedCityId = $(this).val();
                    
                    // Reset district options
                    districtSelect.empty();
                    
                    // Add placeholder option
                    districtSelect.append(`<option value="">{{ __("Selecione um bairro") }}</option>`);
                    
                    // Filter and add matching district options
                    districtOptions.forEach(option => {
                        if (option.value === '' || option.dataset.city === selectedCityId) {
                            districtSelect.append($(option).clone());
                        }
                    });
                });

                // Trigger initial filtering
                if (citySelect.val()) {
                    citySelect.trigger('change');

                    // Restore selected district if there was one
                    const oldDistrictValue = '{{ old("district_id") }}';
                    if (oldDistrictValue) {
                        setTimeout(() => {
                            districtSelect.val(oldDistrictValue);
                        }, 100);
                    }
                }
            }
            
            // Show/hide price fields based on purpose selection
            const purposeSelect = $('#purpose');
            
            if (purposeSelect.length) {
                const priceField = $('#price').parent();
                const rentalPriceField = $('#rental_price').parent();
                
                const updatePriceFields = function() {
                    const selectedPurpose = purposeSelect.val();
                    
                    if (selectedPurpose === 'sale') {
                        priceField.show();
                        $('#price').attr('required', 'required');
                        rentalPriceField.hide();
                        $('#rental_price').removeAttr('required');
                    } else if (selectedPurpose === 'rent') {
                        priceField.hide();
                        $('#price').removeAttr('required');
                        rentalPriceField.show();
                        $('#rental_price').attr('required', 'required');
                    } else {
                        priceField.show();
                        rentalPriceField.show();
                    }
                };
                
                purposeSelect.on('change', updatePriceFields);
                
                // Trigger change event to set initial state
                updatePriceFields();
            }

            // Preview das imagens da galeria
            const galleryInput = $('#gallery');
            if (galleryInput.length) {
                galleryInput.on('change', function() {
                    const previewContainer = $('<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2 gallery-preview"></div>');
                    
                    // Remover preview existente
                    $(this).parent().find('.gallery-preview').remove();
                    
                    // Limitar a 10 imagens e verificar tipos de arquivo
                    const maxFiles = 10;
                    let validFiles = 0;
                    let invalidFiles = 0;
                    
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        if (!file.type.startsWith('image/')) {
                            invalidFiles++;
                            continue;
                        }
                        
                        if (validFiles >= maxFiles) {
                            continue;
                        }
                        
                        validFiles++;
                            const reader = new FileReader();
                        const previewItem = $('<div class="relative"></div>');
                            
                            reader.onload = function(e) {
                            previewItem.html(`
                                    <div class="relative pb-[100%] overflow-hidden rounded-md shadow">
                                        <img src="${e.target.result}" alt="Preview" class="absolute inset-0 w-full h-full object-cover" />
                                    </div>
                            `);
                            };
                            
                            reader.readAsDataURL(file);
                        previewContainer.append(previewItem);
                    }
                    
                    if (previewContainer.children().length > 0) {
                        $(this).parent().append(previewContainer);
                    }
                    
                    if (invalidFiles > 0) {
                        alert(`{{ __("Atenção: ") }}${invalidFiles} {{ __("arquivo(s) não são imagens e serão ignorados.") }}`);
                    }
                    
                    if (this.files.length > maxFiles) {
                        alert(`{{ __("Atenção: Apenas as primeiras 10 imagens serão enviadas.") }}`);
                    }
                });
            }
            
            // Preview da imagem destacada
            const featuredImageInput = $('#featured_image');
            if (featuredImageInput.length) {
                featuredImageInput.on('change', function() {
                    // Remover preview existente
                    $(this).parent().find('.featured-preview').remove();
                    
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        
                        if (!file.type.startsWith('image/')) {
                            alert('{{ __("O arquivo selecionado não é uma imagem válida.") }}');
                            this.value = '';
                            return;
                        }
                        
                        const previewContainer = $('<div class="mt-2 featured-preview"></div>');
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer.html(`
                                <div class="relative w-48 h-48 overflow-hidden rounded-md shadow">
                                    <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                            `);
                        };
                        
                        reader.readAsDataURL(file);
                        $(this).parent().append(previewContainer);
                    }
                });
            }

            // Validação do formulário antes de enviar
            const form = $('form[action="{{ route("admin.properties.store") }}"]');
            if (form.length) {
                form.on('submit', function(e) {
                    let hasErrors = false;
                    
                    // Verificar valor conforme a finalidade
                    const purpose = $('#purpose').val();
                    if (purpose === 'sale' && $('#price').val().trim() === '') {
                        hasErrors = true;
                        alert('{{ __("O preço de venda é obrigatório para imóveis à venda.") }}');
                    } else if (purpose === 'rent' && $('#rental_price').val().trim() === '') {
                        hasErrors = true;
                        alert('{{ __("O preço de aluguel é obrigatório para imóveis para alugar.") }}');
                    }
                    
                    if (hasErrors) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout> 