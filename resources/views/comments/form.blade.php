
<div class="content">
    <input type="hidden" name="id" value="{{ isset($c->id) ? $c->id : null }}">
    <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
    <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
    {{ csrf_field() }}
    <div id="addLine-wrap">
        <div class="form-group" >
            <div class="col-md-11">
                <label class="control-label">Commentaire</label>
                <textarea class="form-control" name="comments[0]" required="required" style="height: 36px;min-height: 0">{{ $c->userComment or '' }}</textarea>
            </div>
            @if(!isset($c->id))
            <div class="col-md-1">
                <label class="control-label"> &nbsp; </label>
                <button type="button" class="btn btn-info addLine pull-right"><i class="fa fa-plus"></i></button>
            </div>
            @endif
        </div>
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
            copy.find('textarea').val('')
            copy.find('button').toggleClass('addLine deleteLine')
            copy.find('button>i').toggleClass('fa-plus fa-minus')
            var uid = uuidv4()
            $.each(copy.find('textarea'), function(){
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