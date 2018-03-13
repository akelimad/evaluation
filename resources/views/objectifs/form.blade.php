
<div class="content">
    <input type="hidden" name="id" value="{{ isset($o->id) ? $o->id : null }}">
    <input type="hidden" name="e_id" value="{{ isset($e->e_id) ? $e->e_id : null }}">
    {{ csrf_field() }}
    <div id="addLine-wrap">
        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Titre </label>
                    <input type="text" class="form-control" name="objectifs[0][title]" placeholder="Titre" required="" />
                </div>
                <div class="col-md-6">
                    <label class="control-label">Description </label>
                    <input type="text" class="form-control prevu" name="objectifs[0][description]" placeholder="Description" min="0" required="" />
                </div>
                <div class="col-md-2">
                    <label class="control-label">Pourcentage </label>
                    <input type="number" class="form-control realise" name="objectifs[0][pourcentage]" placeholder="%" min="0" required="" />
                </div>
                <div class="col-md-1">
                    <label class="control-label"> &nbsp; </label>
                    <button type="button" class="btn btn-info addLine pull-right"><i class="fa fa-plus"></i></button>
                </div>
            </div>
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