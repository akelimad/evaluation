<table>
  <thead>
  <tr>
    <th>Brut</th>
    <th>Prime</th>
    <th>Commentaire</th>
  </tr>
  </thead>
  <tbody>
  @foreach($primes as $prime)
    <tr>
      <td>{{ $prime->brut }}</td>
      <td>{{ $prime->prime }}</td>
      <td>{{ $prime->commentaire }}</td>
    </tr>
  @endforeach
  </tbody>
</table>