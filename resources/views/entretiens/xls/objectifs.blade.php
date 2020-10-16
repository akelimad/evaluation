<table>
  <thead>
    <tr>
      <th>Objectifs personnels</th>
      <th>Titre</th>
      <th>Note de l'évalué</th>
      <th>Note de l'évaluateur</th>
    </tr>
  </thead>
  <tbody>
    @foreach($objectifsPersonnal as $objectif)
      @php($collValues = isset(\App\Objectif_user::getValues($eid, $uid, $objectif->id)['collValues']) ? \App\Objectif_user::getValues($eid, $uid, $objectif->id)['collValues'] : [])
      @php($mentorValues = isset(\App\Objectif_user::getValues($eid, $uid, $objectif->id)['mentorValues']) ? \App\Objectif_user::getValues($eid, $uid, $objectif->id)['mentorValues'] : [])
      @foreach($objectif->getIndicators() as $keyInd => $indicator)
        <tr>
          <td>{{ $keyInd == 0 ? $objectif->title : '' }}</td>
          <td>{{ $indicator['title'] }}</td>
          <td>{{ isset($collValues[$keyInd]) ? $collValues[$keyInd] : 0 }}</td>
          <td>{{ isset($mentorValues[$keyInd]) ? $mentorValues[$keyInd] : 0 }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>

<table>
  <thead>
  <tr>
    <th>Objectifs collectifs</th>
    <th>Titre</th>
    <th>Note de l'évalué</th>
    <th>Note de l'évaluateur</th>
  </tr>
  </thead>
  <tbody>
  @foreach($objectifsTeam as $objectif)
    @foreach($objectif->getIndicators() as $keyInd => $indicator)
      @php($teamValues = isset(\App\Objectif_user::getValues($eid, $uid, $objectif->id)['teamValues']) ? \App\Objectif_user::getValues($eid, $uid, $objectif->id)['teamValues'] : [])
      <tr>
        <td>{{ $keyInd == 0 ? $objectif->title : '' }}</td>
        <td>{{ $indicator['title'] }}</td>
        <td>0</td>
        <td>{{ isset($teamValues[$keyInd]) ? $teamValues[$keyInd] : 0 }}</td>
      </tr>
    @endforeach
  @endforeach
  </tbody>
</table>