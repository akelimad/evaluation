@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des entretiens</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding mb40">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id </th>
                                    <th>Type</th>
                                    <th>Salaire</th>
                                    <th>Communication</th>
                                    <th>Salaire</th>
                                    <th>Salaire</th>
                                    <th>Salaire</th>
                                    <th>Salaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entretiens as $e)
                                    <tr>
                                        <td>{{ $e->id }}</td>
                                        <td>{{ $e->titre }}</td>
                                        <td><input type="checkbox" name=""></td>
                                        <td><input type="checkbox" name=""></td>
                                        <td><input type="checkbox" name=""></td>
                                        <td><input type="checkbox" name=""></td>
                                        <td><input type="checkbox" name=""></td>
                                        <td><input type="checkbox" name=""></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                        <input type="submit" class="btn btn-success pull-right" value="Valider la selection">
                        <div class="clearfix"></div>
                    @else
                        <p class="alert alert-default">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  