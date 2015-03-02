/**
 * Created by Akkadius on 3/2/2015.
 */

$('#item_search').submit(function(event){
    event.preventDefault();
    $('#item_search_result_lootdrop').html(global_loader);
    query_string = GetFormQueryString("item_search");
    $.ajax({
        url: "ajax.php?M=NPC&item_search_lootdrop" + query_string,
        context: document.body
    }).done(function(data) {
        $('#item_search_result_lootdrop').html(data);
        $('#item_search').hide();
    });
});