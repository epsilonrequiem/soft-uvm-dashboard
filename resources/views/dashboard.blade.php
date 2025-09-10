@extends('layouts.user_type.auth')

@section('content')

  <h4>Filtros avanzados</h4>

  <form id="form-filtros" class="row mb-4">
    <div class="col-md-4">
      <label for="fecha" class="form-label">Fecha</label>
      <input type="date" class="form-control" id="fecha" name="fecha" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" id="filtrar" class="btn btn-primary mb-0">Filtrar</button>
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
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
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
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
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
              <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
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

      let dateHoy = document.getElementById('fecha').value;
      
      document.querySelectorAll('.date').forEach(objeto => {
          objeto.innerText = dateHoy;
      });

      let ctx1 = document.getElementById("chart-line-total").getContext("2d");
      let ctx2 = document.getElementById("chart-line-calculadora").getContext("2d");
      let ctx3 = document.getElementById("chart-line-general").getContext("2d");

      let gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
      gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
      gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
      gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); 

      let gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
      gradientStroke2.addColorStop(1, 'rgba(203,12,159,0.2)');
      gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
      gradientStroke2.addColorStop(0, 'rgba(203,12,159,0)'); 

      let gradientStroke3 = ctx3.createLinearGradient(0, 230, 0, 50);
      gradientStroke3.addColorStop(1, 'rgba(203,12,159,0.2)');
      gradientStroke3.addColorStop(0.2, 'rgba(72,72,176,0.0)');
      gradientStroke3.addColorStop(0, 'rgba(203,12,159,0)');

      let leadsTotal = Array(24).fill(0);
      let leadsCalculadora = Array(24).fill(0);
      let leadsGeneral = Array(24).fill(0);

      let labels = [
            "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00",
            "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00",
            "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
      ];

      const graficoTotal = new Chart(ctx1, {
        type: "line",
        data: {
          labels: labels,
          datasets: [{
              label: "Leads Total",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#cb0c9f",
              borderWidth: 3,
              backgroundColor: gradientStroke1,
              fill: true,
              data: leadsTotal,
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

      const graficoCalculadora = new Chart(ctx2, {
        type: "line",
        data: {
          labels: labels,
          datasets: [{
              label: "Leads Calculadora",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#cb0c9f",
              borderWidth: 3,
              backgroundColor: gradientStroke1,
              fill: true,
              data: leadsCalculadora,
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

      const graficoGeneral = new Chart(ctx3, {
        type: "line",
        data: {
          labels: labels,
          datasets: [{
              label: "Leads General",
              tension: 0.4,
              borderWidth: 0,
              pointRadius: 0,
              borderColor: "#cb0c9f",
              borderWidth: 3,
              backgroundColor: gradientStroke1,
              fill: true,
              data: leadsGeneral,
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

      // Función para obtener los datos del dashboard (leads totales, leads calculadora, leads general)
      function dateCiclo(date) {

        leadsTotal = Array(24).fill(0);
        leadsCalculadora = Array(24).fill(0);
        leadsGeneral = Array(24).fill(0);

        return fetch('/leads-ciclo/' + date)
        .then(response => {

          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Http -> ' + response.status);
          }
        })
        .then(data => {
          console.log(data);

          let total = 0;
          let calculadora = 0;
          let general = 0;

          data.datos.leads_calculadora.forEach((element, index) => {
            leadsCalculadora[index] = element.total;
            calculadora = calculadora + element.total;  
          });

          data.datos.leads_general.forEach((element, index) => {
            leadsGeneral[index] = element.total;
            general = general + element.total;
          });

          data.datos.leads_total.forEach((element, index) => {
            leadsTotal[index] = element.total;
            total = total + element.total;
          });

          console.log('Leads Calculadora:', leadsCalculadora);
          console.log('Leads General:', leadsGeneral);
          console.log('Leads Total:', leadsTotal);

          document.getElementById("leads-total").innerText = total;
          document.getElementById("leads-calculadora").innerText = calculadora;
          document.getElementById("leads-general").innerText = general;

          graficoTotal.data.datasets[0].data = leadsTotal;
          graficoTotal.update();

          graficoCalculadora.data.datasets[0].data = leadsCalculadora;
          graficoCalculadora.update();

          graficoGeneral.data.datasets[0].data = leadsGeneral;
          graficoGeneral.update();

          return true;
        })
        .catch(error => {
          console.error('Error:', error);
          return false;
        });
      }

      dateCiclo(dateHoy);

      document.getElementById('form-filtros').addEventListener('submit', function(event) {
        
        event.preventDefault();
        let date = document.getElementById('fecha').value;
        
        document.querySelectorAll('.date').forEach(objeto => {
            objeto.innerText = date;
        });

        document.getElementById("filtrar").disabled = true;

        dateCiclo(date).then(resultado => {
          if (resultado) {
            console.log('Datos actualizados para la fecha:', date);
          } else {
            console.log('No se pudieron actualizar los datos para la fecha:', date);
          }
          document.getElementById("filtrar").disabled = false;
        });

      });

    }
  </script>
@endpush

