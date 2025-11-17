<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('ubicacion_texto')->nullable();
            $table->decimal('latitud',10,7)->nullable();
            $table->decimal('longitud',10,7)->nullable();
            $table->foreignId('responsable_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado',['planificado','activo','pausado','cerrado','cancelado'])->default('planificado');
            $table->timestamps();
        });

        Schema::create('beneficiario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('tipo_documento')->default('CI');
            $table->string('nro_documento');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion_vivienda')->nullable();
            $table->decimal('latitud',10,7)->nullable();
            $table->decimal('longitud',10,7)->nullable();
            $table->string('ruta_fotografia')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->index(['proyecto_id','nro_documento']);
        });

        Schema::create('cliente', function (Blueprint $table) {
            $table->id();
            $table->string('nombres')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('nro_fiscal')->nullable(); // NIT/CI
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ruta_fotografia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('cliente');
        Schema::dropIfExists('beneficiario');
        Schema::dropIfExists('proyecto');
    }
};
