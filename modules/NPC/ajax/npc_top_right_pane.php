<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/1/2015
 * Time: 11:43 PM
 */

    /* Display NPC Data in the top right pane */
    if($_GET['load_npc_top_pane_dash']){

        $result = mysql_query("SELECT * FROM `npc_types` WHERE `id` = " . $_GET['load_npc_top_pane_dash']);
        $npc_types = array();
        while($row = mysql_fetch_array($result)){ $npc_types = $row; }
        # p_var_dump($npc_types);

        /* Load Race Image */
        if(file_exists("cust_assets/races/" . $npc_types['race'] . ".jpg")) {
            $race_img = "cust_assets/races/" . $npc_types['race'] . ".jpg";
        }
        else if (file_exists("cust_assets/races/Race (" . $npc_types['race'] . ").png")) {
            $race_img = "cust_assets/races/Race (" . $npc_types['race'] . ").png";
        }
        if($race_img != '') {
            $race_panel_image = '  <span class="image-wrap">
                    <img src="' . $race_img . '" id="' . $npc_types['race'] . '"  style="height:150px;width:auto;">
                </span>
            ';
        }

        $result = mysql_query("SELECT * FROM `loottable` WHERE `id` = " . $npc_types['loottable_id']);
        $loot_table = array();
        while($row = mysql_fetch_array($result)){ $loot_table = $row; }
        # p_var_dump($loot_table);
        # echo $npc_types['loottable_id'] . '<br>';
        # echo var_dump($loot_table);

        echo '<style>
                #top_right_pane table tbody tr{ height:10px !important; }
                #top_right_pane table tbody td{ padding: 1px !important; text-align:center;  }
                #top_right_pane table tbody td{ padding: 1px !important; text-align:center; }
                #top_right_pane table tbody td input{ text-align:center; }
            </style>';

        /* Loot Table Entries */
        echo '
                <table style="width:175px !important">
                    <tr>
                        <td valign="top" style="text-align:center;width:175px !important">
                            <center>
                            <table style="width:175px !important">
                                <tr>
                                    <td style="padding:5px !important;text-align:center;">
                                        ' . $race_panel_image . '<br>
                                        <b>' . $npc_types['name'] . '<br>
                                        ' . $npc_types['id'] . '
                                        </b>
                                    </td>
                                </tr>
                            </table>
                            </td>
                            <td valign="top" style="text-align:left;width:400px !important">


                                <a href="javascript:;" class="btn green btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Add Lootdrop
                                </a>

                                <span class="label label-danger" style="font-weight:bold"> Loot Table ID: ' . $npc_types['loottable_id'] . '</span>

                                <br><br>

                    <table class="table table-condensed table-hover table-bordered loottable_entries ">
                        <thead>
                            <tr>
                                <th>Loot Drop ID</th>
                                <th>Multiplier</th>
                                <th>Probability</th>
                                <th>Droplimit</th>
                                <th>Min Drop</th>
                            </tr>
                        </thead>
            ';

        $result = mysql_query("SELECT * FROM `loottable_entries` WHERE `loottable_id` = " . $npc_types['loottable_id'] . " AND `loottable_id` > 0");
        while($row = mysql_fetch_array($result)){
            echo '
                    <tr loot_table="' . $npc_types['loottable_id'] . '" loot_drop="' . $row['lootdrop_id'] . '">
                        <td loot_drop="' . $row['lootdrop_id'] . '" nonedit="1">' . $row['lootdrop_id'] . '</td>
                        <td loot_drop="' . $row['lootdrop_id'] . '" field_name="multiplier">' . $row['multiplier'] . '</td>
                        <td loot_drop="' . $row['lootdrop_id'] . '" field_name="probability">' . $row['probability'] . '</td>
                        <td loot_drop="' . $row['lootdrop_id'] . '" field_name="droplimit">' . $row['droplimit'] . '</td>
                        <td loot_drop="' . $row['lootdrop_id'] . '" field_name="mindrop">' . $row['mindrop'] . '</td>
                    </tr>';
        }

        echo '</table>';

        if($npc_types['loottable_id'] <= 0){
            echo 'No loot data present';
        }
        else{
            echo '

                ';
        }

        /* Begin Loot table pane */
        echo '</td><td valign="top" style="text-align:left;padding-left:10px !important;">';

        echo '<div id="lootdrop_entries" style="display:inline"></div>';

        echo '</td></tr>
                </table>';

        $FJS .= '<script type="text/javascript" src="modules/NPC/ajax/npc_top_right_pane.js"></script>';
        echo $FJS;
    }
    /*
        Lootdrop Entries call from Loot table row select
    */
    if($_GET['show_lootdrop_entries']){
        echo '<style>
                .lootdrop_entries table { width: 700px !important; }
                .lootdrop_entries table tbody tr{ height:10px !important; }
                .lootdrop_entries table tbody td{ padding: 1px !important; text-align:center;  }
                .lootdrop_entries table tbody td{ padding: 1px !important; text-align:center; }
                .lootdrop_entries table tbody td input{ text-align:center; }
                .lootdrop_entries th { font-size:13px !important; padding: 5px !important; }
            </style>';

        /* Loot Drop Entries */
        echo '
            <a href="javascript:;" class="btn green btn-xs" onclick="loot_drop_add_item(' . $_GET['show_lootdrop_entries'] . ')">
                <i class="fa fa-plus"></i>
                Add Item
            </a>

            <span class="label label-danger" style="font-weight:bold; height:15px !important;position:relative;right:0px"> Loot Drop ID: ' . $_GET['show_lootdrop_entries'] . '</span>
        <br>';

        echo '
                <table class="table table-condensed table-hover table-bordered lootdrop_entries">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Item ID</th>
                            <th style="width:200px !important">Name</th>
                            <th>Equipped</th>
                            <th>% Chance</th>
                            <th>Min LVL</th>
                            <th>Max LVL</th>
                            <th>Multiplier</th>
                        </tr>
                    </thead>
                ';
        $result = mysql_query(
            "SELECT
                lootdrop_entries.*,
                items.id,
                items.`Name`,
                items.icon
                FROM
                lootdrop_entries
                INNER JOIN items ON lootdrop_entries.item_id = items.id
                WHERE `lootdrop_id` = " . $_GET['show_lootdrop_entries']);

        while($row = mysql_fetch_array($result)){
            echo '
                    <tr loot_table="' . $row['loottable_id'] . '">
                        <td>
                            <button type="button" class="btn badge btn-default btn-sm red btn-xs" onclick="do_lootdrop_delete(' . $_GET['show_lootdrop_entries'] . ', ' . $row['item_id'] . ')" title="Delete Item from Lootdrop"><i class="fa fa-times"></i> </button>
                        </td>
                        <td>' . $row['item_id'] . '</td>
                        <td style="text-align:left">
                            <img class="lazy" data-original="cust_assets/icons/item_622.png" style="height:15px;width:auto" src="cust_assets/icons/item_' . $row['icon'] . '.png" style="display: inline;">
                            <a href="javascript:;" ' . HoverTip("global.php?item_view=" . $row['id']) . '>' . $row['Name'] . '</a>
                        </td>
                        <td>' . $row['equip_item'] . '</td>
                        <td>' . $row['chance'] . '</td>
                        <td>' . $row['minlevel'] . '</td>
                        <td>' . $row['maxlevel'] . '</td>
                        <td>' . $row['multiplier'] . '</td>
                    </tr>';
        }
        echo '</table>

        ';
        $FJS .= '<script type="text/javascript" src="modules/NPC/ajax/npc_top_right_pane.js"></script>';
        echo $FJS;
    }

    /* Display Form to queue adding an item */
    if(isset($_GET['loot_drop_add_item'])){
        echo '<style>
            .iframe_seamless{
                background-color: transparent;
                border: 0px none transparent;
                padding: 0px;
                overflow: hidden;
            }
        </style>';

        require_once("modules/NPC/ajax/item_search_lootdrop.php");
        $content_out .= '<script type="text/javascript" src="modules/NPC/ajax/item_search_lootdrop.js"></script>';
        echo Modal('Add Item to Lootdrop', $content_out, '');
    }

    if(isset($_GET['item_search_lootdrop'])) {
        require_once("modules/NPC/ajax/item_search_lootdrop.php");
    }

    /* Lootdrop :: Delete Item :: Confirmation Window */
    if($_GET['do_lootdrop_delete']){
        $loot_drop = $_GET['do_lootdrop_delete'];
        $item_id = $_GET['item_id'];
        $Content .= '
			<center>
				<button type="button" class="btn btn-default btn-sm red btn-xs" onclick="do_lootdrop_delete_confirmed(' . $loot_drop . ', ' . $item_id . ')"><i class="fa fa-times"></i> Confirm Delete </button>
			</center>';
        echo Modal('Lootdrop Item Removal Confirm', $Content, '');
    }
    /* Lootdrop :: Delete Item from DB */
    if($_GET['do_lootdrop_delete_confirmed']){
        $loot_drop = $_GET['do_lootdrop_delete_confirmed'];
        $item_id = $_GET['item_id'];
        $result = mysql_query(
            "DELETE FROM `lootdrop_entries` WHERE `lootdrop_id` = " . $loot_drop . " AND `item_id` = " . $item_id);
        if(!$result){
            echo mysql_error();
        }
    }
    /* Save Loot Table Field Values */
    if(isset($_GET['update_loottable'])){
        $loot_table = $_GET['update_loottable'];
        $loot_drop = $_GET['loot_drop'];
        $db_field = $_GET['field'];
        $db_value = $_GET['value'];

        $result = mysql_query(
            "UPDATE `loottable_entries` SET
            " . $db_field . " = " . $db_value . "
            WHERE loottable_id = " . $loot_table . "
            AND lootdrop_id = " . $loot_drop . "
        ");
    }
    /* Add Loot entry to lootdrop */
    if(isset($_GET['db_loot_drop_add_item'])){
        $loot_drop = $_GET['loot_drop'];
        $item_id = $_GET['db_loot_drop_add_item'];

        $result = mysql_query(
            'REPLACE INTO `lootdrop_entries`
            (lootdrop_id, item_id, item_charges, equip_item, chance, minlevel, maxlevel, multiplier)
            VALUES
            (' . $loot_drop . ', ' . $item_id . ', 1, 1, 100, 0, 255, 1)
            ');
        echo mysql_error();
    }
?>