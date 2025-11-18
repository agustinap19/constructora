<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiario';
    protected $fillable = [
        'proyecto_id','nombres','apellido_paterno','apellido_materno',
        'tipo_documento','nro_documento','telefono','email',
        'direccion_vivienda','latitud','longitud','ruta_fotografia','notas'
    ];

    public function proyecto() { return $this->belongsTo(Proyecto::class, 'proyecto_id'); }
    public function entregas() { return $this->hasMany(Entrega::class, 'beneficiario_id'); }

    public function getNombreCompletoAttribute() {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
