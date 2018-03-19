$(function(){

    $('#datepicker, #datepicker1, #datepicker2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    })

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

    $(document).on('show','.accordion', function (e) {
         //$('.accordion-heading i').toggleClass(' ');
         $(e.target).prev('.accordion-heading').addClass('accordion-opened');
    });
    
    $(document).on('hide','.accordion', function (e) {
        $(this).find('.accordion-heading').not($(e.target)).removeClass('accordion-opened');
        //$('.accordion-heading i').toggleClass('fa-chevron-right fa-chevron-down');
    });

    $(".show-motif").click(function(){
        $(".motif-form-"+ $(this).data('id')).slideToggle();
    })

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
        $(".sendInvitationBtn").slideToggle();
    });

    $(".checkItem").click(function () {
        var check = $('.wrap-checkItem').find('input[type=checkbox]:checked').length;
        if(check >= 1){
            $(".sendInvitationBtn").fadeIn();
        }else if(check <= 1){
            $(".sendInvitationBtn").slideToggle();

        }
    });

    $('#submitAnswers').click(function() {
        checked = $("#surveyForm input[type=checkbox]:checked").length;
        if(checked<0) {
            $(".checkboxError").fadeIn();
            var errorDiv = $('.checkboxError:visible').first();
            var scrollPos = errorDiv.offset().top;
            $(window).scrollTop(scrollPos);
            return false;
        }
    });

    $(".realise").on('keyup change', function(){
        var id = $(this).data('id')
        var nMoins1 = $(".nMoins1-"+id).html()
        var realise = $(this).val()
        var ecart   = $(".ecart-"+id).html(realise - nMoins1);
        if(ecart.html() < 0){
            $(".ecart-"+id).css('color', 'red')
        }else{
            $(".ecart-"+id).css('color', 'green')
        }
    })
  
})