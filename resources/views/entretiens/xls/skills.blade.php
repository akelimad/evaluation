<table>
  <thead>
  <tr>
    <th>Type des compétences</th>
    <th>Titre</th>
    <th>Note de l'évalué / 10</th>
    <th>Note de l'évaluateur / 10</th>
  </tr>
  </thead>
  <tbody>
    @foreach($skill->getSkillsTypes() as $keyType => $type)
      @php($field = 'skill_type_'.$type['id'])
      @php($collNotes = \App\Skill::getFieldNotes($eid, $uid, $parent_id, $field, 'user'))
      @php($mentorNotes = \App\Skill::getFieldNotes($eid, $uid, $parent_id, $field, 'mentor'))
      @php($typeNote = $skill->getSkillTypeNote($eid, $uid, $parent_id, "skill_type_".$type['id'], $type['id'], 'mentor'))
      @foreach($type['skills'] as $keyItem => $skillItem)
        <tr>
          <td>
            {{ isset($type['title']) && $keyItem == 0 ? $type['title'].' '.$typeNote.'/10' : '' }}
          </td>
          <td>{{ isset($skillItem['title']) ? $skillItem['title'] : '' }}</td>
          <td>{{ isset($collNotes[$keyItem]) ? $collNotes[$keyItem] : 0 }}</td>
          <td>{{ isset($mentorNotes[$keyItem]) ? $mentorNotes[$keyItem] : 0 }}</td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>