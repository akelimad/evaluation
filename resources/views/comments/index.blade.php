@extends('layouts.app')
@section('title', 'Commentaires')
@section('content')
  <section class="content comments p-sm-10">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary direct-chat direct-chat-warning card">
          <h3 class="mt-0 mb40">Commentaire pour: {{ $e->titre }} - {{ $user->fullname() }} </h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content p-20 p-sm-0">
              @if($comment)
                <div class="box-body">
                  <div class="direct-chat-messages p-0" style="height: auto;">
                    <div class="col-md-6 pl-0">
                      <h4 class="alert alert-info" style="padding: 5px;margin-top: 0 !important;">Commentaire du collaborateur : {{ $user->name." ".$user->last_name }}</h4>
                      <div class="direct-chat-msg mb20">
                        <div class="direct-chat-info clearfix">
                          <span class="direct-chat-name pull-left">{{ $user->fullname() }}</span>
                          <span
                              class="direct-chat-timestamp pull-right">{{ $comment->userComment != '' ? date('d/m/Y à H:i', strtotime($comment->created_at)) : '---' }}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ App\User::avatar($user->id) }}" alt="message user image">

                        <div class="direct-chat-text">
                          {!! $comment->userComment != '' ? nl2br($comment->userComment) : '---' !!}
                          @if($user->id == Auth::user()->id && !App\Entretien::answered($e->id, $user->id))
                            <a
                                href="javascript:void(0)"
                                chm-modal="{{ route('comment.edit', ['eid' => $e->id, 'uid' => $user->id, 'id' => $comment->id]) }}"
                                chm-modal-options='{"form":{"attributes":{"id":"commentForm"}}}'
                                class="btn-warning icon-fill"
                                data-toggle="tooltip" title="Modifier votre commentaire"
                            ><i class="fa fa-pencil"></i></a>
                          @endif
                        </div>
                      </div>
                    </div>
                    @if(($user->id == Auth::user()->id && App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id)) or ($user->id != Auth::user()->id))
                      <div class="col-md-6 pr-0">
                        <h4 class="alert alert-info" style="padding: 5px;margin-top: 0 !important;">Commentaire du
                          manager : {{ $user->parent->fullname() }}</h4>

                        <div class="direct-chat-msg right">
                          <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right">{{ $user->parent->fullname() }}</span>
                            <span class="direct-chat-timestamp pull-left">{{ $comment->mentor_updated_at != null ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y à H:i') : '' }}</span>
                          </div>
                          <img class="direct-chat-img mb-20" src="{{ App\User::avatar($user->parent->id) }}" alt="message user image">
                          @if($user->id != Auth::user()->id && $comment->mentorComment == '' && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                            <form action="{{ url('entretiens/'.$e->id.'/u/'.$user->id.'/commentaires/'.$comment->id.'/mentorUpdate') }}" method="post">
                              {{ csrf_field() }}
                              {{ method_field('PUT') }}
                              <textarea name="comment" class="form-control" required style="min-height: 130px;" placeholder="Répondez sur le commentaire de votre collaborateur ...">{{ $comment->mentorComment }}</textarea>
                              <p></p>
                              <button type="submit" class="btn btn-success pull-right"><i class="fa fa-send"></i> Répondre</button>
                            </form>
                          @else
                            <div class="direct-chat-text">
                              {!! $comment->mentorComment != '' ? nl2br($comment->mentorComment) : '---' !!}
                              @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                                <a
                                    href="javascript:void(0)"
                                    chm-modal="{{ route('comment.edit', ['eid' => $e->id, 'uid' => $user->id, 'id' => $comment->id]) }}"
                                    chm-modal-options='{"form":{"attributes":{"id":"commentForm"}}}'
                                    class="btn-warning icon-fill icon-fill"
                                    data-toggle="tooltip" title="Modifier votre commentaire"
                                ><i class="fa fa-pencil"></i></a>
                              @endif
                            </div>
                          @endif
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              @endif
              @if(!App\Entretien::answered($e->id, $user->id) && !$comment)
                  <a
                      href="javascript:void(0)"
                      chm-modal="{{ route('comment.add', ['eid' => $e->id, 'uid' => $user->id]) }}"
                      chm-modal-options='{"form":{"attributes":{"id":"commentForm"}}}'
                      class="btn btn-success mb-20"
                  ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter un commentaire" }}</a>
              @endif

                <div class="row">
                  <div class="col-md-12">
                    <div class="save-action bg-gray p-20">
                      <a href="{{ route('anglets.primes', ['eid' => $e->id, 'uid' => $user->id]) }}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> {{ __("Précédent") }}</a>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>

            </div>
          </div>

          @include('partials.submit-eval')

          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche le commentaires de la part du collaborateur: <b>{{ $user->fullname() }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
