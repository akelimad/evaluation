@extends('layouts.app')
@section('content')
    <section class="content index">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('exist_already'))
                @include('partials.alerts.danger', ['messages' => Session::get('exist_already') ])
                @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-upload"></i> Prévisualisation des informations des utilisateurs. </h3>
                        <div class="box-tools">
                            
                      </div>
                    </div>
                    <form class="form-horizontal" method="POST" action="{{ url('users/import_process') }}">
                        <div class="box-body table-responsive no-padding mb40 table-inversed-blue">
                            {{ csrf_field() }}
                            <table class="table table-striped table-hover" style="font-size: 12px">
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

                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Continuez
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
  