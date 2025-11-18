<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('requisicion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('solicitante_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('almacen_id')->constrained('almacen')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha_solicitud');
            $table->enum('estado',['borrador','enviada','aprobada','rechazada','parcial','atendida','cancelada'])->default('borrador');
            $table->integer('total_items')->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('requisicion_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicion_id')->constrained('requisicion')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('cantidad_solicitada',18,6);
            $table->decimal('cantidad_aprobada',18,6)->nullable();
            $table->decimal('cantidad_entregada',18,6)->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('flujo_aprobacion', function (Blueprint $table) {
            $table->id();
            $table->string('entidad_tipo'); // 'requisicion', etc.
            $table->unsignedBigInteger('entidad_id');
            $table->enum('accion',['aprobado','rechazado']);
            $table->foreignId('actor_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('fecha_accion');
            $table->text('comentario')->nullable();
            $table->timestamps();
            $table->index(['entidad_tipo','entidad_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('flujo_aprobacion');
        Schema::dropIfExists('requisicion_detalle');
        Schema::dropIfExists('requisicion');
    }
};
