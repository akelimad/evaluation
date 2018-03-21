@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> Remplir votre évaluation pour: {{ $e->titre }} </h3>
                    <div class="nav-tabs-custom">
                       @include('partials.tabs')
                        <div class="tab-content">
                            <div class="box-body mb40">
                                @if(count(Auth::user()->children)>0 && $user->id != Auth::user()->id)
                                    @include('questions/survey2')
                                @endif
                                @if($user->id == Auth::user()->id)
                                    @include('questions/survey')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  