@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ajouter un collaborateur</h3>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" method="post" action="{{ url('user/store') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="name" class="control-label">Prénom</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Prénom">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="control-label">Nom</label>
                                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Nom">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="info@email.com">
                                </div>
                                <div class="col-md-6"> 
                                    <label for="password" class="control-label">Mot de passe</label>
                                    <input id="password" type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="adress" class="control-label">Adresse</label>
                                    <input type="text" name="address" class="form-control" id="adress" placeholder="Adresse">
                                </div>
                                <div class="col-md-6"> 
                                    <label for="society" class="control-label">Société</label>
                                    <input id="society" type="text" class="form-control" name="society" placeholder="Société">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="zip_code" class="control-label">Code postale</label>
                                    <input type="text" name="zip_code" class="form-control" id="zip_code" placeholder="Code postale">
                                </div>
                                <div class="col-md-3"> 
                                    <label for="ville" class="control-label">Ville</label>
                                    <input id="ville" type="text" class="form-control" name="city" placeholder="Ville">
                                </div>
                                <div class="col-md-3"> 
                                    <label for="pays" class="control-label">Pays</label>
                                    <input id="pays" type="text" class="form-control" name="country" placeholder="Pays">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="tel" class="control-label">Téléphone mobile</label>
                                    <input type="text" name="tel" class="form-control" id="tel" placeholder="0606060606">
                                </div>
                                <div class="col-md-6"> 
                                    <label for="fix" class="control-label">Téléphone fix</label>
                                    <input id="fix" type="text" class="form-control" name="fix" placeholder="0505050505">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="about" class="control-label">A propos de moi </label>
                                    <textarea name="about" id="about" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6"> 
                                    <label for="avatar" class="control-label">Photo</label>
                                    <input type="file" name="avatar" id="avatar">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="function" class="control-label">Fonction </label>
                                    <input id="function" type="text" class="form-control" name="function" placeholder="Fonction">
                                </div>
                                <div class="col-md-6"> 
                                    <label for="service" class="control-label">Service</label>
                                    <input id="service" type="text" class="form-control" name="service" placeholder="Service">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="qualification" class="control-label">Qualification </label>
                                    <textarea name="qualification" id="qualification" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="tel" class="control-label">Statut </label>
                                    <label>
                                        <input type="checkbox" class="minimal" name="status">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="role" class="control-label">Rôle </label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">=== Select ===</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}"> {{$role->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="user_id" class="control-label">Mentor </label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">=== Select ===</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}"> {{$user->email}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Sauvegarder</button>
                                <a href="" class="btn btn-default pull-right">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  