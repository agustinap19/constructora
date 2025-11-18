<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lead', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_lead',['cotizacion','cita']);
            $table->string('nombre_completo');
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('origen')->default('web'); // web/chatbot/formulario
            $table->string('pagina_origen')->nullable();
            $table->json('utm_json')->nullable();
            $table->string('ubicacion_texto')->nullable();
            $table->enum('estado',['nuevo','contactado','programado','cerrado_ganado','cerrado_perdido'])->default('nuevo');
            $table->foreignId('asignado_a')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('proyecto_enlazado_id')->nullable()->constrained('proyecto')->nullOnDelete();
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('lead')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('tipo_proyecto')->nullable();
            $table->string('ubicacion_texto')->nullable();
            $table->decimal('total_estimado',14,2)->default(0);
            $table->dateTime('enviada_al_lead_at')->nullable();
            $table->dateTime('enviada_a_la_empresa_at')->nullable();
            $table->enum('estado',['borrador','enviada','convertida','rechazada','vencida'])->default('borrador');
            $table->timestamps();
        });

        Schema::create('cotizacion_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizacion')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('tipo_concepto',['material','partida','servicio'])->default('material');
            $table->unsignedBigInteger('concepto_id')->nullable(); // si enlazas a material
            $table->string('descripcion');
            $table->decimal('cantidad',18,6)->default(1);
            $table->string('unidad')->nullable();
            $table->decimal('precio_unitario',14,2)->default(0);
            $table->decimal('subtotal',14,2)->default(0);
            $table->timestamps();
        });

        Schema::create('cita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('lead')->nullOnDelete();
            $table->enum('tipo',['presencial','virtual'])->default('presencial');
            $table->dateTime('fecha_hora_solicitada')->nullable();
            $table->dateTime('fecha_hora_programada')->nullable();
            $table->integer('duracion_minutos')->default(30);
            $table->string('ubicacion_texto')->nullable();
            $table->string('enlace_reunion')->nullable();
            $table->enum('estado',['pendiente','confirmada','asistida','no_show','cancelada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('historial_estado_lead', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('lead')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('estado_anterior',['nuevo','contactado','programado','cerrado_ganado','cerrado_perdido'])->nullable();
            $table->enum('estado_nuevo',['nuevo','contactado','programado','cerrado_ganado','cerrado_perdido']);
            $table->foreignId('cambiado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('cambiado_en');
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('historial_estado_lead');
        Schema::dropIfExists('cita');
        Schema::dropIfExists('cotizacion_detalle');
        Schema::dropIfExists('cotizacion');
        Schema::dropIfExists('lead');
    }
};
