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
                                <li class="list-group-item {{ $model['active'] == $active ? 'active':'' }}"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="card">
                        <p class="help-block">Selectionnez un élement ...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

  