/**
 * Created by cmiles on 12/5/2016.
 */

function do_translate(){
    translation_type = $('#translate_options').val();
    translation_text = $('#translate_input').val();
    translate_lines = $('#translate_lines').val();

    $.ajax({
        type: "POST",
        url: "ajax.php?M=quests&do_translate",
        data: "type=" + encodeURIComponent(translation_type) + "&text=" + encodeURIComponent(translation_text) + "&translate_lines=" + encodeURIComponent(translate_lines),
        context: document.body
    }).done(function(e) {
        Notific8("Quest Translator", "Script translated!", 2000);
        $('#translate_output').val(e);;
    });
}

function comma_split() {
    $('#translate_input').val($('#translate_input').val().split(" ").join(""));
    $('#translate_input').val($('#translate_input').val().split("\t").join(""));
    $('#translate_input').val($('#translate_input').val().split(",").join(",\n"));
    $('#translate_input').val($('#translate_input').val().replace(/^\s*$[\n\r]{1,}/gm, ''));
    do_translate();
}

function trim_whitespace() {
    $('#translate_input').val($('#translate_input').val().trim());
    do_translate();
}