<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("INSERT INTO `empresas` (`id`, `nombre`, `direccion`, `telefono`, `celular`, `logo`, `rubro`, `fecha_creacion`, `no_registro`, `giro`, `usuario_id`, `created_at`, `updated_at`) VALUES
        (1, 'LENTI', 'san salvador', '74518248', '74524152', 'FotosEmpresa/gAHgECbqtYjiylUV8HI3wDgFVfsE7OIg1PLFnOXw.png', NULL, NULL, '123456', 'SISTEMAS', 1, '2024-09-07 04:11:04', '2024-10-30 20:36:26');");

        DB::statement("INSERT INTO `sucursals` (`id`, `nombre`, `direccion`, `telefono`, `email`, `encargado`, `fecha`, `hora`, `empresa_id`, `usuario_id`, `created_at`, `updated_at`) VALUES
        (1, 'LENTI', 'san salvador', '', '', 'Encargado predeterminado', '2024-09-06', '10:11:04', 1, 1, '2024-09-07 04:11:04', '2024-09-07 04:11:04');");

        DB::statement("INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `correo`, `contr`, `password`, `departamento`, `telefono`, `dui`, `nit`, `cargo`, `estado`, `categoria`, `direccion`, `fecha_creacion`, `empresa_id`, `sucursal_id`, `created_at`, `updated_at`) VALUES
        (4, 'LENTI S.A DE C.V', 'lenti', NULL, 'sistem', '$2y$12$2FxONw.qZesTf9hcLgZY9O.TZ8suJ0NtSO81PmeMEj0eQaGkmO8fm', NULL, '000000', '-', '00000000000', 'medico', '1', '3', '-', '2024-09-06', 1, 1, '2024-09-07 10:11:04', '2024-11-22 03:21:38');");
    }
}
