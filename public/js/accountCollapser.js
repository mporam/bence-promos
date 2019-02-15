$(function() {

    $('.row .title h4').append("<span class='glyphicon glyphicon-chevron-down'></span>");
    $('.row .title').closest('.row').children(':not(.title)').hide();

    $('.row .title h4').click(function() {
        $(this).closest('.row').children(':not(.title)').slideToggle();
        $('.glyphicon', this).toggleClass('glyphicon-chevron-down glyphicon-chevron-up')
    });


});