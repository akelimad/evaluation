
@extends('layouts.app')
@section('content')
    <section class="content setting">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <ul class="list-group">
                            @foreach(App\Setting::$models as $model)
                                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="card">
                        <form action="{{ url('config/settings/store') }}" method="post" onsubmit="return Setting.store(event)">
                            {{ csrf_field() }}
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#generals" data-toggle="tab">Générale</a></li>
                                </ul>
                                <div class="tab-content mb20">
                                    <div class="active tab-pane" id="generals">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <input type="checkbox" name="settings[toggle_sidebar]" id="toggle-sidebar" value="1" {{$settings && $settings->toggle_sidebar == 1 ? 'checked' : ''}}> <label for="toggle-sidebar">Toggle side bar</label>
                                                <p class="help-block">Permet de réduire la taille du side bar.</p>
                                            </div> 
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-block">
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

  