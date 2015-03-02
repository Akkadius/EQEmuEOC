<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/1/2015
 * Time: 11:52 PM
 */

    /* List Zone NPCS :: Grid Editor */
    if(isset($_GET['show_npc_table'])){
        /* Parse Columns */
        $npc_cols = array();
        $npc_a_cols = array();
        $query = "SHOW COLUMNS FROM `npc_types`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            if($row['Field'] == "hp"){
                $field = "Hit Points";
            }
            else if($npc_fields[$row['Field']]){
                $field = $npc_fields[$row['Field']];
            }
            else{ }
            $field = $row['Field'];
            array_push ($npc_cols, $field);
            array_push ($npc_a_cols, $row['Field']);
        }

        /* NPC Name Filter */
        $npc_filter = "";
        if($_GET['npc_filter'] != ""){
            $npc_filter = " AND
                    (npc_types.`name` LIKE '%" . mysql_real_escape_string($_GET['npc_filter']) . "%' OR
                    npc_types.`id` = '" . mysql_real_escape_string($_GET['npc_filter']) . "'
                    )
                ";
        }

        echo '<style>
                #shownpczone table tbody tr{ height:10px !important; }
                #shownpczone table tbody td{ padding: 1px !important; text-align:center; }
                #shownpczone table tbody td input{ text-align:center; }
            </style>';

        # echo var_dump($_GET);

        /* Get NPC List */
        if($_GET['Zone'] == "0") {
            $query = "SELECT
                    npc_types.*
                    FROM
                    npc_types
                    WHERE id > 0
                    " . $npc_filter . "
                    GROUP BY npc_types.id
                    ORDER BY npc_types.id";
        }
        else {
            $query = "SELECT
                    npc_types.*
                    FROM
                    npc_types
                    Inner Join spawnentry ON npc_types.id = spawnentry.npcID
                    Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
                    WHERE spawn2.zone = '" . $_GET['Zone'] . "' AND spawn2.version = " . $_GET['inst_version'] . " " . $npc_filter . "
                    GROUP BY npc_types.id
                    ORDER BY npc_types.id";
        }
        $result = mysql_query($query);

        #echo $query;
        #exit;

        # echo $query . '<br>' . mysql_error();
        $RowData = array();

        /* Print NPC List Table */
        echo '<table class="npc_data_table table table-striped table-hover table-condensed flip-content dataTable table-bordered dataTable" id="npc_head_table" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th></th>';
        foreach ($npc_cols as $key => $val){
            echo '<th>' . $val . '</th>';
        }
        echo '</tr>';
        echo '</thead>';

        echo '<tbody>';
        while($row = mysql_fetch_array($result)){
            echo '<tr npc_row_id_' . $row['id'] . '="1">';
            echo '<td>
                    <button type="button" class="btn btn-default btn-sm red btn-xs" onclick="do_npc_delete(' . $row['id'] . ')" title="Delete NPC"><i class="fa fa-times"></i> </button>
                    <button type="button" class="btn btn-default btn-sm green btn-xs" onclick="do_npc_edit(' . $row['id'] . ')" title="Edit NPC"><i class="fa fa-edit"></i> </button>
                    <button type="button" class="btn btn-default btn-sm btn-xs" onclick="do_npc_copy(' . $row['id'] . ')" title="Copy NPC"><i class="fa fa-sign-in"></i> </button>
                </td>';
            foreach ($row as $key => $val){
                if(is_numeric($key)){
                    # echo $key . ' ' . $val . '<br>';
                    /* Stuff the cells with info */
                    echo '<td npc_db_field="' . mysql_field_name ($result, $key) . '" npc_id="' . $row['id'] . '" ' . $row['id'] . '-' . mysql_field_name ($result, $key) . '="1">' . $val . '</td>';
                    $n++;
                }
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        /* JS Assets */
        $FJS .= '<script type="text/javascript" language="javascript" src="cust_assets/js/datatables/media/js/jquery.dataTables.js"></script>';
        $FJS .= '<script type="text/javascript" language="javascript" src="cust_assets/js/datatables/extensions/ColVis/js/dataTables.colVis.js"></script>';
        $FJS .= '<script type="text/javascript" language="javascript" src="cust_assets/js/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>';
        $FJS .= '<script type="text/javascript" src="modules/NPC/ajax/npc_table.js"></script>';

        # print $ret;
        echo $FJS;
    }


?>