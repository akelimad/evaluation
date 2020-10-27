@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
  <section class="content index">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile box-widget widget-user">
            <h3 class="widget-user-username">{{ __("Bienvenue") }} {{Auth::user()->displayName()}}</h3>
            @if(!Auth::user()->hasRole('ADMIN'))
              <p>{{ __("Voici les informations de votre Manager :") }}</p>
            @endif
            @if(Auth::user()->hasRole('ADMIN'))
              <div class="home-box-img-profile">
                <img src="{{ App\User::logo($user->id) }}" alt="" class="text-center img-responsive">
              </div>
            @else
              <img src="{{ App\User::avatar($mentor->id) }}" alt="" class="profile-user-img img-responsive img-circle">
            @endif
            @if(!Auth::user()->hasRole('ADMIN'))
              <h3 class="profile-username text-center">{{ $mentor->fullname() }} </h3>
              <p class="text-muted text-center">
                {{ (!empty($mentor->function)) ? App\Fonction::findOrFail($mentor->function)->title : '---' }}
              </p>
            @endif

            <ul class="list-group list-group-unbordered">
              @if(!Auth::user()->hasRole('ADMIN'))
                <li class="list-group-item"><b>{{ __("Département :") }}</b>
                  <a class="">{{ (!empty($mentor->service)) ? App\Department::findOrFail($mentor->service)->title : '---' }}</a>
                </li>
                <li class="list-group-item"><b>{{ __("Téléphone mobile:") }}</b> <a class="">{{ $mentor->tel ? $mentor->tel : '---' }}</a></li>
              @endif
              <li class="list-group-item"><b>{{ __("Email :") }}</b> <a class="">{{ $mentor->email }}</a></li>
            </ul>
            @role(["COLLABORATEUR"])
            <p><i>{{ __("N'hésitez pas à solliciter votre Manager si vous avez la moindre question concernant votre suivi RH.") }}</i></p>
            @endrole
          </div>
        </div>
      </div>

      <div class="col-md-9">
        @if(!Auth::user()->hasRole('ADMIN'))
          <div class="card portlet box box-primary mb-20">
            <div class="nav-tabs-custom portlet-title">
              <div class="caption caption-red mb-10">{{ __("Mes entretiens") }}</div>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="entretiens">
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                      <thead>
                      <tr>
                        <th>{{ __("Titre") }}</th>
                        <th>{{ __("Date de lancement") }}</th>
                        <th>{{ __("Date limite") }}</th>
                        <th class="text-center">{{ __("Collaborateur") }}</th>
                        <th class="text-center">{{ __("Manager") }}</th>
                        <th class="text-center">{{ __("Actions") }}</th>
                      </tr>
                      </thead>
                      <tbody>
                      @forelse($user->getUserEvaluationsByModel('ENT') as $eu)
                        @php($user = \App\User::find($eu->user_id))
                        @php($e = \App\Entretien::find($eu->entretien_id))
                        @php($userAnswered = App\Entretien::answered($e->id, Auth::user()->id))
                        <tr>
                          <td>
                            <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">{{$e->titre}}</a>
                          </td>
                          <td>
                            {{ $e->getStartDate() }}
                          </td>
                          <td>
                            {{ Carbon\Carbon::parse($e->date)->format('d/m/Y') }}
                          </td>
                          <td class="text-center">
                            <span class="label label-{{$userAnswered ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{$userAnswered ? 'Remplie le '.Carbon\Carbon::parse($userAnswered->user_updated_at)->format('d/m/Y à H:i') : 'Vous avez une évaluation à remplir'}}"> </span>
                          </td>
                          <td class="text-center">
                            <span class="label label-{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'Validée par manager le '.Carbon\Carbon::parse($eu->mentor_updated_at)->format('d/m/Y à H:i') :'Pas encore validée par votre mentor'}}"> </span>
                          </td>
                          <td class="text-center">
                            @if($userAnswered)
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default btn-block btn-sm"><i class="fa fa-eye"></i> Voir</a>
                            @else
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary btn-block btn-sm"><i class="fa fa-pencil"></i> Remplir</a>
                            @endif
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="6" class="text-center"><p class="m-0">{{ __("Aucune donnée trouvée ... !!") }}</p></td>
                        </tr>
                      @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card portlet box box-primary mb-20">
            <div class="nav-tabs-custom portlet-title">
              <div class="caption caption-red mb-10">{{ __("Mes collaborateurs") }}</div>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="aa">
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                      <thead>
                      <tr>
                        <th>{{ __("Nom et prénom") }}</th>
                        <th>{{ __("Campagne") }}</th>
                        <th>{{ __("Date de lancement") }}</th>
                        <th>{{ __("Date limite") }}</th>
                        <th class="text-center">{{ __("Collaborateur") }}</th>
                        <th class="text-center">{{ __("Manager") }}</th>
                        <th class="text-center">{{ __("Actions") }}</th>
                      </tr>
                      </thead>
                      <tbody>
                      @forelse($managerCollsEntretiens as $eu)
                        @php($e = App\Entretien::find($eu->entretien_id))
                        @php($user = App\User::find($eu->user_id))
                        @php($mentorAnswered = App\Entretien::answeredMentor($e->id, $user->id, Auth::user()->id))
                        <tr>
                          <td>
                            <a href="{{ url('user/'.$user->id) }}">{{ $user->fullname() }}</a>
                          </td>
                          <td>
                            <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">{{ $e->titre }}</a>
                          </td>
                          <td>
                            {{ $e->getStartDate() }}
                          </td>
                          <td>
                            {{ date('d/m/Y', strtotime($e->date_limit)) }}
                          </td>
                          <td class="text-center">
                            <span class="label label-{{App\Entretien::answered($e->id, $user->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($e->id, $user->id) ? 'Remplie le '.Carbon\Carbon::parse(App\Entretien::answered($e->id, $user->id)->user_updated_at)->format('d/m/Y à H:i') :'Pas encore rempli par '.$user->name }}"> </span>
                          </td>
                          <td class="text-center">
                            <span class="label label-{{$mentorAnswered ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{$mentorAnswered ? 'Validée par manager le '.Carbon\Carbon::parse($eu->mentor_updated_at)->format('d/m/Y à H:i') :'Veuillez valider l\'évaluation de '.$user->name}}"> </span>
                          </td>
                          <td class="text-center">
                            @if($mentorAnswered)
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default btn-block btn-sm"><i class="fa fa-eye"></i> Voir</a>
                            @else
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary btn-block btn-sm"><i class="fa fa-pencil"></i> Remplir</a>
                            @endif
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="7" class="text-center"><p class="m-0">{{ __("Aucune donnée trouvée ... !!") }}</p></td>
                        </tr>
                      @endforelse
                      </tbody>
                    </table>
                  </div>
                  <div class="box-pagination mt-20">
                    {{ $managerCollsEntretiens->links() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card portlet box box-primary mb-20">
            <div class="nav-tabs-custom portlet-title">
              <div class=" caption caption-red mb-10">{{ __("Mes Feedback 360 dont je suis l'évaluateur") }}</div>
            </div>
            <div class="portlet-body table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                <tr>
                  <th>{{ __("Evalué") }}</th>
                  <th>{{ __("Campagne") }}</th>
                  <th>{{ __("Date de lancement") }}</th>
                  <th>{{ __("Date limite") }}</th>
                  <th class="text-center">{{ __("Evaluateur (Vous)") }}</th>
                  <th class="text-center">{{ __("Actions") }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(Auth::user()->getUserEvaluationsByModel('FB360') as $eu)
                  @php($user = \App\User::find($eu->user_id))
                  @php($e = \App\Entretien::find($eu->entretien_id))
                  @php($mentorAnswered = App\Entretien::answeredMentor($e->id, $eu->user_id, $eu->mentor_id))
                  <tr>
                    <td>
                      {{ $user ? $user->fullname() : '---' }}
                    </td>
                    <td>
                      {{ $e ? $e->titre : '---' }}
                    </td>
                    <td>{{ date('d/m/Y', strtotime($e->date)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($e->date_limit)) }}</td>
                    <td class="text-center">
                      <span class="label label-{{ $mentorAnswered ? 'success':'danger' }} empty"></span>
                    </td>
                    <td>
                      @if($mentorAnswered)
                        <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default btn-block btn-sm"><i class="fa fa-eye"></i> Voir</a>
                      @else
                        <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary btn-block btn-sm"><i class="fa fa-pencil"></i> Remplir</a>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">{{ __("Aucune donnée trouvée ... !!") }}</td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
          @if (Auth::user()->hasSharedEntretienFb360('FB360', true))
            <div class="card portlet box box-primary">
            <div class="nav-tabs-custom portlet-title">
              <div class=" caption caption-red mb-10">{{ __("Mes Feedback 360 dont je suis l'évalué") }}</div>
            </div>
            <div class="portlet-body table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                <tr>
                  <th>{{ __("Evalué") }}</th>
                  <th>{{ __("Campagne") }}</th>
                  <th>{{ __("Date de lancement") }}</th>
                  <th>{{ __("Date limite") }}</th>
                  <th>{{ __("Evaluateur") }}</th>
                  <th class="text-center">{{ __("Actions") }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse(Auth::user()->hasSharedEntretienFb360('FB360', true) as $eu)
                  @php($user = \App\User::find($eu->user_id))
                  @php($evaluator = \App\User::find($eu->mentor_id))
                  @php($e = \App\Entretien::find($eu->entretien_id))
                  @php($mentorAnswered = App\Entretien::answeredMentor($e->id, $eu->user_id, $eu->mentor_id))
                  <tr>
                    <td>
                      {{ __("Vous") }}
                    </td>
                    <td>
                      {{ $e ? $e->titre : '---' }}
                    </td>
                    <td>{{ date('d/m/Y', strtotime($e->date)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($e->date_limit)) }}</td>
                    <td>
                      @if (isset($e->getOptions()['anonym']))
                        {{ __("Anonyme") }}
                      @else
                        {{ $evaluator ? $evaluator->fullname() : 'N/A' }}
                      @endif
                    </td>
                    <td class="text-center">
                      <a href="{{ route('entretien.apercu', ['id' => $eu->id]) }}" chm-modal="" chm-modal-options='{"width": "1000px"}' class="apercu"><i class="fa fa-search"></i></a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">{{ __("Aucune donnée trouvée ... !!") }}</td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
          @endif
        @endif
      </div>
      <div class="clearfix"></div>
    </div>
  </section>
@endsection
