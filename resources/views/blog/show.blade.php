<x-frontend-layout>
    <!-- Hero Section -->
    <div class="relative">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="{{ $post->featured_image_url }}" alt="{{ $post->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-4">
                    @foreach($post->categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}" 
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/10 text-white hover:bg-white/20 transition-colors duration-300">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {{ $post->title }}
                </h1>
                <div class="mt-6 flex items-center text-white/80">
                    <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-sm">
                        {{ $post->published_at->format('d/m/Y') }}
                    </time>
                    <span class="mx-2">•</span>
                    <span class="text-sm">{{ $post->reading_time }} min de leitura</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <div class="prose prose-lg prose-blue max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Share Buttons -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Compartilhe este artigo</h3>
                    <div class="mt-4 flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fi fi-brands-facebook mr-2"></i>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sky-500 hover:bg-sky-600">
                            <i class="fi fi-brands-twitter mr-2"></i>
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800">
                            <i class="fi fi-brands-linkedin mr-2"></i>
                            LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->isNotEmpty())
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Artigos Relacionados</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($relatedPosts as $relatedPost)
                        <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $relatedPost->featured_image_url }}" alt="{{ $relatedPost->title }}" class="w-full h-48 object-cover">
                                </div>
                                <div class="p-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedPost->title }}</h4>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $relatedPost->meta_description }}</p>
                                    <div class="flex items-center justify-between">
                                        <time datetime="{{ $relatedPost->published_at->format('Y-m-d') }}" class="text-sm text-gray-500">
                                            {{ $relatedPost->published_at->format('d/m/Y') }}
                                        </time>
                                        <span class="text-blue-600 text-sm font-medium">Ler mais →</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="mt-12 lg:mt-0 lg:col-span-4">
                <!-- Author Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full" src="{{ $post->author->profile_photo_url }}" alt="{{ $post->author->name }}">
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $post->author->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $post->author->bio }}</p>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Categorias</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" 
                           class="flex items-center justify-between text-gray-600 hover:text-blue-600">
                            <span>{{ $category->name }}</span>
                            <span class="text-sm text-gray-400">{{ $category->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-blue-600 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-white mb-2">Receba nossas atualizações</h3>
                    <p class="text-blue-100 text-sm mb-4">
                        Inscreva-se para receber as últimas novidades e dicas sobre o mercado imobiliário.
                    </p>
                    <form class="space-y-3">
                        <input type="email" 
                               class="w-full px-4 py-2 rounded-md border border-transparent placeholder-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-600 focus:ring-white focus:border-white" 
                               placeholder="Seu melhor email">
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-600 focus:ring-white">
                            Inscrever-se
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
    }
    .aspect-w-16 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose img {
        border-radius: 0.5rem;
    }
    .prose a {
        color: #2563eb;
        text-decoration: none;
    }
    .prose a:hover {
        text-decoration: underline;
    }
</style>
@endpush 