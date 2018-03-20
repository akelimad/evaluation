@extends('layouts.app')
@section('content')
    <section class="content index">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <p>Bienvenue {{ $user->name }} {{ $user->last_name }}</p>
                        <p>Voici les informations de votre Mentor:</p>
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset('img/avatar.png') }}" alt="User profile picture">
                        <h3 class="profile-username text-center">{{ $mentor->name }} {{ $mentor->last_name }} </h3>
                        <p class="text-muted text-center"> {{ $mentor->function }} </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item"><b>Service: </b> <a class="pull-right">{{ $mentor->service ? $mentor->service : '---' }}</a></li>
                            <li class="list-group-item"><b>Téléphone fixe: </b> <a class="pull-right">{{ $mentor->fix ? $mentor->fix : '---' }}</a></li>
                            <li class="list-group-item"><b>Téléphone mobile: </b> <a class="pull-right">{{ $mentor->tel ? $mentor->tel : '---' }}</a></li>
                            <li class="list-group-item"><b>Email: </b> <a class="pull-right">{{ $mentor->email }}</a></li>
                        </ul>
                        <p> <i>N'hésitez pas à solliciter votre Mentor si vous avez la moindre question concernant votre suivi RH.</i> </p>
                    </div>
                </div>
                @role('ADMIN')
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> Mes actualités </h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled news">
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li>
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li>
                            <li> <i class="fa fa-bell-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> <span class="help-block pull-right">13/02/2018</span></li> 
                        </ul>
                    </div>
                </div>
                @endrole
            </div>
            <div class="col-md-9">
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">Mes entretiens</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#entretiens" data-toggle="tab"> Entretiens </a></li>
                            <li><a href="#objectifs" data-toggle="tab"> Objectifs  </a></li>
                            <li><a href="#formations" data-toggle="tab"> Formations </a></li>
                            <li><a href="#taches" data-toggle="tab"> Tâches à mener  </a></li>
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="entretiens">
                                @if(App\User::getMentor(Auth::user()->id) && count($entretiens)>0)
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Titre </th>
                                                <th>Limité au</th>
                                                <th>Collaborateur</th>
                                                <th>Mentor</th>
                                                <th>RH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entretiens as $e)
                                            
                                            <tr>
                                                <td>
                                                    <a href="{{ url('entretiens/'.$e->id.'/u/'.Auth::user()->id) }}">{{$e->titre}}</a>
                                                </td>
                                                <td>
                                                    {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}
                                                </td>
                                                <td>
                                                    <span class="label label-{{App\Entretien::answered($e->id, Auth::user()->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($e->id, Auth::user()->id) ? 'Vous avez rempli votre évaluation':'Vous avez une évaluation à remplir'}}"> </span>
                                                </td>
                                                <td>
                                                    <span class="label label-{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'Validé par votre mentor':'Pas encore validé par votre mentor'}}"> </span>
                                                </td>
                                                <td>
                                                    <span class="label label-danger empty"> </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <p class="alert alert-default">Aucune donnée disponible !</p>
                                @endif                               
                            </div>
                            <div class="tab-pane" id="objectifs">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="formations">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="taches">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($collaborateurs)>0)
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">Mes collaborateurs</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#aa" data-toggle="tab"> Entretiens </a></li>
                            <li><a href="#bb" data-toggle="tab"> Objectifs  </a></li>
                            <li><a href="#cc" data-toggle="tab"> Formations </a></li>
                            <li><a href="#dd" data-toggle="tab"> Tâches à mener  </a></li>
                            
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="aa">
                                @if(count($collaborateurs)>0)
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nom & prénom </th>
                                                <th>Fonction</th>
                                                <th>Type d'évaluation</th>
                                                <th>Collaborateur</th>
                                                <th>Mentor</th>
                                                <th>RH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($collaborateurs as $coll)
                                            @foreach($coll->entretiens as $en)
                                            <tr>
                                                <td>
                                                    <a href="{{url('user/'.$coll->id)}}">{{$coll->name." ".$coll->last_name}}</a>
                                                </td>
                                                <td> 
                                                    {{ $coll->function ? $coll->function : '---'}} 
                                                </td>
                                                <td> 
                                                    <a href="{{url('entretiens/'.$en->id.'/u/'.$coll->id)}}">{{ $en->titre }}</a> 
                                                </td>
                                                <td>
                                                    <span class="label label-{{App\Entretien::answered($en->id, $coll->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($en->id, $coll->id) ? 'Rempli par '.$coll->name :'Pas encore rempli par '.$coll->name }}"> </span>
                                                </td>
                                                <td>
                                                    <span class="label label-{{App\Entretien::answeredMentor($en->id, $coll->id, Auth::user()->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($en->id, $coll->id, Auth::user()->id) ? 'Vous avez validé l\'évaluation de '.$coll->name :'Veuillez valider l\'évaluation de '.$coll->name}}"> </span>
                                                </td>
                                                <td>
                                                    <span class="label label-danger empty"> </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <p class="alert alert-default">Aucune donnée disponible !</p>
                                @endif
                            </div>
                            <div class="tab-pane" id="bb">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>18555</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="cc">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="dd">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  