<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Si existe 'name', lo dejamos o lo quitamos según prefieras:
            $table->dropColumn('name'); 
            $table->string('nombres')->nullable()->after('id');
            $table->string('apellido_paterno')->nullable()->after('nombres');
            $table->string('apellido_materno')->nullable()->after('apellido_paterno');
            $table->string('telefono')->nullable()->after('email');
            $table->string('ruta_avatar')->nullable()->after('telefono');
            $table->enum('estado', ['activo','inactivo'])->default('activo')->after('ruta_avatar');
            $table->timestamp('ultimo_ingreso_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nombres','apellido_paterno','apellido_materno',
                'telefono','ruta_avatar','estado','ultimo_ingreso_at'
            ]);
        });
    }
};
