/* Variables - Most of which you do not want to change */

var zone_server_count = 0; /* Zone Server Count */
var total_players = 0; /* Total Player Count */
var total_bytes_recieved = 0; /* Total Bytes Received via JSON */
var depop_repop_queue = 0; /* Depop Status Bool */
var zoom = 250; /* 250% */
var canvas_events_hooked = 0; /* Bool to set Canvas Events Status */
var selected_entity_id = 0; /* Entity Selected on the Map */
var show_menu = 0;
var in_ent = 0; /* 2 = Client, 3 = NPC */
var follow_entity_id = 0;
var highlighted_entity_id = 0;
var ent_follow_update_rate = 10; /* The Rate in which the camera will follow an entity */
var follow_iter = 0; /* Follow Iteration, keep at 0 */
var in_context_menu = 0; /* Keeps track of mouse cursor inside context menu, keep at 0 */

var entity_list_cache = []; /* Stores the entity list into a cache variable */
var entity_list_index = 0;
var highlight_timer = '';

var g_zone_id;
var g_instance_id;

/* Entity Events
 0 = Death
 1 = Spawn Mob Corpse
 */

c_images = {
    1: "4487",
    2: "4477",
    3: "4476",
    4: "4471",
    5: "4483",
    6: "4481",
    7: "4475",
    8: "4464",
    9: "4490",
    10: "4491",
    11: "4469",
    12: "4447",
    13: "4470",
    14: "4488",
    15: "4489",
    16: "4465"
};
r_images = {
    1: "4472",
    2: "4466",
    10: "4474",
    330: "4480",
    130: "4461",
    128: "4473",
    11: "4484",
    6: "4462",
    522: "4486",
    8: "4460",
    5: "4463",
    4: "4479",
    9: "4467",
    12: "4485",
    7: "4468",
    3: "4478"
};


/*
 40	Banker
 41	Shopkeeper
 59	Discord Merchant
 60	Adventure Recruiter
 61	Adventure Merchant
 63	Tribute Master
 64	Guild Tribute Master?
 66	Guild Bank
 67	Radiant Crystal Merchant
 68	Ebon Crystal Merchant
 69	Fellowships
 70	Alternate Currency Merchant
 71	Mercenary Merchant
 */

npc_c_images = {
    40: "money-bag-dollar.png",
    41: "briefcase.png",
    59: "skull.png",
    63: "control-power.png",
    64: "control-power.png",
    66: "money-bag-dollar.png"
};

/* Websocket Core */

var socket;
function DoConnect(){
    var query_string = "ajax.php?M=CM&Config";
    $.ajax({url: query_string, context: document.body}).done(function (e) {
        var obj = JSON.parse(e);
        var WS_IP = obj[0];
        var WS_PORT = parseInt(obj[1], 10);
        var WS_TOKEN = obj[2];

        /* Set our server connection */
        socket = new WebSocket('ws://' + WS_IP + ':' + WS_PORT, 'eqemu');

        /* On WS Open */
        socket.onopen = function () {
            console.log('Connection opened to ws://' + WS_IP + ':' + WS_PORT);
            var obj = {};
            obj.id = 'token_auth_id';
            obj.method = 'WebInterface.Authorize';
            obj.params = [WS_TOKEN];
            socket.send(JSON.stringify(obj));
            SideBarMenu("zone_action");
            BuildSideBarMenuOptions();
            // if(ace_file_to_load){
            //     socket.send(JSON.stringify({id: 'quest_get_script', method: 'World.GetFileContents', params: ['' + ace_file_to_load + '']}));
            // }
            // socket.send(JSON.stringify({id: 'quest_get_script', method: 'World.GetFileContents', params: ['quests/global/global_player.pl']}));
        };

        /* Log Errors */
        socket.onerror = function (error) {
            console.log('WebSocket Error ' + error);
        };

        /* Messages from Server */
        socket.onmessage = function (e) {
            // console.log('Server: ' + e.data);
            var IS_JSON = true;
            try {
                var json = $.parseJSON(e.data);
            }
            catch (err) {
                IS_JSON = false;
            }
            if (IS_JSON == true) {

                /* Update Bytes Received */
                total_bytes_recieved += roughSizeOfObject(json);
                $('#status_display').html('MB received ' + round((total_bytes_recieved / 1024 / 1024), 5));

                /* Tests */
                if (json.id == 'quest_get_script') {
                    // console.log(json);
                    // json.result.quest_text = json.result.quest_text.replace("\\n", "<br />");
                    // $('#editor').text(json.result.quest_text);
                    editor.getSession().setValue(json.result.quest_text);
                }

                /* Event Handlers: Server -> Web Client */
                if (json.method == 'On.NPC.Position' || json.method == 'On.Client.Position' || json.id == 'get_initial_entity_positions') {
                    OnPositionUpdate(json);
                }

                if (json.method == 'On.Entity.Events') { OnEntityEvent(json); }
                else if (json.method == 'On.Combat.States') { OnClientCombatState(json); }
                else if (json.method == 'On.NPC.Depop') { OnNPCDepop(json);    }
                else if (json.id == 'zone_get_entity_attributes'){ HandleSideBarShowEntCallBack(json); }
                else if (json.id == 'list_zones_id') { HandleListZones(json); }
                else if (json.id == 'token_auth_id') { socket.send(JSON.stringify({id: 'list_zones_id', method: 'World.ListZones', params: []}));  }
                else if (json.id == 'get_zone_info_id') { GetZoneInfoFromListZone(json); }
            }
        };
    });
}

