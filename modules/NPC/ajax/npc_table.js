/**
 * Created by Akkadius on 2/10/2015.
 */

$(document).ready(function() {
    var table = $("#npc_head_table").DataTable( {
        scrollY:        "800px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        "searching": false,
        "ordering": true,
        "columnDefs": [ { "targets": 0, "orderable": false } ],
        "oLanguage": {
            "sProcessing": "DataTables is currently busy"
        }
    } );

    new $.fn.dataTable.FixedColumns( table, {
        leftColumns: 3
    } );

    var timer = setInterval(function () {
        var table = $("#npc_head_table").DataTable();
        table.draw();
        window.clearInterval(timer);
    }, 500);

    $( "#npc_head_table td, .DTFC_Cloned td" ).unbind("mouseenter");
    $( "#npc_head_table td, .DTFC_Cloned td" ).bind("mouseenter", function() {
        // console.log("Hovering in");

        npc_id = $(this).attr("npc_id");
        field_name = $(this).attr("npc_db_field");
        width = $(this).css("width");
        height = $(this).css("height");
        data = $(this).html();

        // console.log(npc_id + " "  + field_name);

        /* Dont replace the button */
        if(data.match(/button/i)){ return; }

        $(this).html('<input type="text" class="form-control" value="' + data + '" onchange="update_npc_field(npc_id, field_name, this.value)">');
        $(this).children("input").css('width', (parseInt(width) * 1));
        $(this).children("input").css('height', (parseInt(height)));
        $(this).children("input").css("font-size", "12px");
        // $('textarea').autosize();
        data = "";
    });

    $( "#npc_head_table td, .DTFC_Cloned td" ).unbind("mouseleave");
    $( "#npc_head_table td, .DTFC_Cloned td" ).bind("mouseleave", function() {
        data = $(this).children("input").val();
        $(this).html(data);
        data = "";
    });
} );
$( "#npc_head_table td" ).click(function() {
    npc_id = $(this).attr("npc_id");
    db_field = $(this).attr("npc_db_field");
    width = $(this).css("width");
    height = $(this).css("height");
    data = $(this).html();

    if(data.match(/button/i)){ return; }
    console.log(npc_id);

    if($('#top_right_pane').attr('npc_loaded') == npc_id){
        // console.log('npc already loaded, returning...');
        return;
    }

    $.ajax({
        url: "ajax.php?M=NPC&load_npc_top_pane_dash=" + npc_id,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#top_right_pane').html(e).fadeIn();
        $('#top_right_pane').attr('npc_loaded', npc_id);
    });

});

$( "#npc_head_table td" ).dblclick(function() {
    npc_id = $(this).attr("npc_id");
    db_field = $(this).attr("npc_db_field");
    width = $(this).css("width");
    height = $(this).css("height");
    data = $(this).html();

    if(data.match(/button/i)){ return; }
});