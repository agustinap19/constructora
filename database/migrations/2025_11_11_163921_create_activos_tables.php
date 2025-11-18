<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('activo', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_activo',['maquinaria','herramienta','vehiculo'])->default('herramienta');
            $table->date('fecha_compra')->nullable();
            $table->decimal('costo',14,2)->nullable();
            $table->enum('estado',['operativo','mantenimiento','fuera_de_servicio'])->default('operativo');
            $table->timestamps();
        });

        Schema::create('asignacion_activo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('activo')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('proyecto_id')->nullable()->constrained('proyecto')->nullOnDelete();
            $table->foreignId('almacen_id')->nullable()->constrained('almacen')->nullOnDelete();
            $table->date('asignado_desde');
            $table->date('asignado_hasta')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asignacion_activo');
        Schema::dropIfExists('activo');
    }
};
