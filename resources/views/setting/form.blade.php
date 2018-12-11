
<div class="content">
    <input type="hidden" name="id" value="{{ isset($setting->id) ? $setting->id : null }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <p>{{ $setting->description }}</p>
                <textarea name="value" id="value" class="form-control">{{ $setting->value }}</textarea>
            </div>
        </div>
    </div>
</div>
