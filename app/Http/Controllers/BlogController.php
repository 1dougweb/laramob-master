<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc');
            
        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by search term if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }
        
        $posts = $query->paginate(9)->withQueryString();
        
        // Get categories for sidebar
        $categories = BlogCategory::active()->ordered()->withCount(['posts' => function($query) {
            $query->published();
        }])->get();
        
        // Get featured posts for sidebar
        $featuredPosts = BlogPost::published()->featured()->take(5)->get();
        
        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'author', 'rootComments.replies', 'rootComments.user'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Increment view count
        $post->incrementViewCount();
        
        // Get categories for sidebar
        $categories = BlogCategory::active()->ordered()->withCount(['posts' => function($query) {
            $query->published();
        }])->get();
        
        // Get related posts based on category
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->take(3)
            ->get();
            
        // Get featured posts for sidebar
        $featuredPosts = BlogPost::published()->featured()->where('id', '!=', $post->id)->take(5)->get();
        
        return view('blog.show', compact('post', 'categories', 'relatedPosts', 'featuredPosts'));
    }
    
    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->active()->firstOrFail();
        
        $posts = BlogPost::with(['category', 'author'])
            ->published()
            ->where('blog_category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(9);
            
        // Get all categories for sidebar
        $categories = BlogCategory::active()->ordered()->withCount(['posts' => function($query) {
            $query->published();
        }])->get();
        
        // Get featured posts for sidebar
        $featuredPosts = BlogPost::published()->featured()->take(5)->get();
        
        return view('blog.category', compact('category', 'posts', 'categories', 'featuredPosts'));
    }
    
    /**
     * Store a newly created comment.
     */
    public function storeComment(Request $request, $postId)
    {
        $post = BlogPost::published()->findOrFail($postId);
        
        // Check if comments are allowed
        if (!$post->allow_comments) {
            return back()->with('error', 'Comentários estão desativados para este post.');
        }
        
        // Validate request
        $rules = [
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];
        
        // Add author name and email validation for guest users
        if (!Auth::check()) {
            $rules['author_name'] = 'required|string|max:255';
            $rules['author_email'] = 'required|email|max:255';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Create comment
        $comment = new BlogComment([
            'blog_post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'is_approved' => Auth::check(), // Auto approve for logged in users
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        if (Auth::check()) {
            $comment->user_id = Auth::id();
        } else {
            $comment->author_name = $request->author_name;
            $comment->author_email = $request->author_email;
        }
        
        $comment->save();
        
        return back()->with('success', Auth::check() 
            ? 'Comentário adicionado com sucesso.' 
            : 'Comentário enviado para moderação e será publicado após aprovação.');
    }
}
