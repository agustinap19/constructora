<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('entrega', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('almacen_id')->constrained('almacen')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('beneficiario_id')->constrained('beneficiario')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha_entrega');
            $table->enum('estado',['borrador','emitida','confirmada','cancelada'])->default('borrador');
            $table->string('ruta_fotografia_entrega')->nullable(); // evidencia
            $table->text('notas')->nullable();
            $table->foreignId('creado_por')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('entrega_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrega_id')->constrained('entrega')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('cantidad_entregada',18,6);
            $table->foreignId('requisicion_detalle_id')->nullable()->constrained('requisicion_detalle')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('entrega_detalle');
        Schema::dropIfExists('entrega');
    }
};
