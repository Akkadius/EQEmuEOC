/**
 * Created by Akkadius on 2/10/2015.
 */

$(document).ready(function() {
    var lootdrop_table = $(".lootdrop_entries").DataTable( {
        scrollY:        "120px",
        scrollCollapse: true,
        paging:         false,
        "searching": false,
        "ordering": true,
        "sDom": '<"top">rt<"bottom"flp><"clear">',
        "bSort" : false,
    } );
    $( ".loottable_entries tr" ).unbind( "click");
    $( ".loottable_entries tr" ).bind( "click", function() {
        loot_table = $(this).attr("loot_table");
        loot_drop = $(this).attr("loot_drop");
        data = $(this).html();

        console.log(loot_table + ' ' + loot_drop);

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
});