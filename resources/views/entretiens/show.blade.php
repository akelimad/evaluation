@extends('layouts.app')

@section('title', 'Entretiens')
@section('breadcrumb')
	<li>Entretiens</li>
@endsection

@php($countInterviewUsers = count($e->users))
@php($countNotStart = \App\Entretien_user::countResponse($e->id, 'user', 0))
@php($countInprogress = \App\Entretien_user::countResponse($e->id, 'user', 1))
@php($countFinished = \App\Entretien_user::countResponse($e->id, 'user', 2))

@php($countMentorNotStart = \App\Entretien_user::countResponse($e->id, 'mentor', 0))
@php($countMentorInprogress = \App\Entretien_user::countResponse($e->id, 'mentor', 1))
@php($countMentorFinished = \App\Entretien_user::countResponse($e->id, 'mentor', 2))

@section('content')
	<section class="content">
		<div class="row mb-20">
			<div class="col-md-12">
				<h3><a href="{{ route('entretiens') }}"><i class="fa fa-angle-left"></i> Retour</a></h3>
			</div>
			<div class="col-md-12">
				<h3>Suivi de la campagne : {{ $e->titre }}

					<a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'entretien ?', 'Etes-vous sur de vouloir supprimer cet entretien ?','chmEntretien.delete', {eid: {{ $e->id }} }, {width: 450})" class="btn btn-danger pull-right"><i class="fa fa-trash"></i> Supprimer</a>

					<a href="javascript:void(0)" onclick="return chmEntretien.form({{{$e->id}}})" class="btn btn-success pull-right mr-10"><i class="fa fa-pencil"></i> Modifier</a>

				</h3>
			</div>
		</div>
		<div class="row mb-15">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-body">
						<div class="row">
							<div class="col-md-6 mb-20"><b>Campagne :</b> {{ $e->titre }}</div>
							<div class="col-md-6 mb-20"><b>Participants :</b> {{ $countInterviewUsers }}</div>
							<div class="col-md-6 mb-20"><b>Date de l'entretien :</b> {{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</div>
							<div class="col-md-6 mb-20"><b>Date de clôture :</b> {{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-20">
			<div class="col-md-6">
				<div class="card card-danger p-0">
					<div class="card-header text-center">
						<h3 class="card-title">Auto-évalutions</h3>
					</div>
					<div class="card-body">
						<canvas id="collChart" class="chartjs-render-monitor"></canvas>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card card-danger p-0">
					<div class="card-header text-center">
						<h3 class="card-title">Evaluations Manager</h3>
					</div>
					<div class="card-body">
						<canvas id="managerChart" style="height: 230px;"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-body">
						<div class="row">
							<div class="col-md-6 mb-xs-20">
								<h4>Evalué</h4>
								<ul class="list-group">
									@foreach($e->users as $user)
										<li class="list-group-item">
											<div class="col-sm-6">
												<span>{{$user->name. " ".$user->last_name}}</span>
											</div>
											<div class="col-sm-6">
												@php($statusInfo = \App\Entretien_user::getStatus($user->id, $user->parent->id, $e->id, 'user') )
												<span class="badge {{ $statusInfo['labelClass'] }} pull-right">{{ $statusInfo['name'] }}</span>
											</div>
											<div class="clearfix"></div>
										</li>
									@endforeach
								</ul>
							</div>
							<div class="col-md-6 mb-xs-20">
								<h4>Evaluateur</h4>
								<ul class="list-group">
									@foreach($e->users as $user)
										<li class="list-group-item">
											<div class="col-sm-6">
												<span>{{$user->parent->name. " ".$user->parent->last_name}}</span>
											</div>
											<div class="col-sm-6">
												@php($statusInfo = \App\Entretien_user::getStatus($user->id, $user->parent->id, $e->id, 'mentor'))
												<span class="badge {{ $statusInfo['labelClass'] }} pull-right">{{ $statusInfo['name'] }}</span>
											</div>
											<div class="clearfix"></div>
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('javascript')
	<script src="{{asset('js/chart.min.js')}}"></script>
	<script>
		$(document).ready(function () {
			var chartOptions = {
				responsive: true,
				legend: {
					position: 'top',
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				cutoutPercentage: 70
			}
			var collChart = function () {
				if (!document.getElementById('collChart')) return
				let myChart = new Chart(document.getElementById('collChart'), {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [
								{{ $countNotStart }},
								{{ $countInprogress }},
								{{ $countFinished }}
							],
							backgroundColor: [
								"gray",
								"orange",
								"green"
							],
						}],
						labels: [
							"Non commencé {{$countNotStart .'/'. $countInterviewUsers}}",
							"En cours {{$countInprogress.'/'.$countInterviewUsers}}",
							"Fini {{$countFinished.'/'.$countInterviewUsers}}",
						]
					},
					options: chartOptions,
				});
			}
			var managerChart = function () {
				if (!document.getElementById('managerChart')) return
				let myChart = new Chart(document.getElementById('managerChart'), {
					type: 'doughnut',
					data: {
						datasets: [{
							data: [
								{{ $countMentorNotStart }},
								{{ $countMentorInprogress }},
								{{ $countMentorFinished }}
							],
							backgroundColor: [
								"gray",
								"orange",
								"green"
							],
						}],
						labels: [
							"Non commencé {{$countMentorNotStart .'/'. $countInterviewUsers}}",
							"En cours {{$countMentorInprogress.'/'.$countInterviewUsers}}",
							"Fini {{$countMentorFinished.'/'.$countInterviewUsers}}",
						]
					},
					options: chartOptions,
				});
			}

			collChart()
			managerChart()
		})
	</script>
@endsection