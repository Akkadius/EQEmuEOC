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
            .spawn_table table tbody td input{ text-align:center; height:20px !important; }
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
                <table class="table table-condensed table-hover table-bordered spawn_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Spawn Limit</th>
                            <th>Dist</th>
                            <th>Min X</th>
                            <th>Max X</th>
                            <th>Mix Y</th>
                            <th>Max Y</th>
                            <th>Delay</th>
                            <th>Min Delay</th>
                            <th>Despawn</th>
                            <th>Despawn Timer</th>
                        </tr>
                    </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawngroup` WHERE `id` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr>
                    <td style="background-color:yellow">' . $row['id'] . '</td>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['spawn_limit'] . '</td>
                    <td>' . $row['dist'] . '</td>
                    <td>' . $row['min_x'] . '</td>
                    <td>' . $row['max_x'] . '</td>
                    <td>' . $row['min_y'] . '</td>
                    <td>' . $row['max_y'] . '</td>
                    <td>' . $row['delay'] . '</td>
                    <td>' . $row['mindelay'] . '</td>
                    <td>' . $row['despawn'] . '</td>
                    <td>' . $row['despawn_timer'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        /* Spawn2 */
        $Content .= '<h3>Spawn2</h3>';
        $Content .= '
                <table class="table table-condensed table-hover table-bordered spawn_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Spawn Group ID</th>
                            <th>Zone</th>
                            <th>Version</th>
                            <th>X</th>
                            <th>Y</th>
                            <th>Z</th>
                            <th>Heading</th>
                            <th>Respawn Time</th>
                            <th>Variance</th>
                            <th>Path Grid</th>
                            <th>Condition</th>
                            <th>Condition Value</th>
                            <th>Enabled</th>
                            <th>Animation</th>
                        </tr>
                    </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawn2` WHERE `spawngroupID` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr>
                    <td>' . $row['id'] . '</td>
                    <td style="background-color:yellow">' . $row['spawngroupID'] . '</td>
                    <td>' . $row['zone'] . '</td>
                    <td>' . $row['version'] . '</td>
                    <td>' . $row['x'] . '</td>
                    <td>' . $row['y'] . '</td>
                    <td>' . $row['z'] . '</td>
                    <td>' . $row['heading'] . '</td>
                    <td>' . $row['respawntime'] . '</td>
                    <td>' . $row['variance'] . '</td>
                    <td>' . $row['pathgrid'] . '</td>
                    <td>' . $row['_condition'] . '</td>
                    <td>' . $row['cond_value'] . '</td>
                    <td>' . $row['enabled'] . '</td>
                    <td>' . $row['animation'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        /* Spawn Entry */
        $Content .= '<h3>Spawn Entry</h3>';
        $Content .= '
                <table class="table table-condensed table-hover table-bordered spawn_table">
                    <thead>
                        <tr>
                            <th>NPC ID</th>
                            <th>Spawn Group ID</th>
                            <th>Chance</th>
                        </tr>
                    </thead>
        ';

        $result = mysql_query("SELECT * FROM `spawnentry` WHERE `spawngroupID` = " . $spawn_group_id);
        while ($row = mysql_fetch_array($result)) {
            $Content .= '
                <tr>
                    <td style="background-color:orange">' . $row['npcID'] . '</td>
                    <td style="background-color:yellow">' . $row['spawngroupID'] . '</td>
                    <td>' . $row['chance'] . '</td>
                </tr>';
        }
        $Content .= '</table>';

        $Content .= '<script type="text/javascript" src="modules/NPC/ajax/spawn_editor.js"></script>';
        echo Modal('Spawn Editor', $Content, '');
    }

?>