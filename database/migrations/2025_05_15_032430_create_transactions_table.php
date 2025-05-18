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
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bank_account_id');
                $table->enum('type', ['income', 'expense'])->default('income');
                $table->string('description');
                $table->decimal('amount', 12, 2);
                $table->date('date');
                $table->date('due_date')->nullable();
                $table->date('payment_date')->nullable();
                $table->enum('category', ['rent', 'sale', 'commission', 'tax', 'maintenance', 'other'])->default('other');
                $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
                $table->unsignedBigInteger('contract_id')->nullable();
                $table->unsignedBigInteger('property_id')->nullable();
                $table->unsignedBigInteger('person_id')->nullable();
                $table->string('document_number')->nullable();
                $table->string('attachment')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Add foreign key constraints only if the referenced tables exist
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasTable('bank_accounts')) {
                    $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
                }
                
                if (Schema::hasTable('contracts')) {
                    $table->foreign('contract_id')->references('id')->on('contracts');
                }
                
                if (Schema::hasTable('properties')) {
                    $table->foreign('property_id')->references('id')->on('properties');
                }
                
                if (Schema::hasTable('people')) {
                    $table->foreign('person_id')->references('id')->on('people');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
