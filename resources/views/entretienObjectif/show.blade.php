@if($objectif)
  <div class="box-body table-responsive no-padding mb40">
    <table class="table table-hover table-bordered table-inversed-blue">
      <tr>
        <td>Type</td><td>{{ $objectif->type }}</td>
      </tr>
      <tr>
        <td>Equipe</td><td>{{ $objectif->team > 0 ? \App\Team::find($objectif->team)->name : '---' }}</td>
      </tr>
      <tr>
        <td>Titre</td><td>{{ $objectif->title }}</td>
      </tr>
      <tr>
        <td>Description</td><td>{{ $objectif->description }}</td>
      </tr>
      <tr>
        <td>Echéance</td><td>{{ date('d/m/Y', strtotime($objectif->deadline)) }}</td>
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
            <td>{{ $indicator['title'] }}</td>
            <td>{{ $indicator['fixed'] }}</td>
            <td>{{ $indicator['ponderation'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  @include('partials.alerts.warning', ['messages' => "Aucun résultat trouvé" ])
@endif