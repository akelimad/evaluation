<div class="content">
  <input type="hidden" name="oid" value="{{ isset($oid) ? $oid : null }}">
  <input type="hidden" name="gid" value="{{ isset($groupe) ? $groupe->id : null }}">
  {{ csrf_field() }}
  <div class="form-group">
    <label for="">Titre d'objectif</label>
    <input type="text" name="title" required="" class="form-control" placeholder="ex: Relation interne"
           value="{{ isset($groupe) ? $groupe->title : '' }}">
  </div>
  <div id="addLine-wrap">
    @foreach($objectif as $key => $o)
      <div class="form-group">
        <div class="row">
          <div class="col-md-9 col-sm-9">
            <label class="control-label">Titre du critère </label>
            <input type="text" class="form-control subTitle"
                   name="@if($key == 0) objectifs[0][subTitle] @else objectifs[{{$o->id}}][subTitle] @endif"
                   placeholder="" value="{{isset($o->title) ? $o->title :''}}" placeholder="ex: Travail en équipe"
                   required=""/>
          </div>
          <div class="col-md-2 col-sm-2">
            <label class="control-label">Ponderation(%)</label>
            <input type="number" class="form-control realise"
                   name="@if($key == 0) objectifs[0][ponderation] @else objectifs[{{$o->id}}][ponderation] @endif"
                   placeholder="ex: 10" min="0" max="100" required=""
                   value="{{isset($o->ponderation) ? $o->ponderation :''}}"/>
          </div>
          <div class="col-md-1 col-sm-1">
            <label class="control-label"> &nbsp; </label>
            <button type="button" class="btn btn-info {{ $key == 0 ? 'addLine':'deleteLine' }} pull-right"><i
                  class="fa {{ $key == 0 ? 'fa-plus':'fa-minus' }}"></i></button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="additionnalFields">

    <div class="form-group">
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
                <input type="text" name="objExtrFields[{{ isset($key) ? $key : 1 }}][label]" id="objExtrFields_{{ isset($key) ? $key : 1 }}_label" class="form-control" value="{{ isset($field['label']) ? $field['label'] : '' }}">
              </td>
              <td>
                <label for="">Type du champ</label>
                <select name="objExtrFields[{{ isset($key) ? $key : 1 }}][type]" id="objExtrFields_{{ isset($key) ? $key : 1 }}_type" class="form-control">
                  <option value=""></option>
                  <option value="text" {{isset($field['type']) && $field['type'] == 'text' ? 'selected':''}}>Court text</option>
                  <option value="textarea" {{isset($field['type']) && $field['type'] == 'textarea' ? 'selected':''}}>Long text</option>
                </select>
              </td>
              <td>
                <div><label class="control-label">&nbsp;</label></div>
                <button type="button" class="{{ $class }} pull-right" chm-duplicate><i class="{{$icon}}"></i></button>
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

    $('body').on('click', ['chm-duplicate'], function (event) {
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