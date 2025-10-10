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
                        <button type="button" class="btn btn-uvm bg-gradient-uvm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Nuevo Usuario
                        </button>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nombre
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Correo
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
                            <tbody>
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">1</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Admin</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">admin@softui.com</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">Admin</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">Activo</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-uvm bg-gradient-secondary">
                                            Actualizar
                                        </button>
                                    </td>
                                </tr>
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
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span class="color-close-uvm" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form text-left" method="POST" action="/register">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" placeholder="Nombre" name="name" id="name" aria-label="Name" aria-describedby="name" value="" require>
                        <p class="text-danger text-xs mt-2"></p> 
                    </div>
                    <div class="mb-3">
                        <label for="email">Correo</label>
                        <input type="email" class="form-control" placeholder="Correo" name="email" id="email" aria-label="Email" aria-describedby="email-addon" value="" require>
                        <p class="text-danger text-xs mt-2"></p>
                    </div>
                    <div class="mb-3">
                        <label for="">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" aria-label="Password" aria-describedby="password-addon">
                        <p class="text-danger text-xs mt-2"></p>
                    </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-dark" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-uvm bg-gradient-uvm">Guardar</button>
            </div>
            </div>
        </div>
    </div>

</div>
 
@endsection

@push('usuarios')
  
  <script>
    window.onload = function() {

        function listaUsuarios (){

            return fetch('/usuarios')
            .then(response => {

                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Http -> ' + response.status);
                }
            })
            .then(data => {
                // console.log(data);
                return data.datos;
            })
            .catch(error => {
                console.error('Error:', error);
                return false;
            });
        }

        listaUsuarios().then(usuarios => {

            if (usuarios) {
                
            }
        });





    //   let dateHoy = document.getElementById('date-filtro').value;
      
    //   document.querySelectorAll('.date').forEach(objeto => {
    //       objeto.innerText = dateHoy;
    //   });

    //   const ctx1 = document.getElementById("chart-line-total").getContext("2d");
    //   const ctx2 = document.getElementById("chart-line-calculadora").getContext("2d");
    //   const ctx3 = document.getElementById("chart-line-general").getContext("2d");

    //   let leadsInicial = Array(24).fill(0);

    //   let labels = [
    //     "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00",
    //     "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00",
    //     "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
    //   ];

    //   // Función para construir grafica de linea
    //   function createGraphLine (ctxn, leadsInicial, labels, label){

    //     const gradientStroke1 = ctxn.createLinearGradient(0, 230, 0, 50);
    //     gradientStroke1.addColorStop(1, 'rgba(215, 40, 47, 1)');
    //     gradientStroke1.addColorStop(0.2, 'rgba(215, 40, 47, 0.5)');
    //     gradientStroke1.addColorStop(0, 'rgba(215, 40, 47, 0.2)'); 

    //     return new Chart(ctxn, {
    //       type: "line",
    //       data: {
    //         labels: labels,
    //         datasets: [{
    //             label: label,
    //             tension: 0.4,
    //             borderWidth: 0,
    //             pointRadius: 0,
    //             borderColor: "#d7282f",
    //             borderWidth: 3,
    //             backgroundColor: gradientStroke1,
    //             fill: true,
    //             data: leadsInicial,
    //             maxBarThickness: 6
    //           }
    //         ],
    //       },
    //       options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: {
    //           legend: {
    //             display: false,
    //           }
    //         },
    //         interaction: {
    //           intersect: false,
    //           mode: 'index',
    //         },
    //         scales: {
    //           y: {
    //             grid: {
    //               drawBorder: false,
    //               display: true,
    //               drawOnChartArea: true,
    //               drawTicks: false,
    //               borderDash: [5, 5]
    //             },
    //             ticks: {
    //               display: true,
    //               padding: 10,
    //               color: '#b2b9bf',
    //               font: {
    //                 size: 11,
    //                 family: "Open Sans",
    //                 style: 'normal',
    //                 lineHeight: 2
    //               },
    //             }
    //           },
    //           x: {
    //             grid: {
    //               drawBorder: false,
    //               display: false,
    //               drawOnChartArea: false,
    //               drawTicks: false,
    //               borderDash: [5, 5]
    //             },
    //             ticks: {
    //               display: true,
    //               color: '#b2b9bf',
    //               padding: 20,
    //               font: {
    //                 size: 11,
    //                 family: "Open Sans",
    //                 style: 'normal',
    //                 lineHeight: 2
    //               },
    //             }
    //           },
    //         },
    //       },
    //     });

    //   }

    //   // Función para obtener leads totales, leads calculadora, leads general
    //   function getLeads(date) {

    //     return fetch('/leads-ciclo/' + date)
    //     .then(response => {

    //       if (response.ok) {
    //         return response.json();
    //       } else {
    //         throw new Error('Http -> ' + response.status);
    //       }
    //     })
    //     .then(data => {
    //       // console.log(data);
    //       return data.datos;
    //     })
    //     .catch(error => {
    //       console.error('Error:', error);
    //       return false;
    //     });
    //   }

    //   // Función para actualizar graficas de linea
    //   function updateDataGraph(dataLeads, graphN, idIconLeads){

    //     let leadsTotal = 0;
    //     let leadsInicial = Array(24).fill(0);

    //     dataLeads.forEach((element, index) => {
    //       leadsInicial[index] = element.total;
    //       leadsTotal = leadsTotal + element.total;  
    //     });

    //     console.log('Leads:', leadsInicial);

    //     document.getElementById(idIconLeads).innerText = leadsTotal;

    //     graphN.data.datasets[0].data = leadsInicial;
    //     graphN.update();
    //   }

    //   // Graficas de linea
    //   const GraphTotal = createGraphLine(ctx1, leadsInicial, labels, 'Leads Totales');
    //   const GraphCalculadora = createGraphLine(ctx2, leadsInicial, labels, 'Leads Calculadora');
    //   const GraphGeneral = createGraphLine(ctx3, leadsInicial, labels, 'Leads General');

    //   // Actualizamos datos de graficas de linea
    //   function allUpdateGraph(date){

    //     return getLeads(date).then(data => {

    //       updateDataGraph(data.leads_total, GraphTotal, 'leads-total');
    //       updateDataGraph(data.leads_calculadora, GraphCalculadora, 'leads-calculadora');
    //       updateDataGraph(data.leads_general, GraphGeneral, 'leads-general');
    //       return true;
    //     })
    //   }

    //   allUpdateGraph(dateHoy);

    //   document.getElementById('form-filtros').addEventListener('submit', function(event) {
        
    //     event.preventDefault();
        
    //     let dateFiltro = document.getElementById('date-filtro').value;
       
    //     document.querySelectorAll('.date').forEach(objeto => {
    //         objeto.innerText = dateFiltro;
    //     });

    //     document.getElementById("filtrar").disabled = true;

    //     allUpdateGraph(dateFiltro).then(response =>{
    //       document.getElementById("filtrar").disabled = false;
    //     });
    //   });

    }
  </script>
@endpush