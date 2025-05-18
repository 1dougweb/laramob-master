<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StyleService;
use Illuminate\Support\Facades\Storage;

class GenerateStyles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laramob:generate-styles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate static CSS file from style settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating styles from settings...');
        
        try {
            $styleService = new StyleService();
            $css = $styleService->generateCssVariables();
            
            // Save to storage
            Storage::disk('public')->put('css/dynamic-styles.css', $css);
            
            // Also save to public directory for direct access
            if (!file_exists(public_path('css'))) {
                mkdir(public_path('css'), 0755, true);
            }
            
            file_put_contents(public_path('css/dynamic-styles.css'), $css);
            
            $this->info('Styles generated successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to generate styles: ' . $e->getMessage());
            return 1;
        }
    }
} 