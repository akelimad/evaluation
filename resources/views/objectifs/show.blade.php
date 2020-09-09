@if($objectif)
  <div class="box-body table-responsive no-padding mb40">
    <table class="table table-hover table-bordered table-inversed-blue">
      <tr>
        <td>Type</td><td>{{ $objectif->getType() }}</td>
      </tr>
      <tr>
        <td>Equipe</td><td>{{ \App\Team::find($objectif->team) ? \App\Team::find($objectif->team)->name : '---' }}</td>
      </tr>
      <tr>
        <td>Titre</td><td>{{ $objectif->title }}</td>
      </tr>
      <tr>
        <td>Description</td><td>{{ $objectif->description }}</td>
      </tr>
      <tr>
        <td>Echéance</td><td>{{ $objectif->deadline ? date('d/m/Y', strtotime($objectif->deadline)) : '---' }}</td>
      </tr>
    </table>
    <h3>Indicateurs</h3>
    <table class="table table-hover table-bordered">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Objectif</th>
          <th>Pendération (%)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($objectif->getIndicators() as $indicator)
          <tr>
            <td>{{ $indicator['title'] or '---' }}</td>
            <td>{{ $indicator['fixed'] or '---' }}</td>
            <td>{{ $indicator['ponderation'] or '---' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  @include('partials.alerts.warning', ['messages' => "Aucun résultat trouvé" ])
@endif