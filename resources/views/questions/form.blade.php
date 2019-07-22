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
        @foreach($qChoices as $key => $choice)
          @php($isLastRow = $count == $key)
          @if ($isLastRow)
            @php ($class = 'btn btn-success addLine')
            @php ($icon = 'fa fa-plus')
          @else
            @php ($class = 'btn btn-danger deleteLine')
            @php ($icon = 'fa fa-minus')
          @endif
          <div class="form-group">
          <div class="col-md-2">
            <label class="control-label">Choix : <span class="badge"> </span></label>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" name="subQuestions[{{ isset($choice->id) ? $choice->id : 0  }}][titre]" required="required" value="{{isset($choice->titre) ? $choice->titre : ''}}"/>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" name="subQuestions[{{ isset($choice->id) ? $choice->id : 0  }}][label]" required="required" value="{{ isset($choice->options) ? json_decode($choice->options)->label : '' }}"/>
          </div>
          <div class="col-md-2">
            <button type="button" class="{{ $class }} btn-block btn-xs" style="padding: 4px;outline: 0;"><i class="fa {{ $icon }}"></i></button>
          </div>
          </div>
        @endforeach
  </div>

</div>

<script>
  jQuery(document).ready(function () {
    $('body').on('change', '#questionType', function () {
      showHideQuestionChoices()
    })
    $('#questionType').trigger('change')

    $(".addLine").click(function(event){
      event.preventDefault()
      var copy = $('#addLine-wrap').find(".form-group:first").clone()
      copy.find('input').val('')
      var uid = uuidv4()
      $.each(copy.find('input'), function(){
        var name = $(this).attr('name')
        $(this).attr('name', name.replace('[0]', '['+uid+']'))
      })
      $('#addLine-wrap').append(copy)
    })
    $('#addLine-wrap').on('click', '.deleteLine', function(){
      $(this).closest('.form-group').remove();
    });

    function showHideQuestionChoices() {
      var value = $('#questionType').val()
      if ($.inArray(value, ['radio', 'checkbox', 'select', 'rate']) !== -1) {
        $('#addLine-wrap').show()
        $('#addLine-wrap #choiceField').prop('required', true).attr('name', 'subQuestions[0]')
      } else {
        $('#addLine-wrap').hide()
        $('#addLine-wrap #choiceField').prop('required', false).attr('name', '')
      }
    }

    function uuidv4() {
      return ([1e7]+-1e3).replace(/[018]/g, c =>
          (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      )
    }

  })
</script>