<div class="content">
  <input type="hidden" name="oid" value="{{ isset($oid) ? $oid : null }}">
  <input type="hidden" name="gid" value="{{ isset($groupe) ? $groupe->id : null }}">
  <input type="hidden" name="form" value="storeSectionObj">
  {{ csrf_field() }}
  <div class="">
    <label for="">Titre d'objectif</label>
    <input type="text" name="title" required="" class="form-control" placeholder="ex: Relation interne"
           value="{{ isset($groupe) ? $groupe->title : '' }}">
  </div>
  <div id="addLine-wrap">
    <table class="table mb-10" id="objectifsTable" data-count="{{ count($objectif) }}">
        <tbody>
        @php($i = 0)
          @foreach($objectif as $key => $o)
            @php($i ++)
            @php($islast = count($objectif) == $i)
            @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
            @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
            <tr>
              <td>
                <label class="control-label">Titre du critère </label>
                <input type="text" class="form-control subTitle" id="objectifs_{{ isset($o->id) ? $o->id : 1 }}_subTitle"
                  name="objectifs[{{ isset($o->id) ? $o->id : 1 }}][subTitle]"
                  placeholder="" value="{{isset($o->title) ? $o->title :''}}" placeholder="ex: Travail en équipe"/>
              </td>
              <td>
                <label class="control-label">Ponderation(%)</label>
                <input type="number" class="form-control realise" id="objectifs_{{ isset($o->id) ? $o->id : 1 }}_ponderation"
                  name="objectifs[{{ isset($o->id) ? $o->id : 1 }}][ponderation]"
                  placeholder="ex: 10" min="0" max="100"
                  value="{{isset($o->ponderation) ? $o->ponderation :''}}"/>
              </td>
              <td>
                <div><label class="control-label">&nbsp;</label></div>
                <button type="button" class="{{ $class }} pull-right obj-duplicate-btn" chm-duplicate><i class="{{$icon}}"></i></button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
  <div class="additionnalFields">

    <div class="">
      <h3>Les champs additionnels</h3>
      <table class="table mb-10" id="extraFieldsTable" data-count="{{ count($objExtraFields) }}">
        <tbody>
        @php($i = 0)
          @foreach($objExtraFields as $key => $field)
            @php($i ++)
            @php($islast = count($objExtraFields) == $i)
            @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
            @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
            <tr>
              <td>
                <label for="">Libellé</label>
                <input type="text" name="objExtrFields[{{ isset($key) && !empty($key) ? $key : 1 }}][label]" id="objExtrFields_{{ isset($key) && !empty($key) ? $key : 1 }}_label" class="form-control" value="{{ isset($field['label']) ? $field['label'] : '' }}">
              </td>
              <td>
                <label for="">Type du champ</label>
                <select name="objExtrFields[{{ isset($key) && !empty($key) ? $key : 1 }}][type]" id="objExtrFields_{{ isset($key) && !empty($key) ? $key : 1 }}_type" class="form-control">
                  <option value=""></option>
                  <option value="text" {{isset($field['type']) && $field['type'] == 'text' ? 'selected':''}}>Court text</option>
                  <option value="textarea" {{isset($field['type']) && $field['type'] == 'textarea' ? 'selected':''}}>Long text</option>
                </select>
              </td>
              <td>
                <div><label class="control-label">&nbsp;</label></div>
                <button type="button" class="{{ $class }} pull-right extrafields-duplicate-btn" chm-duplicate><i class="{{$icon}}"></i></button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>

  $(document).ready(function () {

    $('body').on('click', '.obj-duplicate-btn',function (event) {
      var $row = $('#objectifsTable tr:last').find('[chm-duplicate]').closest('tr')
      var count = $('#objectifsTable').data('count')
      $($row).find('input, select').each(function(key, value) {
        var id = $(this).attr('id')
        var name = $(this).attr('name')
        var index = name.split('objectifs[').pop().split(']').shift()
        if (key == 0) {
          count += 1
          $('#objectifsTable').data('count', count)
        }
        name = name.replace('['+ index +']', '['+ count +']')
        $(this).attr('name', name)
        id = id.replace('_'+ index + '_', '_'+ count + '_')
        $(this).attr('id', id)
      })
      $row.find('input').removeClass('chm-has-error')
      $row.find('.chm-error-block').remove()
    })

    $('body').on('click', '.extrafields-duplicate-btn',function (event) {
      var $row = $('#extraFieldsTable tr:last').find('[chm-duplicate]').closest('tr')
      var count = $('#extraFieldsTable').data('count')
      $($row).find('input, select').each(function(key, value) {
        var id = $(this).attr('id')
        var name = $(this).attr('name')
        var index = name.split('objExtrFields[').pop().split(']').shift()
        if (key == 0) {
          count += 1
          $('#extraFieldsTable').data('count', count)
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