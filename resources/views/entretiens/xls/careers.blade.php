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
      @php($collAnswer = App\Answer::getCollAnswers($q->id, $uid, $eid))
      @php($evaluatorAnswer = App\Answer::getMentorAnswers($q->id, $uid, $evaluator_id, $eid))
      <tr>
        <td>{{ $keyQst == 0 ? $groupe->name : '' }}</td>
        <td>{{ $q->titre }}</td>
        <td>
          @if ($collAnswer && !empty($collAnswer->answer))
            @if (in_array($q->type, ['text', 'textarea']))
              {{ $collAnswer ? $collAnswer->answer : '' }}
            @elseif (in_array($q->type, ['select', 'radio']))
              @foreach($q->children as $child)
                {{ $collAnswer && $collAnswer->answer == $child->id ? $child->titre : '' }}
              @endforeach
            @elseif (in_array($q->type, ['checkbox']))
              @foreach($q->children as $child)
                {{ in_array($child->id, json_decode($collAnswer->answer, true)) ? $child->titre . ' & ' : '' }}
              @endforeach
            @endif
          @endif
        </td>

        <td>
          @if ($evaluatorAnswer && !empty($evaluatorAnswer->mentor_answer))
            @if (in_array($q->type, ['text', 'textarea']))
              {{ $evaluatorAnswer ? $evaluatorAnswer->mentor_answer : ''}}
            @elseif (in_array($q->type, ['select', 'radio']))
              @foreach($q->children as $child)
                {{ $evaluatorAnswer && $evaluatorAnswer->mentor_answer == $child->id ? $child->titre : ''}}
              @endforeach
            @elseif (in_array($q->type, ['checkbox']))
              @foreach($q->children as $child)
                {{ in_array($child->id, json_decode($evaluatorAnswer->mentor_answer, true)) ? $child->titre . ' & ' : '' }}
              @endforeach
            @endif
          @endif
        </td>
      </tr>
      @if ($q->type == 'array')
        @php($collQstAnswer = $collAnswer ? json_decode($collAnswer->answer, true) : [])
        @php($evaluatorQstAnswer = $evaluatorAnswer ? json_decode($evaluatorAnswer->mentor_answer, true) : [])
        @foreach($q->getOptions('subquestions') as $subKey => $subquestion)
          <tr>
            <td></td>
            <td>{{ $subquestion['title'] }}</td>
            <td>
              @foreach($q->getOptions('answers') as $key => $answer)
                {{ isset($collQstAnswer[$subquestion['id']]) && $collQstAnswer[$subquestion['id']] == $answer['id'] ? $answer['title'] :'' }}
              @endforeach
            </td>
            <td>
              @foreach($q->getOptions('answers') as $key => $answer)
                {{ isset($evaluatorQstAnswer[$subquestion['id']]) && $evaluatorQstAnswer[$subquestion['id']] == $answer['id'] ? $answer['title'] :'' }}
              @endforeach
            </td>
          </tr>
        @endforeach
      @endif
    @endforeach
  @endforeach
  </tbody>
</table>