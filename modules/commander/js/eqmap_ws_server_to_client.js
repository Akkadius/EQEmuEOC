/**
 * Created by Akkadius on 12/7/2014.
 */

/*
    Server -> Client Event Handlers
*/

function GetZoneInfoFromListZone(json){
    r = json.result;
    total_players += parseInt(r['player_count']);
    $('#total_players').html(total_players);
    if (r['long_name'] == "") {
        r['long_name'] = "Sleeping";
    }
    $('#zone_servers_list tr:last').after('<tr><td style="text-align:center"><button type="button" class="btn btn-default btn-block" onclick="ProcessZoneView(\'' + r['short_name'] + '\', \'' + r['zone_id'] + '\', \'' + r['instance_id'] + '\')">' + r['long_name'] + ' (' + r['type'] + ')</button></td><td> <i class="fa fa-users"></i> ' + r['player_count'] + '</td><td>' + r['port'] + '</td><td>' + r['short_name'] + '</td><td>' + r['zone_id'] + '<td>' + r['instance_id'] + '</td></td></tr>');
}

function HandleListZones(json){
    for (var key in json.result) {
        if (json.result.hasOwnProperty(key)) {
            var str = JSON.stringify({
                id: 'get_zone_info_id',
                method: 'World.GetZoneDetails',
                params: [json.result[key]]
            });
            socket.send(str);
        }
        zone_server_count++;
    }
    $('#zone_server_count').html('Zone Server(s): ' + zone_server_count);
}

function OnNPCDepop(json){
    if (depop_repop_queue != 1) {
        depop_repop_queue = 1;
        // $.notific8('Zone depopped!', { heading: "Commander", theme: "ruby", life: 3000 });
        var Repop_Timer = setTimeout(function () {
            RepopZone()
        }, 500);
    }
    json.entity = json.params[0];
    $('#ent_' + json.entity).fadeOut(200, function () {
        $(this).remove();
    });
}

function OnEntityEvent(json) {
    var entity = null;
    switch (json.params[0]) {
        case "0": /* Depop Entity */
            var ent = json.params[1];
            entity = '#ent_' + ent;
            var MadeCorpse = json.params[2];
            // console.log('Processing death event');
            if (MadeCorpse == 1) {
                $(entity + ' a').css('background-color', '#333').css('color', '#fff');
                $(entity + ' a').append('\'s corpse');
                $(entity + ' a span').html('<img src="cust_assets/js/context/icons/headstone-rip.png" style="height:18px;width:auto">');
            }
            else {
                $(entity + ' a').css('background-color', '#333').css('color', '#fff');
                $(entity + ' a span').html('<img src="cust_assets/js/context/icons/headstone-rip.png" style="height:18px;width:auto">');
                $(entity).fadeOut(4000, function () {
                    $(this).remove();
                });
                $(entity).unbind("mouseleave");
                $(entity).unbind("mouseenter");
            }
            break;
        /* Depop Corpse */
        case "1":
            ent = json.params[1];
            entity = '#ent_' + ent;
            $(entity).fadeOut(4000, function () {
                $(this).remove();
            });
            $(entity).unbind("mouseleave");
            $(entity).unbind("mouseenter");
            break;
        default:
    }
}

