@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ajouter une permission</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" action="{{ url('permission/store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nom</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="eg. create users, import, export ...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="display_name" class="col-sm-2 control-label">Le nom affiché</label>
                                <div class="col-sm-10">
                                    <input type="text" name="display_name" class="form-control" id="display_name" placeholder="eg. création des collaborateurs, .....">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" rows="3" placeholder="Description detailée ...."></textarea>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Sauvegarder</button>
                                <a href="" class="btn btn-default pull-right">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  