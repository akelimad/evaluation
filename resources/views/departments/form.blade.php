
<div class="content commentsForm">
    <input type="hidden" name="id" value="{{ $department->id }}">
    {{ csrf_field() }}
    <div id="addLine-wrap">
        <div class="form-group" >
            <label class="col-md-3 control-label">Département : <span class="badge"> </span></label>
            <div class="col-md-8">
                <input type="text" name="departments[]" id="title" class="form-control" value="{{ $department->title }}" required>
            </div>
            @if($department->id == "")
                <div class="col-md-1">
                    <button type="button" class="btn btn-info addLine pull-right"><i class="fa fa-plus"></i></button>
                </div>
            @endif
        </div>
    </div>
    @if($department->id == "")
        <div class="row footerAddLine">
            <div class="col-md-12">
                <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i> Ajouter</button>
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
      copy.find('input[type="text"]').val('')
      copy.find('button').toggleClass('addLine deleteLine')
      copy.find('button>i').toggleClass('fa-plus fa-minus')
      var uid = uuidv4()
      $.each(copy.find('input[type="text"]'), function(){
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