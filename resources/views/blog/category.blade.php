<x-frontend-layout>
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="{{ $category->image_url }}" alt="{{ $category->name }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {{ $category->name }}
                </h1>
                <p class="mt-6 text-xl text-white/80">
                    {{ $category->description }}
                </p>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            @foreach($post->categories as $postCategory)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $postCategory->name }}
                            </span>
                            @endforeach
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $post->meta_description }}</p>
                        <div class="flex items-center justify-between">
                            <time datetime="{{ $post->published_at->format('Y-m-d') }}" class="text-sm text-gray-500">
                                {{ $post->published_at->format('d/m/Y') }}
                            </time>
                            <span class="text-blue-600 text-sm font-medium">Ler mais →</span>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @endif
    </div>

    <!-- Categories -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Outras Categorias</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($otherCategories as $otherCategory)
                <a href="{{ route('blog.category', $otherCategory->slug) }}" 
                   class="group relative rounded-lg overflow-hidden bg-gray-100 hover:bg-gray-200 transition-colors duration-300">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ $otherCategory->image_url }}" alt="{{ $otherCategory->name }}" class="w-full h-32 object-cover opacity-75 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-lg font-semibold text-white">{{ $otherCategory->name }}</h3>
                        <p class="text-sm text-gray-200">{{ $otherCategory->posts_count }} artigos</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Newsletter -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Receba nossas atualizações
                </h2>
                <p class="mt-4 text-lg text-blue-100">
                    Inscreva-se para receber as últimas novidades e dicas sobre o mercado imobiliário.
                </p>
                <form class="mt-8 sm:flex justify-center">
                    <label for="email-address" class="sr-only">Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-5 py-3 border border-transparent placeholder-gray-500 focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white focus:border-white sm:max-w-xs rounded-md" placeholder="Seu melhor email">
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white">
                            Inscrever-se
                        </button>
                    </div>
                </form>
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
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush 