
<div class="content subQuestions">
    <input type="hidden" name="id" value="{{ isset($g->id) ? $g->id : null }}">
    <input type="hidden" name="survey_id" value="{{$sid}}">
    <input type="hidden" name="groupe_id" value="{{$gid}}">
    <input type="hidden" name="parent_id" value="{{$parent_id}}">
    {{ csrf_field() }}
    @if(empty($parent_id))
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <input type="text" name="titre" id="titre" class="form-control" value="{{isset($g->titre) ? $g->titre :''}}">
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" id="type" class="form-control">
                <option value="text" > Text  </option>
                <option value="textarea" > Textarea   </option>
                <option value="checkbox" > Case à cocher  </option>
                <option value="radio"> Radio button   </option>
            </select>
        </div>
    </div>
    @else
    <div id="addLine-wrap">
        <div class="form-group" >
            <label class="col-md-2 control-label">choix : <span class="badge"> </span></label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="subQuestions[0]" required="required" />
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
    @endif

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


    })
</script>