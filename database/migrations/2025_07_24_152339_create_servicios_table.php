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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',20)->unique(); // Considero suficientes 20 caracteres para un código de servicio
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('destino',100);
            $table->date('fecha');
            $table->decimal('costo', 10,2);
            $table->softDeletes();
            $table->timestamps();

            $table->index('codigo');
            $table->index('destino');
            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
