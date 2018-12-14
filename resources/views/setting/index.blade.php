
@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <ul class="list-group">
                            @foreach(App\Setting::$models as $model)
                                <li class="list-group-item"><a href="{{ url($model['route']) }}"><i class="{{ $model['icon'] }}"></i> {{ $model['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    Sélectionnez un élément ....
                </div>
            </div>
        </div>
    </section>
@endsection

  