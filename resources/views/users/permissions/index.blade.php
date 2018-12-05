@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">La liste des permissions</h3>
                        <div class="box-tools">
                            <a href="{{ url('permission/create') }}" class="btn bg-maroon"> <i class="fa fa-plus"></i> Ajouter </a>
                      </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Le nom d'affichage</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            @foreach($permissions as $key => $permission)
                            <tr>
                                <td> {{$key+1}} </td>
                                <td> {{ $permission->name }} </td>
                                <td> {{ $permission->display_name }} </td>
                                <td> {{ $permission->description }} </td>
                                <td>  
                                    <a href="#"> <i class="fa fa-eye"></i> </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  