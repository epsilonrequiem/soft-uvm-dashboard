@extends('layouts.user_type.auth')

@section('content')

  <hr>

  <h4>Filtros</h4>

  <form id="form-filtros" class="row mb-4">

    <div class="row">

      <div class="col-md-5">
        <label for="id_campus">Campus</label>
        <select multiple name="id_campus" class="select-invisible" id="id_campus" aria-label="campus" aria-describedby="campus">
          <option value="TODOS">TODOS</option>
        </select>
      </div>

      <div class="col-md-7">
        <label for="id_programa">Programa</label>
        <select multiple name="id_programa" class="select-invisible" id="id_programa" aria-label="programa" aria-describedby="programa">
          <option value="TODOS">TODOS</option>
        </select>
      </div>

    </div>

    <div class="row mt-3">

      <div class="col-md-3">
        <label for="year">Año</label>
        <select name="year" id="year" class="form-control" aria-label="year" aria-describedby="year" required>
          <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
        </select>
      </div>

      <div class="col-md-3">
        <label for="date-filtro" class="form-label">Fecha inicio</label>
        <input type="date" class="form-control" id="date-filtro" name="date-filtro" value="<?= date('Y-m-d') ?>" min="" max="" required>
      </div>

      <div class="col-md-3">
        <label for="date-filtro-fin" class="form-label">Fecha fin</label>
        <input type="date" class="form-control" id="date-filtro-fin" name="date-filtro-fin" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" id="filtrar" class="btn btn-uvm bg-gradient-uvm mb-0">Buscar</button>
      </div>

    </div>
  
  </form>

  <!-- Indicadores iconos presente -->
  <div class="row">

    <!-- Leads Total -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads Totales:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-total">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm shadow text-center border-radius-md">
                <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads Calculadora -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads Calculadora:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-calculadora">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm shadow text-center border-radius-md">
                <i class="fa-solid fa-calculator text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads General -->
    <div class="col-xl-4 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads General:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-general">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm shadow text-center border-radius-md">
                <i class="fa-solid fa-filter text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>

    <!-- Indicadores iconos pasado -->
  <div class="row mt-4">

    <!-- Leads Total -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads Totales:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-total-pasado">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm-alt shadow text-center border-radius-md">
                <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads Calculadora -->
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads Calculadora:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-calculadora-pasado">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm-alt shadow text-center border-radius-md">
                <i class="fa-solid fa-calculator text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads General -->
    <div class="col-xl-4 col-sm-6">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Leads General:</p>
                <h5 class="font-weight-bolder mb-0" id="leads-general-pasado">
                  0
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-uvm-alt shadow text-center border-radius-md">
                <i class="fa-solid fa-filter text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>

  <!-- Indicadores gráficos actual -->
  <div class="row mt-4">

    <!-- Leads Total -->
    <div class="col-lg-12">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Leads Totales</h6>
          <p class="text-sm date"></p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-total" class="chart-canvas" height="400"></canvas>
          </div>
          <div class="chart">
            <canvas id="chart-barra-total" class="chart-canvas" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads Calculadora -->
    <div class="col-lg-12 mt-4">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Leads Calculadora</h6>
          <p class="text-sm date"></p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-calculadora" class="chart-canvas" height="400"></canvas>
          </div>
          <div class="chart">
            <canvas id="chart-barra-calculadora" class="chart-canvas" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Leads General -->
    <div class="col-lg-12 mt-4">
      <div class="card z-index-2">
        <div class="card-header pb-0">
          <h6>Leads General</h6>
          <p class="text-sm date"></p>
        </div>
        <div class="card-body p-3">
          <div class="chart">
            <canvas id="chart-line-general" class="chart-canvas" height="400"></canvas>
          </div>
          <div class="chart">
            <canvas id="chart-barra-general" class="chart-canvas" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection
