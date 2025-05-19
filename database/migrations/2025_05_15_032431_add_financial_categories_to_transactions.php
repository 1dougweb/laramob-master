<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add new field to categorize transactions for financial reporting
            $table->string('financial_category')->nullable()->after('category');
            
            // Add installment tracking fields
            $table->integer('installment_number')->nullable()->after('notes');
            $table->integer('total_installments')->nullable()->after('installment_number');
            $table->string('recurring_id')->nullable()->after('total_installments');
            $table->text('recurrence_info')->nullable()->after('recurring_id');
        });
        
        // Update the enum for category to include common accounts payable/receivable categories
        DB::statement("ALTER TABLE transactions MODIFY COLUMN category ENUM('rent', 'sale', 'commission', 'tax', 'maintenance', 'utility', 'salary', 'service', 'loan', 'insurance', 'supplier', 'client', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('financial_category');
            $table->dropColumn('installment_number');
            $table->dropColumn('total_installments');
            $table->dropColumn('recurring_id');
            $table->dropColumn('recurrence_info');
        });
        
        // Revert enum to original values
        DB::statement("ALTER TABLE transactions MODIFY COLUMN category ENUM('rent', 'sale', 'commission', 'tax', 'maintenance', 'other') DEFAULT 'other'");
    }
}; 