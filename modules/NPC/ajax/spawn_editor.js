/**
 * Created by Akkadius on 3/8/2015.
 */

$(document).ready(function() {
    /* Hook Mouse Enter and Leave Events */
    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).unbind("mouseenter");
    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).bind("mouseenter", function() {

        width = $(this).css("width");
        height = $(this).css("height");
        data = $(this).html();

        /* If attr is set to non edit, return */
        if($(this).attr("nonedit") == 1){
            return;
        }

        /* Dont replace the button */
        if(data.match(/button/i)){ return; }

        $(this).html('<input type="text" class="form-control" value="' + data + '" onchange="">');
        $(this).children("input").css('width', (parseInt(width) * 1));
        $(this).children("input").css('height', (parseInt(height)));
        $(this).children("input").css("font-size", "11px");
        // $('textarea').autosize();
        data = "";
    });

    /* Hook Mouse Enter and Leave Events */
    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).unbind("mouseleave");
    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).bind("mouseleave", function() {
        data = "";

        /* If attr is set to non edit, return */
        if($(this).attr("nonedit") == 1){
            return;
        }

        /* Grab data from cell depending on input type */
        if($(this).has("select").length){
            data = $(this).children("select").val();
        }
        else if($(this).has("input").length){
            data = $(this).children("input").val();
        }

        /* If cell contains cell... skip */
        if($(this).has("button").length){ return; }

        $(this).html(data);
        data = "";
    });

    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).unbind("change");
    $( ".spawn_entry td, .spawn2 td, .spawn_group td" ).bind("change", function() {
        field_name = $(this).attr("field_name");
        db_table = $(this).parent('tr').attr("db_table");
        db_key = $(this).parent('tr').attr("db_key");
        db_key_val = $(this).parent('tr').attr("db_key_val");
        data = $(this).children('input').val();

        console.log("html " + $(this).html());
        console.log("val " + data);

        /* Do table update */
        $.ajax({
            url: "ajax.php?M=NPC&do_spawn_edit_update=" + db_table + "&field=" + field_name + "&value=" + data + "&db_key=" + encodeURIComponent(db_key) + "&db_key_val=" + encodeURIComponent(db_key_val),
            context: document.body
        }).done(function(e) {
            Notific8("NPC Editor", "Updated " + field_name + " to value " + data, 2000);
        });
    });
});