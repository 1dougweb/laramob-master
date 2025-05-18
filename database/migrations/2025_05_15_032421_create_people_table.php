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
        if (!Schema::hasTable('people')) {
            Schema::create('people', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('document')->nullable();
                $table->date('birth_date')->nullable();
                $table->foreignId('user_id')->nullable()->constrained();
                $table->enum('type', ['employee', 'client', 'owner', 'tenant', 'broker'])->default('client');
                $table->decimal('commission_rate', 5, 2)->nullable();
                $table->string('address')->nullable();
                $table->string('address_number')->nullable();
                $table->string('complement')->nullable();
                $table->string('neighborhood')->nullable();
                $table->string('city')->nullable();
                $table->string('state', 2)->nullable();
                $table->string('zip_code', 10)->nullable();
                $table->text('notes')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
