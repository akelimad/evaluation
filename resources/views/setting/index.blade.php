@extends('layouts.app')
@section('content')
    <section class="content users">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    @foreach (['danger', 'warning', 'success', 'info'] as $key)
                        @if(Session::has($key))
                          <div class="chm-alerts alert alert-info alert-white rounded">
                              <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
                              <div class="icon"><i class="fa fa-info-circle"></i></div>
                              <span> {!! Session::get($key) !!} </span>
                          </div>
                        @endif
                    @endforeach
                    <div class="box-header">
                        <h3 class="box-title">Liste des options <span class="badge"></span></h3>
                        <div class="box-tools mb40">

                        </div>
                    </div>
                    <p class="help-block">Ici vous avez la possibilité de personnaliser le options disponibles</p>
                    @if(count($settings)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-strped table-inversed-blue">
                                <tr>
                                    <th>Id</th>
                                    <th>Description</th>
                                    <th>Valeur</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                @foreach($settings as $key => $setting)
                                <tr>
                                    <td> {{ $setting->id }}</td>
                                    <td> {{ $setting->description ? $setting->description : '---' }} </td>
                                    <td> {{ $setting->value }}</td>
                                    <td class="text-center">  
                                      <a href="javascript:void(0)" onclick="return Setting.edit({id: {{$setting->id}}})" class="btn-primary icon-fill" title="Modifier" data-toggle="tooltip"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            {{ $settings->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
  