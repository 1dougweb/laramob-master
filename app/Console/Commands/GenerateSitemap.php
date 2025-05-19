<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');
        
        try {
            $sitemapService = new SitemapService();
            $result = $sitemapService->generate();
            
            if ($result) {
                $this->info('Sitemap generated successfully!');
                return 0;
            } else {
                $this->error('Failed to generate sitemap.');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error generating sitemap: ' . $e->getMessage());
            return 1;
        }
    }
} 