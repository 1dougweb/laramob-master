<?php

namespace App\Services;

use App\Models\Property;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    public function generate()
    {
        try {
            Log::info('Starting sitemap generation');
            
            $sitemap = Sitemap::create();
            $baseUrl = config('app.url');

            // Add static pages
            $staticPages = [
                '/' => ['priority' => 1.0, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
                '/properties' => ['priority' => 0.9, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
                '/blog' => ['priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
                '/about' => ['priority' => 0.7, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
                '/contact' => ['priority' => 0.7, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ];

            foreach ($staticPages as $path => $config) {
                $sitemap->add(Url::create($path)
                    ->setChangeFrequency($config['frequency'])
                    ->setPriority($config['priority']));
                Log::info("Added static page to sitemap: {$path}");
            }

            // Add properties
            $properties = Property::where('is_active', true)->get();
            Log::info("Found {$properties->count()} active properties");
            
            foreach ($properties as $property) {
                $sitemap->add(Url::create("/properties/{$property->slug}")
                    ->setLastModificationDate($property->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8));
                Log::info("Added property to sitemap: {$property->slug}");
            }

            // Add blog posts
            $posts = BlogPost::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->get();
            Log::info("Found {$posts->count()} published blog posts");
            
            foreach ($posts as $post) {
                $sitemap->add(Url::create("/blog/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7));
                Log::info("Added blog post to sitemap: {$post->slug}");
            }

            // Ensure the public directory exists and is writable
            $sitemapPath = public_path('sitemap.xml');
            $directory = dirname($sitemapPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                Log::info("Created directory: {$directory}");
            }

            if (!is_writable($directory)) {
                chmod($directory, 0755);
                Log::info("Changed permissions for directory: {$directory}");
            }

            // Save the sitemap
            $sitemap->writeToFile($sitemapPath);
            
            if (file_exists($sitemapPath)) {
                Log::info("Sitemap generated successfully at: {$sitemapPath}");
                return true;
            } else {
                Log::error("Failed to generate sitemap at: {$sitemapPath}");
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error generating sitemap: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return false;
        }
    }
} 