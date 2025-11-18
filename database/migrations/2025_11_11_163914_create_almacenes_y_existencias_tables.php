<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('almacen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyecto')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('codigo');
            $table->string('nombre');
            $table->string('ubicacion_texto')->nullable();
            $table->decimal('latitud',10,7)->nullable();
            $table->decimal('longitud',10,7)->nullable();
            $table->timestamps();
            $table->unique(['proyecto_id','codigo']);
        });

        Schema::create('existencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->constrained('almacen')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('cantidad_disponible',18,6)->default(0);
            $table->decimal('nivel_minimo',18,6)->nullable();
            $table->decimal('nivel_maximo',18,6)->nullable();
            $table->timestamps();
            $table->unique(['almacen_id','material_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('existencia');
        Schema::dropIfExists('almacen');
    }
};
