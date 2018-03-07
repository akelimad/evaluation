@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('success_evaluations_save'))
                    @include('partials.alerts.success', ['messages' => Session::get('success_evaluations_save') ])
                @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des entretiens</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding mb40">
                        <form action="{{ url('entretiens/storeEntretienEvals') }}" method="post">
                            {{ csrf_field() }}
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id </th>
                                        <th>Type</th>
                                        @foreach($to_fill as $key => $value)
                                        <th> {{ $value }} </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entretiens as $e)
                                        <tr>
                                            <td>{{ $e->id }}</td>
                                            <td>{{ $e->titre }}</td>
                                            @foreach($to_fill as $key => $value)
                                            <td>
                                                <input type="checkbox" name="evaluations[{{$e->id}}][]" value="{{ $key }}" {{ $e->evaluations && in_array($key, json_decode($e->evaluations)) ? 'checked' : '' }}>
                                            </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="submit" class="btn btn-success pull-right" value="Valider la selection">
                            <div class="clearfix"></div>
                        </form>
                    </div>
                    @else
                        <p class="alert alert-default">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  