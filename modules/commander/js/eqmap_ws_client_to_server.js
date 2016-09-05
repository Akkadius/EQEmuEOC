/**
 * Created by Akkadius on 12/7/2014.
 */


/*
 Client -> Server Handlers
 */

/* Repop Zone: Client -> Server */
function RepopZone() {
    socket.send(JSON.stringify({
        id: 'get_initial_entity_positions',
        method: 'Zone.GetInitialEntityPositions',
        params: [g_zone_id, g_instance_id]
    }));
    depop_repop_queue = 0;
    $.notific8('Zone repopped!', {heading: "Commander", theme: "ruby", life: 3000, horizontalEdge: "bottom"});
}

/* Adds Subscriptions for the map, initial populate and subscriptions: Client -> Server */
function ProcessZoneView(ZoneSN, zone_id, instance_id) {
    g_zone_id = zone_id;
    g_instance_id = instance_id;
    if (ZoneSN == "") {
        return;
    }
    $.ajax({url: "ajax.php?M=CM&GetZoneMap=" + ZoneSN, context: document.body}).done(function (xml) {
        eval($(xml).find("script").text());
        $('.page-content').html(xml);
        if (canvas_events_hooked != 1) {
            Hook_Canvas_Events();
        }
        /* Subscriptions */

        /* Subscribe: NPC Depops */
        socket.send(JSON.stringify({
            id: 'subscribe_id',
            method: 'Zone.Subscribe',
            params: [zone_id, instance_id, 'NPC.Depop']
        }));

        /* Subscribe: NPC Position Updates */
        socket.send(JSON.stringify({
            id: 'subscribe_id',
            method: 'Zone.Subscribe',
            params: [zone_id, instance_id, 'NPC.Position']
        }));

        /* Subscribe: Client Position Updates */
        socket.send(JSON.stringify({
            id: 'subscribe_id',
            method: 'Zone.Subscribe',
            params: [zone_id, instance_id, 'Client.Position']
        }));

        /* Subscribe: Client Combat States */
        socket.send(JSON.stringify({
            id: 'subscribe_id',
            method: 'Zone.Subscribe',
            params: [zone_id, instance_id, 'Combat.States']
        }));

        /* Subscribe: Entity Events */
        socket.send(JSON.stringify({
            id: 'subscribe_id',
            method: 'Zone.Subscribe',
            params: [zone_id, instance_id, 'Entity.Events']
        }));

        /* Grab Initial Entity Positions (Client/NPC/etc) */
        socket.send(JSON.stringify({
            id: 'get_initial_entity_positions',
            method: 'Zone.GetInitialEntityPositions',
            params: [zone_id, instance_id]
        }));
        $.notific8('Loading Map ' + ZoneSN + '...', {heading: "Commander", theme: "ruby", life: 3000});

        $('body, html').contextPopup();
        /* Context Menu Init */

    });
}

/* Helper function for dynamic args */
function def(providedValue, defaultValue) {return providedValue !== undefined ? providedValue : defaultValue;}

/* Generic Function Passer, will relay calls to */
function ZoneAction() {
    var Action = def(ZoneAction.arguments[0], '');
    var arg1 = def(ZoneAction.arguments[1], '');
    var arg2 = def(ZoneAction.arguments[2], '');
    var arg3 = def(ZoneAction.arguments[3], '');
    var arg4 = def(ZoneAction.arguments[4], '');
    var arg5 = def(ZoneAction.arguments[5], '');
    var arg6 = def(ZoneAction.arguments[6], '');

    socket.send(JSON.stringify({
        id: 'zone_action',
        method: 'Zone.Action',
        params: [g_zone_id, g_instance_id, "" + Action + "", "" + arg1 + "", "" + arg2 + "", "" + arg3 + "", "" + arg4 + "", "" + arg5 + "", "" + arg6 + ""]
    }));
    if (Action == "Repop" || Action == "RepopForce") {
        $.notific8('Issuing Zone Repop...', {
            heading: "Commander",
            theme: "ruby",
            life: 3000,
            horizontalEdge: "bottom"
        });
    }
    if (Action == "ReloadQuests") {
        $.notific8('Reloading Quests...', {heading: "Commander", theme: "ruby", life: 3000, horizontalEdge: "bottom"});
    }
}

/* Temporary testing function to troubleshoot any issues with dynamic args */
function ZoneCommand(Action, arg1, arg2) {
    socket.send(JSON.stringify({
        id: 'zone_action',
        method: 'Zone.Action',
        params: [g_zone_id, g_instance_id, "" + Action + "", "" + arg1 + "", "" + arg2 + ""]
    }));
}

/* Save Sky Zone Headers */
function SaveZoneHeaders() {
    socket.send(JSON.stringify({
        id: 'zone_action',
        method: 'Zone.Action',
        params: [g_zone_id, g_instance_id, "ZoneSaveHeaders"]
    }));
    $.notific8('Saving Zone Headers...', {heading: "Commander", theme: "ruby", life: 3000, horizontalEdge: "bottom"});
}

/* Kill Specific Entity */
function KillEntity(entity_killed) {
    socket.send(JSON.stringify({
        id: 'zone_action',
        method: 'Zone.Action',
        params: [g_zone_id, g_instance_id, "Kill", "" + entity_killed + ""]
    }));
    $.notific8('Killing Entity...', {heading: "Commander", theme: "ruby", life: 3000, horizontalEdge: "bottom"});
}

function SetEntAttribute(l_ent, attribute, l_val){
    socket.send(JSON.stringify({id: "set_entity_attribute",
        method: "Zone.SetEntityAttribute",
        params: [g_zone_id, g_instance_id, "" + l_ent + "", "" + attribute + "", "" + l_val + ""]}));
}