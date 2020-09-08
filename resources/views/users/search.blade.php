@extends('layouts.search')

@php($tableId = 'UsersTableContainer')
@php($resetUrl = route('users'))


@section('fields')
  <div class="col-md-3 mb-15">
    <div class="form-group">
      <label for="name">Mot clé</label>
      <input type="text" name="q" id="q" class="form-control" value="{{ Request::get('q', '') }}">
    </div>
  </div>
  <div class=" col-md-3 mb-15">
    <div class="form-group">
      <label for="department">Département</label>
      <select name="department" id="dep" class="form-control">
        <option value=""></option>
        @foreach($departments as $dep)
          <option value="{{ $dep->id }}" {{ Request::get('department', '') ? 'selected':'' }}>{{ $dep->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class=" col-md-3 mb-15">
    <div class="form-group">
      <label for="function">Fonction</label>
      <select name="function" id="function" class="form-control">
        <option value=""></option>
        @foreach($fonctions as $func)
          <option value="{{ $func->id }}" {{ (isset($function) && $function == $func->id) ? 'selected':'' }}>{{ $func->title }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class=" col-md-3 mb-15">
    <div class="form-group">
      <label for="role">Rôle</label>
      <select name="role" id="role" class="form-control">
        <option value=""></option>
        @foreach($roles as $r)
          <option value="{{$r->id}}" {{ isset($role) && $role == $r->id ? 'selected' :'' }} > {{$r->name}} </option>
        @endforeach
      </select>
    </div>
  </div>
  <div class=" col-md-3">
    <div class="form-group">
      <label for="role">Equipe</label>
      <select name="team" id="role" class="form-control">
        <option value=""></option>
        @foreach($teams as $t)
          <option value="{{$t->id}}" {{ app('request')->input('team') && app('request')->input('team') == $t->id ? 'selected':'' }}> {{$t->name}} </option>
        @endforeach
      </select>
    </div>
  </div>
@endsection