<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->get();
        $categories = BlogCategory::withCount('posts')->get();
        $comments = BlogComment::with('post')->latest()->get();

        return view('admin.blog.index', compact('posts', 'categories', 'comments'));
    }
} 