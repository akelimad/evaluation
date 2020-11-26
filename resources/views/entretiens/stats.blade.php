@extends('layouts.app')

@section('title', 'Statistiques')
@section('style')
  @parent
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.css">
  <style>
    .scrtabs-tabs-fixed-container, .scrtabs-tab-container, #stats-tabs {
      height: 85px;
    }
    #stats-tabs>li.active>a,
    #stats-tabs>li.active>a:hover,
    #stats-tabs>li.active>a:focus {
      background-color: #fff;
      font-weight: 700;
      color: dodgerblue;
      border: none;
    }
    #stats-tabs {
      background-color: #CCC;
      font-weight: 700;
      color: #CCC;
    }
    #stats-tabs li {
      height: 100%;
    }
    #stats-tabs li a {
      border-radius: 0;
      height: 100%;
      color: gray;
    }
    .tab-content .nav-tabs li a {
      border-radius: 0;
      color: gray;
      font-weight: 700;
    }
    .tab-content .nav-tabs li.active a {
      border: none;
      color: dodgerblue;
    }
    .scrtabs-tab-scroll-arrow {
      height: 70px;
      position: relative;
    }
    .scrtabs-tab-scroll-arrow .glyphicon {
      position: absolute;
      top: 43%;
    }
  </style>
@endsection
@section('breadcrumb')
  <li><a href="{{ route('entretiens') }}" class="text-blue">{{ __("Campagnes") }}</a></li>
  <li>{{ $e->titre }}</li>
