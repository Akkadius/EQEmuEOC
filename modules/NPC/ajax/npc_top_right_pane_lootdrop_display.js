/**
 * Created by Akkadius on 3/5/2015.
 */

$(document).ready(function() {
    var lootdrop_table = $(".lootdrop_entries").DataTable( {
        scrollY:        "105px",
        scrollX:        "200px",
        sScrollXInner: "700px",
        scrollCollapse: true,
        paging:         false,
        "searching": false,
        "ordering": true,
        "sDom": '<"top">rt<"bottom"flp><"clear">',
        "bSort" : false,
    } );

    $(".lootdrop_entries").css("width", "700px");
    var timer = setInterval(function () {
        $(".lootdrop_entries").css("width", "700px");
        lootdrop_table.draw();
        window.clearInterval(timer);
    }, 100);

    /* Hook Mouse Enter and Leave Events for Lootdrop (table) */
    $( ".lootdrop_entries td" ).unbind("mouseenter");
    $( ".lootdrop_entries td" ).bind("mouseenter", function() {
        loot_drop = $(this).parent().attr("loot_drop");
        l_item_id = $(this).parent().attr("item_id");
        field_name = $(this).attr("field_name");

        console.log(field_name);

        width = $(this).css("width");
        height = $(this).css("height");
        data = $(this).html();

        /* If attr is set to non edit, return */
        if($(this).attr("nonedit") == 1){
            return;
        }

        // console.log(loot_table + ' ' + loot_drop);

        /* Dont replace the button */
        if(data.match(/button/i)){ return; }

        $(this).html('<input type="text" class="form-control" value="' + data + '" onchange="update_loot_drop(' + loot_drop + ', ' + l_item_id + ', \'' + field_name + '\', this.value)">');
        $(this).children("input").css('width', (parseInt(width) * 1));
        $(this).children("input").css('height', (parseInt(height)));
        $(this).children("input").css("font-size", "12px");
        // $('textarea').autosize();
        data = "";
    });

    /* Hook Mouse Leave Events for Lootdrop (table) */
    $( ".lootdrop_entries td" ).unbind("mouseleave");
    $( ".lootdrop_entries td" ).bind("mouseleave", function() {
        data = "";

        /* Grab data from cell depending on input type */
        if($(this).has("select").length){
            data = $(this).children("select").val();
        }
        else if($(this).has("input").length){
            data = $(this).children("input").val();
        }

        /* If cell contains cell... skip */
        if($(this).has("button").length){ return; }

        /* If no data present and */
        if(!data && (!$(this).has("select").length && !$(this).has("input").length)){
            $(this).attr("is_field_translated", 0);
            return;
        }

        $(this).html(data);
        data = "";
        $(this).attr("is_field_translated", 0);
    });
});