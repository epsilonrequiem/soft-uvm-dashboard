@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Lista Usuarios</h5>
                        </div>
                        <button type="button" class="btn btn-uvm bg-gradient-uvm" id="agregar-usuario" data-tipo="add">
                            <i class="fas fa-user-plus"></i>&nbsp;Nuevo Usuario
                        </button>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="table-user">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Email
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Perfil
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Estatus
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="users-filas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal registro usuarios -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Usuario</h5>
                    <button type="button" class="btn-close close-modal" aria-label="Close">
                        <span class="color-close-uvm close-modal" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form text-left" id="form-users" acttion="">
                <div class="modal-body">
                    <input type="hidden" name="_method" value="">
                    <div class="mb-3">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" placeholder="Nombre" name="name" id="name" aria-label="Name" aria-describedby="name" value="" required>
                        <p class="text-danger text-xs mt-2"></p> 
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="Correo" name="email" id="email" aria-label="Email" aria-describedby="email-addon" value="" required>
                        <p class="text-danger text-xs mt-2"></p>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" pattern="(?=.*[A-Z])(?=.*\d).{8,12}" placeholder="Password" name="password" id="password" aria-label="Password" aria-describedby="password-addon" value="" required>
                        <p class="text-dark text-xs mt-2">Mínimo 8 caracteres (máx. 12), con A-Z y 0-9.</p>
                        <p class="text-danger text-xs mt-2"></p>
                    </div>
                    <div class="mb-3">
                        <label for="id_perfil">Perfil</label>
                        <select name="id_perfil" id="id_perfil" class="form-control" aria-label="perfil" aria-describedby="perfil" required>
                        </select>
                        <p class="text-danger text-xs mt-2"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-dark close-modal">Cerrar</button>
                    <button type="submit" class="btn btn-uvm bg-gradient-uvm" id="guardar-usuario" style="display: none">Guardar</button>
                    <button type="submit" class="btn btn-uvm bg-gradient-uvm" id="actualizar-usuario" style="display: none">Actualizar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>
 
@endsection

