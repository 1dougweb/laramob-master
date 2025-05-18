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
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->text('message');
                $table->foreignId('property_id')->nullable()->constrained();
                $table->boolean('is_read')->default(false);
                $table->enum('status', ['pending', 'contacted', 'converted', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable(); // We'll add the constraint later
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
        // Add foreign key constraint only if people table exists
        if (Schema::hasTable('people') && Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->foreign('assigned_to')->references('id')->on('people');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