@endsection
@section('content')
  <section class="content p-sm-10">
    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-tabs text-center" role="tablist" id="stats-tabs">
          <li role="presentation" class="active">
            <a href="#deptTab" role="tab" data-toggle="tab">
              {{ __("Départements") }}
              <p class="m-0 font-18">{{ App\Answer::getGlobalNoteByTab('Department', $entretienUsersDeptsId, $e->id) }}%</p>
              <p class="m-0"><i class="fa fa-chevron-down"></i></p>
            </a>
          </li>
          <li role="presentation">
            <a href="#funcTab" role="tab" data-toggle="tab">
              {{ __("Fonctions") }}
              <p class="m-0 font-18">{{ App\Answer::getGlobalNoteByTab('Fonction', $entretienUsersFunctsId, $e->id) }}%</p>
              <p class="m-0"><i class="fa fa-chevron-down"></i></p>
            </a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade active in" id="deptTab">
            <ul class="nav nav-tabs text-center" id="deptNavTabs">
              @php($deptCounter = 0)
              @foreach($entretienUsersDeptsId as $dept_id => $users)
                @php($dept = App\Department::find($dept_id))
                <li class="nav-item {{ $deptCounter == 0 ? 'active':'' }}">
                  <a href="#department-{{ $dept_id }}-table" class="nav-link" data-toggle="tab">
                    {{ $dept ? $dept->title : 'N/A' }} ({{ $users->count() }})
                    <p class="m-0">
                      {{ App\Answer::getUsersNotesBy('Department', $dept_id, $users, $e->id)  }}%
                    </p>
                  </a>
                </li>
                @php($deptCounter += 1)
              @endforeach
            </ul>
            <div class="tab-content custom-scrollbar card p-15">
              @php($deptCounter = 0)
              @foreach($entretienUsersDeptsId as $dept_id => $users)
                <div class="tab-pane fade {{ $deptCounter == 0 ? 'active in':'' }}" id="department-{{ $dept_id }}-table">
                  <div class="row mb-0">
                    <div class="col-md-8">
                      <div class="card mb-0">
                        <div class="card-body">
                          <canvas id="dept-{{ $dept_id }}-chart" style="max-height: 400px;"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @php($deptCounter += 1)
              @endforeach
            </div>
          </div>
          <div class="tab-pane fade" id="funcTab">
            <ul class="nav nav-tabs text-center" id="funcNavTabs">
              @php($funcCounter = 0)
              @foreach($entretienUsersFunctsId as $func_id => $users)
                @php($func = App\Fonction::find($func_id))
                <li class="nav-item {{ $funcCounter == 0 ? 'active':'' }}">
                  <a href="#function-{{ $func_id }}-table" class="nav-link" data-toggle="tab">
                    {{ $func ? $func->title : 'N/A' }} ({{ $users->count() }})
                    <p class="m-0" title="{{ __("Taux de réalisations") }}" data-toggle="tooltip">
                      {{ App\Answer::getUsersNotesBy('Fonction', $func_id, $users, $e->id)  }}%
                    </p>
                  </a>
                </li>
                @php($funcCounter += 1)
              @endforeach
            </ul>
            <div class="tab-content custom-scrollbar card p-15">
              @php($funcCounter = 0)
              @foreach($entretienUsersFunctsId as $func_id => $users)
                <div class="tab-pane fade {{ $funcCounter == 0 ? 'active in':'' }}" id="function-{{ $func_id }}-table">
                  <div class="row mb-0">
                    <div class="col-md-8">
                      <div class="card mb-0">
                        <div class="card-body">
                          <canvas id="func-{{ $func_id }}-chart" style="max-height: 400px;"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @php($funcCounter += 1)
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script src="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.4.0/dist/jquery.scrolling-tabs.min.js"></script>
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
      $('#stats-tabs').scrollingTabs({
        scrollToTabEdge: true,
        enableSwiping: true
      });

      var chartOptions = {
        responsive: true,
        legend: {
          display: false,
        },
        tooltips: {
          callbacks: {
            label: function (tooltipItems, data) {
              return  tooltipItems.xLabel + " %";
            }
          }
        },
        animation: {
          animateScale: true,
          animateRotate: true
        },
        cutoutPercentage: 70,
        scales: {
          yAxes: [{
            display: true,
            ticks: {
              autoSkip: false,
            }
          }],
          xAxes: [{
            beginAtZero: true,
            ticks: {
              autoSkip: false,
              beginAtZero: true,
              steps: 10,
              max: 100
            }
          }]
        },
        plugins: {
          datalabels: {
            anchor: 'center',
            color: '#fff',
            align: 'center',
            formatter: function (value, context) {
              return value + ' %';
            },
            font: {
              weight: 'bold',
            }
          }
        }
      }

      @foreach($entretienUsersDeptsId as $dept_id => $users)
        let myChart{{ $dept_id }} = new Chart(document.getElementById('dept-{{ $dept_id }}-chart'), {
            type: 'horizontalBar',
            data: {
              datasets: [{
                data: [
                  @foreach(App\Answer::usersNotes($e->id, $users) as $userData)
                    {{ $userData['note'] }},
                  @endforeach
                ],
                backgroundColor: [
                  @foreach($users as $user)
                  getRandomColor(),
                  @endforeach
                ],
              }],
              labels: [
                @foreach(App\Answer::usersNotes($e->id, $users) as $userData)
                  "{{ $userData['user_fullname'] }}",
                @endforeach
              ]
            },
            options: chartOptions,
          });
      @endforeach

      @foreach($entretienUsersFunctsId as $func_id => $users)
        let myChart{{ $func_id }} = new Chart(document.getElementById('func-{{ $func_id }}-chart'), {
            type: 'horizontalBar',
            data: {
              datasets: [{
                data: [
                  @foreach(App\Answer::usersNotes($e->id, $users) as $userData)
                    {{ $userData['note'] }},
                  @endforeach
                ],
                backgroundColor: [
                  @foreach($users as $user)
                  getRandomColor(),
                  @endforeach
                ],
              }],
              labels: [
                @foreach(App\Answer::usersNotes($e->id, $users) as $userData)
                  "{{ $userData['user_fullname'] }}",
                @endforeach
              ]
            },
            options: chartOptions,
          });
      @endforeach
    })
  </script>
@endsection