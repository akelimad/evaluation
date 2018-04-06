
<div class="content subQuestions">
    <input type="hidden" name="id" value="{{ isset($q) ? $q->id : null }}">
    <input type="hidden" name="survey_id" value="{{ $sid }}">
    <input type="hidden" name="groupe_id" value="{{ $gid }}">
    <input type="hidden" name="parent_id" value="{{ isset($parent_id) ? $parent_id : '' }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="titre" id="titre" class="form-control" value="{{isset($q) ? $q->titre :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" id="questionType" class="form-control">
                <option value="text" {{ isset($q) && $q->type == "text" ? 'selected':''  }} > Text  </option>
                <option value="textarea" {{ isset($q) && $q->type == "textarea" ? 'selected':''  }}> Textarea   </option>
                <option value="checkbox" {{ isset($q) && $q->type == "checkbox" ? 'selected':''  }}> Case à cocher  </option>
                <option value="radio" {{ isset($q) && $q->type == "radio" ? 'selected':''  }}> Radio button   </option>
            </select>
        </div>
    </div>
    <div id="addLine-wrap">
        @if( isset($q) && count($q->children)>0 )
            @foreach($q->children as $key => $choice)
            <div class="form-group" >
                <label class="col-md-2 control-label">choix : <span class="badge"> </span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="subQuestions[{{$choice->id}}]" required="required" value="{{$choice->titre}}" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info {{ $key == 0 ? 'addLine':'deleteLine'}}"><i class="fa {{ $key == 0 ? 'fa-plus':'fa-minus' }}"></i></button>
                </div>
            </div>
            @endforeach
        @else
            <div class="form-group" >
                <label class="col-md-2 control-label">choix : <span class="badge"> </span></label>
                <div class="col-md-8">
                    <input type="text" id="choiceField" class="form-control" name="subQuestions[0]" required="required" value="" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        @endif
    </div>

</div>

<script>
    $(function(){
        function uuidv4() {
            return ([1e7]+-1e3).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            )
        }
        $(".addLine").click(function(event){
            event.preventDefault()
            var copy = $('#addLine-wrap').find(".form-group:first").clone()
            copy.find('input').val('')
            copy.find('button').toggleClass('addLine deleteLine')
            copy.find('button>i').toggleClass('fa-plus fa-minus')
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

        $("#questionType").change(function(){
            value = $(this).val()
            if(value == "text" || value == "textarea" ){
                $("#addLine-wrap").hide()
                $("#addLine-wrap #choiceField").prop('required', false).attr('name', '')
            }else{
                $("#addLine-wrap").show()
                $("#addLine-wrap #choiceField").prop('required', true).attr('name', 'subQuestions[0]')
            }
        })
        $("#questionType").change()

    })
</script>