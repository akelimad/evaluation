@extends('layouts.app')
@section('title', 'Salaires')
@section('content')
  <section class="content evaluations">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des salaires pour: {{ $e->titre }} - {{ $user->name." ".$user->last_name }}</h2>
            <div class="nav-tabs-custom">
              @include('partials.tabs')
              <div class="tab-content">
                @if(count($salaries)>0)
                  <div class="box-body table-responsive no-padding mb40">
                    <table class="table table-hover table-striped text-center">
                      <thead>
                      <tr>
                        <th>Date</th>
                        <th>Brut</th>
                        <th>Prime</th>
                        <th>Commentaire</th>
                        @if($user->id != Auth::user()->id)
                          <th class="">Actions</th>
                        @endif
                      </tr>
                      </thead>
                      <tbody>
                      @foreach($salaries as $s)
                        <tr>
                          <td>{{ Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }}</td>
                          <td> {{ $s->brut or '---' }} </td>
                          <td> {{ $s->prime or '---' }} </td>
                          <td> {{ $s->comment ? $s->comment : '---' }} </td>
                          @if($user->id != Auth::user()->id)
                            <td>
                              @if($user->id != Auth::user()->id && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                                <a href="javascript:void(0)"
                                   onclick="return chmSalary.edit({eid: {{$e->id}} , uid: {{$user->id}}, sid: {{$s->id}} })"
                                   class="btn-warning icon-fill"> <i class="glyphicon glyphicon-pencil"></i> </a>
                              @endif
                            </td>
                          @endif
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                @endif

                {{ $salaries->links() }}

                <div class="mt-20">
                  <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour </a>
                  @if($user->id != Auth::user()->id && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                    <a onclick="return chmSalary.create({eid: {{$e->id}} , uid: {{$user->id}} })" data-id="{{$e->id}}"
                       class="btn btn-success"><i class="fa fa-plus"></i> Ajouter un salaire</a>
                  @endif
                </div>
              </div>
            </div>

            @include('partials.submit-eval')

            <div class="callout callout-info">
              <p class="">
                <i class="fa fa-info-circle fa-2x"></i>
                <span class="content-callout">Cette page affiche Liste des salaires de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
              </p>
            </div>
        </div>
      </div>
    </div>
  </section>
@endsection
  