@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ajouter un nouveau rôle</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" action="{{ url('role/store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nom</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="eg. admin, Rh, ....">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="display_name" class="col-sm-2 control-label">Le nom affiché</label>
                                <div class="col-sm-10">
                                    <input type="text" name="display_name" class="form-control" id="display_name" placeholder="eg. role admin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" rows="3" placeholder="Description ...."></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Permissions</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                    @foreach($permissions as $p)
                                        <div class="col-sm-3 checkbox">
                                            <label>
                                                <input type="checkbox" value="{{$p->id}}" name="permissions[]" >{{$p->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Annuler</button>
                                <button type="submit" class="btn btn-info pull-right">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  