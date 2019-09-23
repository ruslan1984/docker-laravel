(function(){
    $('.form-control').on('click',function () {
        $( this ).removeClass('is-invalid');
        $( this ).next('.errorText').text('');

    });
    $('#example-show-hide-callbacks').datepicker({});
    $("#phone1").mask("+7(999) 999-9999");

})();