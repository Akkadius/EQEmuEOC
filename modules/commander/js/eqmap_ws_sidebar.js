/**
 * Created by Akkadius on 12/7/2014.
 */

function BuildSideBarMenuOptions(){
    $.ajax({url: "ajax.php?M=CM&quick_sidebar_menu_options", context: document.body}).done(function (e) {
        eval($(e).find("script").text());
        $('#quick_sidebar_menu_options').html(e);
    });
}

function SidebarShowEnt(l_ent_id){
    socket.send(JSON.stringify({
        id: 'zone_get_entity_attributes',
        method: 'Zone.GetEntityAttributes',
        params: [g_zone_id, g_instance_id, "" + l_ent_id + ""]
    }));
}

function HandleSideBarShowEntCallBack(json){
    var query_string = "doquery=1";
    for (var key in json.result) {
        if (json.result.hasOwnProperty(key)) {
            query_string = query_string + "&" + key + "=" + encodeURIComponent(json.result[key]);
        }
    }

    $.ajax({
        url: "ajax.php?M=CM&sidebar_menu=entity&ent_id=" + json.result.ent_id,
        type: "POST",
        data: query_string,
        context: document.body}).done(function (e) {
        eval($(e).find("script").text());
        $('#quick_sidebar_content').html(e);
    });
}

function SideBarMenu(menu){
    var query_string = "ajax.php?M=CM&sidebar_menu=" + menu;
    $.ajax({url: query_string, context: document.body}).done(function (e) {
        eval($(e).find("script").text());
        $('#quick_sidebar_content').html(e);
    });
}
