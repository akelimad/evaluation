<div class="preview">
  <p>{{ $survey->description }}</p>
  @if(count($survey->groupes) > 0)
    <div class="panel-group">
      @foreach($survey->groupes as $g)
        @if(count($g->questions)>0)
          <div class="panel panel-info">
            <div class="panel-heading">{{ $g->name }}</div>
            <div class="panel-body">
              <div class="row">
                @forelse($g->questions as $q)
                  <div class="col-md-12 mb-20">
                    <div class="form-group">
                      @if($q->parent == null)
                        <label for="" class="questionTitle help-block"><i class="fa fa-caret-right"></i> {{$q->titre}}</label>
                      @endif
                      @if($q->type == 'text')
                        <input type="{{$q->type}}" class="form-control" readonly="">
                      @elseif($q->type == 'textarea')
                        <textarea class="form-control" readonly=""></textarea>
                      @elseif($q->type == "checkbox")
                        @foreach($q->children as $child)
                          <div class="survey-checkbox d-block w-100">
                            <label><input type="{{$q->type}}"> {{ $child->titre }}</label>
                          </div>
                        @endforeach
                        <div class="clearfix"></div>
                      @elseif($q->type == "radio")
                        @foreach($q->children as $child)
                          <div>
                            <label><input type="{{$q->type}}" name="question[{{ $q->id }}]"> {{ $child->titre }}</label>
                          </div>
                        @endforeach
                      @elseif ($q->type == "slider")
                        <div class="" style="margin-top: 30px;">
                          <input type="text" required="" name="" data-provide="slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="" data-slider-tooltip="always">
                        </div>
                      @elseif ($q->type == "rate")
                        <div class="row">
                          @foreach($q->children as $child)
                            <div class="col-md-2"><input type="radio" disabled> {{ $child->titre }}</div>
                            <div class="col-md-10">
                              @php($options = json_decode($child->options, true))
                              <label class="">{{ isset($options['label']) ? $options['label'] : 'vide' }}</label>
                            </div>
                          @endforeach
                        </div>
                      @elseif ($q->type == "select")
                        <select name="" id="" class="form-control">
                          <option value=""></option>
                          @foreach($q->children as $child)
                            <option value="{{ $child->titre }}">{{ $child->titre }}</option>
                          @endforeach
                        </select>
                      @elseif ($q->type == "array")
                        <div class="table-responsive">
                          <table class="table table-hover">
                            <thead>
                            <tr>
                              <th></th>
                              @foreach(json_decode($q->options)->answers as $key => $answer)
                                <th>{{ $answer->value }}</th>
                              @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($q->children as $child)
                              <tr>
                                <td>{{ $child->titre }}</td>
                                @foreach(json_decode($q->options)->answers as $key => $answer)
                                  <td><input type="radio" disabled></td>
                                @endforeach
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      @endif
                    </div>
                  </div>
                @empty
                  <div class="col-md-12">
                    <p class="help-block"> Aucune question </p>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  @else
    @include('partials.alerts.warning', ['messages' => "Aucun résultat trouvé" ])
  @endif
</div>

<script>
  $(document).ready(function () {
    var mySlider = $('[data-provide="slider"]').slider();
  })
</script>