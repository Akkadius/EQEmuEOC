/**
 * Created by Akkadius on 2/10/2015.
 */

$(document).ready(function() {
    var CalcDataTableHeight = function () {
        return $(window).height() * 60 / 100;
    };

    var table = $("#npc_head_table").DataTable( {
        scrollY:        CalcDataTableHeight(),
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

    // $(window).resize(function () {
    //     var table = $("#npc_head_table").DataTable();
    //     var oSettings = table.fnSettings().oScroll.sY = CalcDataTableHeight();
    //     table.fnDraw();
    // });

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

        if($(this).attr("is_field_translated") == 1){
            return;
        }

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
        data = "";
        if($(this).has("select").length){ data = $(this).children("select").val(); }
        else if($(this).has("input").length){ data = $(this).children("input").val(); }

        if($(this).has("button").length){ return; }

        /* If no data present and */
        if(!data && (!$(this).has("select").length && !$(this).has("input").length)){ $(this).attr("is_field_translated", 0); return; }

        // console.log('data catch ' + data);

        $(this).html(data);
        data = "";
        $(this).attr("is_field_translated", 0);
    });

} );

/* NPC TABLE :: Click */
$( "#npc_head_table td" ).click(function() {
    npc_id = $(this).attr("npc_id");
    db_field = $(this).attr("npc_db_field");
    width = $(this).css("width");
    height = $(this).css("height");
    data = $(this).html();

    if(data.match(/button/i)){ return; }

    // console.log(npc_id);

    /* Highlight row */
    if($('#top_right_pane').attr('npc_loaded') == npc_id){
        console.log('npc already loaded, returning...');
        return;
    }

    /* Highlight Row when selected */
    $("td[background='yellow']").css("background", "").attr("background", "");
    $("td[npc_id='" + npc_id + "']").css("background", "yellow").attr("background", "yellow");


    $.ajax({
        url: "ajax.php?M=NPC&load_npc_top_pane_dash=" + npc_id,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#top_right_pane').html(e).fadeIn();
        $('#top_right_pane').attr('npc_loaded', npc_id);
    });

});

/* NPC TABLE :: Double Click */
$( "#npc_head_table td" ).dblclick(function() {
    npc_id = $(this).attr("npc_id");
    db_field = $(this).attr("npc_db_field");
    width = $(this).css("width");
    height = $(this).css("height");
    data = $(this).html();
    input_data = $(this).find("input").val();

    cell = $(this);

    if(data.match(/button/i)){ return; }

    if(db_field == "special_abilities"){
        DoModal("ajax.php?M=NPC&special_abilities_editor&val=" + input_data + "&npc_id=" + npc_id + "&db_field=" + db_field);
        return;
    }

    $.ajax({
        url: "ajax.php?M=NPC&get_field_translator&npc_id=" + npc_id + "&field_name=" + db_field + "&value=" + input_data,
        context: document.body
    }).done(function(e) {
        // console.log(e);
        /* Return if tool result is empty */
        if(e == ""){ return; }
        /* Update Table Cell with Field Translator Tool */
        cell.html(e).fadeIn();
        cell.attr("is_field_translated", 1);
    });
});