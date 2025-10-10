@extends('layouts.user_type.auth')

@section('content')

  <h4>Filtros avanzados</h4>

  <form id="form-filtros" class="row mb-4">
    <div class="col-md-4">
      <label for="date-filtro" class="form-label">Fecha</label>
      <input type="date" class="form-control" id="date-filtro" name="date-filtro" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" id="filtrar" class="btn btn-uvm bg-gradient-uvm mb-0">Filtrar</button>
    </div>
  </form>

  <!-- Indicadores iconos -->
  <div class="row">

  <h4>Leads del: <?= date('Y-m-d') ?></h4>

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
                <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
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
                <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
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
                <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  </div>

  <!-- Indicadores gráficos -->
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
            <canvas id="chart-line-total" class="chart-canvas" height="300"></canvas>
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
            <canvas id="chart-line-calculadora" class="chart-canvas" height="300"></canvas>
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
            <canvas id="chart-line-general" class="chart-canvas" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection
@push('dashboard')
  
  <script>
    window.onload = function() {

      let dateHoy = document.getElementById('date-filtro').value;
      
      document.querySelectorAll('.date').forEach(objeto => {
          objeto.innerText = dateHoy;
      });

      const ctx1 = document.getElementById("chart-line-total").getContext("2d");
      const ctx2 = document.getElementById("chart-line-calculadora").getContext("2d");
      const ctx3 = document.getElementById("chart-line-general").getContext("2d");

      let leadsInicial = Array(24).fill(0);
      let leadsInicialLast = Array(24).fill(0);

      let labels = [
        "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00",
        "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00",
        "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
      ];

      // Función para construir grafica de linea
      function createGraphLine (ctxn, leadsInicial, label, leadsInicialLast, labelLast, labels){

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
                borderColor: "#00000",
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

      // Función para obtener leads totales, leads calculadora, leads general
      function getLeads(date) {

        return fetch('/leads-ciclo/' + date)
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

      // Función para actualizar graficas de linea
      function updateDataGraph(dataLeads, dataLeadsLast, graphN, idIconLeads){

        let leadsTotal = 0;
        let leadsInicial = Array(24).fill(0);
        let leadsInicialLast = Array(24).fill(0);

        dataLeads.forEach((element, index) => {
          leadsInicial[index] = element.total;
          leadsTotal = leadsTotal + element.total;  
        });

        dataLeadsLast.forEach((element, index) => {
          leadsInicialLast[index] = element.total;
        });

        // console.log('Leads:', leadsInicial);

        document.getElementById(idIconLeads).innerText = leadsTotal;

        graphN.data.datasets[0].data = leadsInicial;
        graphN.data.datasets[1].data = leadsInicialLast;
        graphN.update();
      }

      // Graficas de linea
      const GraphTotal = createGraphLine(ctx1, leadsInicial,  'Leads Totales', leadsInicialLast,  'Leads Totales Pasado', labels);
      const GraphCalculadora = createGraphLine(ctx2, leadsInicial, 'Leads Calculadora', leadsInicialLast, 'Leads Calculadora Pasado', labels);
      const GraphGeneral = createGraphLine(ctx3, leadsInicial, 'Leads General', leadsInicialLast, 'Leads General Pasado', labels);

      // Actualizamos datos de graficas de linea
      function allUpdateGraph(date){

        return getLeads(date).then(data => {

          updateDataGraph(data.leads_total, data.leads_total_last, GraphTotal, 'leads-total');
          updateDataGraph(data.leads_calculadora, data.leads_calculadora_last, GraphCalculadora, 'leads-calculadora');
          updateDataGraph(data.leads_general, data.leads_general_last, GraphGeneral, 'leads-general');
          return true;
        })
      }

      allUpdateGraph(dateHoy);

      document.getElementById('form-filtros').addEventListener('submit', function(event) {
        
        event.preventDefault();
        
        let dateFiltro = document.getElementById('date-filtro').value;
       
        document.querySelectorAll('.date').forEach(objeto => {
            objeto.innerText = dateFiltro;
        });

        document.getElementById("filtrar").disabled = true;

        allUpdateGraph(dateFiltro).then(response =>{
          document.getElementById("filtrar").disabled = false;
        });
      });

    }
  </script>
@endpush

