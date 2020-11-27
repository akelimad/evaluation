@extends('layouts.app')
@section('title', 'Statistiques')
@section('style')
  @parent
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.css">
@endsection
@section('breadcrumb')
  <li><a href="{{ route('skills') }}" class="text-blue">{{ __("Fiches métiers") }}</a></li>
  <li>{{ __("Statistiques") }}</li>
@endsection
@section('content')
  <section class="content p-sm-10" id="content">
    <div class="card p-20">
      <h3>{{ __("La fiche métier : :fiche", ['fiche' => $skill->title]) }}</h3>
      <form action="" method="GET" class="" id="">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="eid" class="required" style="font-weight: bold;">{{ __("Choisissez une campagne") }}</label>
              <select name="eid" id="eid" class="form-control" required>
                <option value=""></option>
                @foreach(App\Entretien::getAll()->get() as $e)
                  <option value="{{ $e->id }}" {{ request('eid', 0) == $e->id ? 'selected':'' }}>{{ $e->titre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label for="">&nbsp;</label>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      @if (!empty($entretienUsers))
        <div class="graphs-container">
          <div class="row">
            <div class="col-md-8">
              <div class="card mb-0">
                <div class="card-body">
                  <canvas id="colls-skills-chart" style="max-height: 400px; min-height: 400px;"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection


@section('javascript')
  @parent
  <script src="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
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
      $('#stats-tabs').scrollingTabs({
        scrollToTabEdge: true,
        enableSwiping: true
      });

      var horizontalStackedBarOptions = {
        maintainAspectRatio: false,
        tooltips: {
          mode: 'index',
          intersect: false
        },
        responsive: true,
        plugins: {
          datalabels: {
            anchor: 'center',
            align: 'center',
            color: 'white',
            formatter: function (value, context) {
              return value;
            }
          }
        },
        scales: {
          xAxes: [{
            stacked: false,
            ticks: {
              autoSkip: false,
              max: 10
            }
          }],
          yAxes: [{
            stacked: false
          }]
        }
      }

      let collsSkillsChart = new Chart(document.getElementById('colls-skills-chart'), {
        type: 'horizontalBar',
        data: {
          labels: [
            @foreach($entretienUsers as $user)
              "{{ $user->name. ' ' . $user->last_name }}",
            @endforeach
          ],
          datasets: [
            @forelse($skill->getSkillsTypes() as $type)
              {
                label: "{{ $type['title'] }}",
                backgroundColor: getRandomColor(),
                data: [
                  @foreach($entretienUsers as $user)
                  {{ $skill->getSkillTypeNote($entretien->id, $user->id, App\User::getMentor($user->id)->id, "skill_type_".$type['id'], $type['id'], 'mentor') }},
                  @endforeach
                ]
              },
            @endforeach
          ]
        },
        options: horizontalStackedBarOptions,
      });
    })
  </script>
@endsection