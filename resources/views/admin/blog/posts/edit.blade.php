                <!-- Google Preview Card -->
                <div class="mt-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Visualização no Google</h3>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="text-sm text-gray-500">{{ config('app.url') }}/blog/</div>
                            <div class="text-xl text-blue-800 font-medium" id="preview-title">{{ $post->title }}</div>
                            <div class="text-sm text-gray-600" id="preview-description">{{ $post->meta_description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ... existing code ...

    // Atualiza a prévia do Google
    function updateGooglePreview() {
        const title = document.getElementById('title').value || 'Título do seu post aparecerá aqui';
        const description = document.getElementById('meta_description').value || 'A meta descrição do seu post aparecerá aqui. Esta é uma prévia de como seu post será exibido nos resultados de busca do Google.';
        
        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-description').textContent = description;
    }

    // Adiciona os event listeners para atualizar a prévia
    document.getElementById('title').addEventListener('input', updateGooglePreview);
    document.getElementById('meta_description').addEventListener('input', updateGooglePreview);
</script>
@endpush 

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold">Editar Post</h2>
    <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <i class="fi fi-rr-arrow-left mr-2"></i>
        Voltar
    </a>
</div>

<form action="{{ route('admin.blog-posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="md:col-span-3 space-y-6">
            <div>
                <x-input-label for="title" value="Título" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <div>
                <x-input-label for="slug" value="Slug" />
                <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $post->slug)" required />
                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
            </div>

            <div>
                <x-input-label for="content" value="Conteúdo" />
                <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">{{ old('content', $post->content) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('content')" />
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>Salvar Alterações</x-primary-button>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h4 class="font-medium text-gray-800 mb-3">Imagem Destacada</h4>
                
                <div class="mb-3" x-data="{ fileName: '', previewUrl: '{{ $post->featured_image_url }}' }">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Selecione uma imagem
                    </label>
                    
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" 
                                 x-show="!previewUrl">
                                <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500" x-text="fileName || 'Clique para selecionar ou arraste uma imagem'"></p>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG ou WebP (máx. 2MB)</p>
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
                                            alert('O arquivo selecionado não é uma imagem suportada. Use PNG, JPG, JPEG, WebP ou AVIF.');
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
                        <span class="text-gray-600">Arquivo selecionado:</span>
                        <span class="font-medium text-blue-600" x-text="fileName"></span>
                        <button type="button" @click="fileName = ''; previewUrl = ''; $refs.fileInput.value = ''" 
                                class="ml-2 text-red-500 hover:text-red-700">
                            <span>Remover</span>
                        </button>
                    </div>
                    @error('featured_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-2">Imagem principal do post que será exibida em destaque.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h4 class="font-medium text-gray-800 mb-3">SEO</h4>
                <div class="space-y-4">
                    <div>
                        <x-input-label for="seo_title" value="Título SEO" />
                        <x-text-input id="seo_title" name="seo_title" type="text" class="mt-1 block w-full" :value="old('seo_title', $post->seo_title)" />
                        <p class="mt-1 text-sm text-gray-500">Título otimizado para mecanismos de busca. Se não preenchido, será usado o título do post.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('seo_title')" />
                    </div>

                    <div>
                        <x-input-label for="meta_description" value="Meta Descrição" />
                        <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" maxlength="160">{{ old('meta_description', $post->meta_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Máximo 160 caracteres. Importante para SEO.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('meta_description')" />
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <h4 class="font-medium text-gray-800 mb-3">Categorias</h4>
                <div class="mt-1">
                    <select id="category_id" name="category_id[]" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('category_id', $post->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="mt-1 text-sm text-gray-500">Selecione uma ou mais categorias para o post.</p>
                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
            </div>
        </div>
    </div>
</form>