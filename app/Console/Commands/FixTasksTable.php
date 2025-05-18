<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTasksTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:fix-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the tasks table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking tasks table structure...');

        // Check if the table exists
        if (!Schema::hasTable('tasks')) {
            $this->error('Tasks table does not exist!');
            return 1;
        }

        // Get table columns
        $columns = Schema::getColumnListing('tasks');
        $this->info('Current columns: ' . implode(', ', $columns));

        // Check if user_id column exists
        if (!in_array('user_id', $columns)) {
            $this->warn('user_id column does not exist. Adding it...');
            
            try {
                Schema::table('tasks', function ($table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                });
                
                $this->info('user_id column added successfully!');
                
                // Set default user_id to 1 (assuming admin)
                DB::table('tasks')->update(['user_id' => 1]);
                $this->info('Set default user_id to 1 for all tasks');
            } catch (\Exception $e) {
                $this->error('Failed to add user_id column: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('user_id column already exists.');
        }

        // Check if deleted_at column exists for soft deletes
        if (!in_array('deleted_at', $columns)) {
            $this->warn('deleted_at column does not exist. Adding it...');
            
            try {
                Schema::table('tasks', function ($table) {
                    $table->softDeletes();
                });
                
                $this->info('deleted_at column added successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to add deleted_at column: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('deleted_at column already exists.');
        }

        $this->info('Tasks table structure fixed successfully!');
        return 0;
    }
}
