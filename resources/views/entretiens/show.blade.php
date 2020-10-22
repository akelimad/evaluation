@extends('layouts.app')

@section('title', 'Entretiens')
@section('style')
	@parent
	<style>
		.table-inversed-blue th {
			background: none !important;
			color: black;
			font-weight: bold;
		}
		.table-striped>tbody>tr:nth-of-type(odd) {
			background: none !important;
		}
	</style>
@endsection
@section('breadcrumb')
	<li><a href="{{ route('entretiens') }}" class="text-blue">Campagnes</a></li>
	<li>{{ $e->titre }}</li>
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
				<h2 class="pageName m-0"><a href="{{ route('entretiens') }}"><i class="fa fa-chevron-left"></i></a> Suivi de la campagne : {{ $e->titre }}
					<a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer l\'entretien ?', 'Etes-vous sur de vouloir supprimer cet entretien ?','chmEntretien.delete', {eid: {{ $e->id }} }, {width: 450})" class="btn btn-danger pull-right"><i class="fa fa-trash"></i> Supprimer</a>

					<a href="javascript:void(0)" onclick="return chmEntretien.form({{{$e->id}}})" class="btn btn-success pull-right mr-10"><i class="fa fa-pencil"></i> Modifier</a>
				</h2>
			</div>
		</div>
		<div class="row mb-15">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-body">
						<div class="row mb-0">
							<div class="col-md-6 mb-sm-20">
								<div class="custom-switch-btn">
									<label class="switch" for="change_status" title="Activer / Désactiver la campagne" data-toggle="tooltip">
										<input type="checkbox" id="change_status" value="0" class="hidden" onchange="chmEntretien.changeStatus(event, [{{ $e->id }}])" {{ $e->isEnabled() ? 'checked':'' }}>
										<div class="slider-toggle round">
											<span class="on">ON</span><span class="off">OFF</span>
										</div>
									</label>
									<label class="mb-0 ml-5" style="display: inline-block; position: absolute; height: 27px; line-height: 27px;"><b>Activation</b></label>
								</div>
							</div>
							<div class="col-md-6 mb-30">
								<div class="inner-content">
									<b>Participants :</b> {{ $countInterviewUsers }}
									<span class="pull-right"><b>Créée le</b> {{ date('d/m/Y à H:i', strtotime($e->created_at)) }}</span>
								</div>
							</div>
							<div class="col-md-6 mb-sm-20">
								<b>Date limite pour l'auto-évaluation :</b> {{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}
							</div>
							<div class="col-md-6 ">
								<b>Date limite pour l'évaluation manager :</b> {{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-20">
			<div class="col-md-6">
				<div class="card card-danger p-0">
					<div class="card-header text-center">
						<h3 class="card-title text-muted font-22">Auto-évalutions</h3>
					</div>
					<div class="card-body">
						<canvas id="collChart" style="height: 230px;"></canvas>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card card-danger p-0">
					<div class="card-header text-center">
						<h3 class="card-title text-muted font-22">Evaluations {{ $e->isFeedback360() ? "des collègues" : "Manager" }} </h3>
					</div>
					<div class="card-body">
						<canvas id="managerChart" style="height: 230px;"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="row mb-20">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header text-center">
						<h3 class="card-title text-muted font-22">Les évalués et leurs évaluateurs</h3>
					</div>
					<div class="card-body pt-0">
						{{ request()->query->set('eid', $e->id) }}
						<div chm-table="{{ route('entretien_user.table') }}"
								 chm-table-options='{"with_ajax": true}'
								 chm-table-params='{{ json_encode(request()->query->all()) }}'
								 id="EntretienUserTableContainer"
						></div>
					</div>
				</div>
			</div>
		</div>
		@if(!$e->isFeedback360())
		<div class="row">
			<div class="col-md-12">
				<div class="card p-0">
					<div class="card-header text-center">
						<h3 class="card-title text-muted font-22">Notes obtenues par les collaborateurs</h3>
					</div>
					<div class="card-body pt-0">
						{{ request()->query->set('eid', $e->id) }}
						<div chm-table="{{ route('entretien_user.notes.table') }}"
								 chm-table-options='{"with_ajax": true}'
								 chm-table-params='{{ json_encode(request()->query->all()) }}'
								 id="EntretienUserNotesTableContainer"
						></div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</section>
@endsection

@section('javascript')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
	<script>
		$(document).ready(function () {

			var countCheckedInPage = 0

			$('#check-all').on("change", function() {
				var pageRows = oTable.rows({page: 'current'}).nodes()
				var checkAllChecked = $(this).is(':checked')
				$.each(pageRows, function (index, row) {
					$(row).find('.raw_cb').prop('checked', checkAllChecked);
				})
			})
			$('.raw_cb').on("change", function() {
				var pageRows = oTable.rows({page: 'current'}).nodes()
				var checkedRows = oTable.$(".raw_cb:checked", { "page": "current" })
				$('#check-all').prop('checked', pageRows.length == checkedRows.length)
			})

			$('#usersEntretiensTable').on('page.dt', function () {
				countCheckedInPage = 0
				$('#check-all').prop('checked', false)
			});

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