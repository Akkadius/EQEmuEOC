/**
 * Created by Akkadius on 2/10/2015.
 */

$(document).ready(function() {
    var lootdrop_table = $(".lootdrop_entries").DataTable( {
        scrollY:        "145px",
        scrollX:        "200px",
        sScrollXInner: "650px",
        scrollCollapse: true,
        paging:         false,
        "searching": false,
        "ordering": true,
        "sDom": '<"top">rt<"bottom"flp><"clear">',
        "bSort" : false,
    } );

    $(".lootdrop_entries").css("width", "650px");
    var timer = setInterval(function () {
        lootdrop_table.draw();
        $(".lootdrop_entries").css("width", "650px");
        window.clearInterval(timer);
    }, 500);

    /* Loot Table Click Hooks */
    $( ".loottable_entries tr" ).unbind( "click");
    $( ".loottable_entries tr" ).bind( "click", function() {
        loot_table = $(this).attr("loot_table");
        loot_drop = $(this).attr("loot_drop");
        data = $(this).html();

        console.log(loot_table + ' ' + loot_drop);

        /* Highlight Row entry for loot table */
        $(".loottable_entries td").each(function() {
            $(this).css("background", "");
        });

        $(".loottable_entries td").each(function() {
            if($(this).attr("loot_drop") == loot_drop){
                $(this).css("background", "yellow");
            }
        });

        if($('#lootdrop_entries').attr('lootdrop_loaded') == loot_drop){
            return;
        }

        $.ajax({
            url: "ajax.php?M=NPC&show_lootdrop_entries=" + loot_drop,
            context: document.body
        }).done(function(e) {
            /* Update Data Table as well */
            $('#lootdrop_entries').html(e).fadeIn();
            $('#lootdrop_entries').attr('lootdrop_loaded', loot_drop);
            HookHoverTips();
        });

    });

    /* Hook Mouse Enter and Leave Events for Loottable (table) */
    $( ".loottable_entries td, .DTFC_Cloned td" ).unbind("mouseenter");
    $( ".loottable_entries td, .DTFC_Cloned td" ).bind("mouseenter", function() {
        loot_table = $(this).parent().attr("loot_table");
        loot_drop = $(this).parent().attr("loot_drop");
        field_name = $(this).attr("field_name");
        width = $(this).css("width");
        height = $(this).css("height");
        data = $(this).html();

        /* If attr is set to non edit, return */
        if($(this).attr("nonedit") == 1){
            return;
        }

        /* Dont replace the button */
        if(data.match(/button/i)){ return; }

        $(this).html('<input type="text" class="form-control" value="' + data + '" onchange="update_loottable(' + loot_table + ', ' + loot_drop + ', \'' + field_name + '\', this.value)">');
        $(this).children("input").css('width', (parseInt(width) * 1));
        $(this).children("input").css('height', (parseInt(height)));
        $(this).children("input").css("font-size", "12px");
        // $('textarea').autosize();
        data = "";
    });

    /* Hook Mouse Leave Events for Loottable (table) */
    $( ".loottable_entries td" ).unbind("mouseleave");
    $( ".loottable_entries td" ).bind("mouseleave", function() {
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
    $( ".lootdrop_entries td, .DTFC_Cloned td" ).unbind("mouseleave");
    $( ".lootdrop_entries td, .DTFC_Cloned td" ).bind("mouseleave", function() {
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

function update_loottable(loot_table, loot_drop, field_name, val){
    $.ajax({
        url: "ajax.php?M=NPC&update_loottable=" + loot_table + "&loot_drop=" + loot_drop + "&field=" + field_name + "&value=" + val,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#lootdrop_entries').html(e).fadeIn();
        Notific8("NPC Editor", loot_drop + " :: Updated " + field_name + " to value '" + val + "'", 3000);
    });
}

function update_loot_drop(loot_drop, item_id, field_name, val){
    $.ajax({
        url: "ajax.php?M=NPC&update_loot_drop=" + loot_drop + "&item_id=" + item_id + "&field=" + field_name + "&value=" + val,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        Notific8("NPC Editor", "Loot Drop :: Updated " + field_name + " to value '" + val + "' for item " + item_id, 1000);
        if(e != ''){
            alert(e);
        }
    });
}

function loot_drop_add_item(loot_drop_add_item){
    DoModal("ajax.php?M=NPC&loot_drop_add_item=" + loot_drop_add_item);
}

function reload_lootdrop_entries_table_pane(loot_drop){
    /* Lets Update the top right pane */
    $.ajax({
        url: "ajax.php?M=NPC&show_lootdrop_entries=" + loot_drop,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#lootdrop_entries').html(e).fadeIn();
        $('#lootdrop_entries').attr('lootdrop_loaded', loot_drop);
        HookHoverTips();
    });
}

function add_to_lootdrop(loot_drop, item_id){
    $.ajax({
        url: "ajax.php?M=NPC&db_loot_drop_add_item=" + item_id + "&loot_drop=" + loot_drop,
        context: document.body
    }).done(function(e) {
        Notific8("NPC Editor", loot_drop + " :: Added " + item_id + " ", 2000);
        reload_lootdrop_entries_table_pane(loot_drop);
    });
}

function do_lootdrop_delete(lootdrop_id, item_id){
    DoModal("ajax.php?M=NPC&do_lootdrop_delete=" + lootdrop_id + "&item_id=" + item_id);
}

function do_lootdrop_delete_confirmed(lootdrop_id, item_id){
    /* Delete item from lootdrop */
    $.ajax({
        url: "ajax.php?M=NPC&do_lootdrop_delete_confirmed=" + lootdrop_id + "&item_id=" + item_id,
        context: document.body
    }).done(function(e) {
        $('#ajax-modal').modal('hide');
        Notific8("NPC Editor", "Removed item: " + item_id + " from Lootdrop ID: " + lootdrop_id + "" + e, 2000);
        /* Refresh Table when removed */
        reload_lootdrop_entries_table_pane(lootdrop_id);
    });
}

function do_loot_table_delete(loot_table, loot_drop_id){
    DoModal("ajax.php?M=NPC&do_loot_table_delete=" + loot_table + "&loot_drop_id=" + loot_drop_id);
}

function do_loot_table_delete_confirmed(loot_table, loot_drop_id){
    /* Delete item from lootdrop */
    $.ajax({
        url: "ajax.php?M=NPC&do_loot_table_delete_confirmed=" + loot_table + "&loot_drop_id=" + loot_drop_id,
        context: document.body
    }).done(function(e) {
        $('#ajax-modal').modal('hide');
        Notific8("NPC Editor", "Removed Lootdrop ID: " + loot_drop_id + " from Loot Table ID: " + loot_table, 2000);
        if(e != ''){
            alert(e);
        }
        /* Remove Row physically from table */
        $(".loottable_entries tr").each(function() {
            if($(this).attr("loot_drop") == loot_drop_id){
                $(this).remove();
            }
        });
    });
}

function do_loottable_delete(loottable_id, lootdrop_id){
    DoModal("ajax.php?M=NPC&do_loottable_delete=" + loottable_id + "&lootdrop_id=" + lootdrop_id);
}