/* END: Websocket Core */

DoConnect();

function CalcEQHeadingToBrowser(heading) {
    heading = (256 - heading) * 1.40625;
    return heading;
}

function SendMessage() {
    socket.send($('#text_field').val());
}

function FollowEntity(ent_id) {
    follow_entity_id = ent_id;
}

function GoToEntity(ent_id){
    follow_entity_id = null;
    var entity = '#ent_' +  ent_id;
    $('html, body').animate({
        scrollTop: $(entity).offset().top - ($(window).height() / 2),
        scrollLeft: $(entity).offset().left - ($(window).width() / 2)
    }, 2500);
}

function GetZoneServerList() {

}

function RegisterEntity(ent_id) {
    console.log('selected ent id ' + ent_id);
    selected_entity_id = ent_id;
    $.notific8('Dragging entity position, press (Esc) to let go<br><br>', {
        heading: "Commander",
        theme: "ruby",
        life: 8000
    });
}

/* Calculates bytes */
function roughSizeOfObject(object) {
    var objectList = [];
    var stack = [object];
    var bytes = 0;
    while (stack.length) {
        var value = stack.pop();
        if (typeof value === 'boolean') {
            bytes += 4;
        }
        else if (typeof value === 'string') {
            bytes += value.length * 2;
        }
        else if (typeof value === 'number') {
            bytes += 8;
        }
        else if (typeof value === 'object' && objectList.indexOf(value) === -1) {
            objectList.push(value);
            for (var i in value) {
                stack.push(value[i]);
            }
        }
    }
    return bytes;
}

/* Number rounding */
function round(num, places) {
    var multiplier = Math.pow(10, places);
    return Math.round(num * multiplier) / multiplier;
}

/* Currently hackish map canvas scaling function that doesn't quite work with everything for zooming */
function ScaleMapCanvas(zoom) {
    console.log('Zoom is ' + zoom);
    if (zoom >= 200) {
        $('.entity_name').css('font-size', '6px');
    }
    else if (zoom >= 1250) {
        $('.entity_name').css('font-size', '8px');
    }
    else if (zoom >= 125) {
        $('.entity_name').css('font-size', '10px');
    }
    else if (zoom >= 250) {
        $('.entity_name').css('font-size', '12px');
    }
}

/* Controls */
function mouse_move_parse(event) {
    //var msg = "Handler for .mousemove() called at ";
    //msg += event.pageX + ", " + event.pageY;
    // console.log(msg);
    if (selected_entity_id > 0) {
        // follow_entity_id = selected_entity_id;
        // console.log('Issuing move test with ' + selected_entity_id + ' zone_id ' + g_zone_id + ' instance_id ' + g_instance_id);
        // socket.send(JSON.stringify({id: 'move_entity', method: 'Zone.Move.Entity', params: [g_zone_id, g_instance_id, 3]}));
        // console.log('diff x ' + difference_x );
        // console.log('diff y ' + difference_y );
        // console.log('left offset ' + left_offset  );
        // console.log('top offset ' + top_offset   );
        // console.log('EQ X - ' + (left_offset - event.pageX + 5) + '');
        // console.log('EQ Y - ' + (top_offset - event.pageY + 55) + '');
        var EQ_X = (left_offset - event.pageX + 5);
        var EQ_Y = (top_offset - event.pageY + 55);
        var selectedentity = '#ent_' + selected_entity_id;
        socket.send(JSON.stringify({
            id: 'move_entity',
            method: 'Zone.MoveEntity',
            params: [g_zone_id, g_instance_id, "" + selected_entity_id + "", "" + EQ_X + "", "" + EQ_Y + "", "" + 0 + "", "" + 0 + ""]
        }));
        $(selectedentity).css('left', (left_offset - EQ_X) + 'px');
        $(selectedentity).css('top', (top_offset - EQ_Y) + 'px');
        $(selectedentity).data('data-x', EQ_X);
        $(selectedentity).data('data-y', EQ_Y);
    }
}

function HighlightEntity(ent_id) {
    socket.send(JSON.stringify({id: "set_entity_attribute", method: "Zone.SetEntityAttribute", params: [g_zone_id, g_instance_id, "" + ent_id + "", "appearance_effect", "" + 135 + ""]}));
    socket.send(JSON.stringify({id: "set_entity_attribute", method: "Zone.SetEntityAttribute", params: [g_zone_id, g_instance_id, "" + ent_id + "", "appearance_effect", "" + 140 + ""]}));

    var entity = "#ent_" + ent_id;
    $(entity).removeClass("fadeIn");
    $(entity).fadeIn(250).fadeOut(250).fadeIn(250).fadeOut(250).fadeIn(250);
}

