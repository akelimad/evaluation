<style>
  .indexContainer label, .valueContainer label {
    font-size: 13;
  }
  .section-title {
    padding: 5px;
    background: #bbc0c1;
    color: white;
  }
</style>
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
        <option value="select" {{ isset($q) && $q->type == "select" ? 'selected':''  }}>Select</option>
        <option value="array" {{ isset($q) && $q->type == "array" ? 'selected':''  }}>Table</option>
      </select>
    </div>
  </div>

  <div id="addLine-wrap">
    <div class="section-title">Les sous questions</div>
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
            <div class="row">
              <div class="col-md-6 indexContainer">
                <label for="">Index</label>
                <input type="text" class="form-control" name="subQuestions[{{ isset($choice->id) ? $choice->id : 1  }}][titre]" id="subQuestions_{{isset($choice->id) ? $choice->id : 1}}_titre" value="{{isset($choice->titre) ? $choice->titre : ''}}"/>
              </div>
              <div class="col-md-6 valueContainer">
                <label for="">Valeur</label>
                <input type="text" class="form-control" name="subQuestions[{{ isset($choice->id) ? $choice->id : 1  }}][label]" id="subQuestions_{{isset($choice->id) ? $choice->id : 1}}_label" value="{{ isset($choice->options) ? json_decode($choice->options)->label : '' }}"/>
              </div>
            </div>
          </td>
          <td width="3%">
            <div class="addButton">
              <button type="button" class="{{ $class }} pull-right qchoice-duplicate-btn" chm-duplicate><i class="{{$icon}}"></i></button>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <div id="array-type-wrap">
    <div class="section-title">Les réponses</div>
    @if (isset($q) && !is_null(json_decode($q->options)))
      @php($answersColumns = json_decode($q->options, true))
      @php($answersColumns = $answersColumns['answers'])
    @else
      @php($answersColumns = ['' => ''])
    @endif
    <table class="table mb-10" id="questionAnwersTable" data-count="{{ count($answersColumns) }}">
      <tbody>
      @php($i = 0)
      @foreach($answersColumns as $key => $a)
        @php($i ++)
        @php($islast = count($answersColumns) == $i)
        @php ($class = $islast ? 'btn btn-success addLine' : 'btn btn-danger deleteLine')
        @php ($icon = $islast ? 'fa fa-plus' : 'fa fa-minus')
        <tr>
          <td width="17%">
            <label class="control-label">Choix <span class="badge"> </span></label>
          </td>
          <td width="80%">
            <div class="row">
              <div class="col-md-2" title="index">
                <input type="text" class="form-control" name="options[answers][{{ isset($a['id']) ? $a['id'] : 1 }}][id]" id="options_answers_{{ isset($a['id']) ? $a['id'] : 1 }}_id" value="{{ isset($a['id']) ? $a['id'] : 1 }}">
              </div>
              <div class="col-md-10 pl-0">
                <input type="text" class="form-control" name="options[answers][{{ isset($a['id']) ? $a['id'] : 1 }}][value]" id="options_answers_{{ isset($a['id']) ? $a['id'] : 1 }}_value" value="{{ isset($a['value']) ? $a['value'] : '' }}">
              </div>
            </div>
          </td>
          <td width="3%">
            <div class="addButton">
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

    $('body').on('click', '.qchoice-duplicate-btn',function (event) {
      var $row = $('#questionAnwersTable tr:last').find('[chm-duplicate]').closest('tr')
      var count = $('#questionAnwersTable').data('count')
      $($row).find('input, select').each(function(key, value) {
        var id = $(this).attr('id')
        var name = $(this).attr('name')
        var index = name.split('options[answers][').pop().split(']').shift()
        if (key == 0) {
          count += 1
          $('#questionAnwersTable').data('count', count)
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
      if ($.inArray(value, ['radio', 'checkbox', 'select', 'rate', 'array']) !== -1) {
        $('#addLine-wrap #choiceField').prop('required', true).attr('name', 'subQuestions[0]')
        if (value == 'rate') {
          $('#addLine-wrap').show()
          $('.valueContainer').show()
          $('.indexContainer label').show()
          $('.addButton label').show()
        } else if (value == 'array') {
          $('#addLine-wrap').show()
          $('.valueContainer').hide()
          $('.indexContainer').removeClass('col-md-6').addClass('col-md-12')
          $('#array-type-wrap').show()
        } else {
          $('.valueContainer').hide()
          $('.indexContainer label').hide()
          $('.addButton label').hide()
        }
      } else {
        $('#array-type-wrap').hide()
        $('#addLine-wrap').hide()
        $('#array-type-wrap').hide()
        $('#addLine-wrap #choiceField').prop('required', false).attr('name', '')
      }
    }

  })
</script>