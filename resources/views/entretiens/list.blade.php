
<div class="content p-xxs-10">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-12">
            <input type="hidden" name="ids" value="{{$ids}}">
            <label for="user_id" class="control-label">Choisissez un entretien</label>
            <select name="entretien_id" id="entretien_id" class="form-control select2" data-placeholder="select " style="width: 100%;" required>
                <option value=""></option>
                @foreach($entretiens as $e)
                    <option value="{{ $e->id }}" > {{ $e->titre }} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
