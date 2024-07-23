<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoCobroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [
                'id' => 1,
                'descripcion' => 'EFECTIVO',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id' => 2,
                'descripcion' => 'CHEQUE BANCOMER',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>3,
                'descripcion'=>'CHEQUE OTRO BANCO',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>4,
                'descripcion'=>'TARJETA DE CREDITO',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>5,
                'descripcion'=>'VALES DE INTERCAMBIO',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>6,
                'descripcion'=>'BECAS',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>7,
                'descripcion'=>'DEPOSITO',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>8,
                'descripcion'=>'TRANSFERENCIA',
                'comision' => 0,
                'aplicacion' => '1120002',
                'cue_banco' => '1120002'
            ],
            [
                'id'=>9,
                'descripcion'=>'BONIFICACION POR DEVOLUCIÓN DE INSCRIPCIÓN',
                'comision' => 0,
                'aplicacion'=>'',
                'cue_banco'=>''
            ],
            [
                'id'=>10,
                'descripcion'=>'APLICA INSCRIPCION',
                'comision' => 0,
                'aplicacion'=>'',
                'cue_banco'=>''
            ],
        ];
        foreach ($registros as $registro){
            DB::table('tipo_cobro')->updateOrInsert(
                ['id'=>$registro['id']],
                $registro
            );
        }
    }
}
