@extends('layouts.app')
@section('title', 'Fiches métiers')
@section('breadcrumb')
  <li>Fiches métiers</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des fiches métiers <span class="badge"></span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('skill.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"skillForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </div>
          <div class="box-body">
            <div chm-table="{{ route('skills.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="SkillsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection