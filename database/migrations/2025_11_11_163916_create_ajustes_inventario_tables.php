<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('motivo_ajuste_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('ajuste_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->constrained('almacen')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('motivo_ajuste_id')->constrained('motivo_ajuste_inventario')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('fecha_ajuste');
            $table->text('observaciones')->nullable();
            $table->foreignId('creado_por')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('ajuste_inventario_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuste_inventario_id')->constrained('ajuste_inventario')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('variacion_cantidad',18,6);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ajuste_inventario_detalle');
        Schema::dropIfExists('ajuste_inventario');
        Schema::dropIfExists('motivo_ajuste_inventario');
    }
};
