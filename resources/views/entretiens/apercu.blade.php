<div class="apercu">
    <p class="help-block">Aperçu sur les formations partagées entre {{ $user->name." ".$user->last_name }} et {{ $user->parent->name." ".$user->parent->last_name }} sur l'entretien : {{ $e->titre }}</p>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-evaluations">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-evaluations"  aria-controls="collapse-evaluations">
                    <i class="more-less fa fa-angle-right"></i>
                    Evaluations
                </a>
                </h4>
            </div>
            <div id="collapse-evaluations" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-evaluations">
                <div class="panel-body">
                    @foreach($groupes as $g)
                        @if(count($g->questions)>0)
                        <h3 class="groupe-heading">{{ $g->name }}</h3>
                            @forelse($g->questions as $q)
                                <div class="form-group">
                                    @if($q->parent == null)
                                        <label for="" class="questionTitle help-block text-blue"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                                    @endif
                                    @if($q->type == 'text')
                                    <p>
                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->answer : ''}}
                                    </p>
                                    @elseif($q->type == 'textarea')
                                    <p>
                                        {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->answer : ''}}
                                    </p>
                                    @elseif($q->type == "checkbox")
                                        <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au moins un élement</p>
                                        @foreach($q->children as $child)
                                            <div class="survey-checkbox">
                                                @if(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)  && in_array($child->id, App\Answer::getMentorAnswers($q->id, $user->id, $e->id)))
                                                    <label for="{{$child->id}}">{{ $child->titre }}</label>
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="clearfix"></div>
                                    @elseif($q->type == "radio")
                                        @foreach($q->children as $child)
                                            @if(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)  && in_array($child->id, App\Answer::getMentorAnswers($q->id, $user->id, $e->id)))
                                                <label for="{{$child->id}}">{{ $child->titre }}</label>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @empty
                                <p class="help-block"> Aucune question </p>
                            @endforelse
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-carrieres">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-carrieres"  aria-controls="collapse-carrieres">
                    <i class="more-less fa fa-angle-right"></i>
                    Carrières
                </a>
                </h4>
            </div>
            <div id="collapse-carrieres" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-carrieres">
                <div class="panel-body">
                    
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-formations">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-formations"  aria-controls="collapse-formations">
                    <i class="more-less fa fa-angle-right"></i>
                    Formations
                </a>
                </h4>
            </div>
            <div id="collapse-formations" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-formations">
                <div class="panel-body">
                    <p class="help-block">
                        La liste des formations souhaitées de la part de {{ $user->name." ".$user->last_name }} acceptées par {{ $user->parent->name." ".$user->parent->last_name }}
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
                    <p class="alert alert-default">Aucune donnée disponible !</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-skills">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-skills"  aria-controls="collapse-skills">
                    <i class="more-less fa fa-angle-right"></i>
                    Compétences
                </a>
                </h4>
            </div>
            <div id="collapse-skills" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-skills">
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
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-objectifs">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-objectifs"  aria-controls="collapse-objectifs">
                    <i class="more-less fa fa-angle-right"></i>
                    Objectifs
                </a>
                </h4>
            </div>
            <div id="collapse-objectifs" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-objectifs">
                <div class="panel-body">
                    @if(count($objectifs)>0)
                            <div class="box-body table-responsive no-padding mb40">
                                <form action="{{url('objectifs/updateNoteObjectifs')}}">
                                    <input type="hidden" name="entretien_id" value="{{$e->id}}">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <table class="table table-hover table-bordered table-inversed-blue">
                                        <tr>
                                            <th>Critères d'évaluation</th>
                                            <th>Note</th>
                                            <th>Apréciation</th>
                                            <th>Pondération(%) </th>
                                            <th>Objectif N+1 </th>
                                        </tr>
                                        @php($c = 0)
                                        @php($total = 0)
                                        @php($sousT = 0)
                                        @foreach($objectifs as $objectif)
                                            @php($c+=1)
                                            <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                                            <tr>
                                                <td colspan="5" class="objectifTitle"> 
                                                    {{ $objectif->title }} 
                                                </td>
                                            </tr>
                                            @php($sousTotal = 0)
                                            @php($sumPonderation = 0)
                                            @foreach($objectif->children as $sub)

                                                @php( $sumPonderation += $sub->ponderation )
                                                @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
                                                    @php( $sousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->note * $sub->ponderation )
                                                @endif
                                                
                                            <tr>
                                                <td>{{ $sub->title }}</td>
                                                <td class="criteres text-center">
                                                    <input type="hidden" name="subObjectifIds[{{$objectif->id}}][]" value="{{$sub->id}}">
                                                    @if(!App\Objectif::getNmoins1Note($sub->id, $e->id) || (App\Objectif::getNmoins1Note($sub->id, $e->id) == true && App\Objectif::getNmoins1Note($sub->id, $e->id)->objNplus1 == 0 ) )
                                                    <input type="text" id="slider" class="slider" placeholder="Votre note" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" data-provide="slider" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->note : '0' }}" data-slider-tooltip="" >
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="">
                                                    @else
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="">
                                                    <table class="table table-bordered table-sub-objectif">
                                                        <tr>
                                                            <td>N-1</td>
                                                            <td>Realisé</td>
                                                            <td>Ecart</td>
                                                            <td>N+1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span class="nMoins1-{{$sub->id}}" > {{App\Objectif::getNmoins1Note($sub->id, $e->id) ? App\Objectif::getNmoins1Note($sub->id, $e->id)->note : ''}} </span>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="0" max="10" class="text-center realise" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" data-id="{{$sub->id}}" value="{{App\Objectif::getRealise($sub->id, $e->id) ? App\Objectif::getRealise($sub->id, $e->id)->realise : ''}}">
                                                            </td>
                                                            <td>
                                                                <span class="ecart-{{$sub->id}}"></span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" class="form-control" value="{{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->appreciation : '' }}" placeholder="Pourquoi cette note ?">
                                                </td>
                                                <td class="text-center">
                                                    {{ $sub->ponderation }}
                                                    <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="{{$sub->ponderation}}">
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" {{isset(App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1) && App\Objectif::getObjectif($e->id,$user->id, $sub->id)->objNplus1 == 1 ? 'checked':''}}>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="sousTotal"> 
                                                    Sous-total  
                                                    <span class="badge badge-success pull-right">{{number_format($sousTotal/$sumPonderation, 2)}}</span>
                                                </td>
                                            </tr>
                                            @php( $total += number_format($sousTotal/$sumPonderation, 2) )
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="btn-warning">
                                                TOTAL DE L'ÉVALUATION  
                                                <span class="btn btn-info pull-right">{{ App\Objectif::cutNum($total/$c) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="btn-danger">
                                                NOTE FINALE
                                                <span class="btn btn-info pull-right"> {{ App\Objectif::cutNum($total/$c) *10 }} % </span>
                                            </td>
                                        </tr>
                                    </table>
                                    @if(!App\Objectif::filledObjectifs($e->id, $user->id, $user->parent->id))
                                    <input type="submit" value="Sauvegarder" class="btn btn-success">
                                    @endif
                                </form>
                                {{ $objectifs->links() }}
                            </div>
                    @else
                        @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                    @endif
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-salary">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-salary"  aria-controls="collapse-salary">
                    <i class="more-less fa fa-angle-right"></i>
                    Salaires
                </a>
                </h4>
            </div>
            <div id="collapse-salary" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-salary">
                <div class="panel-body">
                    
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading-comments">
                <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-comments"  aria-controls="collapse-comments">
                    <i class="more-less fa fa-angle-right"></i>
                    Commentaires
                </a>
                </h4>
            </div>
            <div id="collapse-comments" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-comments">
                <div class="panel-body">
                    
                </div>
            </div>
        </div>
    </div>
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