@push('dashboard')
  
  <script>
    window.onload = function() {

      // Configuracion sweetalert Toast
      const Toast = Swal.mixin({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });

      const selectCampus = document.getElementById('id_campus');
      
      const choicesCampus = new Choices(selectCampus, {
        removeItemButton: true,
        shouldSort: true,
        itemSelectText: 'Presiona para seleccionar',
        placeholder: false,
      });

      selectCampus.classList.remove('select-invisible');

      const select = document.getElementById('id_programa');
      
      const choices = new Choices(select, {
        removeItemButton: true,
        shouldSort: true,
        itemSelectText: 'Presiona para seleccionar',
        placeholder: false,
      });

      select.classList.remove('select-invisible');

      // Función para calcular dias entre un rango de dos fechas
      function diasEntreFechas(fechaInicio, fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);

        // Calcula la diferencia en milisegundos
        const diferenciaMs = fin - inicio;

        // Convierte milisegundos a días
        const dias = (diferenciaMs / (1000 * 60 * 60 * 24)) + 1;

        return Math.floor(dias);
      }

      // Función para obtener array de fechas
      function obtenerFechasEnRango(fechaInicio, fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        const fechas = [];

        // Iterar desde la fecha de inicio hasta la fecha de fin
        for (let fecha = new Date(inicio); fecha <= fin; fecha.setDate(fecha.getDate() + 1)) {
          // Clonamos la fecha para evitar mutaciones
          fechas.push(new Date(fecha));
        }

        return fechas.map(f => f.toISOString().split('T')[0]); // Formato YYYY-MM-DD
      }

      // Función para obtener el catalogo de años
      function catalogoYears(yearVigente){

          fetch('/years')
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
          }).then(years => {
              // console.log(users);
              if (years) {
              
                  let selectYears = ``;

                  years.forEach(objeto => {

                      if (objeto.year == yearVigente) {
                        selectYears += `<option value="${objeto.year}" selected>${objeto.year}</option>`                       
                      } else {
                        selectYears += `<option value="${objeto.year}">${objeto.year}</option>` 
                      }

                  });
                  
                  document.getElementById('year').innerHTML = selectYears;
              }
          });
      }

      let year = document.getElementById('year').value;
      // console.log(year);

      catalogoYears(year)
      
      // Función para restar un año
      function restarUnAñoSoloConElAño(añoStr) {
        const año = parseInt(añoStr, 10);
        return (año - 1).toString();
      }

      let yearLast = restarUnAñoSoloConElAño(year)

      const inputDate = document.getElementById('date-filtro');
      const inputDateFin = document.getElementById('date-filtro-fin');

      inputDate.min = `${year}-01-01`;
      inputDate.max = `${year}-12-31`;
      inputDateFin.min = `${year}-01-01`;
      inputDateFin.max = `${year}-12-31`;

      // Función para obtener el catalogo de campus
      function catalogoCampus(){

          fetch('/campus')
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
          }).then(campus => {
              // console.log(users);
              if (campus) {
              
                  let selectCampusObj = [
                    {                        
                      'value': 'TODOS', 
                      'lable': 'TODOS',
                    }
                  ];

                  campus.forEach(objeto => {
                      selectCampusObj.unshift(
                        {
                          'value': objeto.campus, 
                          'lable': objeto.campus 
                        }
                      );
                  });
                  
                  choicesCampus.setChoices(selectCampusObj, 'value', 'label', true);
                  choicesCampus.setChoiceByValue('TODOS');
              }
          });
      }

      // Función para obtener el catalogo de campus
      function catalogoPrograma(){

          fetch('/programas')
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
          }).then(programas => {
              // console.log(users);
              if (programas) {
              
                  let selectProgramas = [
                    {                        
                      'value': 'TODOS', 
                      'lable': 'TODOS',
                    }
                  ];

                  programas.forEach(objeto => {                                        
                      selectProgramas.unshift(
                        {
                          'value': objeto.carrera, 
                          'lable': objeto.carrera 
                        }
                      );
                  });

                  choices.setChoices(selectProgramas, 'value', 'label', true);
                  choices.setChoiceByValue('TODOS');
              }
          });
      }

      catalogoCampus();
      catalogoPrograma();

      let dateHoy = inputDate.value;
      
      document.querySelectorAll('.date').forEach(objeto => {
          objeto.innerText = dateHoy;
      });

      let ctx1A = document.getElementById("chart-line-total");
      let ctx2A = document.getElementById("chart-line-calculadora");
      let ctx3A = document.getElementById("chart-line-general");

      let ctx1 = ctx1A.getContext("2d");
      let ctx2 = ctx2A.getContext("2d");
      let ctx3 = ctx3A.getContext("2d");

      let leadsInicial = Array(24).fill(0);
      let leadsInicialLast = Array(24).fill(0);

      let labels = [
        "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00",
        "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00",
        "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
      ];

      // -------------------------------------------------------------------------

      let ctxb1 = document.getElementById('chart-barra-total');
      let ctxb2 = document.getElementById('chart-barra-calculadora');
      let ctxb3 = document.getElementById('chart-barra-general');
      
      let leadsInicialBar = [];
      let leadsInicialLastBar = [];

      let labelsBar = [];

      // ---------------------------------------------------------------------------

      // Función para construir grafica de linea
      function createGraphLine(ctxn, leadsInicial, label, leadsInicialLast, labelLast, labels){

        const gradientStroke1 = ctxn.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(215, 40, 47, 1)');
        gradientStroke1.addColorStop(0.2, 'rgba(215, 40, 47, 0.5)');
        gradientStroke1.addColorStop(0, 'rgba(215, 40, 47, 0.2)'); 

        const gradientStroke2 = ctxn.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, 'rgba(19, 17, 17, 1)');
        gradientStroke2.addColorStop(0.2, 'rgba(143, 140, 140, 0.5)');
        gradientStroke2.addColorStop(0, 'rgba(211, 180, 181, 0.2)'); 

        return new Chart(ctxn, {
          type: "line",
          data: {
            labels: labels,
            datasets: [{
                label: label,
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#d7282f",
                borderWidth: 3,
                backgroundColor: gradientStroke1,
                fill: true,
                data: leadsInicial,
                maxBarThickness: 6
              },
              {
                label: labelLast,
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#000000",
                borderWidth: 3,
                backgroundColor: gradientStroke2,
                fill: true,
                data: leadsInicialLast,
                maxBarThickness: 6
              }
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false,
              }
            },
            interaction: {
              intersect: false,
              mode: 'index',
            },
            scales: {
              y: {
                grid: {
                  drawBorder: false,
                  display: true,
                  drawOnChartArea: true,
                  drawTicks: false,
                  borderDash: [5, 5]
                },
                ticks: {
                  display: true,
                  padding: 10,
                  color: '#b2b9bf',
                  font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                  },
                }
              },
              x: {
                grid: {
                  drawBorder: false,
                  display: false,
                  drawOnChartArea: false,
                  drawTicks: false,
                  borderDash: [5, 5]
                },
                ticks: {
                  display: true,
                  color: '#b2b9bf',
                  padding: 20,
                  font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                  },
                }
              },
            },
          },
        });

      }

      // Función para construir grafica de barras
      function createGraphBar(ctxbn, leadsInicialBar, labelBar, leadsInicialLastBar, labelLastBar, labelsBar){

        return new Chart(ctxbn, {
          type: 'bar',
          data: {
            labels: labelsBar,
            datasets: [
              {
                label: labelBar,
                data: leadsInicialBar,
                borderColor: "#d7282f",
                backgroundColor: "rgba(215, 40, 47, 0.5)",
                borderWidth: 1
              },
              {
                label: labelLastBar,
                data: leadsInicialLastBar,
                borderColor: "#000000",
                backgroundColor: "rgba(143, 140, 140, 0.5)",
                borderWidth: 1
              }
            ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }

      // -----------------------------------------------------------------------

      // Función para obtener leads totales, leads calculadora, leads general
      function getLeads(objData){

        return fetch(`/leads-ciclo?campus=${objData.campus}&programa=${objData.programa}&fechaInicio=${objData.dateFiltro}&fechaFin=${objData.dateFiltroFin}`)
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

      // Función para actualizar graficas
      function updateDataGraph(dataLeads, dataLeadsLast, graphN, idIconLeads){

        let dateFiltro = inputDate.value;
        let dateFiltroFin = inputDateFin.value;

        let year = document.getElementById('year').value;
        let yearLast = restarUnAñoSoloConElAño(year)

        let leadsTotal = 0;
        let leadsTotalLast = 0;

        // Validamos si el por dia o hora
        if (dateFiltro == dateFiltroFin) { // hora

          // console.log('por hora');

          let leadsInicial = Array(24).fill(0);
          let leadsInicialLast = Array(24).fill(0);

          dataLeads.forEach((element, index) => {
            leadsInicial[index] = element.total;
            leadsTotal = leadsTotal + element.total;  
          });

          dataLeadsLast.forEach((element, index) => {
            leadsInicialLast[index] = element.total;
            leadsTotalLast = leadsTotalLast + element.total; 
          });
          
          graphN.data.datasets[0].data = leadsInicial;
          graphN.data.datasets[1].data = leadsInicialLast;
          graphN.data.datasets[0].label = 'Total de leads ' + year;
          graphN.data.datasets[1].label = 'Total de leads ' + yearLast;
          graphN.update();
        } else { // dia

          // console.log('por dia');

          let labels = obtenerFechasEnRango(dateFiltro, dateFiltroFin);
          let dias = diasEntreFechas(dateFiltro, dateFiltroFin);
          // console.log(dias)

          let leadsInicial = Array(dias).fill(0);
          let leadsInicialLast = Array(dias).fill(0);

          dataLeads.forEach((element, index) => {
            leadsInicial[index] = element.total;
            // labels[index] = element.hora;
            leadsTotal = leadsTotal + element.total;  
          });

          dataLeadsLast.forEach((element, index) => {
            leadsInicialLast[index] = element.total;
            leadsTotalLast = leadsTotalLast + element.total; 
          });

          graphN.data.labels = labels;
          graphN.data.datasets[0].data = leadsInicial;
          graphN.data.datasets[1].data = leadsInicialLast;
          graphN.data.datasets[0].label = 'Total de leads ' + year;
          graphN.data.datasets[1].label = 'Total de leads ' + yearLast;
          graphN.update();
        }

        // console.log('Leads:', leadsInicial);

        document.getElementById(idIconLeads).innerText = leadsTotal;
        document.getElementById(idIconLeads + '-pasado').innerText = leadsTotalLast;
      }

      // Graficas de linea
      const GraphTotal = createGraphLine(ctx1, leadsInicial,  'Total de leads ' + year, leadsInicialLast,  'Total de leads ' + yearLast, labels);
      const GraphCalculadora = createGraphLine(ctx2, leadsInicial, 'Total de leads ' + year, leadsInicialLast, 'Total de leads ' + yearLast, labels);
      const GraphGeneral = createGraphLine(ctx3, leadsInicial, 'Total de leads ' + year, leadsInicialLast, 'Total de leads ' + yearLast, labels);

      // // Graficas de barra
      const GraphTotalBar = createGraphBar(ctxb1, leadsInicialBar,  'Total de leads '  + year, leadsInicialLastBar,  'Total de leads ' + yearLast, labelsBar);
      const GraphCalculadoraBar = createGraphBar(ctxb2, leadsInicialBar, 'Total de leads '  + year, leadsInicialLastBar, 'Total de leads ' + yearLast, labelsBar);
      const GraphGeneralBar = createGraphBar(ctxb3, leadsInicialBar, 'Total de leads ' + year, leadsInicialLastBar, 'Total de leads ' + yearLast, labelsBar);

      ctxb1.style.display = 'none';
      ctxb2.style.display = 'none';
      ctxb3.style.display = 'none';

      // Actualizamos datos de graficas
      function allUpdateGraph(obj){

        return getLeads(obj).then(data => {

          let dateFiltro = inputDate.value;
          let dateFiltroFin = inputDateFin.value;

          let graficaT = null;
          let graficaC = null;
          let graficaG = null;

          if (dateFiltro == dateFiltroFin) { 
            
            ctxb1.style.display = 'none';
            ctxb2.style.display = 'none';
            ctxb3.style.display = 'none';

            ctx1A.style.display = 'block';
            ctx2A.style.display = 'block';
            ctx3A.style.display = 'block';

            graficaT = GraphTotal;
            graficaC = GraphCalculadora;
            graficaG = GraphGeneral;
          } else {

            ctxb1.style.display = 'block';
            ctxb2.style.display = 'block';
            ctxb3.style.display = 'block';

            ctx1A.style.display = 'none';
            ctx2A.style.display = 'none';
            ctx3A.style.display = 'none';

            graficaT = GraphTotalBar;
            graficaC = GraphCalculadoraBar;
            graficaG = GraphGeneralBar;
          }

          updateDataGraph(data.leads_total, data.leads_total_last, graficaT, 'leads-total');
          updateDataGraph(data.leads_calculadora, data.leads_calculadora_last, graficaC, 'leads-calculadora');
          updateDataGraph(data.leads_general, data.leads_general_last, graficaG, 'leads-general');
          return true;
        });
      }

      let objData = {
        dateFiltro: dateHoy,
        dateFiltroFin: dateHoy,
        campus: '["TODOS"]',
        programa: '["TODOS"]'
      };
      
      allUpdateGraph(objData);

      document.getElementById('year').addEventListener('change', function(event){

        let year = event.target.value;
      
        inputDate.min = `${year}-01-01`;
        inputDate.max = `${year}-12-31`;
        inputDateFin.min = `${year}-01-01`;
        inputDateFin.max = `${year}-12-31`;

        inputDate.value = `${year}-01-01`;
        inputDateFin.value = `${year}-01-01`;
      });

      // Actualizamos datos de grafica manual
      document.getElementById('form-filtros').addEventListener('submit', function(event) {
        
        event.preventDefault();
      
        try {

          let dateFiltro = inputDate.value;
          let dateFiltroFin = inputDateFin.value;

          if (dateFiltro > dateFiltroFin) {
            throw "mayor";
          }

          // let campus = document.getElementById('id_campus').value;
          // let programa = document.getElementById('id_programa').value;

          let campus = JSON.stringify(choicesCampus.getValue(true));
          let programa = JSON.stringify(choices.getValue(true));

          if (choicesCampus.getValue(true) == 0) {
            throw "campus";
          }

          if (choices.getValue(true) == 0) {
            throw "programa";
          }

          let objData = {
            dateFiltro: dateFiltro,
            dateFiltroFin: dateFiltroFin,
            campus:  campus,
            programa: programa
          };

          let fechaTexto = dateFiltro;

          if (dateFiltro != dateFiltroFin) {
            fechaTexto = dateFiltro + ' al ' + dateFiltroFin;
          }

          document.querySelectorAll('.date').forEach(objeto => {
              objeto.innerText = fechaTexto;
          });

          document.getElementById("filtrar").disabled = true;

          allUpdateGraph(objData).then(response =>{
            document.getElementById("filtrar").disabled = false;
          });
          
        } catch (error) {

          console.log(error)
          let mensaje = null;

          if (error == 'mayor') {
            mensaje = 'La fecha inicio no puede ser mayor a la fecha final';          
          } else if(error == 'campus'){
            mensaje = 'Selecciona un campus';          
          } else if(error == 'programa'){
            mensaje = 'Selecciona un programa';          
          }
          
          Toast.fire({
            icon: "info",
            title: mensaje
          });
        }

      });

    }
  </script>
@endpush

