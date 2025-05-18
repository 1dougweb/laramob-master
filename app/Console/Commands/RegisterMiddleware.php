<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegisterMiddleware extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'middleware:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register the admin and role middleware in Kernel.php';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $kernelPath = app_path('Http/Kernel.php');
        $kernelContent = File::get($kernelPath);

        $middlewareAliasesSection = 'protected $middlewareAliases = [';
        $adminMiddlewareString = "'admin' => \App\Http\Middleware\AdminMiddleware::class,";
        $roleMiddlewareString = "'role' => \App\Http\Middleware\CheckUserRole::class,";

        // Check if admin middleware already exists
        if (strpos($kernelContent, $adminMiddlewareString) !== false) {
            $this->info('Admin middleware already registered.');
        } else {
            // Find position to insert the middleware
            $position = strpos($kernelContent, $middlewareAliasesSection);
            if ($position !== false) {
                $insertPosition = strpos($kernelContent, '];', $position);
                $beforeInsert = substr($kernelContent, 0, $insertPosition);
                $afterInsert = substr($kernelContent, $insertPosition);

                // Insert the admin middleware
                $newContent = $beforeInsert . "\n        " . $adminMiddlewareString . $afterInsert;
                File::put($kernelPath, $newContent);
                $this->info('Admin middleware registered successfully.');
                $kernelContent = $newContent;
            } else {
                $this->error('Could not locate middlewareAliases section in Kernel.php.');
                return;
            }
        }

        // Check if role middleware already exists
        if (strpos($kernelContent, $roleMiddlewareString) !== false) {
            $this->info('Role middleware already registered.');
        } else {
            // Find position to insert the middleware
            $position = strpos($kernelContent, $middlewareAliasesSection);
            if ($position !== false) {
                $insertPosition = strpos($kernelContent, '];', $position);
                $beforeInsert = substr($kernelContent, 0, $insertPosition);
                $afterInsert = substr($kernelContent, $insertPosition);

                // Insert the role middleware
                $newContent = $beforeInsert . "\n        " . $roleMiddlewareString . $afterInsert;
                File::put($kernelPath, $newContent);
                $this->info('Role middleware registered successfully.');
            } else {
                $this->error('Could not locate middlewareAliases section in Kernel.php.');
            }
        }
    }
}
