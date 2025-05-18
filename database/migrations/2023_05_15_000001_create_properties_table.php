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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->foreignId('property_type_id')->constrained()->onDelete('restrict');
            $table->text('description');
            $table->foreignId('city_id')->constrained()->onDelete('restrict');
            $table->foreignId('district_id')->constrained()->onDelete('restrict');
            $table->string('address');
            $table->decimal('area', 10, 2);
            $table->decimal('built_area', 10, 2)->nullable();
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('suites')->nullable();
            $table->integer('parking')->nullable();
            $table->json('features')->nullable(); // JSON field for features with icons
            $table->enum('purpose', ['sale', 'rent', 'both']);
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('rental_price', 12, 2)->nullable();
            $table->decimal('condominium_fee', 10, 2)->nullable();
            $table->decimal('iptu', 10, 2)->nullable();
            $table->enum('status', ['available', 'sold', 'rented', 'reserved', 'inactive'])->default('available');
            $table->string('featured_image')->nullable();
            $table->foreignId('owner_id')->nullable()->constrained('people')->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
}; 