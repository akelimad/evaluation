@extends('layouts.app')
@section('content')
    <section class="content profile">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset('img/avatar.png') }}" alt="User profile picture">
                        <h3 class="profile-username text-center">Thomas Bonneville</h3>
                        <p class="text-muted text-center">Agent administratif</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item"><b>Service: </b> <a class="pull-right">Direction technique</a></li>
                            <li class="list-group-item"><b>Téléphone fixe: </b> <a class="pull-right">0606833078</a></li>
                            <li class="list-group-item"><b>Téléphone mobile: </b> <a class="pull-right">0606833078</a></li>
                            <li class="list-group-item"><b>Email: </b> <a class="pull-right">akel.deb@gmail.com</a></li>
                        </ul>
                        <p> <i>N'hésitez pas à solliciter votre Mentor si vous avez la moindre question concernant votre suivi RH.</i> </p>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> Mes actualités </h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled">
                            <li> <i class="fa fa-clock-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> </li>
                            <li> <i class="fa fa-clock-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> </li>
                            <li> <i class="fa fa-clock-o"></i> <a href="#"> Lorem ipsum dolor sit. </a> </li> 
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">MES ENTRETIENS</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#entretiens" data-toggle="tab"> Entretiens </a></li>
                            <li><a href="#objectifs" data-toggle="tab"> Objectifs  </a></li>
                            <li><a href="#formations" data-toggle="tab"> Formations </a></li>
                            <li><a href="#taches" data-toggle="tab"> Tâches à mener  </a></li>
                            
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="formations">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="objectifs">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="entretiens">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="taches">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card portlet box box-primary">
                    <div class="nav-tabs-custom portlet-title">
                        <div class="caption caption-red">Mes Collaborateurs</div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#entretiens_c" data-toggle="tab"> Entretiens </a></li>
                            <li><a href="#objectifs_c" data-toggle="tab"> Objectifs  </a></li>
                            <li><a href="#formations_c" data-toggle="tab"> Formations </a></li>
                            <li><a href="#taches_c" data-toggle="tab"> Tâches à mener  </a></li>
                            
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="entretiens_c">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="objectifs_c">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane active" id="formations_c">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="taches_c">
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>User</th>
                                              <th>Date</th>
                                              <th>Status</th>
                                              <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                            <tr>
                                                <td>183</td>
                                                <td>John Doe</td>
                                                <td>11-7-2014</td>
                                                <td><span class="label label-success">Approved</span></td>
                                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
@endsection
  