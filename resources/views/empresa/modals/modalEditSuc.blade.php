<style>
    .error {
        border-color: red;
    }
</style>
<div class="modal fade" id="EditSucursales" tabindex="-1" aria-labelledby="addEditEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl-down">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light" style="padding: 3px 20px !important;">
                <h5 class="modal-title" id="addEditEmpresaLabel">Editar Datos Sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card p-2 mb-0">
                    <form id="form_edit_sucursal">
                    <div class="row" enctype="multipart/form-data">
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value="" placeholder=" "
                                    id="nombreSucursal1" name="nombreSucursal1">
                                <label class="input-label clear-input" for="">Nombre de la Sucursal*</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value="" placeholder=" "
                                    id="direccionSucursal1" name="direccionSucursal1">
                                <label class="input-label" for="">Direccion</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear_input material" value=""
                                    placeholder=" " maxlength="9" id="telefonoSucursal1" name="telefonoSucursal1">
                                <label class="input-label" for="">Telefono</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value="" placeholder=" "
                                     name="emailSucursal1" id="emailSucursal1">
                                <label class="input-label" for="">Email</label>
                            </div>
                            <div class="content-input mb-2">
                                <input type="text" class="custom-input material clear-input" value="" placeholder=" "
                                    name="encargadoSucursal1"   id="encargadoSucursal1">
                                <label class="input-label" for="">Encargado</label>
                            </div>
                            <div class="content-input mb-2">
                                <input id="id_susc" name="id_susc" type="hidden" />
                                
                                <input id="id_empr" name="id_empr" type="hidden" />
                            </div>
                    </div>
                    <div class="btns d-flex justify-content-end my-2">
                        <button id="EditSucursalButton" class="btn btn-outline-primary btn-sm btn-inline">Actualizar</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>