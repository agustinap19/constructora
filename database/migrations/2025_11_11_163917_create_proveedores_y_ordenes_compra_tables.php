<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nro_fiscal')->nullable(); // NIT
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('orden_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedor')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('almacen_id')->constrained('almacen')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha_orden');
            $table->date('fecha_comprometida')->nullable();
            $table->enum('estado',['borrador','aprobada','recibida','cerrada','cancelada'])->default('borrador');
            $table->decimal('monto_total',14,2)->default(0);
            $table->text('observaciones')->nullable();
            $table->foreignId('creado_por')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('orden_compra_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_compra_id')->constrained('orden_compra')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('cantidad_pedida',18,6);
            $table->decimal('precio_unitario',14,2);
            $table->decimal('subtotal',14,2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orden_compra_detalle');
        Schema::dropIfExists('orden_compra');
        Schema::dropIfExists('proveedor');
    }
};
