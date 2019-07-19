
<div class="content">
    <input type="hidden" name="oid" value="{{ isset($oid) ? $oid : null }}">
    <input type="hidden" name="gid" value="{{ isset($groupe) ? $groupe->id : null }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="">Titre d'objectif</label>
        <input type="text" name="title" required="" class="form-control" placeholder="ex: Relation interne" value="{{ isset($groupe) ? $groupe->title : '' }}">
    </div>
    <div id="addLine-wrap">
        @foreach($objectif as $key => $o)
        <div class="form-group">
            <div class="row">
                <div class="col-md-9 col-sm-9">
                    <label class="control-label">Titre du critère </label>
                    <input type="text" class="form-control subTitle" name="@if($key == 0) objectifs[0][subTitle] @else objectifs[{{$o->id}}][subTitle] @endif" placeholder="" value="{{isset($o->title) ? $o->title :''}}" placeholder="ex: Travail en équipe" required="" />
                </div>
                <div class="col-md-2 col-sm-2">
                    <label class="control-label">Ponderation(%)</label>
                    <input type="number" class="form-control realise" name="@if($key == 0) objectifs[0][ponderation] @else objectifs[{{$o->id}}][ponderation] @endif" placeholder="ex: 10" min="0" max="100" required="" value="{{isset($o->ponderation) ? $o->ponderation :''}}" />
                </div>
                <div class="col-md-1 col-sm-1">
                    <label class="control-label"> &nbsp; </label>
                    <button type="button" class="btn btn-info {{ $key == 0 ? 'addLine':'deleteLine' }} pull-right"><i class="fa {{ $key == 0 ? 'fa-plus':'fa-minus' }}"></i></button>
                </div>
            </div>
        </div>
        @endforeach
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