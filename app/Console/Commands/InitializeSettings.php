<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingsService;

class InitializeSettings extends Command
{
    protected $signature = 'settings:initialize';
    protected $description = 'Initialize default settings';

    public function handle(SettingsService $settingsService)
    {
        $this->info('Initializing default settings...');
        
        $settingsService->initializeDefaultSettings();
        
        $this->info('Default settings initialized successfully!');
    }
} 