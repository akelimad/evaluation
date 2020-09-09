@extends('layouts.app')
@section('title', 'Paramètres')
@section('breadcrumb')
  <li>Paramètres</li>
@endsection
@section('content')
  <section class="content setting">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body">
            <ul class="list-group">
              @foreach(App\Setting::$models as $model)
                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="box box-primary p-20">
          <form action="{{url('config/settings/store')}}" method="post">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="form-check">
                  <input type="checkbox" name="settings[toggle_sidebar]" id="toggle-sidebar" value="1" {{App\Setting::get('toggle_sidebar') == 1 ? 'checked' : ''}}>
                  <label for="toggle-sidebar">Toggle side bar</label>
                </div>
                <p class="help-block">Permet de réduire la taille du side bar.</p>
              </div>

              <div class="col-md-12">
                <label for="max_note control-label">Notation max</label>
                <input type="number" min="1" max="100" name="settings[max_note]" id="max_note" class="form-control" value="{{ App\Setting::get('max_note') }}" required style="max-width: 100px;">
                <p class="help-block">Permet de définir la note maximale pour les sections ou éléments de l'évaluation que le mentor pourrait attribuer</p>
              </div>

              <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Enregistrer</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection

  