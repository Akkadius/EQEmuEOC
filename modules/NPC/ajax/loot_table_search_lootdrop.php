<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/5/2015
 * Time: 3:19 AM
 */


    /* Loot Table :: Search for Loot Drop Tables */
    if($_GET['loottable_add']){
        $loot_table = $_GET['loottable_add'];
        $Content .= FormStart();
        $Content .= FormInput('Create New', '<a href="javascript:;" class="btn green btn-xs" onclick="do_create_new_lootdrop(' . $loot_table . ')"><i class="fa fa-plus"></i> Create New Lootdrop</a>');
        $Content .= FormInput('', 'OR');
        $Content .= FormInput('Lootdrop Search', '<input type="text" class="form-control" id="loot_search" onkeyup="if(event.keyCode == 13){ do_loot_search(this.value, ' . $loot_table . ') }">');
        $Content .= FormInput('',
            '<a href="javascript:;" class="btn green btn-xs" onclick="do_loot_search($(\'#loot_search\').val())">
                    <i class="fa fa-search"></i>
                    Search
                </a>
                ');
        $Content .= FormEnd();
        $Content .= '<div id="loot_search_result"></div>';
        echo Modal('Loot Table, Search for Lootdrop', $Content, '');
        echo '<script type="text/javascript" src="modules/NPC/ajax/loot_table_search_lootdrop.js"></script>';
    }
    /* Loot Table :: Loot Drop Table Search */
    if($_GET['do_loot_search']){
        $search_string = $_GET['do_loot_search'];
        $loot_table = $_GET['loot_table'];

        echo '<table class="table table-condensed table-hover table-bordered loot_table_sub">
                    <thead>
                        <tr>
                            <th style="width:50px"></th>
                            <th style="width:25px">ID</th>
                            <th style="width:20px;text-align:center"><small>Item<br>Count</small></th>
                            <th>Name</th>
                        </tr>
                    </thead>
                ';
        $result = mysql_query(
            "SELECT
            lootdrop.id,
            lootdrop.`name`,
            COUNT(lootdrop_entries.item_id) as item_count
            FROM
            lootdrop
            INNER JOIN lootdrop_entries ON lootdrop.id = lootdrop_entries.lootdrop_id
            WHERE `name` LIKE '%" . $search_string . "%'
            GROUP BY lootdrop.id
            ORDER BY item_count DESC
        ");

        while($row = mysql_fetch_array($result)) {
            echo '
                <tr>
                    <td style="text-align:center">
                        <a href="javascript:;" class="btn green btn-xs" onclick="do_loot_table_loot_drop_add(' . $loot_table . ', ' . $row['id'] . ')">
                            <i class="fa fa-plus"></i>
                            Add
                        </a>
                    </td>
                    <td style="text-align:center">' . $row['id'] . '</td>
                    <td style="text-align:center">' . $row['item_count'] . '</td>
                    <td>' . $row['name'] . '</td>
                </tr>';
        }
        echo '</table>';
        echo '<script type="text/javascript" src="modules/NPC/ajax/loot_table_search_lootdrop.js"></script>';
    }
    /* Loot Table :: DB Add Loot Drop to Loot Table */
    if($_GET['do_loot_table_loot_drop_add']) {
        $loot_table = $_GET['do_loot_table_loot_drop_add'];
        $loot_drop = $_GET['loot_drop'];
        $result = mysql_query('INSERT INTO `loottable_entries` (loottable_id, lootdrop_id, multiplier, probability, droplimit, mindrop)
            VALUES (' . $loot_table . ', ' . $loot_drop . ', 1, 100, 1, 1)');
        if(!$result){ echo mysql_error(); }
    }
    /* Loot Table :: DB Create new Unique Loot Drop and add it */
    if($_GET['do_create_new_lootdrop']){
        $loot_table = $_GET['do_create_new_lootdrop'];
        $next_id = GetNextAvailableIDInTable("lootdrop", "id");

        $result = mysql_query('REPLACE INTO `lootdrop` (id, name)
            VALUES (' . $next_id . ', \'EOC Created :: ' . mysql_real_escape_string(date('Y-m-d H:i:s')) . '\')');
        if(!$result){ echo mysql_error(); }

        $result = mysql_query('REPLACE INTO `loottable_entries` (loottable_id, lootdrop_id, multiplier, probability, droplimit, mindrop)
            VALUES (' . $loot_table . ', ' . $next_id . ', 1, 100, 1, 1)');
        if(!$result){ echo mysql_error(); }

        echo 'Created Lootdrop ID: ' . $next_id . ' and inserted it into Loot Table ID: ' . $loot_table;
    }
?>