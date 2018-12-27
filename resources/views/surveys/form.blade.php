
<div class="content">
    <input type="hidden" name="id" value="{{ $survey->id }}">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-12">
            <label for="title" class="control-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $survey->title }}" required="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="description" class="control-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $survey->description }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="evaluation_id" class="control-label">Pour quelle partie ?</label>
            <select name="evaluation_id" id="evaluation_id" class="form-control" required>
                <option value=""></option>
                @foreach($evaluations as $eval)
                    @if($eval->title =="Evaluations" || $eval->title =="Carrières")
                        <option value="{{$eval->id}}" {{ $eval->id == $survey->evaluation_id ? 'selected':''}}>{{$eval->title}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <label for="description" class="control-label">Type</label>
            <select name="type" class="form-control">
                <option value=""></option>
                <option value="0" {{ $survey->type == 0 ? 'selected':''}}>Standard</option>
                <option value="1" {{ $survey->type == 1 ? 'selected':''}}>Personnalisé</option>
            </select>
        </div>
    </div>
</div>