function SendAppearanceEffect(ent_id, app_effect) {
    socket.send(JSON.stringify({id: "set_entity_attribute", method: "Zone.SetEntityAttribute", params: [g_zone_id, g_instance_id, "" + ent_id + "", "appearance_effect", "" + app_effect + ""]}));
}

function Hook_Canvas_Events() {
    console.log('Loading canvas events...');
    $("#map_canvas").mousemove($.throttle(10, function (event) {
        mouse_move_parse(event);
    }));
    $(".Zoom_In").bind("click", function () {
        zoom += 10;
        $('#map_canvas').css('zoom', zoom + '%');
        ScaleMapCanvas(zoom);
    });
    $(".Zoom_Out").bind("click", function () {
        zoom -= 10;
        $('#map_canvas').css('zoom', zoom + '%');
        ScaleMapCanvas(zoom);
    });
    $("html").keypress(function (e) {
        if (e.which == 96) {
            selected_entity_id = 0;
        }
    });
    console.log('Loading canvas events... Done');
    canvas_events_hooked = 1;
}

$(document).keyup(function (e) {
    if (e.keyCode == 27) { /* Escape */
        $('.contextMenuPlugin').remove();
        selected_entity_id = 0;
        follow_entity_id = 0;
        if(highlight_timer){ clearInterval(highlight_timer); }
    }
});

$('body, html').mousedown(function (event) {
    switch (event.which) {
        case 1:
            if (in_context_menu != 1) {
                $('.contextMenuPlugin').remove();
            }
            break;
        case 2:
            $('.contextMenuPlugin').remove();
            event.preventDefault();
            selected_entity_id = 0;
            follow_entity_id = 0;
            break;
        case 3:
            event.preventDefault();
            selected_entity_id = 0;
            follow_entity_id = 0;
            $('.contextMenuPlugin').remove();
            break;
        default:
    }
});

document.oncontextmenu = function () {
    return false;
};

function SearchEntity(search){
    var entity_search_list = "";

    for (var i = 0; i < entity_list_cache.length; i++) {
        var ent_id = entity_list_cache[i][0];
        var ent_name = entity_list_cache[i][1];
        //var ent_name = ent_name.substring(0, 20);
        var ent_type = entity_list_cache[i][2];
        var ent_class = entity_list_cache[i][3];
        var ent_race = entity_list_cache[i][4];

        if(search != ""){
            var regex_search_match = new RegExp(search, 'i');
            if(ent_name.match(regex_search_match)){ }
            else{ continue; }
            //console.log('Search TEST ' + ent_name.indexOf(search) + ' CRIT ' + ent_name);
        }
        /* Get Class and Race Icon */
        var btn_class = 'red';
        var class_icon = '';
        var race_icon = '';
        var href_style = '';
        var status_icon = '';

        if (ent_type == 'Door' || ent_type == 'Object') {
            ent_race = -1;
            ent_class = -1;

        }
        if(ent_type == 'Door'){  btn_class = 'green'; }
        if(ent_type == 'Object'){  btn_class = 'yellow'; }

        if (ent_type == 'Client') {
            href_style = '';
            btn_class = 'blue';
        }
        if (ent_type == 'Corpse') {
            href_style = 'background-color: rgba(51, 51, 51, 0.6); color: #fff !important;';
            status_icon = '<img src="cust_assets/js/context/icons/headstone-rip.png" style="height:18px;width:auto">';
        }

        if (c_images[ent_class]) {
            class_icon = '<img src="cust_assets/icons/item_' + c_images[ent_class] + '.png" class="character_icon" style="width:20px !important;height:auto">';
        }
        if (r_images[ent_race]) {
            race_icon = '<img src="cust_assets/icons/item_' + r_images[ent_race] + '.png"  class="character_icon" style="width:20px !important;height:auto">';
        }

        if (npc_c_images[ent_class]) {
            class_icon = '<img src="cust_assets/js/context/icons/' + npc_c_images[ent_class] + '" class="character_icon" style="width:20px !important;height:auto">';
        }
        entity_search_list = entity_search_list + '<li style="padding-left:10px"><a href="javascript:;" onclick="SidebarShowEnt(' + ent_id + ');GoToEntity(' + ent_id + ');" class="btn btn-default mini btn-xs"><i class="fa fa-cog"></i></a> <a href="javascript:;" onclick="GoToEntity(' + ent_id + '); return false;" class="btn btn-default ' + btn_class + ' btn-xs entity_name" style="' + href_style + '"><span id="status_icon">' + status_icon + '</span> [' + ent_type + '] ' + class_icon + ' ' + race_icon + ' ' + ent_name + '</a></li>';
    }
    $("#entity_list").html(entity_search_list);
}
