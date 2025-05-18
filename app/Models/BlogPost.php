<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'status',
        'is_featured',
        'allow_comments',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Get the category that owns the post.
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Get the user that owns the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }
    
    /**
     * Get root level comments (no parent comment)
     */
    public function rootComments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id')->where('is_approved', true);
    }

    /**
     * Auto-generate slug from title when creating or updating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->isDirty('slug')) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    /**
     * Increment view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
        return $this;
    }
    
    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        return asset('images/blog-default.jpg');
    }
    
    /**
     * Get formatted published date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y') : '';
    }
}
