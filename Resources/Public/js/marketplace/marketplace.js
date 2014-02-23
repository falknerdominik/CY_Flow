$(window).load(function() {
  // Chosen
    $('.chosen-multiple').chosen({
        no_results_text: "Leider nichts gefunden",
        placeholder_text_multiple: "Bitte wähle das Team aus."

    });
    $('.chosen-single').chosen({
        placeholder_text_single: "Wähle jemanden aus.",
        no_results_text: "Leider nichts gefunden"
    });
});
