
<div class="content commentsForm">
    <input type="hidden" name="id" value="{{ isset($c->id) ? $c->id : null }}">
    <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
    <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
    {{ csrf_field() }}
    <div id="addLine-wrap">
        <div class="form-group" >
            <div class="col-md-12">
                <label class="control-label">Commentaire : <!-- <span class="badge"> </span> --> </label>
                <textarea class="form-control" name="comment" required="required" style="height: 200px;min-height: 0">{{ $comment or '' }}</textarea>
            </div>
            @if(1 == 2)
            <div class="col-md-1">
                <label class="control-label"> &nbsp; </label>
                <button type="button" class="btn btn-info addLine pull-right"><i class="fa fa-plus"></i></button>
            </div>
            @endif
        </div>
    </div>
    @if(!isset($c->id))
    <div class="row footerAddLine">
        <div class="col-md-12">
            <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i> ajouter une carrière</button>
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
            var winHeight = $(".modal-dialog").height();
            if(winHeight >= 461 ){
                console.log(" window height "+$(window).height())
                console.log(" modal height "+ winHeight)
                $(".footerAddLine").fadeIn()
                $('.modal-body').animate({scrollTop: winHeight + winHeight}, 1000)
            }else{
                $(".footerAddLine").fadeOut()
            }
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
            var winHeight = $(".modal-dialog").height();
            if(winHeight >= 461 ){
                $(".footerAddLine").fadeIn()
            }else{
                $(".footerAddLine").fadeOut()
            }
        });

    })
</script>