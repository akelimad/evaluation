<div class="panel-group" id="accordion">
  <div class="panel panel-default mb-20">
    <div class="panel-heading" role="tab" id="heading-departments">
      <h4 class="panel-title">{{ __("Départements") }}</h4>
    </div>
    <div class="panel-body">
      <canvas id="dept-chart" style="max-height: 400px;"></canvas>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-functions">
      <h4 class="panel-title">{{ __("Fonctions") }}</h4>
    </div>
    <div class="panel-body">
      <canvas id="func-chart" style="max-height: 400px;"></canvas>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script>
  function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }

  $(document).ready(function () {
    setTimeout(function () {
      var chartOptions = {
        responsive: true,
        legend: {
          display: true,
        },
        animation: {
          animateScale: true,
          animateRotate: true
        },
        plugins: {
          datalabels: {
            anchor: 'center',
            color: '#ffffff',
            align: 'center',
            formatter: (value, ctx) => {
              let sum = 0;
              let dataArr = ctx.chart.data.datasets[0].data;
              dataArr.map(data => {
                sum += data;
              });
              let percentage = (value * 100 / sum).toFixed(0) + "%";
              return percentage;
            },
          }
        }
      }
      let deptChart = new Chart(document.getElementById('dept-chart'), {
        type: 'pie',
        data: {
          datasets: [{
            data: [
              @foreach($departments as $dept_id => $users)
                @php($department = App\Department::find($dept_id))
                {{ $users->count() }},
              @endforeach
            ],
            backgroundColor: [
              @foreach($departments as $dept_id)
              getRandomColor(),
              @endforeach
            ],
          }],
          labels: [
            @foreach($departments as $dept_id => $users)
            @php($department = App\Department::find($dept_id))
             "{{ $department ? $department->title : 'Indéfinie' }}",
            @endforeach
          ]
        },
        options: chartOptions,
      });

      let funcChart = new Chart(document.getElementById('func-chart'), {
        type: 'pie',
        data: {
          datasets: [{
            data: [
              @foreach($functions as $func_id => $users)
                @php($function = App\Fonction::find($func_id))
                {{ $users->count() }},
              @endforeach
            ],
            backgroundColor: [
              @foreach($functions as $func_id)
              getRandomColor(),
              @endforeach
            ],
          }],
          labels: [
            @foreach($functions as $func_id => $users)
            @php($function = App\Fonction::find($func_id))
             "{{ $function ? $function->title : 'Indéfinie' }}",
            @endforeach
          ]
        },
        options: chartOptions,
      });
    }, 500)
  })
</script>
</script>