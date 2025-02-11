<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccesosMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            //Catalogos
            [
                'numero' => 1,
                'ruta' => '/alumnos',
                'descripcion' => 'Alumnos',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 2,
                'ruta' => '/productos',
                'descripcion' => 'Productos',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 3,
                'ruta' => '/comentarios',
                'descripcion' => 'Comentarios',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 4,
                'ruta' => '/cajeros',
                'descripcion' => 'Cajeros',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 5,
                'ruta' => '/horarios',
                'descripcion' => 'Horarios',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 6,
                'ruta' => '/formapago',
                'descripcion' => 'Forma Pago',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 7,
                'ruta' => '/formfact',
                'descripcion' => 'Formato Variable',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 8,
                'ruta' => '/profesores',
                'descripcion' => 'Profesores',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 9,
                'ruta' => '/asignaturas',
                'descripcion' => 'Asignatura',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            [
                'numero' => 10,
                'ruta' => '/actividades',
                'descripcion' => 'Actividades',
                'icono' => '',
                'menu' => 'Catálogos',
                'baja' => ''
            ],
            //Procesos
            [
                'numero' => 11,
                'ruta' => '/pagos1',
                'descripcion' => 'Pagos',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 12,
                'ruta' => '/adicion_productos_cartera',
                'descripcion' => 'Adicion de Productos a Cartera',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 13,
                'ruta' => '/cancelacion_recibos',
                'descripcion' => 'Cancelacion de Recibo',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 14,
                'ruta' => '/act_cobranza',
                'descripcion' => 'Actualiza Cobranza',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 15,
                'ruta' => '/cambio_ciclo_escolar',
                'descripcion' => 'Cambio de Ciclo Escolar',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 16,
                'ruta' => '/cambio_numero_alumno',
                'descripcion' => 'Cambio de Numero de Alumno',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 17,
                'ruta' => '/cobranza_diaria',
                'descripcion' => 'Cobranza Diaria',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 18,
                'ruta' => '/clases',
                'descripcion' => 'Asignacion de Asignaturas',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 19,
                'ruta' => '/c_calificaciones',
                'descripcion' => 'Calificaciones',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 20,
                'ruta' => '/p_boletas',
                'descripcion' => 'Creacion de Boletas',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 21,
                'ruta' => '/creacion_boletas_3_bimestres',
                'descripcion' => 'Creacion de Boletas 3 Bimestre',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 22,
                'ruta' => '/concentradoCalificaciones',
                'descripcion' => 'Concentrado de Calificaciones',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            [
                'numero' => 23,
                'ruta' => '/c_otras',
                'descripcion' => 'Asistencias y Trabajos Omitidos',
                'icono' => '',
                'menu' => 'Procesos',
                'baja' => ''
            ],
            //Reportes
            [
                'numero' => 24,
                'ruta' => '/rep_femac_6',
                'descripcion' => 'Cobranza',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 25,
                'ruta' => '/rep_femac_1',
                'descripcion' => 'Relacion General de Alumnos',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 26,
                'ruta' => '/Rep_Femac_2',
                'descripcion' => 'Lista de Alumnos por Clase',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 27,
                'ruta' => '/rep_femac_3',
                'descripcion' => 'Lista de Alumnos por Clase del Mes',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 28,
                'ruta' => '/rep_femac_13',
                'descripcion' => 'Lista de Alumnos por Clase Semanal',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 29,
                'ruta' => '/rep_femac_4',
                'descripcion' => 'Credencial',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 30,
                'ruta' => '/rep_femac_5',
                'descripcion' => 'Altas y Bajas de Alumnos',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 31,
                'ruta' => '/rep_femac_7',
                'descripcion' => 'Cartera',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 32,
                'ruta' => '/rep_femac_8_anexo_1',
                'descripcion' => 'Relacion de Recibos',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 33,
                'ruta' => '/Rep_Femac_9_Anexo_4',
                'descripcion' => 'Relacion de Facturas',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 34,
                'ruta' => '/rep_femac_10_Anexo_2',
                'descripcion' => 'Estado de Cuenta',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 35,
                'ruta' => '/rep_femac_11_Anexo_3',
                'descripcion' => 'Reporte Cobranza por Alumno',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 36,
                'ruta' => '/rep_femac_12_anexo_4',
                'descripcion' => 'Reporte Cobranza por Producto',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 37,
                'ruta' => '/rep_recibos_pagos',
                'descripcion' => 'Recibo de Pagos',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 38,
                'ruta' => '/rep_flujo_01',
                'descripcion' => 'Reporte Flujo Efectivo',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 39,
                'ruta' => '/rep_w_becas',
                'descripcion' => 'Alumnos con Beca',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            [
                'numero' => 40,
                'ruta' => '/rep_inscritos',
                'descripcion' => 'Alumnos Inscritos',
                'icono' => '',
                'menu' => 'Reportes',
                'baja' => ''
            ],
            //Utilerias
            [
                'numero' => 41,
                'ruta' => '/catalogo_menus',
                'descripcion' => 'Catalogo de Menus',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
            [
                'numero' => 42,
                'ruta' => '/accesos_menu',
                'descripcion' => 'Puntos de Menu',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
            [
                'numero' => 43,
                'ruta' => '/usuarios',
                'descripcion' => 'Usuarios',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
            [
                'numero' => 44,
                'ruta' => '/accesos_usuarios',
                'descripcion' => 'Accesos Usuario',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
            [
                'numero' => 45,
                'ruta' => '/propietario',
                'descripcion' => 'Propietario',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
            [
                'numero' => 46,
                'ruta' => '/aplicaciones_ejecutables',
                'descripcion' => 'Aplicaciones .EXE',
                'icono' => '',
                'menu' => 'Utilerias',
                'baja' => ''
            ],
        ];
        foreach ($registros as $registro) {
            DB::table('accesos_menu')->updateOrInsert(
                ['numero' => $registro['numero']],
                $registro
            );
        }
    }
}
