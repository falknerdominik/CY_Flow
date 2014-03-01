$(window).load(function() {
  // Chosen - Select besser gemacht
    $('.chosen-multiple').chosen({
        no_results_text: "Leider nichts gefunden",
        placeholder_text_multiple: "Bitte wähle jemand"

    });

    $('.chosen-single').chosen({
        placeholder_text_single: "Wähle jemanden aus.",
        no_results_text: "Leider nichts gefunden"
    });

    // Archivierte Projekte einblenden
    $('#more').click(function(){
        var count = 0;
        $('.col-md-2').each(function(){
            if($(this).css('display') == 'none' && count <= 5){
                $(this).css('display', 'block');
                count++;
            }
        });
    });
});

$(document).ready(function() {
    $('table').filterTable();
});
