<?php

/**
 * User: Akkadius
 * Date: 2013
 */

	require_once("modules/NPC/functions.php");
	require_once('includes/constants.php');
	require_once('includes/functions.php');

    /* sub ajax call files to sectionize things a bit */
    require_once('modules/NPC/ajax/npc_top_right_pane.php');
    require_once('modules/NPC/ajax/npc_special_attacks.php');
    require_once('modules/NPC/ajax/npc_table.php');
    require_once('modules/NPC/ajax/single_npc_edit.php');

	/* Modal: Mass Edit Window */
	if(isset($_GET['MassEdit'])){
		$Content .= '<table class="table" style="width:300px">
			<tr><td style="text-align:right;"></td><td>This very powerful tool will edit entire columns based on all of the NPC\'s shown in the parent window...
				<br> 1st, select the field you want to mass edit, and then select your method of mass value changing, you <br>can either use \'Set all Fields to Value\'
				or use the \'Min/Max\' function below.<br><br></td></tr>
			<tr><td style="text-align:right;">Field</td><td style="width:50px">' . GetNPCTypesSelector() . '</td></tr>
			<tr><td style="text-align:right;">Set All Fields to Value</td><td><input class="btn btn-default" type="text" id="massfieldvalue"><input type="button" value="Execute" class="btn btn-default green" onclick="SetFieldMassValue()" title="Click this when you are ready to execute"></td></tr>
			<tr><td style="text-align:right;">OR</td><td>Set Field Values with a Random Min/Max Range</td></tr>
			<tr><td style="text-align:right;font-size:13px;">Min </td><td><input class="form-control btn btn-default" type="text" id="minfieldvalue"></td></tr>
			<tr><td style="text-align:right;font-size:13px;">Max </td><td><input class="form-control btn btn-default" type="text" id="maxfieldvalue"></td></tr>
			<tr><td style="text-align:right;"></td><td><input class="form-control btn btn-default green" type="button" value="Execute" onclick="SetFieldMassValueMinMax()" title="Click this when you are ready to execute"></td></tr>
		</table>';
		echo Modal('Mass Field Editor', $Content, '');
	}

	/* Modal: Confirm NPC Delete */
	if($_GET['npc_delete_confirm']){
		$Content .= '
			<center>
				<button type="button" class="btn btn-default btn-sm red btn-xs" onclick="do_npc_confirm(' .$_GET['npc_delete_confirm'] . ')"><i class="fa fa-times"></i> Confirm Delete </button>
			</center>';
		echo Modal('NPC Confirm Delete', $Content, '');
	}

    /* Confirm NPC Copy */
    if($_GET['do_npc_copy_confirm']){
        $copies_to_make = $_GET['copies_to_make'];
        $starting_insert = $_GET['starting_insert'];
        # echo var_dump($_GET);
        echo 'Copied NPC to the following:<br>';
        for($i = 0; $i < $copies_to_make; $i++){
            echo DuplicateMySQLRecord("npc_types", "id", $_GET['do_npc_copy_confirm'], $starting_insert);
            $starting_insert++;
            echo '<br>';
        }
    }

    /* Modal : Copy NPC Function */
    if($_GET['npc_copy']){
        /* Get Last ID Available before doing an Insert */
        $query = "SELECT
            t1.ID + 1 AS next_id
            FROM npc_types t1
            LEFT JOIN npc_types t2
            ON t1.ID + 1 = t2.ID
            WHERE t2.ID IS NULL
            ORDER BY next_id DESC
            LIMIT 1";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $last_insert = $row['next_id'];
        }

        $Content .= '
			<center>
			    <table class="table-bordered table-striped table-condensed flip-content" style="width:500px">
			        <tr>
			            <td style="text-align:right">NPC ID to be Copied</td>
			            <td><input type="text" class="form-control" value="' . $_GET['npc_copy'] . '" disabled></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Starting Insert ID</td>
                        <td><input type="text" class="form-control" value="' . $last_insert . '" id="starting_insert"></td>
                    </tr>
                    <tr>
                        <td style="text-align:right">Number of NPC Copies to make</td>
                        <td><input type="text" class="form-control" value="1" id="copies_to_make"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type="button" class="btn btn-default btn-sm btn-xs" onclick="do_npc_copy_confirm(' . $_GET['npc_copy'] . ')" title="Copy NPC"><i class="fa fa-sign-in"></i> Copy NPC</button></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div id="npc_copy_result"></div></td>
                    </tr>
                </table>
            </center>';
        echo Modal('Copy NPC', $Content, '');
    }

    /* Get Field Translator on double click of cell */
    if(isset($_GET['get_field_translator'])){
        echo GetFieldSelect($_GET['field_name'], $_GET['value'], $_GET['npc_id'], 1);
    }

	/* Actual Delete */
	if($_GET['delete_npc']){
		mysql_query("DELETE FROM `npc_types` WHERE `id` = " . $_GET['delete_npc']);
		mysql_query("DELETE FROM `spawnentry` WHERE `npcID` = " . $_GET['delete_npc']);
	}

	/* Update NPC Data Field */
	if($_GET['DoFieldUpdate']){ 
		$query = "UPDATE `npc_types` SET `" . $_GET['Field'] . "` = '" . $_GET['Value'] . "' WHERE `id` = " . $_GET['NPC'] . "";
		echo $query;  
		$result = mysql_query($query);	
		if($result){ echo '<b>Field `'. $_GET['Field'] . '` Updated to Value \'' . $_GET['Value'] . '\' on NPC ID: \'' . $_GET['NPC'] . '\' </b>';} else{ echo 'Field update FAILED! ' . mysql_error(); }
	}
	/* Update NPC Data Field */
	if($_GET['DoFieldUpdateSingleNPC']){ 
		$query = "UPDATE `npc_types` SET `" . $_GET['Field'] . "` = '" . $_GET['Value'] . "' WHERE `id` = " . $_GET['NPC'] . "";
		echo $query; 
		$result = mysql_query($query);	
		if($result){ echo '<b>Field `'. $_GET['Field'] . '` Updated to Value \'' . $_GET['Value'] . '\' on NPC ID: \'' . $_GET['NPC'] . '\' </b>';} else{ echo 'Field update FAILED! ' . mysql_error(); }
	}

?>