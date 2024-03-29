$(document).ready(function() {

    $('a.panel').click(function () {

        $('a.panel').removeClass('selected');
        $(this).addClass('selected');

        current = $(this);

        $('#wrapper').scrollTo($(this).attr('href'), 500);

        return false;
    });

    $(window).resize(function () {
        resizePanel();
    });

});

$(document).ready(function() {
    $('html, body, *').mousewheel(function(e, delta) {
        this.scrollLeft -= (delta * 1920);
        e.preventDefault();
    });

    setCSS();
});
$(window).ready(function(){
    setCSS();
});
$(window).resize(function () {
    setCSS();
});


function resizePanel() {

    width = $(window).width();
    height = $(window).height();

    mask_width = width * $('.item').length;

    $('#debug').html(width  + ' ' + height + ' ' + mask_width);

    $('#wrapper .item').css({'width': width, 'height': height});
    $('#mask').css({width: mask_width, height: height});
    $('#wrapper').scrollTo($('a.selected').attr('href'), 0);

}

function setCSS(){
    // Auslensen der Werte
    var img = $('#login_bg');
    var wrapperWidth = $('.login_bg_wrapper');

    // Beim Auslesen wird mit der Einheit ausgelesen!!!!

    // Berechnen
    // .replace ist notwendig um die Einheit wegzugrigen (bis auf . und - ...
    var height = img.css('height').replace(/[^-\d\.]/g, '') / 2;
    var marginTop = height /2 * -1;
    // Einsetzen
    $('.login_bg_wrapper').css('height', height);
    $('#login_bg').css('margin-top', marginTop);

    var bottom = $('.login_bg_wrapper').css('bottom').replace(/[^-\d\.]/g, '');
    var height = $('.login_bg_wrapper').css('height').replace(/[^-\d\.]/g, '') - 35;
    $('#register').css('bottom', bottom - height);
}
