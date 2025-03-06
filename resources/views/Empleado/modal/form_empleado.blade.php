<div class="modal fade" id="modal_new_empleado" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-1 px-2 bg-dark text-light">
                <h1 class="modal-title fs-7">NUEVO COLABORADOR</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form_data_emp">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <h4 class="fs-6 m-0"><i class="bi bi-person-vcard-fill"></i> Agregar información del colaborador</h4>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <label for="categoria_empleado" class="input-group-title1">Categoria*: </label>
                                        <select name="categoria_empleado" class="form-select validInputEmpleado border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar categoria de empleado/a">
                                            <option value="">Selecccionar</option>
                                            <option value="Administrativo">Administrativo</option>
                                            <option value="Operativo">Operativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="codigo_empleado" type="text"
                                            class="custom-input material validInputEmpleado" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="codigo_empleado">Código empleado</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="nombre_empleado" type="text"
                                            class="custom-input material validInputEmpleado" value="" placeholder=" "
                                            placeholder=" " style="text-transform: uppercase">
                                        <label class="input-label" for="nombre_empleado">Nombre completo</label>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="fecha_nac_emp" type="text"
                                            class="custom-input material validInputEmpleado fecha_nac_emp" value="" placeholder=" "
                                            placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="fecha_nac_emp">Fecha de nacimiento</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <label for="genero_emp" class="input-group-title1">Género*: </label>
                                        <select name="genero_emp" class="form-select validInputEmpleado border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="0">Selecccionar</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                            <option value="Otro">Otros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="telefono" type="text"
                                            class="custom-input material validInputEmpleado" value="" placeholder=" "
                                            placeholder=" ">
                                        <label class="input-label" for="telefono">Teléfono</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                                    <div class="content-input mb-2">
                                        <input name="fecha_ing_emp" type="text"
                                            class="custom-input material validInputEmpleado fecha_ing_emp" value="" placeholder=" "
                                            placeholder=" ">
                                        <span class="icon-calendar"><i class="bi bi-calendar2-week-fill"></i></span>
                                        <label class="input-label" for="fecha_ing_emp">Fecha de ingreso</label>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                    <div class="input-group mb-2">
                                        <label for="depto_emp" class="input-group-title1">Departamento/Área </label>
                                        <select name="depto_emp" id="depto_emp" class="form-select border-radius" data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="">Selecccionar</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 mb-2">
                                    <div class="input-group">
                                        <label for="cargo_emp" class="input-group-title1">Cargos </label>
                                        <select name="cargo_emp" id="cargo_emp" class="form-select border-radius" data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar cargo de empleado">
                                            <option value="">Selecccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <label for="sucursal_emp" class="input-group-title1">Sucursal*: </label>
                                        <select name="sucursal_emp" class="form-select validInputEmpleado border-radius"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Seleccionar genero de empleado/a">
                                            <option value="0">Selecccionar</option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm btn-save-emp"><i class="bi bi-floppy"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>