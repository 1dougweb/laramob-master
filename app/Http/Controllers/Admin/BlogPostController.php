<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id) {
            $query->where('blog_category_id', $request->category_id);
        }
        
        // Filter by search term if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        $posts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = BlogCategory::active()->get();
        
        return view('admin.blog.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blog.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts',
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Handle featured image upload
            $featuredImagePath = null;
            if ($request->hasFile('featured_image')) {
                $featuredImagePath = $request->file('featured_image')->store('blog', 'public');
            }
            
            // Set published_at based on status
            $publishedAt = null;
            if ($request->status === 'published') {
                $publishedAt = $request->filled('published_at') ? $request->published_at : now();
            }
            
            // Create blog post
            $post = new BlogPost([
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'summary' => $request->summary,
                'content' => $request->content,
                'featured_image' => $featuredImagePath,
                'blog_category_id' => $request->blog_category_id,
                'user_id' => Auth::id(),
                'status' => $request->status,
                'is_featured' => $request->has('is_featured'),
                'allow_comments' => $request->has('allow_comments'),
                'published_at' => $publishedAt,
            ]);
            
            $post->save();
            
            DB::commit();
            
            return redirect()
                ->route('admin.blog-posts.index')
                ->with('success', 'Post criado com sucesso.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Erro ao criar post: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = BlogPost::with(['category', 'author', 'comments.user'])->findOrFail($id);
        return view('admin.blog.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::active()->ordered()->get();
        return view('admin.blog.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = BlogPost::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'summary' => 'nullable|string|max:1000',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old featured image if exists
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                
                $featuredImagePath = $request->file('featured_image')->store('blog', 'public');
                $post->featured_image = $featuredImagePath;
            }
            
            // Set published_at based on status
            if ($request->status === 'published' && $post->status !== 'published') {
                $post->published_at = $request->filled('published_at') ? $request->published_at : now();
            } elseif ($request->status !== 'published') {
                $post->published_at = null;
            } elseif ($request->filled('published_at')) {
                $post->published_at = $request->published_at;
            }
            
            // Update post
            $post->title = $request->title;
            $post->slug = $request->slug ?: Str::slug($request->title);
            $post->summary = $request->summary;
            $post->content = $request->content;
            $post->blog_category_id = $request->blog_category_id;
            $post->status = $request->status;
            $post->is_featured = $request->has('is_featured');
            $post->allow_comments = $request->has('allow_comments');
            
            $post->save();
            
            DB::commit();
            
            return redirect()
                ->route('admin.blog-posts.index')
                ->with('success', 'Post atualizado com sucesso.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Erro ao atualizar post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = BlogPost::findOrFail($id);
            
            // Delete featured image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            // Delete the post
            $post->delete();
            
            return redirect()
                ->route('admin.blog-posts.index')
                ->with('success', 'Post excluído com sucesso.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir post: ' . $e->getMessage());
        }
    }
}
