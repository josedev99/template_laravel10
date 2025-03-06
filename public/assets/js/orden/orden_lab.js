
document.addEventListener('DOMContentLoaded', main)

function main(){
    try{
        let form_orden_lab = document.getElementById('form_orden_lab');
        if(form_orden_lab){
            form_orden_lab.addEventListener('submit', (e) => {
                e.preventDefault();
                const examenesFiltrados = array_data_examenes.reduce((acc, categoria) => {
                    const examenesChecked = categoria.examenes.filter(examen => examen.check_examen === true);
                    return acc.concat(examenesChecked);
                }, []);
                //Validate count array
                if(examenesFiltrados.length === 0){
                    Swal.fire({
                        title: "Aviso",
                        text: 'Para registrar esta orden, debe agregar por lo menos un examen.',
                        icon: "warning"
                    });return;
                }
                let formData = new FormData(form_orden_lab);
                let emp_id = sessionStorage.getItem('emp_id') !== null ? sessionStorage.getItem('emp_id') : 0;
                formData.append('emp_id',emp_id);
                formData.append('data_examenes',JSON.stringify(examenesFiltrados));
                //button disabled
                document.querySelector('.btnSaveOrden').disabled = true;
                axios.post(route('orden.lab.save'),formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Content-Encoding': 'gzip'
                    }
                }).then((result) => {
                    console.log(result);
                    if(result.data.status === "success"){
                        $("#modal_nueva_orden_examen").modal('hide');
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                          });
                        $("#jornada_orden").selectize()[0].selectize.clear();
                        generar_orden_pdf(result.data.results.id); //Generar el pdf de orden
                        form_orden_lab.reset();
                    }else if(result.data.status === "exists"){
                        Toast.fire({
                            icon: "warning",
                            title: result.data.message
                          });
                    }else{
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                          });
                    }
                    document.querySelector('.btnSaveOrden').disabled = false;
                }).catch((err) => {
                    console.log(err);
                    document.querySelector('.btnSaveOrden').disabled = false;
                });
            })
        }
        //Formulario para guardar orden de lab perfil
        let form_orden_lab_perfil = document.getElementById('form_orden_lab_perfil');
        if(form_orden_lab_perfil){
            form_orden_lab_perfil.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('emit...');
            })
        }
    }catch(err){
        console.log(err);
    }
}

function generar_orden_pdf(id){
    let token_csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let form = document.createElement('form');
    form.action = route('orden.examenes.pdf');
    form.method = "POST";
    form.target = '_blank';
    let input_csrf = document.createElement('input');
    input_csrf.name = "_token";
    input_csrf.value = token_csrf;
    form.appendChild(input_csrf);

    let inputID = document.createElement('input');
    inputID.name = 'id';
    inputID.value = id;
    form.appendChild(inputID);
    document.body.appendChild(form);
    form.submit();
    form.remove();
}