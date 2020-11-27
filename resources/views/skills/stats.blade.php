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
      <form action="" method="GET" class="" id="">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="eid" class="required">{{ __("Choisissez une campagne") }}</label>
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
      <div class="graphs-container">
        <ul class="nav nav-tabs text-center" role="tablist" id="stats-tabs">
          @forelse($skill->getSkillsTypes() as $key => $type)
            <li role="presentation" class="{{ $key == 0 ? 'active':'' }}">
              <a href="#tab-{{ $key }}" role="tab" data-toggle="tab">{{ $type['title'] }}</a>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          @forelse($skill->getSkillsTypes() as $key => $type)
            <div class="tab-pane fade {{ $key == 0 ? 'active in':'' }}" id="tab-{{ $key }}">
              {{ $key }}
            </div>
          @endforeach
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
    $(document).ready(function () {
      $('#stats-tabs').scrollingTabs({
        scrollToTabEdge: true,
        enableSwiping: true
      });
    })
  </script>
@endsection