<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('adjunto', function (Blueprint $table) {
            $table->id();
            $table->string('entidad_tipo');
            $table->unsignedBigInteger('entidad_id');
            $table->string('ruta_archivo');
            $table->string('nombre_original');
            $table->string('mime_type',100)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();
            $table->foreignId('subido_por')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->index(['entidad_tipo','entidad_id']);
        });

        Schema::create('log_email', function (Blueprint $table) {
            $table->id();
            $table->string('para_email');
            $table->string('asunto')->nullable();
            $table->string('entidad_tipo')->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->dateTime('enviado_en')->nullable();
            $table->string('estado',50)->nullable();
            $table->string('message_id')->nullable();
            $table->timestamps();
        });

        Schema::create('log_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('entidad_tipo');
            $table->unsignedBigInteger('entidad_id');
            $table->string('accion',50); // creado/actualizado/eliminado/aprobado/rechazado/login...
            $table->json('datos_antes_json')->nullable();
            $table->json('datos_despues_json')->nullable();
            $table->string('ip_address',45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('log_auditoria');
        Schema::dropIfExists('log_email');
        Schema::dropIfExists('adjunto');
    }
};
