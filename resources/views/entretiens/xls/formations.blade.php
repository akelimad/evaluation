<table>
  <thead>
  <tr>
    <th>Date</th>
    <th>Exircice</th>
    <th>Titre</th>
    <th>Statut</th>
  </tr>
  </thead>
  <tbody>
    @foreach($formations as $formation)
      <tr>
        <td>{{ date('d/m/Y', strtotime($formation->date)) }}</td>
        <td>{{ $formation->exercice }}</td>
        <td>{{ $formation->title }}</td>
        <td>{{ $formation->getStatus() }}</td>
      </tr>
    @endforeach
  </tbody>
</table>