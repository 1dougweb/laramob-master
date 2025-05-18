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
        if (!Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('broker_id');
                $table->unsignedBigInteger('contract_id');
                $table->unsignedBigInteger('transaction_id')->nullable();
                $table->decimal('amount', 12, 2);
                $table->decimal('rate', 5, 2)->nullable();
                $table->date('date');
                $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
                $table->date('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Add foreign key constraints only if the referenced tables exist
        if (Schema::hasTable('commissions')) {
            Schema::table('commissions', function (Blueprint $table) {
                if (Schema::hasTable('people')) {
                    $table->foreign('broker_id')->references('id')->on('people');
                }
                
                if (Schema::hasTable('contracts')) {
                    $table->foreign('contract_id')->references('id')->on('contracts');
                }
                
                if (Schema::hasTable('transactions')) {
                    $table->foreign('transaction_id')->references('id')->on('transactions');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
