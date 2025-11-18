<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('presupuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('codigo');
            $table->string('nombre');
            $table->string('version')->nullable();
            $table->enum('estado',['borrador','aprobado','cerrado'])->default('borrador');
            $table->decimal('monto_total',14,2)->default(0);
            $table->foreignId('creado_por')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['proyecto_id','codigo']);
        });

        Schema::create('presupuesto_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuesto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('codigo_partida')->nullable();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('cantidad_planificada',18,6);
            $table->decimal('precio_unitario_planificado',14,2);
            $table->decimal('total_planificado',14,2);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('presupuesto_detalle');
        Schema::dropIfExists('presupuesto');
    }
};
