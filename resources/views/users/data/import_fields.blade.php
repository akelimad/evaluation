@extends('layouts.app')
@section('content')
  <section class="content index">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-eye"></i> {{ __("Prévisualisation des informations des utilisateurs") }} <span class="badge badge-count">{{ count($csv_data) }}</span>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box">
          <form class="form-horizontal" method="POST" action="{{ url('users/import_process') }}">
            <div class="box-body table-responsive no-padding table-inversed-blue">
              {{ csrf_field() }}
              <table class="table table-striped table-hover table-bordered" style="font-size: 12px">
                @if (count($csv_header_fields) > 0)
                  <tr>
                    @foreach ($csv_header_fields as $csv_header_field)
                      <th>{{ $csv_header_field }}</th>
                    @endforeach
                  </tr>
                @endif
                @foreach ($csv_data as $row)
                  <tr>
                    @foreach ($row as $key => $value)
                      <td>{{ $value }}</td>
                    @endforeach
                  </tr>
                @endforeach
              </table>

              <div class="actions mt-30">
                <button type="submit" class="btn btn-success pull-right ml-10"><i class="fa fa-save"></i> {{ __("Enregistrer") }}</button>
                <a href="{{ route('users') }}" class="btn btn-default pull-right"><i class="fa fa-long-arrow-left"></i> {{ __("Annuler") }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection
  