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
                    <div class="filter-box mb40">
                        <h4 class="help-block">  <i class="fa fa-filter text-info"></i> Choisissez les critères de recherche que vous voulez <button class="btn btn-info btn-sm pull-right showFormBtn"> <i class="fa fa-chevron-down"></i></button></h4>
                        <form action="{{ url('entretiens/filter') }}" class="criteresForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="datepicker"> Date limite </label>
                                        <input type="text" name="d" id="datepicker" class="form-control" value="{{ isset($d) ? $d :'' }}" readonly="" data-date-format="dd-mm-yyyy">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="n"> Nom </label>
                                        <input type="text" name="n" id="n" class="form-control" value="{{ isset($n) ? $n :'' }}">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="t"> Type d'évaluation </label>
                                        <select name="t" id="t" class="form-control">
                                            <option value=""></option>
                                            @foreach($entretiens as $e)
                                            <option value="{{ $e->id }}" {{ isset($t) && $t == $e->id ? 'selected':'' }}>{{ $e->titre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    <div class="form-group">
                                        <label for="f"> Fonction </label>
                                        <select name="f" id="f" class="form-control">
                                            @foreach(App\Setting::asList('society.functions', false, true) as $key => $value)
                                            <option value="{{ $key }}" {{ (isset($f) && $f == $key) ? 'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Rechercher</button>
                                    <a href="{{url('entretiens/evaluations')}}" class="btn btn-default"><i class="fa fa-refresh"></i> Actualiser</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title">Liste des entretiens d'évaluations</h3>
                        <div class="box-tools">
                            
                        </div>
                    </div>
                    @if(count($results)>0)
                    <div class="box-body table-responsive no-padding">
                        <form action="{{ url('notifyMentorsInterview') }}" method="POST">
                        {{ csrf_field() }}
                        <table class="table table-hover table-inversed-blue">
                            <thead>
                                <tr>
                                    <th> <input type="checkbox" id="checkAll" </th>
                                    <th>Date limite </th>
                                    <th>Nom & prénom </th>
                                    <th>Fonction</th>
                                    <th>Type d'évaluation</th>
                                    <th>Réf</th>
                                    <th>Mentor</th>
                                    <th>Fonction</th>
                                    <th>Coll.</th>
                                    <th>Mentor</th>
                                    <th>RH</th>
                                    <th class="text-center"> Actions </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as  $k => $row)
                                    <tr class="{{ App\User::hasMotif($row->entretienId, $row->userId) ? 'has-motif': 'no-motif' }}" data-toggle="tooltip" title="{{ App\User::hasMotif($row->entretienId, $row->userId) ? 'Il ya un motif mentionné pour '.$row->name.''.$row->last_name.'. cliquer sur l\'icon de paramettre pour le voir ou le mettre à jour' : '' }}">
                                        <td>
                                            @if(!App\Entretien::answeredMentor($row->entretienId, $row->userId, App\User::getMentor($row->userId) ? App\User::getMentor($row->userId)->id : $row->userId))
                                           <div class="wrap-checkItem">
                                                <input type="checkbox" name="data[{{$k}}][mentorId]" class="usersId checkItem" autocomplete="off" value="{{ App\User::getMentor($row->userId) ? App\User::getMentor($row->userId)->id: $row->userId }}" >
                                                <input type="hidden" name="data[{{$k}}][entretienId]" value="{{ $row->entretienId }}">
                                            </div>
                                           @endif
                                        </td>
                                        <td class="text-blue">
                                            {{ Carbon\Carbon::parse($row->date_limit)->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            <b><a href="{{url('user/'.$row->userId)}}">{{ $row->name. ' '.$row->last_name  }}</a></b>
                                        </td>
                                        <td>
                                            {{$row->function ? str_limit($row->function, $limit = 20, $end = '...') : '---'}}
                                        </td>
                                        <td>
                                            <a href="{{url('entretiens/'.$row->entretienId.'/u/'.$row->userId)}}">
                                                {{ str_limit($row->titre, $limit = 20, $end = '...') }}</a>
                                        </td>
                                        <td>
                                            {{$row->entretienId}}
                                        </td>
                                        <td>
                                            @if(App\User::getMentor($row->userId))
                                                <a href="{{url('user/'.App\User::getMentor($row->userId)->id)}}">{{ App\User::getMentor($row->userId)->name.' '. App\User::getMentor($row->userId)->last_name }}</a>
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>
                                            {{App\User::getMentor($row->userId) && App\User::getMentor($row->userId)->function ? str_limit(App\User::getMentor($row->userId)->function, $limit = 20) : '---'}}
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-{{App\Entretien::answered($row->entretienId, $row->userId) == true ? 'success':'danger'}} empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-{{App\Entretien::answeredMentor($row->entretienId, $row->userId, App\User::getMentor($row->userId) ? App\User::getMentor($row->userId)->id : $row->userId ) ? 'success':'danger'}} empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-danger empty"> </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('entretiens/'.$row->entretienId.'/u/'.$row->userId.'/printPdf') }}" class="btn-primary icon-fill" data-toggle="tooltip" title="Imprimer"> <i class="fa fa-print"></i> </a>
                                            <a href="javascript:void(0)" class="bg-navy icon-fill show-motif" data-toggle="tooltip" title="Motif de non réaliation" data-id="{{$row->userId}}"> <i class="glyphicon glyphicon-wrench"></i> </a>
                                            @if(!App\Entretien::answeredMentor($row->entretienId, $row->userId, App\User::getMentor($row->userId) ? App\User::getMentor($row->userId)->id : $row->userId))
                                                <button type="button" class="btn-danger icon-fill notifyMentor" data-toggle="tooltip" title="Relancer le mentor pour evaluer {{ $row->name.' '.$row->last_name }}" data-entretien-id="{{$row->entretienId}}" data-user-id="{{$row->userId}}"> <i class="fa fa-bell" id="icon-{{$row->userId}}"></i> </button>
                                            @else
                                                <button class="btn-danger icon-fill relanceMentor" data-toggle="tooltip" title="Ya pas de relance. le mentor a déjà rempli son evaluation" ><i class="fa fa-bell"></i></button>
                                            @endif
                                            <a href="javascript:void(0)" class="bg-purple icon-fill" data-toggle="tooltip" title="Aperçu" onclick="return chmEntretien.apercu({eid: {{$row->entretienId}}, uid: {{$row->userId}} })"> <i class="fa fa-search"></i> </a>
                                        </td>
                                    </tr>
                                    <tr class="entretien-row motif-form-{{$row->userId}}">
                                        <td colspan="11" >
                                            {{ csrf_field() }}
                                            <div class="">
                                                <div class="col-md-6">
                                                    <select name="motif" id="motif-row-{{$row->userId}}" class="form-control" >
                                                        <option value=""> Veuillez indiquer le motif d'abscence pour {{ $row->name." ".$row->last_name }} </option>
                                                        <option value="MAL" {{App\User::hasMotif($row->entretienId, $row->userId) == "MAL" ? 'selected' : ''}} > Abscence maladie sur la periode </option>
                                                        <option value="MAT" {{App\User::hasMotif($row->entretienId, $row->userId) == "MAT" ? 'selected' : ''}} > Abscence maternité sur la periode </option>
                                                        <option value="CP" {{App\User::hasMotif($row->entretienId, $row->userId) == "CP" ? 'selected' : ''}}> Abscence congé parental sur la periode </option>
                                                        <option value="INV" {{App\User::hasMotif($row->entretienId, $row->userId) == "INV" ? 'selected' : ''}} > Abscence invalidité sur la periode </option>
                                                        <option value="CIP" {{App\User::hasMotif($row->entretienId, $row->userId) == "CIP" ? 'selected' : ''}} > Abscence pour congé individuel de la formation sur la periode </option>
                                                        <option value="FPI" {{App\User::hasMotif($row->entretienId, $row->userId) == "FPI" ? 'selected' : ''}} > Entretien de fin de période d'essai prévu sur la periode </option>
                                                        <option value="AD" {{App\User::hasMotif($row->entretienId, $row->userId) == "AD" ? 'selected' : ''}} > Annulation de la demande d'entretien </option>
                                                        <option value="SE" {{App\User::hasMotif($row->entretienId, $row->userId) == "SE" ? 'selected' : ''}} > Sortie des effectifs </option>
                                                        <option value="AUTRE" {{App\User::hasMotif($row->entretienId, $row->userId) == "AUTRE" ? 'selected' : ''}} > Autre motif </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-success motifUpdateBtn" data-entretien-id="{{$row->entretienId}}" data-user-id="{{$row->userId}}"><i class="fa fa-check"></i> Sauvegarder </button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $results->links() }}
                        <div class="sendInvitationBtn mb40">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-envelope"></i> Envoyer l'invitation</button>
                        </div>
                        </form>
                    </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection

@section('javascript')
<script>
    $('#datepicker').datepicker({
        autoclose: true,
        // format: 'dd-mm-yyyy',
        language: 'fr'
    })
    @if(isset($n))
        $(".showFormBtn i").toggleClass("fa-chevron-down fa-chevron-up")
        $(".criteresForm").fadeToggle()
    @endif
    $(function(){
        var baseUrl =  $("base").attr("href")
        $(".table").on('click', '.notifyMentor',function () {
            var eid= $(this).data('entretien-id');
            var uid= $(this).data('user-id');
            $(".notifyMentor>i#icon-"+uid).removeClass("fa-bell").addClass("fa-refresh fa-spin");
            var token = $('input[name="_token"]').val();
            var url = baseUrl+'/notifyMentorInterview/'+ eid +'/'+uid ;
            $.ajax({
                type: 'POST',
                url:  url,
                data: {
                    "eid": eid,
                    "uid": uid,
                    "_token": token,
                },
            }).done(function(response){
                $(".notifyMentor>i#icon-"+uid).removeClass("fa-spin").addClass("fa-bell");
                swal({ 
                    title: "Envoyé!", 
                    text: "Un email a bien été envoyé au mentor !", 
                    type: "success" 
                });
            }).fail(function(){
                swal('Oops...', "Il ya quelque chose qui ne va pas ! Il se peut que cet utilisateur fait la coordiantion des cours il faut supprimer tout d'abord ses cours!", 'error');
            });
        });

        $(".table").on('click', '.motifUpdateBtn',function () {
            var eid= $(this).data('entretien-id');
            var uid= $(this).data('user-id');
            var token = $('input[name="_token"]').val();
            var motif = $('#motif-row-'+uid).val()
            var url = baseUrl+'/entretiens/'+ eid +'/u/'+ uid +'/updateMotif' ;
            $.ajax({
                type: 'POST',
                url:  url,
                data: {
                    "eid": eid,
                    "uid": uid,
                    "_method" : "PUT",
                    "_token": token,
                    "motif" : motif
                },
            }).done(function(response){
                swal({ 
                    title: "Mis à jour !", 
                    text: "Le motif a bie été Sauvegardé !", 
                    type: "success" 
                }).then(function(){
                    location.reload(); 
                });
            }).fail(function(){
                swal('Oops...', "Il ya quelque chose qui ne va pas ! Il se peut que cet utilisateur fait la coordiantion des cours il faut supprimer tout d'abord ses cours!", 'error');
            });
        });

    })
</script>
@endsection