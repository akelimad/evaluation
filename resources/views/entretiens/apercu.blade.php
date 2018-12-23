<div class="apercu">
    @if($user->parent)
    <p class="help-block">Aperçu sur les formations partagées entre 
        {{ $user->name." ".$user->last_name }} et 
        {{ $user->parent ? $user->parent->name : $user->name }} {{ $user->parent ? $user->parent->last_name : $user->last_name }} sur l'entretien : {{ $e->titre }}
    </p>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        @if(in_array('Evaluations', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-evaluations">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-evaluations"  aria-controls="collapse-evaluations" style="padding: 10px 15px;">
                    <i class="more-less fa fa-angle-right"></i>
                    Evaluations
                </a>
                </h4>
            </div>
            <div id="collapse-evaluations" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-evaluations">
                <div class="panel-body">
                    <div class="row">
                        @if(count($groupes)>0)
                        <div class="col-md-6">
                            <h4 class="alert alert-info"> {{ $user->name." ".$user->last_name }} </h4>
                            <div class="panel-group">
                                @foreach($groupes as $g)
                                    @if(count($g->questions)>0)
                                    <div class="panel panel-info">
                                        <div class="panel-heading">{{ $g->name }}</div>
                                        <div class="panel-body">
                                        @forelse($g->questions as $q)
                                            <div class="form-group">
                                                @if($q->parent == null)
                                                    <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                                                @endif
                                                @if($q->type == 'text')
                                                    <div class="text-background">
                                                        {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}
                                                    </div>
                                                @elseif($q->type == 'textarea')
                                                    <div class="text-background">
                                                        {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer :''}}
                                                    </div>
                                                @elseif($q->type == "checkbox")
                                                    @foreach($q->children as $child)
                                                        <div class="survey-checkbox">
                                                            <input type="{{$q->type}}" value="{{$child->id}}" {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer)) ? 'checked' : '' }} disabled>
                                                            <label >{{ $child->titre }}</label>
                                                        </div>
                                                    @endforeach
                                                    <div class="clearfix"></div>
                                                @elseif($q->type == "radio")
                                                    @foreach($q->children as $child)
                                                        <input type="{{$q->type}}" value="{{$child->id}}" {{App\Answer::getCollAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer ? 'checked' : '' }} disabled> 
                                                        <label >{{ $child->titre }}</label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @empty
                                            <p class="help-block"> Aucune question </p>
                                        @endforelse
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="alert alert-info"> {{ App\User::getMentor($user->id)->name." ".App\User::getMentor($user->id)->last_name }} </h4>
                            <div class="panel-group">
                                @foreach($groupes as $g)
                                    @if(count($g->questions)>0)
                                    <div class="panel panel-info">
                                        <div class="panel-heading">{{ $g->name }}
                                            <span class="pull-right"> Note : {{App\Answer::getGrpNote($g->id, $user->id, $e->id)}}</span>
                                        </div>
                                        <div class="panel-body">
                                        @forelse($g->questions as $q)
                                            <div class="form-group">
                                            @if($q->parent == null)
                                                    <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                                                @endif
                                                @if($q->type == 'text')
                                                    <div class="text-background">
                                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}
                                                    </div>
                                                @elseif($q->type == 'textarea')
                                                    <div class="text-background">
                                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}
                                                    </div>
                                                @elseif($q->type == "checkbox")
                                                    <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au moins un élement</p>
                                                    @foreach($q->children as $child)
                                                        <div class="survey-checkbox">
                                                            <input type="{{$q->type}}" name="answers[{{$q->id}}][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer)) ? 'checked' : '' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                                                            <label for="{{$child->titre}}">{{ $child->titre }}</label>
                                                        </div>
                                                    @endforeach
                                                    <div class="clearfix"></div>
                                                @elseif($q->type == "radio")
                                                    @foreach($q->children as $child)
                                                        <input type="{{$q->type}}" name="answers[{{$q->id}}]" id="{{$child->id}}" value="{{$child->id}}" required="" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer ? 'checked':'' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}> 
                                                        <label for="{{$child->id}}">{{ $child->titre }}</label>
                                                    @endforeach
                                                @endif
                                            </div>

                                        @empty
                                            <p class="help-block"> Aucune question </p>
                                        @endforelse
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>  
                            <div class="callout callout-success" style="margin-top:15px">
                                <p class="">
                                    <i class="fa fa-info-circle fa-2x"></i>
                                    <span class="content-callout h4"><b style="margin-right: 1em;">Note globale : {{App\Entretien::note($e->id, $user->id)}}</b>
                                        @foreach(App\Answer::NOTE_DEGREE as $key => $value)
                                            <span class="fa fa-star {{$key <= App\Entretien::note($e->id, $user->id) ? 'checked':''}}" title="{{$value['title'].' ('.$value['ref'].')'}}" data-toggle="tooltip"></span>
                                        @endforeach
                                    </span>
                                </p>
                            </div>
                        </div>
                        @else
                            <p class="alert alert-default">Aucune donnée disponible !</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Carrières', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-carrieres">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-carrieres"  aria-controls="collapse-carrieres">
                    <i class="more-less fa fa-angle-right"></i>
                    Carrières
                </a>
                </h4>
            </div>
            <div id="collapse-carrieres" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-carrieres">
                <div class="panel-body">
                    @if(count($carreers)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover text-center table-striped">
                                <thead>
                                    <tr>  
                                        <th>Date création</th>
                                        <th>Carrière</th>
                                        <th>Commentaire du mentor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carreers as $c)
                                    <tr>
                                        <td> {{ Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i' )}} </td>
                                        <td> {{ $c->userCarreer or "---" }} </td>
                                        <td> {{ $c->mentorComment or "---" }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Formations', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-formations">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-formations"  aria-controls="collapse-formations">
                    <i class="more-less fa fa-angle-right"></i>
                    Formations
                </a>
                </h4>
            </div>
            <div id="collapse-formations" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-formations">
                <div class="panel-body">
                    <p class="help-block">
                        Liste des formations souhaitées de la part de {{ $user->name." ".$user->last_name }} acceptées par {{ $user->parent ? $user->parent->name : $user->name  }} {{ $user->parent ? $user->parent->last_name : $user->last_name }}
                    </p>
                    @if(count($formations)>0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Exercice</th>
                                <th>Formation</th>
                                <th>Date d'acceptation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($formations as $f)
                            <tr>
                                <td> {{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}} </td>
                                <td> {{ $f->exercice }} </td>
                                <td> {{ $f->title }} </td>
                                <td> {{ Carbon\Carbon::parse($f->updated_at)->format('d/m/Y')}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Compétences', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-skills">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-skills"  aria-controls="collapse-skills">
                    <i class="more-less fa fa-angle-right"></i>
                    Compétences
                </a>
                </h4>
            </div>
            <div id="collapse-skills" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-skills">
                <div class="panel-body">
                    <table class="table table-hover">
                        <tr>
                            <th>Axe</th>
                            <th>Famille</th>
                            <th>Catégorie</th>
                            <th>Compétence</th>
                            <th>Objectif</th>
                            <th>N+1</th>
                            <th>Ecart</th>
                        </tr>
                        @php($totalObjectif = 0)
                        @php($totalNplus1 = 0)
                        @php($totalEcart = 0)
                        @foreach($skills as $skill)
                        <tr>
                            <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                            <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                            <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                            <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                            <td class="text-center">
                                {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : '---' }}
                                @php($totalObjectif += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : 0)
                            </td>
                            <td class="text-center">
                                {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : '---' }}
                                @php($totalNplus1 += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : 0)
                            </td>
                            <td class="text-center">
                                {{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : '---' }}
                                @php($totalEcart += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : 0)
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4">
                                Totaux des compétences :
                            </td>
                            <td class="text-center"><span class="badge">{{$totalObjectif}}</span></td>
                            <td class="text-center"><span class="badge">{{$totalNplus1}}</span></td>
                            <td class="text-center"><span class="badge">{{$totalEcart}}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Objectifs', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-objectifs">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-objectifs"  aria-controls="collapse-objectifs">
                    <i class="more-less fa fa-angle-right"></i>
                    Objectifs
                </a>
                </h4>
            </div>
            <div id="collapse-objectifs" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-objectifs">
                <div class="panel-body objectifs">
                    @if(count($objectifs)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped">
                                @if($user->id != Auth::user()->id)
                                <tr>
                                    <td colspan="3" class="objectifTitle {{ $user->id != Auth::user()->id ? 'separate':'' }}"> 
                                        {{ $user->name." ".$user->last_name }} 
                                    </td>
                                    <td colspan="4" class="objectifTitle"> 
                                        {{ $user->parent ? $user->parent->name : $user->name }} {{ $user->parent ? $user->parent->last_name : $user->last_name }} 
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th >Critères d'évaluation</th>
                                    <th >Coll. note(%)</th>
                                    <th class="{{ $user->id != Auth::user()->id ? 'separate':'' }}">Apréciation</th>
                                    <th >Pondération(%) </th>
                                    <th >Objectif N+1 </th>
                                    @if($user->id != Auth::user()->id)
                                    <th >Mentor note (%)</th>
                                    <th >Appreciation </th>
                                    @endif
                                </tr>
                                @php($c = 0)
                                @php($userTotal = 0)
                                @php($mentorTotal = 0)
                                @foreach($objectifs as $objectif)
                                    @php($c+=1)
                                    <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                                    <tr>
                                        <td colspan="7" class="objectifTitle text-center"> 
                                            {{ $objectif->title }} 
                                        </td>
                                    </tr>
                                    @php($usersousTotal = 0)
                                    @php($mentorsousTotal = 0)
                                    @php($sumPonderation = 0)
                                    @foreach($objectif->children as $sub)
                                        
                                        @php( $sumPonderation += $sub->ponderation )
                                        @if($user->id == Auth::user()->id )
                                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                                                @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
                                            @endif
                                        @else
                                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                                                @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
                                            @endif
                                            @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                                                @php( $mentorsousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote * $sub->ponderation )
                                            @endif
                                        @endif
                                        
                                    <tr>
                                        <td>{{ $sub->title }}</td>
                                        <td class="criteres text-center slider-note {{$user->id != Auth::user()->id ? 'disabled':''}}">
                                            @if(!App\Objectif::getNmoins1Note($sub->id, $e->id) || (App\Objectif::getNmoins1Note($sub->id, $e->id) == true && App\Objectif::getNmoins1Note($sub->id, $e->id)->objNplus1 == 0 ) )
                                            <input type="text" class="slider" placeholder="Votre note" required="" name="objectifs[{{$objectif->id}}][{{$sub->id}}][userNote]" data-provide="slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote : '0' }}" data-slider-tooltip="always">
                                            @else
                                            <table class="table table-bordered table-sub-objectif">
                                                <tr>
                                                    <td>N-1</td>
                                                    <td>Realisé</td>
                                                    <td>Ecart</td>
                                                    <td>N+1</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span class="nMoins1-{{$sub->id}}" > {{App\Objectif::getNmoins1Note($sub->id, $e->id) ? App\Objectif::getNmoins1Note($sub->id, $e->id)->userNote : ''}} </span>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" max="10" class="text-center realise" name="objectifs[{{$objectif->id}}][{{$sub->id}}][realise]" data-id="{{$sub->id}}" value="{{App\Objectif::getRealise($sub->id, $e->id) ? App\Objectif::getRealise($sub->id, $e->id)->realise : ''}}">
                                                    </td>
                                                    <td>
                                                        <span class="ecart-{{$sub->id}}"></span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                            @endif
                                        </td>
                                        <td class="{{ $user->id != Auth::user()->id ? 'separate':'' }}">
                                            <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][userAppr]" class="form-control" value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userAppreciation : '' }}" placeholder="Pourquoi cette note ?">
                                        </td>
                                        <td class="text-center">
                                            {{ $sub->ponderation }}
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="objectifs[{{$objectif->id}}][{{$sub->id}}][objNplus1]" value="1" {{isset(App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1) && App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1 == 1 ? 'checked':''}}>
                                        </td>
                                        @if($user->id != Auth::user()->id)
                                        <td class="slider-note">
                                            <input type="text" class="slider" placeholder="Votre note" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorNote]" data-provide="slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote : '0' }}" data-slider-tooltip="always" >
                                        </td>
                                        <td>
                                            <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorAppr]" class="form-control" value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorAppreciation : '' }}" placeholder="Pourquoi cette note ?">
                                        </td>
                                        @else
                                        <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorNote]" value="">
                                        <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][mentorAppreciation]" value="">
                                        @endif


                                    </tr>
                                    @endforeach
                                    <tr>
                                        @if($user->id == Auth::user()->id)
                                        <td colspan="7" class="sousTotal"> 
                                            <span>Sous-total</span>
                                            <span class="badge badge-success pull-right">{{App\Objectif::cutNum($usersousTotal/$sumPonderation)}}</span>
                                        </td>
                                        @else
                                        <td colspan="3" class="sousTotal {{ $user->id != Auth::user()->id ? 'separate':'' }}"> 
                                            <span>Sous-total</span>
                                            <span class="badge badge-success pull-right">{{App\Objectif::cutNum($usersousTotal/$sumPonderation)}}</span>
                                        </td>
                                        <td colspan="4" class="sousTotal"> 
                                            <span class="badge badge-success pull-right">{{App\Objectif::cutNum($mentorsousTotal/$sumPonderation)}}</span>
                                        </td>
                                        @endif
                                    </tr>
                                    @php( $userTotal += App\Objectif::cutNum($usersousTotal/$sumPonderation))
                                    @php( $mentorTotal += App\Objectif::cutNum($mentorsousTotal/$sumPonderation))
                                @endforeach
                                <tr>
                                    @if($user->id == Auth::user()->id)
                                    <td colspan="7" class="btn-warning" valign="middle">
                                        <span>TOTAL DE L'ÉVALUATION</span>  
                                        <span class="btn-default pull-right badge">
                                        {{ App\Objectif::cutNum($userTotal/$c) }} %
                                        </span>
                                    </td>
                                    @else
                                    <td colspan="3" class="btn-warning {{ $user->id != Auth::user()->id ? 'separate':'' }}" valign="middle">
                                        <span>TOTAL DE L'ÉVALUATION</span>  
                                        <span class="btn-default pull-right badge">
                                        {{ App\Objectif::cutNum($userTotal/$c) }} %
                                        </span>
                                    </td>
                                    <td colspan="4" class="btn-warning" valign="middle">
                                        <span class="btn-default pull-right badge">
                                        {{ App\Objectif::cutNum($mentorTotal/$c) }} %
                                        </span>
                                    </td>
                                    @endif
                                </tr>
                            </table>
                            {{ $objectifs->links() }}
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Salaires', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-salary">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-salary"  aria-controls="collapse-salary">
                    <i class="more-less fa fa-angle-right"></i>
                    Salaires
                </a>
                </h4>
            </div>
            <div id="collapse-salary" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-salary">
                <div class="panel-body">
                    @if(count($salaries)>0)
                        <div class="box-body table-responsive no-padding mb40">
                            <table class="table table-hover table-striped text-center">
                                <thead>
                                    <tr>  
                                        <th>Date </th>
                                        <th>Brut</th>
                                        <th>Prime</th>
                                        <th>Commentaire</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaries as $s)
                                    <tr>
                                        <td> {{ Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }} </td>
                                        <td> {{ $s->brut or '---' }} </td>
                                        <td> {{ $s->prime or '---' }} </td>
                                        <td> {{ $s->comment ? $s->comment : '---' }} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if(in_array('Commentaires', $entreEvalsTitle))
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-comments">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-comments"  aria-controls="collapse-comments">
                    <i class="more-less fa fa-angle-right"></i>
                    Commentaires
                </a>
                </h4>
            </div>
            <div id="collapse-comments" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-comments">
                <div class="panel-body">
                    @if($comment)
                        <div class="direct-chat-messages" style="height: auto;">
                            <div class="col-md-6">
                                <div class="direct-chat-msg mb20">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">{{ $user->name." ".$user->last_name }}</span>
                                        <span class="direct-chat-timestamp pull-right">{{ Carbon\Carbon::parse($comment->created_at)->format('d/m/Y à H:i')}}</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ App\User::avatar($user->id) }}" alt="message user image">
                                    <div class="direct-chat-text">
                                        {{ $comment->userComment or '---' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-right">{{ $user->parent->name." ".$user->parent->last_name }}</span>
                                        <span class="direct-chat-timestamp pull-left">{{ $comment->mentor_updated_at != null ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y à H:i') : '' }}</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ App\User::avatar($user->parent->id) }}" alt="message user image">
                                    <div class="direct-chat-text">
                                        {{ $comment->mentorComment or '---' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    @else
        @include('partials.alerts.info', ['messages' => "l'utlisateur ".$user->name." ".$user->last_name." n'a pas de mentor" ])
    @endif
</div>

<script>
    $(function(){
        function toggleIcon(e) {
        $(e.target).prev('.panel-heading').find(".more-less").toggleClass('fa-angle-right fa-angle-down');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $(".slider").bootstrapSlider()
    })
</script>

