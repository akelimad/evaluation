@extends('layouts.search')

@php($tableId = 'EntretiensTableContainer')
@php($resetUrl = route('entretiens'))

@section('fields')
  <div class="col-md-3 mb-15">
    <div class="form-group">
      <label for="name">{{ __("Titre") }}</label>
      <input type="text" name="q" id="q" class="form-control" value="{{ Request::get('q', '') }}">
    </div>
  </div>
  <div class="col-md-3 mb-15">
    <div class="form-group">
      <label for="name">{{ __("Statut") }}</label>
      <select name="status" id="status" class="form-control">
        @php($current = \App\Entretien::CURRENT_STATUS)
        @php($expired = \App\Entretien::EXPIRED_STATUS)
        @php($finished = \App\Entretien::FINISHED_STATUS)
        <option value=""></option>
        <option value="{{ $current }}" {{ Request::get('status') == $current ? 'selected':'' }}>{{ $current }}</option>
        <option value="{{ $expired }}" {{ Request::get('status') == $expired ? 'selected':'' }}>{{ $expired }}</option>
        <option value="{{ $finished }}" {{ Request::get('status') == $finished ? 'selected':'' }}>{{ $finished }}</option>
      </select>
    </div>
  </div>
@endsection