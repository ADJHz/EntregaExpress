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
        Schema::create('elementos_entregas', function (Blueprint $table) {
            $table->id();

            // Datos del elemento
            $table->string('nombre');
            $table->string('csp')->nullable()->comment('Código de Seguridad o Identificador');
            $table->string('ubicacion')->nullable();
            $table->string('coordinacion')->nullable();
            $table->string('genero', 50)->nullable();

            // Detalles del uniforme
            $table->string('tipo_uniforme')->nullable();
            $table->string('color_franja')->nullable(); // Asumí "FRANJA" en lugar de "FLANJA"

            // Tallas (Usamos string por si hay tallas como 'XL', '28', 'Unitalla')
            $table->string('camisola', 20)->nullable();
            $table->string('pantalon', 20)->nullable();
            $table->string('chamarra', 20)->nullable();
            $table->string('bota', 20)->nullable();
            $table->string('cinturon', 20)->nullable();

            // Estatus de entrega
            // Usamos boolean si solo es un "Sí/No", o puedes cambiarlo a string si necesitas el nombre de quien firma
            $table->boolean('recibio')->default(false);

            // Trazabilidad (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementos_entregas');
    }
};
