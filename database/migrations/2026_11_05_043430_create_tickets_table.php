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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('estado')->default('abierto');
            $table->string('prioridad')->default('media');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('solicitante_id')->constrained('users');
            $table->foreignId('tecnico_id')->nullable()->constrained('users');
            $table->timestamp('cerrado_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
