<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';

    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'razon_social',
        'nro_fiscal',      // NIT o CI
        'telefono',
        'email',
        'direccion',
        'ruta_fotografia',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** 🔹 Nombre completo o razón social */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->razon_social) {
            return $this->razon_social;
        }

        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    /** 🔹 Mostrar iniciales (útil para avatares) */
    public function getInicialesAttribute(): string
    {
        $partes = preg_split('/\s+/', $this->getNombreCompletoAttribute());
        $iniciales = '';
        foreach ($partes as $p) {
            $iniciales .= mb_substr($p, 0, 1);
        }
        return strtoupper($iniciales);
    }

    /** 🔹 Scopes para búsquedas rápidas */
    public function scopeBuscar($query, $texto)
    {
        return $query->where(function ($q) use ($texto) {
            $q->where('nombres', 'like', "%$texto%")
              ->orWhere('apellido_paterno', 'like', "%$texto%")
              ->orWhere('apellido_materno', 'like', "%$texto%")
              ->orWhere('razon_social', 'like', "%$texto%")
              ->orWhere('nro_fiscal', 'like', "%$texto%")
              ->orWhere('telefono', 'like', "%$texto%")
              ->orWhere('email', 'like', "%$texto%");
        });
    }

    /** 🔹 Si quieres relación con proyectos (opcional futuro) */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'cliente_id');
    }
}
