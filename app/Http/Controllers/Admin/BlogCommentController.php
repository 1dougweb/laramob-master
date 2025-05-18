<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['post', 'user']);
        
        // Filter by approval status
        if ($request->has('is_approved') && $request->is_approved !== '') {
            $query->where('is_approved', $request->is_approved == '1');
        }
        
        // Filter by post if provided
        if ($request->has('post_id') && $request->post_id) {
            $query->where('blog_post_id', $request->post_id);
        }
        
        // Filter by search term if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%");
            });
        }
        
        $comments = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $posts = BlogPost::published()->orderBy('title')->get();
        
        return view('admin.blog.comments.index', compact('comments', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = BlogComment::with(['post', 'user', 'parent', 'replies'])->findOrFail($id);
        return view('admin.blog.comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $comment = BlogComment::with(['post'])->findOrFail($id);
        return view('admin.blog.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'is_approved' => 'nullable|boolean',
        ]);
        
        $comment = BlogComment::findOrFail($id);
        
        $comment->update([
            'content' => $request->content,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'is_approved' => $request->has('is_approved'),
        ]);
        
        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Comentário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $comment = BlogComment::findOrFail($id);
            $comment->delete();
            
            return redirect()
                ->route('admin.blog-comments.index')
                ->with('success', 'Comentário excluído com sucesso.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir comentário: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle approval status for a comment
     */
    public function toggleApproval(string $id)
    {
        try {
            $comment = BlogComment::findOrFail($id);
            $comment->is_approved = !$comment->is_approved;
            $comment->save();
            
            return redirect()
                ->back()
                ->with('success', 'Status do comentário alterado com sucesso.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao alterar status do comentário: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve multiple comments
     */
    public function approveMultiple(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id',
        ]);
        
        try {
            BlogComment::whereIn('id', $request->comment_ids)
                ->update(['is_approved' => true]);
            
            return redirect()
                ->back()
                ->with('success', count($request->comment_ids) . ' comentários aprovados com sucesso.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao aprovar comentários: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete multiple comments
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id',
        ]);
        
        try {
            BlogComment::whereIn('id', $request->comment_ids)->delete();
            
            return redirect()
                ->back()
                ->with('success', count($request->comment_ids) . ' comentários excluídos com sucesso.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir comentários: ' . $e->getMessage());
        }
    }
}
