<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // First add document_type if it doesn't exist
            if (!Schema::hasColumn('people', 'document_type')) {
                $table->string('document_type')->nullable()->after('document');
            }

            // Then add the rest of the fields
            if (!Schema::hasColumn('people', 'marital_status')) {
                $table->string('marital_status')->nullable()->after('document_type');
            }
            
            if (!Schema::hasColumn('people', 'nationality')) {
                $table->string('nationality', 50)->nullable()->after('marital_status');
            }
            
            if (!Schema::hasColumn('people', 'profession')) {
                $table->string('profession', 100)->nullable()->after('nationality');
            }
            
            if (!Schema::hasColumn('people', 'bank_name')) {
                $table->string('bank_name', 100)->nullable()->after('notes');
            }
            
            if (!Schema::hasColumn('people', 'bank_agency')) {
                $table->string('bank_agency', 20)->nullable()->after('bank_name');
            }
            
            if (!Schema::hasColumn('people', 'bank_account')) {
                $table->string('bank_account', 20)->nullable()->after('bank_agency');
            }
            
            if (!Schema::hasColumn('people', 'pix_key')) {
                $table->string('pix_key', 100)->nullable()->after('bank_account');
            }
            
            if (!Schema::hasColumn('people', 'broker_id')) {
                $table->foreignId('broker_id')->nullable()->after('user_id')
                      ->references('id')->on('people')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('people', 'broker_id')) {
                $table->dropForeign(['broker_id']);
            }
            
            $columns = [
                'marital_status',
                'nationality',
                'profession',
                'bank_name',
                'bank_agency',
                'bank_account',
                'pix_key',
                'broker_id',
                'document_type'
            ];
            
            // Only drop columns that exist
            $existingColumns = [];
            foreach ($columns as $column) {
                if (Schema::hasColumn('people', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (count($existingColumns) > 0) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