/* Process position updates for all entities */
function OnPositionUpdate(json){
    if (json.method == 'On.Client.Position') {
        // $('html, body').scrollTop(($('#ent_' + json.params[2]).offset().top - 500));
        // $('html, body').scrollLeft(($('#ent_' + json.params[2]).offset().left - 500));
    }
    var btn_class = '';
    var ent_class = 0;
    var ent_race = 0;
    var status_icon = '';
    var href_style = '';
    var aggro_radius = -1;
    var aggro_bubble = '';

    /*
         Called from Zone.GetInitialEntityPositions

         We're extracting variables received from the server here to set what we use when we construct the
         entity on the map
     */
    if (json.id == 'get_initial_entity_positions') {
        if(json.type == "Door" || json.type == "Object"){
            json.entity = json.result.ent_id;
            json.name = json.result.name;
            json.type = json.result.type;
            json.x = json.result.x;
            json.y = json.result.y;
            json.z = json.result.z;
            json.h = json.result.h;
            ent_class = -1;
            ent_race = -1;
        }
        else {
            json.entity = json.result.ent_id;
            json.name = json.result.name;
            json.type = json.result.type;
            json.x = json.result.x;
            json.y = json.result.y;
            json.z = json.result.z;
            json.h = json.result.h;
            ent_class = json.result.class_id;
            ent_race = json.result.race_id;
            if(json.type == 'NPC'){
                // console.log(json.result.aggro_range);
                aggro_radius = json.result.aggro_range;
                // aggro_radius = 12;
                // top:' + (aggro_radius * 1.2) + '%;left:-' + (aggro_radius * .10) + '%;
                aggro_bubble = '<i class="fa fa-circle-thin" style="position:absolute;top:0%;left:-' + (aggro_radius * 7) + '%;font-size:' + round(aggro_radius * 2.4, 0) + 'px;color:red !important;opacity:.3"></i>';
            }
        }
        if(json.type == 'Door'){  btn_class = 'green'; }
        if(json.type == 'Object'){  btn_class = 'yellow'; }
        /* Store Entity List Cache */
        entity_list_cache[entity_list_index] = [json.entity, json.name, json.type, ent_class, ent_race];
        entity_list_index++;
    }
    else {
        json.entity = json.params[0];
        json.name = json.params[1];
        json.x = json.params[2];
        json.y = json.params[3];
        json.z = json.params[4];
        json.h = json.params[5];
        ent_class = Math.round(json.params[6]);
        ent_race = Math.round(json.params[7]);
        if (json.params[2] == selected_entity_id) {
            return;
        }
    }

    /* Follow Camera */
    if (follow_entity_id > 0) {
        var entity2follow = '#ent_' + follow_entity_id;
        if (follow_iter > ent_follow_update_rate) {
            $('html, body').animate({
                scrollTop: $(entity2follow).offset().top - ($(window).height() / 2),
                scrollLeft: $(entity2follow).offset().left - ($(window).width() / 2)
            }, 300);
            follow_iter = 0;
        }
        follow_iter++;
    }

    /* If Entity exists, update their heading and position */
    var entity = '#ent_' + json.entity;
    if ($(entity).length > 0) {
        $(entity).css('left', (left_offset - json.x));
        $(entity).css('top', (top_offset - json.y));
        $(entity).data('data-x', json.x);
        $(entity).data('data-y', json.y);
        $('#ent_header_' + json.entity).css({
            '-webkit-transform': 'rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg)',
            '-moz-transform': 'rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg)',
            '-o-transform': 'rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg)',
            '-ms-transform': 'rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg)',
            'transform': 'rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg)'
        });
    }
    /* Draw new entity if it does not exist */
    else {
        href_style = 'opacity:.8';

        /* On Position Update */
        if (json.method == 'On.Client.Position') {
            btn_class = 'blue';
        }

        /* Get Initial Entity Positions */
        if (json.type == 'NPC') {
            btn_class = 'red';
        }
        if (json.type == 'Client') {
            href_style = '';
            btn_class = 'blue';
        }
        if (json.type == 'Corpse') {
            href_style = 'background-color: rgba(51, 51, 51, 0.6); color: #fff !important;';
            status_icon = '<img src="cust_assets/js/context/icons/headstone-rip.png" style="height:18px;width:auto">';
        }

        /* Get Class and Race Icon */
        var class_icon = "";
        var race_icon = "";

        if (c_images[ent_class]) {
            class_icon = '<img src="cust_assets/icons/item_' + c_images[ent_class] + '.png" class="character_icon" style="width:20px !important;height:auto">';
        }
        if (r_images[ent_race]) {
            race_icon = '<img src="cust_assets/icons/item_' + r_images[ent_race] + '.png"  class="character_icon" style="width:20px !important;height:auto">';
        }

        if (npc_c_images[ent_class]) {
            class_icon = '<img src="cust_assets/js/context/icons/' + npc_c_images[ent_class] + '" class="character_icon" style="width:20px !important;height:auto">';
        }

        /* Draw Entity Canvas on Map */
        $("#map_canvas").append(
            '<span id="ent_' + json.entity + '" class="fadeIn ent_container" style="position:absolute;left:' + (left_offset - json.x) + 'px;top:' + (top_offset - json.y) + 'px;" data-x="' + json.x + '" data-y="' + json.y + '">' +
            '<i id="ent_header_' + json.entity + '" class="fa fa-chevron-circle-up" style="color: #333;-webkit-transform:rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg);-moz-transform:rotate(' + CalcEQHeadingToBrowser(json.h) + 'deg);display:inline-block">' + aggro_bubble + '</i> '  +
            '<a href="javascript:;" onclick="SidebarShowEnt(' + json.entity + ')" class="btn btn-default ' + btn_class + ' btn-xs entity_name" style="' + href_style + '"><span id="status_icon">' + status_icon + '</span>' + class_icon + ' ' + race_icon + ' ' + json.name + '</a></span>');

        /* Hook events to new entity */
        if (json.type == 'Client') {
            $(entity).bind("mouseenter", function () {
                in_ent = 2;
                highlighted_entity_id = json.entity;
            });
        }
        else {
            $(entity).bind("mouseenter", function () {
                in_ent = 3;
                highlighted_entity_id = json.entity;
            });
        }
        $(entity).bind("mouseleave", function () {
            in_ent = 0;
            highlighted_entity_id = 0;
        });
    }
}

/* Processes Combat State for Client */
function OnClientCombatState(json) {
    json.entity = json.params[0];
    if ($('#ent_' + json.entity).length > 0) {
        var options = {};
        if (json.params[1] == 1) { /* Attack State ON */
            $('#ent_header_' + json.entity + '').css('color', 'red');
            $('#ent_' + json.entity + ' a span').html('<img src="cust_assets/images/attack_icon.png" style="height:18px;width:auto">');
            $('#ent_' + json.entity + ' a').addClass('red');
        }
        if (json.params[1] == 0) {
            $('#ent_' + json.entity + ' a').removeClass('red');
            $('#ent_header_' + json.entity + '').css('color', '#333');
            $('#ent_' + json.entity + ' a span').html('');
        }
    }
}