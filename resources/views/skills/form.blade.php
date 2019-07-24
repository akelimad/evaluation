
<div class="content">
    <input type="hidden" name="id" value="{{ isset($entretien) ? $entretien->id : null }}">
    {{ csrf_field() }}
    <div class="row form-group">
        <div class="col-md-12">
            <label for="entretien" class="control-label required required">Entretien</label>
            <select name="entretien_id" id="entretien" class="form-control">
                @if( isset($entretien) )
                    <option value="{{ $entretien->id }}"> {{ $entretien->titre }} </option>
                @else
                    @foreach( $entretiens as $e )
                    <option value="{{ $e->id }}"> {{ $e->titre }} </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div id="addLine-wrap">
        <table class="table mb-10" id="entretienSkillsTable" data-count="{{ count($skills) }}">
            <tbody>
            @php($i = 0)
            @foreach($skills as $key => $s)
                @php($i ++)
                @php($islast = count($skills) == $i)
                @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
                @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
                <tr>
                    <td>
                        <label for="axe" class="control-label required">Axe</label>
                        <input type="text" class="form-control" name="skills[{{ isset($s->id) ? $s->id : 1 }}][axe]" id="skills_{{ isset($s->id) ? $s->id : 1 }}_axe" placeholder="" value="{{isset($s->axe) ? $s->axe :''}}" required="">
                    </td>
                    <td>
                        <label for="famille" class="control-label required">Famille</label>
                        <input type="text" class="form-control" name="skills[{{ isset($s->id) ? $s->id : 1 }}][famille]" id="skills_{{ isset($s->id) ? $s->id : 1 }}_famille" placeholder="" value="{{isset($s->famille) ? $s->famille :''}}" required="">
                    </td>
                    <td>
                        <label for="categorie" class="control-label required">Catégorie</label>
                        <input type="text" class="form-control" name="skills[{{ isset($s->id) ? $s->id : 1 }}][categorie]" id="skills_{{ isset($s->id) ? $s->id : 1 }}_categorie" placeholder="" value="{{isset($s->categorie) ? $s->categorie :''}}" required="">
                    </td>
                    <td>
                        <label for="competence" class="control-label required">Compétence</label>
                        <input type="text" class="form-control" name="skills[{{ isset($s->id) ? $s->id : 1 }}][competence]" id="skills_{{ isset($s->id) ? $s->id : 1 }}_competence" placeholder="" value="{{isset($s->competence) ? $s->competence :''}}" required="">
                    </td>
                    <td>
                        <label class="control-label">&nbsp;</label>
                        <button type="button" class="{{ $class }} pull-right skills-duplicate-btn" chm-duplicate><i class="{{$icon}}"></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('body').on('click', '.skills-duplicate-btn',function (event) {
            var $row = $('#entretienSkillsTable tr:last').find('[chm-duplicate]').closest('tr')
            var count = $('#entretienSkillsTable').data('count')
            $($row).find('input, select').each(function(key, value) {
                var id = $(this).attr('id')
                var name = $(this).attr('name')
                var index = name.split('skills[').pop().split(']').shift()
                if (key == 0) {
                    count += 1
                    $('#entretienSkillsTable').data('count', count)
                }
                name = name.replace('['+ index +']', '['+ count +']')
                $(this).attr('name', name)
                id = id.replace('_'+ index + '_', '_'+ count + '_')
                $(this).attr('id', id)
            })
            $row.find('input').removeClass('chm-has-error')
            $row.find('.chm-error-block').remove()
        })

    })
</script>