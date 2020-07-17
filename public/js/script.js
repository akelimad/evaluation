$(window).on('load', function() {
    $('.spinner-wp').fadeOut();
});

function tableResponsive () {
    $.fn.hasScrollBar = function (direction) {
        if (this.get(0) !== undefined) {
            if (direction === 'vertical') {
                return this.get(0).scrollHeight > this.innerHeight()
            } else if (direction === 'horizontal') {
                return this.get(0).scrollWidth > this.innerWidth()
            }
        }
        return false
    }
    var hasHorizScrollBar = $('.table-responsive').hasScrollBar('horizontal')
    if (hasHorizScrollBar) {
        $('.btn-group').on('hide.bs.dropdown', function () {
            console.log('on hide')
            $('.table-responsive').css('overflow', 'auto')
        })
    } else {
        $('.btn-group').on('show.bs.dropdown', function () {
            console.log('on show')
            $('.table-responsive').css('overflow', 'inherit')
        })
    }
}

$(function(){
    var baseUrl =  $("base").attr("href")

    var max_note = parseInt($('#max_note').text())

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
        $(".motif-form-"+ $(this).data('id')).fadeToggle();
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
        $('.form-group').find('input[type="checkbox"]')
        var check = true;
        $(".form-group").each(function(){
            checkboxLength = $(this).find('input[type="checkbox"]').length
            radiosLength = $(this).find('input[type="radio"]').length
            if(checkboxLength > 0) {
                var checkedCount = $(this).find('input[type="checkbox"]:checked').length
                if(checkedCount == 0){
                    check = false;
                }
            }
            if(radiosLength > 0) {
                var checkedRadioCount = $(this).find('input[type="radio"]:checked').length
                if(checkedRadioCount == 0){
                    check = false;
                }
            }
        });
        if(!check){
            alert('Veuillez selectionner au moins une option.');
            return false;
        }

        check = true
        $(".mentor-item .inputNote[required]").each(function() {
            var val = $(this).val()
            if(/^(\d+(?:[\.\,]\d{1})?)$/.test(val) == false || parseFloat(val) < 1 || parseFloat(val) > max_note) {
                check = false; 
            }
        })
        if(!check) {
           alert("Veuillez entrer une note valide entre 1 et " + max_note + " !")
            return false; 
        }
    });

    $(".realise").on('keyup click', function(){
        var id = $(this).data('id')
        var nMoins1 = $(".nMoins1-"+id).text()
        var realise = $(this).val()
        var ecart   = $(".ecart-"+id).val(parseInt(realise) - parseInt(nMoins1) );
        if(ecart.val() < 0){
            $(".ecart-"+id).css('color', 'red')
        }else{
            $(".ecart-"+id).css('color', 'green')
        }
    })

    $(".nplus1").on('keyup change', function(){
        var id = $(this).data('id')
        var objectif = $("#objectif-"+id).val()
        var ecart   = $(this).val() - objectif
        $("#ecart-"+id).val(ecart)
        if(ecart < 0){
            $("#ecart-"+id).css('color', 'red')
        }else{
            $("#ecart-"+id).css('color', 'green')
        }
    })

    //delete user
    $(".table").on('click', '.delete-user',function () {
        var id= $(this).data('id');
        var token = $('input[name="_token"]').val();
        var url = baseUrl+'/user/'+id+'/delete';
        var $tr = $(this).closest('tr');
        swal({
            title: 'Etes-vous sûr ?',
            text: "Vous ne serez pas en mesure de rétablir ceci! En supprimant un utilisateur, ses collaborateurs seront aussi supprimés",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler',
            showLoaderOnConfirm: true,
            preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    type: 'POST',
                    url:  url,
                    data: {
                        "id": id,
                        "_method": 'DELETE',
                        "_token": token,
                    },
                }).done(function(response){
                    swal({ 
                        title: "Supprimé!", 
                        text: "L'utilisateur a été supprimé avec succès.", 
                        type: "success" 
                    }).then(function(){
                        location.reload(); 
                    });
                }).fail(function(){
                    swal('Oops...', "Il ya quelque chose qui ne va pas ! Il se peut que cet utilisateur fait la coordiantion des cours il faut supprimer tout d'abord ses cours!", 'error');
                });
            });
            },
            allowOutsideClick: false     
        }); 
    });

    //hide show criteria search users
    $(".showFormBtn").click(function(){
        $(".showFormBtn i").toggleClass("fa-chevron-down fa-chevron-up")
        $(".criteresForm").fadeToggle()
    })

    // fix table action overflow
    var actionsHeight = $('.dropdown-menu').innerHeight()
    $('.table-responsive').css('min-height', actionsHeight)
    tableResponsive()
    $(window).on('resize', function () {
        if ($('.table-responsive').length > 0) tableResponsive()
    })
})