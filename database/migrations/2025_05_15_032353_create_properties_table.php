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
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->foreignId('property_type_id')->constrained();
                $table->foreignId('district_id')->constrained();
                $table->foreignId('city_id')->constrained();
                $table->foreignId('owner_id')->nullable()->constrained('people');
                $table->decimal('price', 12, 2)->default(0);
                $table->decimal('rental_price', 12, 2)->nullable();
                $table->decimal('condominium_fee', 12, 2)->nullable();
                $table->decimal('iptu', 12, 2)->nullable();
                $table->decimal('area', 10, 2)->nullable();
                $table->decimal('built_area', 10, 2)->nullable();
                $table->integer('bedrooms')->default(0);
                $table->integer('bathrooms')->default(0);
                $table->integer('suites')->default(0);
                $table->integer('parking')->default(0);
                $table->string('address');
                $table->string('address_number')->nullable();
                $table->string('complement')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->text('features')->nullable();
                $table->enum('purpose', ['sale', 'rent', 'both'])->default('sale');
                $table->enum('status', ['available', 'sold', 'rented', 'reserved', 'inactive'])->default('available');
                $table->boolean('is_featured')->default(false);
                $table->string('featured_image')->nullable();
                $table->string('slug')->unique();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
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
        Schema::dropIfExists('properties');
    }
};
