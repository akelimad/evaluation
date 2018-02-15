$(function(){
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
    });

    $('#datepicker, #datepicker1, #datepicker2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    })
})