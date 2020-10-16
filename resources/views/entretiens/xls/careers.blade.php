<table>
  <thead>
  <tr>
    <th>Thèmes</th>
    <th>Questions</th>
    <th>L'évalué</th>
    <th>L'évaluateur</th>
  </tr>
  </thead>
  <tbody>
  @foreach($survey->groupes as $keyGrp => $groupe)
    @foreach($groupe->questions as $keyQst => $q)
      <tr>
        <td>{{ $keyQst == 0 ? $groupe->name : '' }}</td>

        <td>{{ $q->titre }}</td>

        <td>
          @if (in_array($q->type, ['text', 'textarea']))
            {{ App\Answer::getCollAnswers($q->id, $uid, $eid) ? App\Answer::getCollAnswers($q->id, $uid, $eid)->answer : '' }}
          @elseif ($q->type == 'select')
            @foreach($q->children as $child)
              {{ App\Answer::getCollAnswers($q->id, $uid, $eid) && App\Answer::getCollAnswers($q->id, $uid, $eid)->answer == $child->id ? $child->titre : '' }}
            @endforeach
          @endif
        </td>

        <td>
          {{ App\Answer::getMentorAnswers($q->id, $uid, $eid) ? App\Answer::getMentorAnswers($q->id, $uid, $eid)->mentor_answer : ''}}
        </td>
      </tr>
    @endforeach
  @endforeach
  </tbody>
</table>