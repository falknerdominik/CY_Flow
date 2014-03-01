/**
 * Created by falkner_d2 on 27.02.14.
 */
$(document).ready(function() {
var user = $("#iuser").val().length;

$('#iuser').on('keyup', maleLogo);
$('#ipass').on('keyup', maleLogo);
$('#ipass2').on('keyup', maleLogo);
$('#imail').on('keyup', maleLogo);

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

var Registrierung = (function () {
    function Registrierung() {
        this.abcklein = /[a-z]/;
        this.abcgross = /[A-Z]/;
        this.zahlen = /[0-9]/;
        this.specialChar = /\W+/;
        this.keinezahl = /\D/;
    }
    Registrierung.prototype.validiereUsername = function (user) {
        var resultspecial = user.match(this.specialChar);

        if (resultspecial != null) {
            return false;
        }
        if (user.length <= 5 || user.length >= 15) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validierePassord = function (pass) {
        var resultspecial = pass.match(this.specialChar);
        var resultabcklein = pass.match(this.abcklein);
        var resultabcgross = pass.match(this.abcgross);
        var resultzahl = pass.match(this.zahlen);

        if (resultspecial == null) {
            return false;
        }
        if (resultabcgross == null) {
            return false;
        }
        if (resultabcklein == null) {
            return false;
        }
        if (resultzahl == null) {
            return false;
        }
        if (pass.length < 8) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validiereCompany = function (companyname) {
        if (companyname.length < 3) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validierePlz = function (plz) {
        var resultzahl = plz.match(this.zahlen);
        var resultkeinezahl = plz.match(this.keinezahl);

        if (resultzahl == null) {
            return false;
        }
        if (resultkeinezahl != null) {
            return false;
        }
        if (resultkeinezahl > 0) {
            return false;
        }
        if (plz.length == 4) {
            return true;
        } else
            return false;
    };

    Registrierung.prototype.validiereCity = function (cityname) {
        if (cityname.length < 2) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validiereStreet = function (streetnumber) {
        if (streetnumber.length <= 1) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validiereStreetNumber = function (streetnumber) {
        if (streetnumber.length <= 1) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validiereTel = function (telnumber) {
        var resultkeinezahl = telnumber.match(this.keinezahl);

        if (telnumber.length == null) {
            return false;
        }
        if (telnumber.length < 4) {
            return false;
        }
        if (resultkeinezahl != null) {
            return false;
        }
        return true;
    };

    Registrierung.prototype.validiereEmail = function (email) {
        var mailmusthave = /^[a-z-_0-9]+@+[a-z-_0-9]+(.com|.at|.de|.edu|.net|.mil|.co.uk)$/;

        if (!mailmusthave.test(email)) {
            return false;
        }
        return true;
    };
    return Registrierung;
})();

var NeuesProjekt = (function () {
    function NeuesProjekt() {
    }
    NeuesProjekt.prototype.validiereName = function (projektname) {
        if (projektname.length <= 12 && projektname.length >= 3) {
            return true;
        } else
            return false;
    };

    NeuesProjekt.prototype.validiereDescription = function (projektbeschreibung) {
        if (projektbeschreibung.length < 1000 && projektbeschreibung.length > 49) {
            return true;
        } else
            return false;
    };

    NeuesProjekt.prototype.validiereTyp = function (projekttyp) {
        if (projekttyp.length < 21 && projekttyp.length > 3) {
            return true;
        } else
            return false;
    };
    return NeuesProjekt;
})();

// ANWENDUNG
window.onload = function () {
    var x = new Registrierung();

    $("#registrierung").submit(function (e) {
        // DATEN AUSLESEN
        var username = $("#iuser").val();
        var password = $("#ipass").val();
        var password2 = $("#ipass2").val();
        var company = $("#icompany").val();
        var streetNumber = $('istreetNumber').val();
        var tel = $("#itel").val();
        var mail = $("#imail").val();
        var street = $("#istreet").val();
        var city = $("#icity").val();
        var plz = $("#iplz").val();

        if (!x.validiereUsername(username)) {
            alert("User Invalid!");

            //$('#erg').append("<h1>hallo</h1>");
            e.preventDefault();
        } else if (!x.validierePassord(password)) {
            alert("Passwort Invalid!");
            e.preventDefault();
        } else if(!x.validierePassord(password2)){
            alert("Passwort Invalid!");
            e.preventDefault();
        } else if (!x.validiereCompany(company)) {
            alert("Company Invalid!");
            e.preventDefault();
        } else if (!x.validiereTel(tel)) {
            alert("Telephone Invalid!");
            e.preventDefault();
        } else if (!x.validiereEmail(mail)) {
            alert("mail Invalid!");
            e.preventDefault();
        } else if (!x.validiereCity(city)) {
            alert("City Invalid!");
            e.preventDefault();
        } else if (!x.validiereStreet(street)) {
            alert("Street Invalid!");
            e.preventDefault();
        } else if(!x.validiereStreet(streetNumber)){
            alert("Straßennumber Invalid!");
            e.preventDefault();
        } else if (!x.validierePlz(plz)) {
            alert("PLZ Invalid!");
            e.preventDefault();
        } else {
            $("#registrierung").unbind('submit').submit();
        }
    });

    $(function () {
        $("#iuser").popover({
            content: "Keine Sonderzeichen <br> Mindestens 6 Zeichen <br> Höchstens 15 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#ipass").popover({
            content: "Mindestens 1 Sonderzeichen <br> Mindestens 8 Zeichen <br> Mindestens 1 Großbuchstabe <br> Mindestens 1 Kleinbuchstabe <br> Mindestens 1 Zahl",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#ipass2").popover({
            content: "Mindestens 1 Sonderzeichen <br> Mindestens 8 Zeichen <br> Mindestens 1 Großbuchstabe <br> Mindestens 1 Kleinbuchstabe <br> Mindestens 1 Zahl",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#icompany").popover({
            content: "Mindestens 3 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#iplz").popover({
            content: "4 Ziffern <br> 4 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#icity").popover({
            content: "Mindestens 2 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#istreet").popover({
            content: "Mindestens 2 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#istreetnumber").popover({
            content: "Mindestens 2 Zeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#iphone").popover({
            content: "Mindestens 3 Zeichen <br> Nur Ziffern ohne Leerzeichen",
            title: "Hinweis",
            trigger: "focus"
        });
        $("#imail").popover({
            content: "Muss eine gültige Email Adresse sein",
            title: "Hinweis",
            trigger: "focus"
        });
    });
};