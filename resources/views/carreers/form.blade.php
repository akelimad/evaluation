
<div class="content carreers">
    <input type="hidden" name="id" value="{{ isset($c->id) ? $c->id : null }}">
    <input type="hidden" name="eid" value="{{ isset($e->id) ? $e->id : null }}">
    <input type="hidden" name="uid" value="{{ isset($user->id) ? $user->id : null }}">
    {{ csrf_field() }}
    <div id="addLine-wrap">
        <div class="form-group" >
            <div class="col-md-11">
                <label class="control-label">Carrière : <span class="badge"> </span> </label>
                <input type="text" class="form-control" name="carreers[0]" required="required" value="{{ $c->userCarreer or '' }}">
            </div>
            @if(!isset($c->id))
            <div class="col-md-1">
                <label class="control-label"> &nbsp; </label>
                <button type="button" class="btn btn-info addLine pull-right"><i class="fa fa-plus"></i></button>
            </div>
            @endif
        </div>
    </div>
    <div class="row footerAddLine">
        @if(!isset($c->id))
        <div class="col-md-12">
            <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i> ajouter une carrière</button>
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
        var counter = 1
        $(".addLine").click(function(event){
            var winHeight = $(".modal-dialog").height();
            if(winHeight >= 461 ){
                $(".footerAddLine").fadeIn()
            }else{
                $(".footerAddLine").fadeOut()
            }
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
            counter ++
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

        // var counter = 2;
        
        // $("#addButton").click(function () {
                      
        //     var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
                    
        //     newTextBoxDiv.after().html('<label>Textbox #'+ counter + ' : </label>' +
        //       '<input type="text" name="textbox' + counter + 
        //       '" id="textbox' + counter + '" value="" >');
                
        //     newTextBoxDiv.appendTo("#TextBoxesGroup");

                    
        //     counter++;
        // });


    })
</script>