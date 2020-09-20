@extends('layouts.app')
@section('title', 'Courriels')
@section('breadcrumb')
  <li>Courriels</li>
@endsection
@section('style')
  @parent
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title"><i class="glyphicon glyphicon-envelope"></i> Liste des emails <span class="badge badge-count">0</span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('email.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"emailForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-user-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </div>
          <div class="box-body">
            <div chm-table="{{ route('emails.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="EmailsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script src="{{asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
@endsection
