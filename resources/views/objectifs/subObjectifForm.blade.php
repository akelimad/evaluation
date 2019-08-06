<div class="content">
  <input type="hidden" name="oid" value="{{ isset($oid) ? $oid : null }}">
  <input type="hidden" name="objSectionId" value="{{ isset($gid) ? $gid : null }}">
  <input type="hidden" name="subObjId" value="{{ isset($subObj) ? $subObj->id : null }}">
  <input type="hidden" name="form" value="storeSubObj">
  {{ csrf_field() }}
  <div class="">
    <label for="">Titre de sous objectif</label>
    <input type="text" name="title" required="" class="form-control" placeholder="ex: Relation interne"
           value="{{ isset($subObj) ? $subObj->title : '' }}" readonly>
  </div>
  <div id="addLine-wrap">
    <table class="table mb-10" id="objectifsTable" data-count="{{ count($objectifs) }}">
      <tbody>
      @php($i = 0)
      @foreach($objectifs as $key => $o)
        @php($i ++)
        @php($islast = count($objectifs) == $i)
        @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
        @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
        <tr>
          <td>
            <label class="control-label">Titre du critère </label>
            <input type="text" class="form-control subTitle" id="objectifs_{{ isset($o['id']) ? $o['id'] : 1 }}_subTitle" name="objectifs[{{ isset($o['id']) ? $o['id'] : 1 }}][subTitle]" placeholder="" value="{{isset($o['title']) ? $o['title'] :''}}" placeholder="ex: Travail en équipe"/>
          </td>
          <td>
            <label class="control-label">Ponderation(%)</label>
            <input type="number" class="form-control realise" id="objectifs_{{ isset($o['id']) ? $o['id'] : 1 }}_ponderation"
                   name="objectifs[{{ isset($o['id']) ? $o['id'] : 1 }}][ponderation]"
                   placeholder="ex: 10" min="0" max="100"
                   value="{{ isset($o['ponderation']) ? $o['ponderation'] :'' }}"/>
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

  })


</script>