
<div class="content">
    <input type="hidden" name="id" value="{{ isset($entretien) ? $entretien->id : null }}">
    {{ csrf_field() }}
    <div class="row form-group">
        <div class="col-md-12">
            <label for="entretien" class="control-label">Entretien <span class="asterisk">*</span></label>
            <select name="entretien_id" id="entretien" class="form-control">
                @if( isset($entretien) )
                    <option value="{{ $entretien->id }}"> {{ $entretien->titre }} </option>
                @else
                    @foreach( $entretiens as $e )
                    <option value="{{ $e->id }}"> {{ $e->titre }} </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div id="addLine-wrap">
        @foreach($skills as $key => $s)
        <div class="row form-group">
            <div class="col-md-2">
                <label for="axe" class="control-label">Axe <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="@if($key == 0) skills[0][axe] @else skills[{{$s->id}}][axe] @endif" id="axe" placeholder="" value="{{isset($s->axe) ? $s->axe :''}}" required="">
            </div>
            <div class="col-md-3">
                <label for="famille" class="control-label">Famille <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="@if($key == 0) skills[0][famille] @else skills[{{$s->id}}][famille] @endif" id="famille" placeholder="" value="{{isset($s->famille) ? $s->famille :''}}" required="">
            </div>
            <div class="col-md-3"> 
                <label for="categorie" class="control-label">Catégorie <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="@if($key == 0) skills[0][categorie] @else skills[{{$s->id}}][categorie] @endif" id="categorie" placeholder="" value="{{isset($s->categorie) ? $s->categorie :''}}" required="">
            </div>
            <div class="col-md-3">
                <label for="competence" class="control-label">Compétence <span class="asterisk">*</span></label>
                <input type="text" class="form-control" name="@if($key == 0) skills[0][competence] @else skills[{{$s->id}}][competence] @endif" id="competence" placeholder="" value="{{isset($s->competence) ? $s->competence :''}}" required=""> 
            </div>
            <div class="col-md-1">
                <label class="control-label"> &nbsp; </label>
                <button type="button" class="btn btn-info {{ $key == 0 ? 'addLine':'deleteLine' }} pull-right"><i class="fa {{ $key == 0 ? 'fa-plus':'fa-minus' }}"></i></button>
            </div>
            <div class="clearfix"></div>
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
            if(confirm('Voulez-vous supprimer cette ligne ?')){
                $(this).closest('.form-group').remove();   
            }
        });

    })
</script>