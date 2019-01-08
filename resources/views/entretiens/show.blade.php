<div class="row">
	<div class="col-md-12">
		<h3>{{$e->titre}} : {{Carbon\Carbon::parse($e->date)->format('d/m/Y')}} <i class="fa fa-long-arrow-right"></i> {{Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}}</h3>
		<p>Collaborateurs : </p>
		@foreach($e->users as $user)
			<span class="badge">{{$user->name. " ".$user->last_name}}</span> 
		@endforeach
	</div>
</div>