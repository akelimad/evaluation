@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
  <section class="content index">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile box-widget widget-user">
            <h3 class="widget-user-username">Bienvenue {{Auth::user()->displayName()}}</h3>
            @if(!Auth::user()->hasRole('ADMIN'))
              <p>Voici les informations de votre Manager :</p>
            @endif
            @if(Auth::user()->hasRole('ADMIN'))
              <div class="home-box-img-profile">
                <img src="{{ App\User::logo($user->id) }}" alt="" class="text-center img-responsive">
              </div>
            @else
              <img src="{{ App\User::avatar($mentor->id) }}" alt="" class="profile-user-img img-responsive img-circle">
            @endif
            @if(!Auth::user()->hasRole('ADMIN'))
              <h3 class="profile-username text-center">{{ $mentor->name }} {{ $mentor->last_name }} </h3>
              <p class="text-muted text-center">
                {{ (!empty($mentor->function)) ? App\Fonction::findOrFail($mentor->function)->title : '---' }}
              </p>
            @endif

            <ul class="list-group list-group-unbordered">
              @if(!Auth::user()->hasRole('ADMIN'))
                <li class="list-group-item"><b>Département : </b>
                  <a class="">{{ (!empty($mentor->service)) ? App\Department::findOrFail($mentor->service)->title : '---' }}</a>
                </li>
                <li class="list-group-item"><b>Téléphone mobile: </b> <a class="">{{ $mentor->tel ? $mentor->tel : '---' }}</a></li>
              @endif
              <li class="list-group-item"><b>Email: </b> <a class="">{{ $mentor->email }}</a></li>
            </ul>
            @role(["COLLABORATEUR"])
            <p><i>N'hésitez pas à solliciter votre Manager si vous avez la moindre question concernant votre suivi
                RH.</i></p>
            @endrole
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <div class="card portlet box box-primary">
          <div class="nav-tabs-custom portlet-title">
            <div class="caption caption-red mb-10">Mes entretiens</div>
          </div>
          <div class="portlet-body">
            <div class="tab-content">
              <div class="tab-pane active" id="entretiens">
                @if(App\User::getMentor(Auth::user()->id) && count($entretiens)>0)
                  <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                      <thead>
                      <tr>
                        <th>Titre</th>
                        <th>Date limite</th>
                        <th class="text-center">Collaborateur</th>
                        <th class="text-center">Manager</th>
                        <th class="text-center">Actions</th>
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($entretiens as $e)
                        @php($userAnswered = App\Entretien::answered($e->id, Auth::user()->id))
                        <tr>
                          <td>
                            <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">{{$e->titre}}</a>
                          </td>
                          <td>
                            {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}
                          </td>
                          <td class="text-center">
                            <span class="label label-{{$userAnswered ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{$userAnswered ? 'Remplie le '.Carbon\Carbon::parse($userAnswered->user_updated_at)->format('d/m/Y à H:i') : 'Vous avez une évaluation à remplir'}}"> </span>
                          </td>
                          <td class="text-center">
                            <span class="label label-{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answeredMentor($e->id, Auth::user()->id, App\User::getMentor(Auth::user()->id)->id) ? 'Validée par manager le '.Carbon\Carbon::parse($userAnswered->mentor_updated_at)->format('d/m/Y à H:i') :'Pas encore validée par votre mentor'}}"> </span>
                          </td>
                          <td class="text-center">
                            @if($userAnswered)
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default btn-block"><i class="fa fa-eye"></i> Voir</a>
                            @else
                              <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i> Remplir</a>
                            @endif
                          </td>
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
        </div>
        @if(count($collaborateurs)>0)
          <div class="card portlet box box-primary">
            <div class="nav-tabs-custom portlet-title">
              <div class="caption caption-red mb-10">Mes collaborateurs</div>
            </div>
            <div class="portlet-body">
              <div class="tab-content">
                <div class="tab-pane active" id="aa">
                  @if(count($collaborateurs) > 0)
                    <div class="box-body table-responsive no-padding">
                      <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                          <th>Nom et prénom</th>
                          <th>Campagne</th>
                          <th class="text-center">Date d'expiration</th>
                          <th class="text-center">Collaborateur</th>
                          <th class="text-center">Manager</th>
                          <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collaborateurs as $user)
                          @foreach($user->entretiens as $e)
                            @php($mentorAnswered = App\Entretien::answeredMentor($e->id, $user->id, Auth::user()->id))
                            <tr>
                              <td>
                                <a href="{{url('user/'.$user->id)}}">{{ $user->fullname() }}</a>
                              </td>
                              <td>
                                <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}">{{ $e->titre }}</a>
                              </td>
                              <td class="text-center">
                                {{ date('d/m/Y', strtotime($e->date_limit)) }}
                              </td>
                              <td class="text-center">
                                <span class="label label-{{App\Entretien::answered($e->id, $user->id) ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{App\Entretien::answered($e->id, $user->id) ? 'Remplie le '.Carbon\Carbon::parse(App\Entretien::answered($e->id, $user->id)->user_updated_at)->format('d/m/Y à H:i') :'Pas encore rempli par '.$user->name }}"> </span>
                              </td>
                              <td class="text-center">
                                <span class="label label-{{$mentorAnswered ? 'success':'danger'}} empty" data-toggle="tooltip" title="{{$mentorAnswered ? 'Validée par manager le '.Carbon\Carbon::parse($mentorAnswered->mentor_updated_at)->format('d/m/Y à H:i') :'Veuillez valider l\'évaluation de '.$user->name}}"> </span>
                              </td>
                              <td class="text-center">
                                @if($mentorAnswered)
                                  <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default btn-block"><i class="fa fa-eye"></i> Voir</a>
                                @else
                                  <a href="{{ route('anglets.synthese', ['e_id' => $e->id, 'uid' => $user->id]) }}" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i> Remplir</a>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        @endforeach
                        </tbody>
                      </table>
                    </div>

                    <div class="box-pagination">
                      {{--{{ $collaborateurs->links() }}--}}
                    </div>
                  @else
                    @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                  @endif
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

@section('javascript')
  <script>
    // this to show popup message for ADMIN only after authentication
    $(window).on('load', function () {
      @if(\Auth::user()->hasRole('ADMIN') && session('popup'))
          setTimeout(function () {
        swal({
          title: "Bienvenue",
          text: "Bienvenue {{Auth::user()->name}} à votre espace d'administration",
          type: "success"
        }, {
          @php(session()->forget('popup'))
        });
      }, 2000)
      @endif

    });
  </script>
@endsection
