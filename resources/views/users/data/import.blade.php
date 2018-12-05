@extends('layouts.app')
@section('content')
    <section class="content index">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> <i class="fa fa-upload"></i> Importer les utilisateurs</h3>
                        <div class="box-tools">
                            
                      </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="form-group">
                            <div class="col-md-4">
                                <p>Fichier modèle</p>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    <a href="{{ asset('data/user_modele.csv') }}" class="btn btn-warning"> Télécharger un modèle au format csv </a>
                                </p>
                                <p class="help-block">
                                    Voici les consignes à respecter pour importer ou mettre à jour la liste de compte :
                                </p>
                                <ul class="list-unstyled user-data">
                                    <li><i class="fa fa-hand-o-right"></i> Le nombre d'utilisateurs est limité à 500 comptes</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne prénom correspond au champ name</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne nom correspond au champ last_name</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne email correspond au champ email</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne rôle correspond au champ ser à associer les rôles, si l'utilisateur est un collaborateur veuille saisir COLLABORATEUR, s'il est mentor et même temps collaborateur veuillez saisir le 1er rôle suivi d'un (,) puis un espace( ) puis le 2em rôle. ex: COLLABORATEUR, MENTOR. <br> N.B: Voici les rôles disponible : ADMIN, RH, MENTOR, COLLABORATEUR. Il faut les saisir en majuscule.</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne mentor email au champ user_id, il est indesponsable pour récuperer ID du mentor, si l'utilisateur n'as pas un mentor il faut mettre 0 </li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne adresse au champ adress</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne code postale au champ zip_code</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne ville correspond au champ city</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne pays correspond au champ country</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne tel fix correspond au champ fix</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne tel mobile correspond au champ tel</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne fonction correspond au champ function</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne serivce correspond au champ service</li>
                                    <li><i class="fa fa-hand-o-right"></i> La colonne qualification correspond au champ qualification</li>
                                </ul>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <p>Vuillez choisir le fichier csv :</p>
                            </div>
                            <div class="col-md-8">
                                <form action="{{url('users/import_parse')}}" method="post" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                                    <div class="input-group form-group col-md-6">
                                        <label class="input-group-btn">
                                            <span class="btn btn-primary">
                                                Parcourir <input type="file" name="usersDataCsv" style="display: none;" required="" accept=".csv">
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" readonly="">
                                    </div>
                                    <div class="form-group">
                                        <label for="header">
                                            <input type="checkbox" id="header" name="header" checked=""> Le fichier contient les titres des champs
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"> Valider l'importation </button>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  