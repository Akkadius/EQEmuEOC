<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/8/2015
 * Time: 7:54 AM
 */

    /* Spawn Editor */
    if(isset($_GET['do_spawn_editor'])){
        $npc_id = $_GET['do_spawn_editor'];

        echo '<style>
            .spawn_table table tbody th{ font-size:12px !important; }
            .spawn_table table tbody tr{ height:10px !important; }
            .spawn_table td{ padding: 1px !important; text-align:center !important;  }
            .spawn_table input{ text-align:center; height:20px !important; width:100% !important;  }
            .spawn_table th { padding:1px !important; font-size: 12px !important; text-align:center }
        </style>';

        /* Get spawnentry info */
        $result = mysql_query("SELECT * FROM `spawnentry` WHERE `npcID` = " . $npc_id);
        $spawn_entry = array();
        while ($row = mysql_fetch_array($result)) {
            $spawn_entry = $row;
        }

        $spawn_group_id = $spawn_entry['spawngroupID'];

        /* Spawn Group */
        $Content .= '<h3>Spawn Group</h3>';
        $Content .= '
            <table class="table table-condensed table-hover table-bordered spawn_table spawn_group">
                <thead>
                    <tr>
                        <th style="width:100px">ID</th>
                        <th style="width:100px">Name</th>
                        <th style="width:100px">Spawn Limit</th>
                        <th style="width:100px">Dist</th>
                        <th style="width:100px">Min X</th>
                        <th style="width:100px">Max X</th>
                        <th style="width:100px">Mix Y</th>
                        <th style="width:100px">Max Y</th>
                        <th style="width:100px">Delay</th>
                        <th style="width:100px">Min Delay</th>
                        <th>Despawn</th>
                        <th>Despawn Timer</th>
                    </tr>
                </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawngroup` WHERE `id` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr db_table="spawngroup" db_key="id" db_key_val="' . $row['id'] . '">
                    <td style="background-color:yellow" nonedit="1">' . $row['id'] . '</td>
                    <td field_name="name">' . $row['name'] . '</td>
                    <td field_name="spawn_limit">' . $row['spawn_limit'] . '</td>
                    <td field_name="dist">' . $row['dist'] . '</td>
                    <td field_name="min_x">' . $row['min_x'] . '</td>
                    <td field_name="max_x">' . $row['max_x'] . '</td>
                    <td field_name="min_y">' . $row['min_y'] . '</td>
                    <td field_name="max_y">' . $row['max_y'] . '</td>
                    <td field_name="delay">' . $row['delay'] . '</td>
                    <td field_name="mindelay">' . $row['mindelay'] . '</td>
                    <td field_name="despawn">' . $row['despawn'] . '</td>
                    <td field_name="despawn_timer">' . $row['despawn_timer'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        /* Spawn2 */
        $Content .= '<h3>Spawn2</h3>';
        $Content .= '
            <table class="table table-condensed table-hover table-bordered spawn_table spawn2">
                <thead>
                    <tr>
                        <th style="width:100px">ID</th>
                        <th style="width:100px">Spawn Group ID</th>
                        <th style="width:150px">Zone</th>
                        <th style="width:100px">Version</th>
                        <th style="width:130px">X</th>
                        <th style="width:130px">Y</th>
                        <th style="width:130px">Z</th>
                        <th style="width:170px">Heading</th>
                        <th style="width:100px">Respawn Time</th>
                        <th style="width:100px">Variance</th>
                        <th style="width:100px">Path Grid</th>
                        <th style="width:100px">Condition</th>
                        <th style="width:100px">Condition Value</th>
                        <th>Enabled</th>
                        <th>Animation</th>
                    </tr>
                </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawn2` WHERE `spawngroupID` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr db_table="spawn2" db_key="id,spawngroupID" db_key_val="' . $row['id'] . ',' . $row['spawngroupID'] . '">
                    <td nonedit="1">' . $row['id'] . '</td>
                    <td style="background-color:yellow" nonedit="1">' . $row['spawngroupID'] . '</td>
                    <td field_name="zone">' . $row['zone'] . '</td>
                    <td field_name="version">' . $row['version'] . '</td>
                    <td field_name="x">' . $row['x'] . '</td>
                    <td field_name="y">' . $row['y'] . '</td>
                    <td field_name="z">' . $row['z'] . '</td>
                    <td field_name="heading">' . $row['heading'] . '</td>
                    <td field_name="respawntime">' . $row['respawntime'] . '</td>
                    <td field_name="variance">' . $row['variance'] . '</td>
                    <td field_name="pathgrid">' . $row['pathgrid'] . '</td>
                    <td field_name="_condition">' . $row['_condition'] . '</td>
                    <td field_name="cond_value">' . $row['cond_value'] . '</td>
                    <td field_name="enabled">' . $row['enabled'] . '</td>
                    <td field_name="animation">' . $row['animation'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        /* Spawn Entry */
        $Content .= '<h3>Spawn Entry</h3>';
        $Content .= '
                <table class="table table-condensed table-hover table-bordered spawn_table spawn_entry">
                    <thead>
                        <tr>
                            <th style="width:150px">NPC ID</th>
                            <th style="width:150px">Spawn Group ID</th>
                            <th>Chance</th>
                        </tr>
                    </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawnentry` WHERE `spawngroupID` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr db_table="spawnentry" db_key="spawngroupID,npcID" db_key_val="' . $row['spawngroupID'] . ',' . $row['npcID'] . '">
                    <td style="background-color:orange" nonedit="1">' . $row['npcID'] . '</td>
                    <td style="background-color:yellow" nonedit="1">' . $row['spawngroupID'] . '</td>
                    <td field_name="chance">' . $row['chance'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        $Content .= '<script type="text/javascript" src="modules/NPC/ajax/spawn_editor.js"></script>';
        echo Modal('Spawn Editor', $Content, '');
    }
    /* Handle field updates from spawn editor tables */
    if(isset($_GET['do_spawn_edit_update'])){
        $table_name = mysql_real_escape_string($_GET['do_spawn_edit_update']);
        $field = mysql_real_escape_string($_GET['field']);
        $value = mysql_real_escape_string($_GET['value']);
        $db_key = $_GET['db_key'];
        $db_key_val = $_GET['db_key_val'];


        if(preg_match('/,/i', $_GET['db_key'])){
            $db_key = explode(",", $db_key);
            $db_key_val = explode(",", $db_key_val);
            $filter = $db_key[0] . " = " . $db_key_val[0] . " AND " . $db_key[1] . " = " . $db_key_val[1];
        }
        else{
            $filter = $db_key . " = " . $db_key_val;
        }

        mysql_query("UPDATE `" . $table_name . "` SET `" . $field . "` = " . $value . " WHERE " . $filter);
        # echo "UPDATE `" . $table_name . "` SET `" . $field . "` = " . $value . " WHERE " . $filter;
        echo mysql_error();
    }

?>