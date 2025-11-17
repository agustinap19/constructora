<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unidad', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',10)->unique();
            $table->string('nombre',50);
            $table->timestamps();
        });

        Schema::create('categoria_material', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('material', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->foreignId('unidad_id')->constrained('unidad')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('categoria_material_id')->nullable()->constrained('categoria_material')->nullOnDelete();
            $table->enum('estado',['activo','inactivo'])->default('activo');
            $table->timestamps();
            $table->index(['descripcion']);
        });

        Schema::create('precio_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('material')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('precio',14,2);
            $table->string('moneda',3)->default('BOB');
            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable(); // null = vigente
            $table->timestamps();
            $table->index(['material_id','vigente_desde']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('precio_material');
        Schema::dropIfExists('material');
        Schema::dropIfExists('categoria_material');
        Schema::dropIfExists('unidad');
    }
};
