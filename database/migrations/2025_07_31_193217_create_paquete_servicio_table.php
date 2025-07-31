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
        Schema::create('paquete_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paquete_id')->constrained()->onDelete('restrict');
            $table->foreignId('servicio_id')->constrained()->onDelete('restrict');
            $table->timestamps();

            // Evitar duplicados con el mismo paquete y servicio
            $table->unique(['paquete_id', 'servicio_id']);

            $table->index('paquete_id');
            $table->index('servicio_id');

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquete_servicio');
    }
};
