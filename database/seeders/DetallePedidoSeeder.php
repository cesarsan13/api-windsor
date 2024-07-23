<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DetallePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [
                'recibo' => 2,
                'alumno' => 18,
                'articulo' => 10,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1551,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 2,
                'alumno' => 18,
                'articulo' => 9999,
                'documento' => 0,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 240,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 3,
                'alumno' => 16,
                'articulo' => 10,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1551,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 4,
                'alumno' => 25,
                'articulo' => 10,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1551,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 5,
                'alumno' => 24,
                'articulo' => 10,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1551,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 6,
                'alumno' => 26,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 6,
                'alumno' => 26,
                'articulo' => 9999,
                'documento' => 0,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 240,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 7,
                'alumno' => 23,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 8,
                'alumno' => 19,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-10',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 1
            ],
            [
                'recibo' => 9,
                'alumno' => 14,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-10',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 10,
                'alumno' => 22,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-10',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 11,
                'alumno' => 12,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 11,
                'alumno' => 12,
                'articulo' => 9999,
                'documento' => 0,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 240,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 12,
                'alumno' => 33,
                'articulo' => 11,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 1689,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 12,
                'alumno' => 33,
                'articulo' => 9999,
                'documento' => 0,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 240,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 13,
                'alumno' => 36,
                'articulo' => 28,
                'documento' => 9,
                'fecha' => '2010-05-19',
                'cantidad' => 1,
                'precio_unitario' => 2027,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 14,
                'alumno' => 29,
                'articulo' => 28,
                'documento' => 9,
                'fecha' => '2010-05-07',
                'cantidad' => 1,
                'precio_unitario' => 2027,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 15,
                'alumno' => 17,
                'articulo' => 26,
                'documento' => 8,
                'fecha' => '2010-04-09',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 16,
                'alumno' => 3,
                'articulo' => 24,
                'documento' => 9,
                'fecha' => '2010-04-28',
                'cantidad' => 1,
                'precio_unitario' => 1560,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 1
            ],
            [
                'recibo' => 17,
                'alumno' => 2,
                'articulo' => 9,
                'documento' => 9,
                'fecha' => '2010-05-06',
                'cantidad' => 1,
                'precio_unitario' => 1300,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 1
            ],
            [
                'recibo' => 18,
                'alumno' => 17,
                'articulo' => 26,
                'documento' => 9,
                'fecha' => '2010-05-10',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 19,
                'alumno' => 10,
                'articulo' => 10,
                'documento' => 9,
                'fecha' => '2010-05-07',
                'cantidad' => 1,
                'precio_unitario' => 1551,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ],
            [
                'recibo' => 19,
                'alumno' => 17,
                'articulo' => 26,
                'documento' => 10,
                'fecha' => '2010-05-07',
                'cantidad' => 1,
                'precio_unitario' => 1861,
                'descuento' => 0,
                'iva' => 0,
                'numero_factura' => 0
            ]
        ];
        foreach ($registros as $registro) {
            DB::table('detalle_pedido')->updateOrInsert(
                ['recibo'=>$registro['recibo'],
                'alumno'=>$registro['alumno'],
                'articulo'=>$registro['articulo'],
                'documento'=>$registro['documento']
            ],
            $registro
        );
        }
    }
}
