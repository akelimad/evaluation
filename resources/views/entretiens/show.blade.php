@extends('layouts.app')

@section('title', 'Entretiens')
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
				<h2 class="pageName m-0">Suivi de la campagne : {{ $e->titre }}
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
							<div class="col-md-6 mb-20"><b>Campagne :</b> {{ $e->titre }}</div>
							<div class="col-md-6 mb-20"><b>Participants :</b> {{ $countInterviewUsers }}</div>
							<div class="col-md-6 mb-sm-20"><b>Date limite pour l'auto-évaluation :</b> {{Carbon\Carbon::parse($e->date)->format('d/m/Y')}}</div>
							<div class="col-md-6 "><b>Date limite pour l'évaluation manager :</b> {{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</div>
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
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="usersEntretiensTable">
								<thead>
								<tr>
									<th class="text-center" style="width: 10px;">
										<input type="checkbox" id="check-all" id="select-all">
									</th>
									<th>Evalué</th>
									<th></th>
									<th>{{ $e->isFeedback360() ? "Collègue" : "Evaluateur" }}</th>
									<th></th>
									<th class="text-center" style="width: 30px;">Actions</th>
								</tr>
								</thead>
								<tbody>
								@foreach($entrentiensList as $user)
									@if ($e->isFeedback360() && $e->users[0]->id == $user->id) @continue @endif
									<tr>
										<td class="text-center">
											<input type="checkbox" class="raw_cb" data-value="{{ $user->id }}" name="users_records[]" value="{{ $user->id }}">
										</td>
										<td>{{ $e->isFeedback360() ? $e->users[0]->fullname() : $user->fullname() }}</td>
										<td>
											@php($statusInfo = \App\Entretien_user::getStatus($user->id, $user->parent->id, $e->id, 'user') )
											<span class="badge {{ $statusInfo['labelClass'] }}">{{ $statusInfo['name'] }}</span>
										</td>
										<td>{{ $e->isFeedback360() ? $user->fullname() : $user->parent->fullname() }}</td>
										<td>
											@php($statusInfo = \App\Entretien_user::getStatus($user->id, $user->parent->id, $e->id, 'mentor'))
											<span class="badge {{ $statusInfo['labelClass'] }}">{{ $statusInfo['name'] }}</span>
										</td>
										<td class="text-center">
											<div class="btn-group dropdown">
												<button aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button"><i class="fa fa-ellipsis-v"></i></button>
												<ul class="dropdown-menu dropdown-menu-right">
													<li>
														<a href="javascript:void(0)" onclick="return chmEntretien.apercu({eid: {{$e->id}}, uid: {{$user->id}} })"><i class="fa fa-search"></i> Aperçu</a>
													</li>
													<li>
														<a href="javascript:void(0)" onclick="return chmEntretien.reminder({eid: {{$e->id}}, usersId: [{{$user->id}}], role: 'coll'})"><i class="fa fa-bell-o"></i> Rappeler à l'évalué de remplir son entretien</a>
													</li>
													<li>
														<a href="javascript:void(0)" onclick="return chmEntretien.reminder({eid: {{$e->id}}, usersId: [{{$user->id}}], role: 'mentor'})"><i class="fa fa-bell-o"></i> Rappeler à l'évaluateur de remplir son entretien</a>
													</li>
													<li>
														<a href="javascript:void(0)" onclick="return chmEntretien.reOpen({eid: {{$e->id}}, uid: {{$user->id}}, parent_id: {{$user->parent->id}}})"><i class="fa fa-refresh"></i> Réouvrir</a>
													</li>
													<li>
														<a href="javascript:void(0)" class="delete" onclick="chmModal.confirm(this, '', 'Etes-vous sûr de vouloir supprimer ?', 'chmEntretien.deleteUsers', {eid: {{$e->id}}, usersId: [{{$user->id}}]}, {width: 450}); return false;"><i class="fa fa-trash"></i> Supprimer</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							<div class="bulk-action-container">
								<form action="" method="post">
									<input type="hidden" name="entretien_id" id="entretien_id" value="{{ $e->id }}">
									<select name="" id="bulkActions" class="form-control" style="width: 150px; display: inline-block">
										<option value="">Actions groupées</option>
										<option value="reminder-coll">Rappeler à l'évalué de remplir son entretien</option>
										<option value="reminder-mentor">Rappeler à l'évaluateur de remplir son entretien</option>
										<option value="delete">Supprimer</option>
									</select>
									<button type="submit" class="btn btn-primary" id="butlkActionsSubmit" style="height: 34px;">Appliquer</button>
								</form>
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
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap.min.css">
	<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
	<script>
		$(document).ready(function () {
			var oTable = $('#usersEntretiensTable').DataTable({
				lengthMenu: [5, 10, 20, 50, 100],
				language: {
					url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/French.json",
					searchPlaceholder: "Rechercher dans tous les champs ..."
				},
				order: [], //Initial no order
				columnDefs: [
					{orderable: false, className: 'select-checkbox', targets: [0, 5]}
				],
				initComplete: function () {
					$('.dataTables_filter input[type="search"]').css({ 'width': '230px', 'display': 'inline-block' });
				},
			})

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

			$('#butlkActionsSubmit').on('click', function (e) {
				e.preventDefault()
				var eid = $('#entretien_id').val()
				var usersId = [];
				var method = $('#bulkActions option:selected').val()
				var selectedRows = oTable.$(".raw_cb:checked", { "page": "all" })
				$.each(selectedRows, function (index, row) {
					usersId.push($(row).data('value'))
				})
				if (usersId.length < 1) {
					window.chmAlert.error('Vous devez choisir au moins une ligne.')
					return
				}
				if (method == '') {
					window.chmAlert.error('Merci de choisir une action.')
					return
				}
				if (method == 'reminder-coll') {
					return chmEntretien.reminder({eid: eid, usersId: usersId, role: 'coll'})
				} else if (method == 'reminder-mentor') {
					return chmEntretien.reminder({eid: eid, usersId: usersId, role: 'mentor'})
				} else {
					return chmEntretien.deleteUsers({eid: eid, usersId: usersId})
				}
			})

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