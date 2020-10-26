@extends('layouts.app')
@section('title', 'Importer les utilisateurs')
@section('breadcrumb')
  <li><a href="{{ route('users') }}" class="text-blue">{{ __("Utilisateurs") }}</a></li>
  <li>{{ __("Importer") }}</li>
@endsection
@section('content')
  <section class="content index">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-upload"></i> {{ __("Importer les utilisateurs") }}
            <div class="pull-right">
              <a href="{{ asset('data/user_modele.csv') }}" class="btn btn-warning"> {{ __("Télécharger un modèle") }}</a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="row mb-0">
              <div class="col-md-8 col-md-offset-4">
                <form action="{{url('users/import_parse')}}" method="post" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-12">
                      <label for="" class="control-label required">{{ __("Veuillez choisir votre fichier CSV") }}</label>
                      <div class="input-group form-group col-md-6">
                        <label class="input-group-btn">
                        <span class="btn btn-primary">
                          Parcourir <input type="file" name="usersDataCsv" style="display: none;" required="" accept=".csv" chm-validate="required">
                        </span>
                        </label>
                        <input type="text" class="form-control" readonly="">
                      </div>
                    </div>
                  </div>
                  <div class="row mb-0">
                    <div class="col-md-12">
                      <a href="{{ route('users') }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> {{ __("Annuler") }}</a>
                      <button type="submit" class="btn btn-success">{{ __("Continuer") }} <i class="fa fa-long-arrow-right"></i></button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  