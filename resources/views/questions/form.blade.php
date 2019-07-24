<div class="content subQuestions">
  <input type="hidden" name="id" value="{{ isset($q) ? $q->id : null }}">
  <input type="hidden" name="survey_id" value="{{ $sid }}">
  <input type="hidden" name="groupe_id" value="{{ $gid }}">
  <input type="hidden" name="parent_id" value="{{ isset($parent_id) ? $parent_id : '' }}">
  {{ csrf_field() }}
  <div class="form-group">
    <label for="titre" class="col-md-2 control-label">Titre</label>

    <div class="col-md-10">
      <textarea name="titre" id="titre" class="form-control">{{isset($q) ? $q->titre :''}}</textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="type" class="col-md-2 control-label">Type</label>

    <div class="col-md-10">
      <select name="type" id="questionType" class="form-control">
        <option value="text" {{ isset($q) && $q->type == "text" ? 'selected':''  }} >Text</option>
        <option value="textarea" {{ isset($q) && $q->type == "textarea" ? 'selected':''  }}>Textarea</option>
        <option value="slider" {{ isset($q) && $q->type == "slider" ? 'selected':''  }}>Slider note</option>
        <option value="checkbox" {{ isset($q) && $q->type == "checkbox" ? 'selected':''  }}>Case à cocher</option>
        <option value="radio" {{ isset($q) && $q->type == "radio" ? 'selected':''  }}>Radio button</option>
        <option value="rate" {{ isset($q) && $q->type == "rate" ? 'selected':''  }}>Rating</option>
      </select>
    </div>
  </div>
  <div id="addLine-wrap">
    <table class="table mb-10" id="questionChoicesTable" data-count="{{ count($qChoices) }}">
      <tbody>
      @php($i = 0)
      @foreach($qChoices as $key => $choice)
        @php($i ++)
        @php($islast = count($qChoices) == $i)
        @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
        @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
        <tr>
          <td width="17%">
            <label class="control-label">Choix <span class="badge"> </span></label>
          </td>
          <td width="80%">
            <div class="indexContainer">
              <label for="">Index</label>
              <input type="text" class="form-control mb20" name="subQuestions[{{ isset($choice->id) ? $choice->id : 1  }}][titre]" id="subQuestions_{{isset($choice->id) ? $choice->id : 1}}_titre" required="required" value="{{isset($choice->titre) ? $choice->titre : ''}}"/>
            </div>
            <div class="valueContainer">
              <label for="">Valeur</label>
              <input type="text" class="form-control" name="subQuestions[{{ isset($choice->id) ? $choice->id : 1  }}][label]" id="subQuestions_{{isset($choice->id) ? $choice->id : 1}}_label" required="required" value="{{ isset($choice->options) ? json_decode($choice->options)->label : '' }}"/>
            </div>
          </td>
          <td width="3%">
            <div class="addButton">
              <label class="control-label">&nbsp;</label>
              <button type="button" class="{{ $class }} pull-right qchoice-duplicate-btn" chm-duplicate><i class="{{$icon}}"></i></button>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

</div>

<script>
  jQuery(document).ready(function () {
    $('body').on('change', '#questionType', function () {
      showHideQuestionChoices()
    })
    $('#questionType').trigger('change')

    $('body').on('click', '.qchoice-duplicate-btn',function (event) {
      var $row = $('#questionChoicesTable tr:last').find('[chm-duplicate]').closest('tr')
      var count = $('#questionChoicesTable').data('count')
      $($row).find('input, select').each(function(key, value) {
        var id = $(this).attr('id')
        var name = $(this).attr('name')
        var index = name.split('subQuestions[').pop().split(']').shift()
        if (key == 0) {
          count += 1
          $('#questionChoicesTable').data('count', count)
        }
        name = name.replace('['+ index +']', '['+ count +']')
        $(this).attr('name', name)
        id = id.replace('_'+ index + '_', '_'+ count + '_')
        $(this).attr('id', id)
      })
      $row.find('input').removeClass('chm-has-error')
      $row.find('.chm-error-block').remove()
    })

    function showHideQuestionChoices() {
      var value = $('#questionType').val()
      if ($.inArray(value, ['radio', 'checkbox', 'select', 'rate']) !== -1) {
        $('#addLine-wrap').show()
        $('#addLine-wrap #choiceField').prop('required', true).attr('name', 'subQuestions[0]')
        if (value == 'rate') {
          $('.valueContainer').show()
          $('.indexContainer label').show()
          $('.indexContainer input').addClass('mb20')
          $('.addButton label').show()
        } else {
          $('.valueContainer').hide()
          $('.indexContainer label').hide()
          $('.indexContainer input').removeClass('mb20')
          $('.addButton label').hide()
        }
      } else {
        $('#addLine-wrap').hide()
        $('#addLine-wrap #choiceField').prop('required', false).attr('name', '')
      }
    }

  })
</script>