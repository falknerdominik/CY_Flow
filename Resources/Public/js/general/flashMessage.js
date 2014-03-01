$(document).ready(function () {

    setTimeout(function(){
       $('.flashmessages li').fadeOut('fast');
    }, 5000);

    $('.flashmessages li').click(function(){
       $(this).fadeOut('fast');
    });


});