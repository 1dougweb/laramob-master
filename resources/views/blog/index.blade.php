<x-frontend-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-14">
            <x-ui.text type="heading" size="xl" class="text-5xl font-extrabold mb-4 leading-tight">
                Blog <span class="text-[{{ get_setting('primary_color', '#3b82f6') }}]">Imobiliário</span>
            </x-ui.text>
            <x-ui.text type="body" size="lg" class="max-w-2xl mx-auto">
                Conteúdo de qualidade sobre imóveis, mercado, dicas e tendências para você se informar e se inspirar.
            </x-ui.text>
        </div>

        <div class="lg:flex lg:gap-12">
            <!-- Main Content -->
            <div class="flex-1 min-w-0">
                <!-- Destaques -->
                @if($featuredPosts->isNotEmpty())
                <div class="mb-12">
                    <x-ui.text type="heading" size="lg" class="mb-6">Destaques</x-ui.text>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($featuredPosts as $post)
                        <x-ui.card class="overflow-hidden flex flex-col">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block group">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($post->categories as $category)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[{{ get_setting('primary_color', '#3b82f6') }}]/10 text-[{{ get_setting('primary_color', '#3b82f6') }}]">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                    <x-ui.text type="heading" size="lg" class="mb-2 line-clamp-2 group-hover:text-[{{ get_setting('hover_text_color', '#3b82f6') }}]">
                                        {{ $post->title }}
                                    </x-ui.text>
                                    <x-ui.text type="body" class="mb-4 line-clamp-3">
                                        {{ $post->meta_description }}
                                    </x-ui.text>
                                    <div class="flex items-center justify-between mt-auto">
                                        <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-sm text-gray-500">{{ $post->published_at->format('d/m/Y') }}</time>
                                        <x-ui.text type="link" class="text-sm font-medium">Ler mais →</x-ui.text>
                                    </div>
                                </div>
                            </a>
                        </x-ui.card>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Últimos Artigos -->
                <div>
                    <x-ui.text type="heading" size="lg" class="mb-6">Últimos Artigos</x-ui.text>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($posts as $post)
                        <x-ui.card class="overflow-hidden flex flex-col">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block group">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($post->categories as $category)
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[{{ get_setting('primary_color', '#3b82f6') }}]/10 text-[{{ get_setting('primary_color', '#3b82f6') }}]">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                    <x-ui.text type="heading" size="md" class="mb-2 line-clamp-2 group-hover:text-[{{ get_setting('hover_text_color', '#3b82f6') }}]">
                                        {{ $post->title }}
                                    </x-ui.text>
                                    <x-ui.text type="body" class="mb-4 line-clamp-3">
                                        {{ $post->meta_description }}
                                    </x-ui.text>
                                    <div class="flex items-center justify-between mt-auto">
                                        <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-sm text-gray-500">{{ $post->published_at->format('d/m/Y') }}</time>
                                        <x-ui.text type="link" class="text-sm font-medium">Ler mais →</x-ui.text>
                                    </div>
                                </div>
                            </a>
                        </x-ui.card>
                        @endforeach
                    </div>
                    @if($posts->hasPages())
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="hidden lg:block w-80 flex-shrink-0">
                <!-- Busca -->
                <x-ui.card class="mb-8">
                    <form action="{{ route('blog.index') }}" method="GET">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar no blog</label>
                        <input type="text" name="search" id="search" placeholder="Digite para buscar..." class="{{ get_input_classes() }}">
                    </form>
                </x-ui.card>

                <!-- Categorias -->
                <x-ui.card class="mb-8">
                    <x-ui.text type="heading" size="md" class="mb-4">Categorias</x-ui.text>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('blog.category', $category->slug) }}" class="flex items-center justify-between text-[{{ get_setting('text_color', '#4b5563') }}] hover:text-[{{ get_setting('hover_text_color', '#3b82f6') }}]">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs text-gray-400">{{ $category->posts_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </x-ui.card>

                <!-- Newsletter -->
                <div class="bg-[{{ get_setting('primary_color', '#3b82f6') }}] rounded-2xl p-6 text-center">
                    <x-ui.text type="heading" size="md" class="text-white mb-2">Receba nossas atualizações</x-ui.text>
                    <x-ui.text type="body" class="text-blue-100 mb-4 text-sm">
                        Inscreva-se para receber as últimas novidades e dicas sobre o mercado imobiliário.
                    </x-ui.text>
                    <form class="space-y-3">
                        <input type="email" class="{{ get_input_classes() }}" placeholder="Seu melhor email">
                        <x-ui.button type="primary" class="w-full">
                            Inscrever-se
                        </x-ui.button>
                    </form>
                </div>
            </aside>
        </div>
    </div>

    @push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</x-frontend-layout> 