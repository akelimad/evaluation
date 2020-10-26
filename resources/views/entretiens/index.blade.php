@extends('layouts.app')
@section('title', 'Campagnes')
@section('breadcrumb')
  <li>Campagnes</li>
@endsection

@section('style')
  @parent
  <style>
    .entretiens-status {
      border-bottom: 1px solid #b7b4b4;
    }
    .entretiens-status a {
      transition: all .2s ease-in-out;
      padding: 12px 30px;
      display: inline-block;
      border-bottom: 3px solid transparent;
      color: black;
    }
    .entretiens-status a.active,
    .entretiens-status a:focus,
    .entretiens-status a:hover {
      border-bottom: 3px solid #337ab7;
      color: #337ab7;
    }
  </style>
@endsection

@section('content')
  <section class="content entretiens-list">
    <div class="row">
      <div class="col-md-8 col-sm-8">
        <h2 class="pageName m-0"><i class="fa fa-comments-o"></i> Campagnes <span class="badge badge-count">0</span></h2>
      </div>
      <div class="col-md-4 col-sm-4">
        <div class="pull-right">
          <a href="javascript:void(0)" onclick="return chmEntretien.form({})" class="btn bg-maroon"><i class="fa fa-plus"></i> Ajouter</a>
        </div>
      </div>
    </div>

    @include('entretiens.search')

    <div class="row mb-0">
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('entretiens.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="EntretiensTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script>
    $(document).ready(function () {

    })
  </script>
@endsection