/**
 * Created by falkner_d2 on 27.02.14.
 */
$(document).ready(function() {
var user = $("#iuser").val().length;

$('#iuser').on('keyup', maleLogo);
$('#ipass').on('keyup', maleLogo);
$('#ipass2').on('keyup', maleLogo);
$('#imail').on('keyup', maleLogo);

$('#ivname').on('keyup', maleLogo);
$('#inname').on('keyup', maleLogo);
$('#iplz').on('keyup', maleLogo);
$('#icity').on('keyup', maleLogo);
$('#istreet').on('keyup', maleLogo);
$('#istreetnumber').on('keyup', maleLogo);
$('#icompany').on('keyup', maleLogo);
$('#iphone').on('keyup', maleLogo);

//$("#bild_a").css('clip', 'rect(0px, 385px, auto, 0px)');
function maleLogo() {

    var lange = 0;

    if ($('#iuser').val().length > 0) {
        lange += 1;
    }
    if ($('#ipass').val().length > 0) {
        lange += 1;
    }
    if ($('#ipass2').val().length > 0) {
        lange += 1;
    }
    if ($('#imail').val().length > 0) {
        lange += 1;
    }
    if ($('#iplz').val().length > 0) {
        lange += 1;
    }
    if ($('#icity').val().length > 0) {
        lange += 1;
    }
    if ($('#istreet').val().length > 0) {
        lange += 1;
    }
    if ($('#istreetnumber').val().length > 0) {
        lange += 1;
    }
    if ($('#icompany').val().length > 0) {
        lange += 1;
    }
    if ($('#iphone').val().length > 0) {
        lange += 1;
    }

    if (lange == 0) {
        $("#bild_a").css('clip', 'rect(0px, 0px, auto, 0px)');
    }
    else if (lange == 1) {
        $("#bild_a").css('clip', 'rect(0px, 20px, auto, 0px)');
    }
    else if (lange == 2) {
        $("#bild_a").css('clip', 'rect(0px, 40px, auto, 0px)');
    }
    else if (lange == 3) {
        $("#bild_a").css('clip', 'rect(0px, 60px, auto, 0px)');
    }
    else if (lange == 4) {
        $("#bild_a").css('clip', 'rect(0px, 80px, auto, 0px)');
    }
    else if (lange == 5) {
        $("#bild_a").css('clip', 'rect(0px, 100px, auto, 0px)');
    }
    else if (lange == 6) {
        $("#bild_a").css('clip', 'rect(0px, 120px, auto, 0px)');
    }
    else if (lange == 7) {
        $("#bild_a").css('clip', 'rect(0px, 140px, auto, 0px)');
    }
    else if (lange == 8) {
        $("#bild_a").css('clip', 'rect(0px, 160px, auto, 0px)');
    }
    else if (lange == 9) {
        $("#bild_a").css('clip', 'rect(0px, 180px, auto, 0px)');
    }
    else if (lange == 9) {
        $("#bild_a").css('clip', 'rect(0px, 200px, auto, 0px)');
    }
    else if (lange == 9) {
        $("#bild_a").css('clip', 'rect(0px, 220px, auto, 0px)');
    }
    else if (lange == 10) {
        $("#bild_a").css('clip', 'rect(0px, 500px, auto, 0px)');
    }
}
});