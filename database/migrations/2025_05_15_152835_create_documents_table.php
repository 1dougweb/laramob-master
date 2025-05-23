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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size'); // tamanho em bytes
            $table->text('description')->nullable();
            $table->enum('type', ['contract', 'identity', 'address_proof', 'property', 'financial', 'other']);
            $table->date('expiration_date')->nullable();
            $table->boolean('is_private')->default(true); // Se true, apenas admin pode ver
            $table->timestamp('shared_at')->nullable(); // Quando foi compartilhado com o cliente
            $table->timestamps();
            $table->softDeletes(); // Permitir exclusão lógica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
