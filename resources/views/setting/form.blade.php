
<div class="content">
    <input type="hidden" name="id" value="{{ isset($setting->id) ? $setting->id : null }}">
    {{ csrf_field() }}
    <div class="row">
        @if($setting->field_type == "textarea")
        <div class="col-md-12">
            <div class="form-group">
                <p>{{ $setting->description }}</p>
                <textarea name="value" id="value" class="form-control">{{ $setting->value }}</textarea>
            </div>
        </div>
        @endif
        @if($setting->field_type == "file")
        <div class="col-md-6"> 
            <p class="">Logo</p>
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Parcourir <input type="file" name="logo" style="display: none;" accept=".png, .jpg, .jpeg">
                    </span>
                </label>
                <input type="text" id="logo" class="form-control" readonly="">
            </div>
            <p class="help-block">Les extentions acceptées : .png, .jpg, .jpeg</p>
            <p class="help-block">Veuillez respecter le format (253 x 69)</p>
        </div>
        <div class="col-md-6">
            @if(App\Setting::findOne($setting->name)->value != '')
                <img src="{{ asset('logos/'.App\Setting::findOne($setting->name)->value) }}" alt="" class="img-responsive">
            @else
                <img src="{{ asset('img/logo.png') }}" alt="" class="img-responsive">
            @endif
        </div>
        @endif
    </div>

</div>

<script>
    $(function(){
        // to show the choosen filename in input like: avatar.png
        $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });
        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {
                var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
                if( input.length ) {
                    input.val(log);
                }
            });
        });
    })
</script>