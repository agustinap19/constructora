<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 🧾 Tabla principal de movimientos de inventario
         * (entradas, salidas, compras o ajustes)
         */
        Schema::create('movimiento_inventario', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo_movimiento', ['entrada', 'salida', 'compra', 'ajuste']);
            $table->foreignId('almacen_id')
                ->constrained('almacen')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('referencia_tipo')->nullable(); // orden_compra / requisicion / ajuste / manual / entrega
            $table->unsignedBigInteger('referencia_id')->nullable();

            $table->dateTime('fecha_movimiento');
            $table->text('observaciones')->nullable();

            $table->foreignId('creado_por')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            // ✅ índice con nombre corto
            $table->index(['almacen_id', 'fecha_movimiento'], 'mi_mov_alm_fecha_idx');
        });

        /**
         * 🧩 Detalle de cada movimiento (materiales y cantidades)
         */
        Schema::create('movimiento_inventario_detalle', function (Blueprint $table) {
            $table->id();

            $table->foreignId('movimiento_inventario_id')
                ->constrained('movimiento_inventario')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained('material')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('cantidad', 18, 6);
            $table->decimal('precio_unitario', 14, 2)->nullable();
            $table->decimal('subtotal', 14, 2)->nullable();
            $table->timestamps();

            // ✅ índice con nombre corto para evitar el error 1059
            $table->index(['movimiento_inventario_id', 'material_id'], 'mi_det_mov_mat_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventario_detalle');
        Schema::dropIfExists('movimiento_inventario');
    }
};
