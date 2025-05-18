<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::withCount('posts')
            ->orderBy('order', 'asc')
            ->paginate(15);
            
        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $category = new BlogCategory([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'order' => $request->order ?: 0,
            'is_active' => $request->has('is_active'),
        ]);
        
        $category->save();
        
        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = BlogCategory::findOrFail($id);
        
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = BlogCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'order' => $request->order ?: 0,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $category = BlogCategory::findOrFail($id);
            
            // Update posts to remove category association
            $category->posts()->update(['blog_category_id' => null]);
            
            // Delete the category
            $category->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.blog-categories.index')
                ->with('success', 'Categoria excluída com sucesso.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Erro ao excluir categoria: ' . $e->getMessage());
        }
    }
    
    /**
     * Reorder categories
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:blog_categories,id',
            'categories.*.order' => 'required|integer|min:0',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->categories as $categoryData) {
                BlogCategory::where('id', $categoryData['id'])
                    ->update(['order' => $categoryData['order']]);
            }
            
            DB::commit();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
