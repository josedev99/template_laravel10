@extends('layouts.app')

@section('title', 'Empleados - App')

@section('section-title')
<h1 class="fs-5">Gestionar colaboradores</h1>
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Empleados</li>
    </ol>
</nav>
@endsection

@section('content')

@include('Empleado.modal.form_empleado')

<div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12 col-sm-12">
        <div class="card info-card sales-card">
            <div class="card-header p-1">
               {{--  <button class="btn btn-outline-info btn-sm btn_new_examen"><i class="bi bi-clipboard-plus"></i>
                    Examenes</button>
                <button class="btn btn-outline-secondary btn-sm btn_new_perfil"><i
                        class="bi bi-file-earmark-person"></i> Nuevo perfiles</button>
                <button class="btn btn-outline-info btn-sm btn_new_jornada"><i class="bi bi-journal-plus"></i> Nueva
                    jornadas</button> --}}
                        <button class="btn btn-outline-success btn-sm btn_new_empleado"><i class="bi bi-person-add"></i> Nuevo colaborador</button>
                    
            </div>
            <div class="card-body p-1">
                <table id="dt_listado_empleados" width="100%" data-order='[[ 0, "desc" ]]'
                    class="table-hover table-striped">
                    <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                        <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                            <th style="text-align:center">#</th>
                            <th style="text-align:center">COD. EMPLEADO</th>
                            <th style="text-align:center">CATEGORIA</th>
                            <th style="text-align:center">NOMBRE</th>
                            <th style="text-align:center">TELÉFONO</th>
                            <th style="text-align:center">DEPTO/AREA</th>
                            <th style="text-align:center">GENERO</th>
                            <th style="text-align:center">SUCURSAL</th>
                            <th style="text-align:center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" style="font-size: 12px;text-align:center">

                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- End Left side columns -->

</div>

@endsection

@push('js')
<script src="{{ versioned_asset('assets/js/empleado/empleado.js') }}"></script>
@endpush