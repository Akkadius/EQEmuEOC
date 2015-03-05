/**
 * Created by
 * Akkadius on 3/5/2015.
 */

$(document).ready(function() {
    var loot_table = $(".loot_table_sub").DataTable( {
        scrollY:        "205px",
        sScrollXInner: "400px",
        scrollCollapse: true,
        paging:         false,
        "searching": false,
        "ordering": true,
        "bSort" : false,
    } );

    var timer = setInterval(function () {
        loot_table.draw();
        window.clearInterval(timer);
    }, 500);
});

function do_loot_search(search_val, loot_table){
    $.ajax({
        url: "ajax.php?M=NPC&do_loot_search=" + search_val + "&loot_table=" + loot_table,
        context: document.body
    }).done(function(e) {
        $("#loot_search_result").html(e);
    });
}

function refresh_npc_pane(){
    $.ajax({
        url: "ajax.php?M=NPC&load_npc_top_pane_dash=" + $('#top_right_pane').attr('npc_loaded'),
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#top_right_pane').html(e).fadeIn();
    });
}

function do_loot_table_loot_drop_add(loot_table, loot_drop){
    $.ajax({
        url: "ajax.php?M=NPC&do_loot_table_loot_drop_add=" + loot_table + "&loot_drop=" + loot_drop,
        context: document.body
    }).done(function(e) {
        refresh_npc_pane();
    });
}

function do_create_new_lootdrop(loot_table){
    $.ajax({
        url: "ajax.php?M=NPC&do_create_new_lootdrop=" + loot_table,
        context: document.body
    }).done(function(e) {
        refresh_npc_pane();
        $('#ajax-modal').modal('hide');
        Notific8("NPC Editor", e, 2000);
    });
}