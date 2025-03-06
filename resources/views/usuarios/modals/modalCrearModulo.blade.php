<div class="modal fade" id="modal_new_modulo" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">CREAR MODULOS</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0"><i class="bi bi-bookmarks"></i>Crear nuevos modulos</h4>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-4 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="nombreModulo" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " id="nombreModulo">
                                        <label class="input-label" for="nombreModulo">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="descripcionModulo" type="text"
                                            class="custom-input material" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase" id="descripcionModulo">
                                        <label class="input-label" for="descripcionModulo">Descripción</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-2 mb-2">
                                    <div class="content-input mb-2">
                                        <button id="btn-save-modulo" class="btn btn-outline-success btn-xl btn-save-modulo"><i class="bi bi-floppy"></i> Crear módulo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body py-2 px-1">
                            <table class="table-responsive table-hover table-bordered table1 table-custom table-td"
                            style="width: 100%" id="modulos" data-order='[[ 0, "desc" ]]'>
                            <thead>
                                <tr>
                                    <th style="padding: 0px; text-align: center;">ID</th>
                                    <th style="padding: 0px; text-align: center;">Nombre</th>
                                    <th style="padding: 0px; text-align: center;">Descripcion</th>
                                    <th style="padding: 0px; text-align: center;">Accion</th>
                                </tr>
                            </thead>                
                            <tbody style="text-align: center;">
                            </tbody>
                        </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>