@push('usuarios')
  
  <script>
    window.onload = function() {

        // Función para obtener el catalogo de perfiles
        function catalogoPerfiles(){

            fetch('/perfiles')
            .then(response => {

                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Http -> ' + response.status);
                }
            })
            .then(data => {
                // console.log(data);
                return data.response.data;
            })
            .catch(error => {
                console.error('Error:', error);
                return false;
            }).then(perfiles => {
                // console.log(users);
                if (perfiles) {
                
                    let selectPerfiles = `<option value="">Seleccione una opción</option>`;

                    perfiles.forEach(objeto => {
                        selectPerfiles += 
                        `<option value="${objeto.id}">${objeto.perfil}</option>` 
                    });
                    
                    document.getElementById('id_perfil').innerHTML = selectPerfiles;
                }
            });
        }

        // Función para obtener el listado de usuarios
        function listaUsuarios(){

            fetch('/usuarios-lista')
            .then(response => {

                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Http -> ' + response.status);
                }
            })
            .then(data => {
                // console.log(data);
                return data.response.data;
            })
            .catch(error => {
                console.error('Error:', error);
                return false;
            }).then(users => {
                // console.log(users);
                if (users) {
                
                    let filasUsers = '';

                    users.forEach(objeto => {
                        filasUsers += 
                        `<tr>
                            <td class="ps-4">
                                <p class="text-xs font-weight-bold mb-0">${objeto.id}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">${objeto.name}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">${objeto.email}</p>
                            </td>
                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">${objeto.perfil.perfil}</p>
                            </td>
                            <td class="text-center">
                                <button type="button" data-id="${objeto.id}" class="btn btn-uvm ${objeto.status == 1 ? 'bg-gradient-success' : 'bg-gradient-secondary' } actualizar-user-status"  style="margin: 0">
                                    ${objeto.status == 1 ? 'Activo' : 'Desactivado'}
                                </button>
                            </td>
                            <td class="text-center">
                                <button type="button" title="Actualizar" data-tipo="update" data-id="${objeto.id}" data-name="${objeto.name}" data-email="${objeto.email}" data-perfil="${objeto.perfil.id}" class="btn btn-uvm bg-gradient-secondary btn-table-uvm actualizar-user">
                                    <i class="fas fa-pen-square"></i>
                                </button>
                            </td>
                        </tr>` 
                    });
                    
                    document.getElementById('users-filas').innerHTML = filasUsers;
                }
            });
        }

        catalogoPerfiles();

        listaUsuarios();

        const miModalEl = document.getElementById('exampleModal');
        const miModal = new bootstrap.Modal(miModalEl);
        const form = document.querySelector('form'); // Obtén la referencia al formulario una sola vez
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para resetear el formulario al modo 'CREAR'
        function resetFormularioCrear() {
            form.reset();
            form.action = `/usuario`;
            form.querySelector('input[name="_method"]').value = 'POST'; // método POST
            document.getElementById('guardar-usuario').style.display = 'block';
            document.getElementById('actualizar-usuario').style.display = 'none';
            document.getElementById('password').setAttribute('required', 'required');
        }

        // Función para resetear el formulario al modo 'ACTUALIZAR'
        function resetFormularioActualizar(id) {
            form.reset();
            form.action = `/usuario/${id}`;
            form.querySelector('input[name="_method"]').value = 'PUT'; // método PUT 
            document.getElementById('guardar-usuario').style.display = 'none';
            document.getElementById('actualizar-usuario').style.display = 'block';
            document.getElementById('password').removeAttribute('required');
        }

        function envioFormulario(url, method, dataJSON){

            if (method == 'POST') {
                document.getElementById('guardar-usuario').disabled = true            
            } else {
                document.getElementById('actualizar-usuario').disabled = true
            }

            return fetch(url,{
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": CSRF_TOKEN // Pasamos el token CSRF de la etiqueta meta 
                    },
                    body: dataJSON
                })
                .then(response => {

                    if (response.ok) {
                        return response.json();
                    } else {
                        // console.log(response)
                        return response.json().then(errorData => {
                            throw errorData; 
                        });
                    }
                })
                .then(data => {
                    // console.log(data);
                    return data.response;
                })
                .catch(error => {
                    console.error('Error:', error.code);
                    return error;
                }).then(data =>{
                    if (method == 'POST') {
                        document.getElementById('guardar-usuario').disabled = false            
                    } else {
                        document.getElementById('actualizar-usuario').disabled = false
                    }
                    return data;
                });
        }

        // Manejo global del formulario
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const url = form.action;
            const method = form.querySelector('input[name="_method"]').value;
            const formulario = event.target;
            const formData = new FormData(formulario);
            const dataObject = Object.fromEntries(formData.entries());
            const dataJSON = JSON.stringify(dataObject);

            envioFormulario(url, method, dataJSON).then(data => {
                // console.log(data);
                let title = '';
                let text = '';
                let icon = ''; 

                if (data.code == 201) { // agregado con exito
                    title = 'Agregado con exito!';
                    icon = 'success'; 
                } else if (data.code == 200) { // actualizado con extio
                    title = 'Actualizado con exito!';
                    icon = 'success'; 
                } else if(data.code == 400) { // datos incorrectos                    
                    // console.log(data.errors);
                    const arraErrors = Object.values(data.errors)
                    title = 'Datos incorrectos!';
                    text = arraErrors[0];
                    icon = 'info'; 
                } else { // http: 500 error del sistema 
                    title = 'Ocurrio un error!'
                    text = 'Informar al responsable del sistema';
                    icon = 'error'; 
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    allowOutsideClick: false, 
                    showCancelButton: false,
                    confirmButtonColor: "#d7282f",
                    confirmButtonText: "Cerrar"
                }).then((result) => {
                    if (result.isConfirmed && (data.code == 200 || data.code == 201)) {
                        resetFormularioCrear(); 
                        miModal.hide();
                        listaUsuarios();
                    } 
                });

            });
        });

        // Abrir modal para crear
        document.getElementById('agregar-usuario').addEventListener('click', function(){
            resetFormularioCrear(); // Pone el formulario en modo 'Crear'
            miModal.show();
        });

        // Abrir modal para actualizar
        document.getElementById('table-user').addEventListener('click', function(event){
            
            const elementoButton = event.target.closest('.actualizar-user');

            if (elementoButton && elementoButton.tagName === 'BUTTON') {
        
                let id = elementoButton.getAttribute('data-id');
                
                resetFormularioActualizar(id) // Pone el formulario en modo 'Actualizar'

                let nombre = elementoButton.getAttribute('data-name');
                let correo = elementoButton.getAttribute('data-email');
                let perfil = elementoButton.getAttribute('data-perfil');

                document.getElementById('name').value = nombre;
                document.getElementById('email').value = correo;
                document.getElementById('id_perfil').value = perfil;

                miModal.show();
            }
        });

        // Actualizar estatus usuarios
        document.getElementById('table-user').addEventListener('click', function(event){
            
            const elementoButton = event.target.closest('.actualizar-user-status');

            if (elementoButton && elementoButton.tagName === 'BUTTON') {
        
                let id = elementoButton.getAttribute('data-id');
                
                elementoButton.disabled = true;
                
                fetch(`usuario-status/${id}`,{
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": CSRF_TOKEN // Pasamos el token CSRF de la etiqueta meta 
                    }
                })
                .then(response => {

                    if (response.ok) {
                        return response.json();
                    } else {
                        // console.log(response)
                        return response.json().then(errorData => {
                            throw errorData; 
                        });
                    }
                })
                .then(data => {
                    // console.log(data);
                    return data.response;
                })
                .catch(error => {
                    console.error('Error:', error.code);
                    return error;
                }).then(data =>{

                    elementoButton.disabled = true;

                    if (data.code == 200) {
                        listaUsuarios();
                    } else {

                        Swal.fire({
                            title: 'Ocurrio un error!',
                            text: 'Informar al responsable del sistema',
                            icon: 'error',
                            allowOutsideClick: false, 
                            showCancelButton: false,
                            confirmButtonColor: "#d7282f",
                            confirmButtonText: "Cerrar"
                        })
                    }
                });
            }
        });

        
        // Cerrar Modal usuario
        miModalEl.addEventListener('click', function (event) {
            if (event.target.classList.contains('close-modal')) {
                resetFormularioCrear(); 
                miModal.hide();
            }
        });
    }
  </script>
@endpush