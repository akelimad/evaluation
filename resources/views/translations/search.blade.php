@extends('layouts.search')

@php($tableId = 'TranslationsTableContainer')
@php($resetUrl = route('interface.translations'))

@section('fields')
  <div class="col-md-3 mb-15">
    <div class="form-group">
      <label for="name">Mot clé</label>
      <input type="text" name="q" id="q" class="form-control" value="{{ Request::get('q', '') }}">
    </div>
  </div>
@endsection