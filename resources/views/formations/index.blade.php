@extends('layouts.app')
@section('title', 'Formations')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des formations <span class="badge">{{ $formations->total() }}</span> : {{ $e->titre }} - {{ $user->name." ".$user->last_name }} </h3>
          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="row">
                <div class="col-md-12 mb-0">
                  <h3 class="styled-title">Formations demandées</h3>
                </div>
                <div class="col-md-12">
                  @if(count($formations)>0)
                    <div class="table-responsive">
                      <table class="table table-hover table-striped text-center">
                        <thead>
                        <tr>
                          <th>Date</th>
                          <th>Exercice</th>
                          <th>Formation demandée</th>
                          <th>Description</th>
                          <th>Statut</th>
                          <th>Réalisé</th>
                          <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($formations as $f)
                          <form action="{{url('entretiens/formations/'.$f->id.'/mentorUpdate')}}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            {{ csrf_field() }}
                            <tr>
                              <td>{{ Carbon\Carbon::parse($f->date)->format('d/m/Y')}}</td>
                              <td>{{$f->exercice}}</td>
                              <td>{{$f->title}}</td>
                              <td><a title="{{$f->coll_comment}}" data-toggle="tooltip"><i class="fa fa-comment"></i></a></td>
                              <td>
                                @if($user->id == Auth::user()->id)
                                  <span class="label">
                                    @if($f->status == 0)En attente
                                    @elseif($f->status == 1)Refusé
                                    @elseif($f->status == 2)Accepté
                                    @endif
                                  </span>
                                @else
                                  <select name="status" id="status" class="" {{$user->id == Auth::user()->id ? 'disabled':'' }} >
                                    <option value="0" {{$f->status == 0 ? 'selected':''}} >En attente</option>
                                    <option value="1" {{$f->status == 1 ? 'selected':''}} >Refusé</option>
                                    <option value="2" {{$f->status == 2 ? 'selected':''}} >Accepté</option>
                                  </select>
                                @endif
                              </td>
                              <td>
                                <input type="checkbox" name="done" {{$f->done == 1 ? 'checked':''}} {{$user->id == Auth::user()->id ? 'disabled':'' }} >
                              </td>
                              <td class="text-center">
                                @if($user->id == Auth::user()->id && !App\Entretien::answered($e->id, $user->id))
                                  <a href="javascript:void(0)" onclick="return chmFormation.edit({e_id: {{$e->id}} , id: {{$f->id}} })" class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                @endif
                                @if($user->id != Auth::user()->id && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                                  <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Mettre à jour</button>
                                @endif
                              </td>
                            </tr>
                          </form>
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                  @else
                    @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                  @endif
                </div>
              </div>
              @if(!App\Entretien::answered($e->id, $user->id) && $user->id == Auth::user()->id)
                <div class="row">
                  <div class="col-md-12">
                    <a onclick="return chmFormation.create()" data-id="{{$e->id}}" class="btn btn-success addBtn mt-20"><i class="fa fa-plus"></i> Demander une formation</a>
                  </div>
                </div>
              @endif
            </div>
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche Liste des formations demandées de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  