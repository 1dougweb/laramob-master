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
        if (!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id');
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('owner_id');
                $table->unsignedBigInteger('broker_id')->nullable();
                $table->string('code')->unique();
                $table->enum('type', ['sale', 'rent'])->default('sale');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->decimal('value', 12, 2);
                $table->decimal('commission_value', 12, 2)->nullable();
                $table->decimal('commission_rate', 5, 2)->nullable();
                $table->enum('payment_frequency', ['monthly', 'yearly', 'once'])->default('once');
                $table->integer('payment_day')->nullable();
                $table->enum('status', ['draft', 'active', 'finished', 'canceled'])->default('draft');
                $table->text('notes')->nullable();
                $table->string('file')->nullable();
                $table->string('inspection_report')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Add foreign key constraints only if the referenced tables exist
        if (Schema::hasTable('contracts')) {
            Schema::table('contracts', function (Blueprint $table) {
                if (Schema::hasTable('properties')) {
                    $table->foreign('property_id')->references('id')->on('properties');
                }
                
                if (Schema::hasTable('people')) {
                    $table->foreign('client_id')->references('id')->on('people');
                    $table->foreign('owner_id')->references('id')->on('people');
                    $table->foreign('broker_id')->references('id')->on('people');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
