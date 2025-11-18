<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosDemoBasicosSeeder extends Seeder
{
    public function run(): void
    {
        // Proyecto demo
        $proyectoId = DB::table('proyecto')->updateOrInsert(
            ['codigo'=>'PRJ-001'],
            [
                'nombre'=>'Proyecto Piloto',
                'descripcion'=>'Proyecto de prueba para Constructora',
                'ubicacion_texto'=>'La Paz - Bolivia',
                'responsable_id'=>1,
                'fecha_inicio'=>now()->toDateString(),
                'estado'=>'activo',
                'updated_at'=>now(), 'created_at'=>now()
            ]
        );

        $proyecto = DB::table('proyecto')->where('codigo','PRJ-001')->first();

        // Almacén principal del proyecto
        DB::table('almacen')->updateOrInsert(
            ['proyecto_id'=>$proyecto->id,'codigo'=>'ALM-01'],
            [
                'nombre'=>'Almacén Principal',
                'ubicacion_texto'=>'Obra central',
                'created_at'=>now(),'updated_at'=>now()
            ]
        );

        // Material demo + precio
        $unidad = DB::table('unidad')->where('codigo','u')->first();
        $cat = DB::table('categoria_material')->where('nombre','Aceros')->first();

        DB::table('material')->updateOrInsert(
            ['codigo'=>'MAT-0001'],
            [
                'descripcion'=>'Fierro estriado 3/8"',
                'unidad_id'=>$unidad->id,
                'categoria_material_id'=>$cat->id,
                'estado'=>'activo',
                'created_at'=>now(),'updated_at'=>now()
            ]
        );
        $mat = DB::table('material')->where('codigo','MAT-0001')->first();

        DB::table('precio_material')->updateOrInsert(
            ['material_id'=>$mat->id,'vigente_desde'=>now()->toDateString()],
            [
                'precio'=>35.50,'moneda'=>'BOB',
                'created_at'=>now(),'updated_at'=>now()
            ]
        );

        // Existencia base en 0
        $alm = DB::table('almacen')->where('codigo','ALM-01')->first();
        DB::table('existencia')->updateOrInsert(
            ['almacen_id'=>$alm->id,'material_id'=>$mat->id],
            ['cantidad_disponible'=>0,'created_at'=>now(),'updated_at'=>now()]
        );
    }
}
