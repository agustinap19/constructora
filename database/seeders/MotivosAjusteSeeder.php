<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivosAjusteSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            ['codigo'=>'INV_DAÑO','nombre'=>'Daño/merma','descripcion'=>'Material dañado o vencido'],
            ['codigo'=>'INV_CONTEO','nombre'=>'Diferencia de conteo','descripcion'=>'Ajuste por inventario físico'],
            ['codigo'=>'INV_OTROS','nombre'=>'Otros','descripcion'=>null],
        ];

        foreach ($motivos as $m) {
            DB::table('motivo_ajuste_inventario')->updateOrInsert(
                ['codigo'=>$m['codigo']], $m + ['created_at'=>now(),'updated_at'=>now()]
            );
        }
    }
}
