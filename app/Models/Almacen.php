<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacen';
    protected $fillable = ['proyecto_id','codigo','nombre','ubicacion_texto','latitud','longitud'];

    public function proyecto() { return $this->belongsTo(Proyecto::class, 'proyecto_id'); }
    public function existencias() { return $this->hasMany(Existencia::class, 'almacen_id'); }
}
