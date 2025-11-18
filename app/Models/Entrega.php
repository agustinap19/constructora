<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entrega';
    protected $fillable = [
        'proyecto_id','almacen_id','beneficiario_id','fecha_entrega',
        'estado','ruta_fotografia_entrega','notas','creado_por'
    ];

    public function proyecto() { return $this->belongsTo(Proyecto::class, 'proyecto_id'); }
    public function almacen() { return $this->belongsTo(Almacen::class, 'almacen_id'); }
    public function beneficiario() { return $this->belongsTo(Beneficiario::class, 'beneficiario_id'); }
    public function detalles() { return $this->hasMany(EntregaDetalle::class, 'entrega_id'); }
    public function autor() { return $this->belongsTo(User::class, 'creado_por'); }
}
