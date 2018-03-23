@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('success_motif_save'))
                    @include('partials.alerts.success', ['messages' => Session::get('success_motif_save') ])
                @endif
                @if(session()->has('relanceMentor'))
                    @include('partials.alerts.success', ['messages' => session()->get('relanceMentor') ])
                @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">La liste des entretiens d'évaluations</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($entretiens)>0)
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-bordered table-inversed-blue table-striped">
                            <thead>
                                <tr>
                                    <th>Date limit </th>
                                    <th>Nom & prénom </th>
                                    <th>Fonction</th>
                                    <th>Type d'eval</th>
                                    <th>Réf</th>
                                    <th>Mentor</th>
                                    <th>Fonction</th>
                                    <th>Auto</th>
                                    <th>Visa N+1</th>
                                    <th>Visa N+2</th>
                                    <th class="text-center"> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entretiens as $entretien)
                                    @foreach($entretien->users()->paginate(10,['*'],'usersPage') as $key =>$user)
                                    <tr class="{{ !empty($user->motif) ? 'has-motif': '' }}">
                                        <td class="text-blue">
                                            {{ Carbon\Carbon::parse($entretien->date_limit)->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            <b><a href="{{url('user/'.$user->id)}}">{{ $user->name. ' '.$user->last_name  }}</a></b>
                                        </td>
                                        <td>
                                            {{$user->function ? str_limit($user->function, $limit = 20, $end = '...') : '---'}}
                                        </td>
                                        <td>
                                            <a href="{{url('entretiens/'.$entretien->id.'/u/'.$user->id)}}">
                                                {{ str_limit($entretien->titre, $limit = 20, $end = '...') }}</a>
                                        </td>
                                        <td>
                                            {{$entretien->id}}
                                        </td>
                                        <td>
                                            @if($user->parent)
                                                <a href="{{url('user/'.$user->parent->id)}}">{{ $user->parent->name.' '. $user->parent->last_name }}</a>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            {{$user->parent ? str_limit($user->parent->function, $limit = 20, $end = '...') : '---'}}
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-{{App\Entretien::answered($entretien->id, $user->id) == true ? 'success':'danger'}} empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-{{App\Entretien::answeredMentor($entretien->id, $user->id, App\User::getMentor($user->id) ? App\User::getMentor($user->id)->id : $user->id ) ? 'success':'danger'}} empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-danger empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="" class="btn-primary icon-fill" data-toggle="tooltip" title="Imprimer"> <i class="fa fa-print"></i> </a>
                                            <a href="javascript:void(0)" class="bg-navy icon-fill show-motif" data-toggle="tooltip" title="Motif de non réaliation" data-id="{{$entretien->id.$key}}"> <i class="glyphicon glyphicon-wrench"></i> </a>
                                            @if(!App\Entretien::answered($entretien->id, $user->id))
                                            <form action="{{ url('notifyMentorInterview/'.$entretien->id.'/'.$user->id) }}" method="post" style="display: inline-block;">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn-danger icon-fill" data-toggle="tooltip" title="Relancer le mentor pour evaluer {{ $user->name.' '.$user->last_name }}"> <i class="fa fa-bell"></i> </button>
                                            </form>
                                            @else
                                            <button class="btn-danger icon-fill relanceMentor" data-toggle="tooltip" title="Ya pas de relance. le mentor et collaborateur ont déjà fait l'entretien" ><i class="fa fa-bell"></i></button>
                                            @endif
                                            <a href="javascript:void(0)" class="bg-purple icon-fill" data-toggle="tooltip" title="Aperçu" onclick="return chmEntretien.apercu({eid: {{$entretien->id}}, uid: {{$user->id}} })"> <i class="fa fa-search"></i> </a>
                                        </td>
                                    </tr>
                                    <tr class="entretien-row">
                                        <td colspan="11">
                                            <form action="{{ url('entretiens/'.$user->id.'/update') }}" method="post" class="motif-form-{{$entretien->id.$key}}">
                                                <input name="_method" type="hidden" value="PUT">
                                                {{ csrf_field() }}
                                                <div class="">
                                                    <div class="col-md-6">
                                                        <select name="motif" id="motif" class="form-control" required="">
                                                            <option value=""> Vueillez indiquer le motif d'abscence pour {{ $user->name." ".$user->last_name }} </option>
                                                            <option value="MAL" {{$user->motif == "MAL" ? 'selected' : ''}} > Abscence maladie sur la periode </option>
                                                            <option value="MAT" {{$user->motif == "MAT" ? 'selected' : ''}} > Abscence maternité sur la periode </option>
                                                            <option value="CP" {{$user->motif == "CP" ? 'selected' : ''}}> Abscence congé parental sur la periode </option>
                                                            <option value="INV" {{$user->motif == "INV" ? 'selected' : ''}} > Abscence invalidité sur la periode </option>
                                                            <option value="CIP" {{$user->motif == "CIP" ? 'selected' : ''}} > Abscence pour congé individuel de la formation sur la periode </option>
                                                            <option value="FPI" {{$user->motif == "FPI" ? 'selected' : ''}} > Entretien de fin de période d'essai prévu sur la periode </option>
                                                            <option value="AD" {{$user->motif == "AD" ? 'selected' : ''}} > Annulation de la demande d'entretien </option>
                                                            <option value="SE" {{$user->motif == "SE" ? 'selected' : ''}} > Sortie des effectifs </option>
                                                            <option value="AUTRE" {{$user->motif == "AUTRE" ? 'selected' : ''}} > Autre motif </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Sauvegarder </button>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {!! $entretien->users()->paginate(10,['*'],'usersPage')->links() !!}
                                @endforeach
                            </tbody>
                        </table>
                        {!! $entretiens->links() !!}
                    </div>
                    
                    @else
                        <p class="alert alert-default">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  