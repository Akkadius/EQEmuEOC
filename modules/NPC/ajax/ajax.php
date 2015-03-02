<?php

	require_once("modules/NPC/functions.php");
	require_once('includes/constants.php');
	require_once('includes/functions.php');

    /* sub ajax call files to sectionize things a bit */
    require_once('modules/NPC/ajax/npc_top_right_pane.php');
    require_once('modules/NPC/ajax/npc_special_attacks.php');

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

	/* List Zone NPCS :: Grid Editor */
	if(isset($_GET['show_npc_result'])){
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
	/* 
		Single NPC Edit Form
	    Spawned from Modal
	*/
	if($_GET['SingleNPCEdit'] > 0){
        /* Get Col Defs */
		$npc_cols = array();
		$npc_a_cols = array();
		$query = "SHOW COLUMNS FROM `npc_types`";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
            if ($row['Field'] == "hp") {
                $field = "Hit Points";
            }
            else if ($npc_fields[$row['Field']]) {
                $field = $npc_fields[$row['Field']];
            }
            else {
                $field = $row['Field'];
            }
			array_push ($npc_cols, $field);
			array_push ($npc_a_cols, $row['Field']);
		}

		$npc_fields = array();
		$query = "SELECT * FROM `npc_types` WHERE `id` = " . $_GET['SingleNPCEdit'] . "";
		$result = mysql_query($query);	
		$Content .= '<center>';
		while($row = mysql_fetch_array($result)){
			$n = 1;
			
			echo '<input type="hidden" id="npc_id" value="'. $row['id'] . '">';

            echo '<style>
                .single_edit_table table tbody tr{ height:10px !important; }
                .single_edit_table table tbody td{ padding: 1px !important; text-align:center;  }
                .single_edit_table table tbody td input{
                    text-align:center;
                    font-size: 12px !important;
                    height: 25px !important;
                }
                .single_edit_table td{ width:50px; }
                .single_edit_table table input{ min-width:150px !important; }
                #picker {
                    margin:0;
                    padding:0;
                    border:0;
                    width:70px;
                    height:20px;
                    border-right:20px solid rgb(' . $row['armortint_red'] . ', ' . $row['armortint_green'] . ', ' . $row['armortint_blue']  . ');
                    line-height:20px;
                }
            </style>';

			foreach ($row as $key => $val){
				if(is_numeric($key)){
					/* Races */
                    $field_name = $npc_a_cols[($n - 1)];
					if($field_name == "race"){
						$npc_fields["Visual Texture"][$field_name][0] = $npc_cols[($n - 1)];
						$field = "" .
						    '<span class="image-wrap " style="width: auto; height: auto;">
						        <img src="includes/img.php?type=race&id=' . $val . '" id="RaceIMG" style="width:auto;height:280px;" class="soft-embossed">
						    </span>' . "
						    <select title='" . ProcessFieldTitle($field_name) . "' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='RaceChange(" . $_GET['SingleNPCEdit'] . ", this.value)'>";
                        foreach ($races as $key => $val2) {
                            if ($val == $key) {
                                $field .= '<option selected value="' . $key . '">' . $key . ': ' . $val2 . ' </option>';
                            }
                            else {
                                $field .= '<option value="' . $key . '">' . $key . ': ' . $val2 . ' </option>';
                            }
                        }
						$field .= '</select>';
						$npc_fields["Visual Texture"][$field_name][1] = $field;
					}
					else if($field_name == "d_melee_texture1" || $field_name == "d_melee_texture2"){
						$npc_fields["Visual Texture"][$field_name][0] = $npc_cols[($n - 1)] . '';
						$npc_fields["Visual Texture"][$field_name][1] =
                            '<a href="javascript:;" onclick="OpenWindow(\'min.php?Mod=IE&prevITfile=1&Field=' . $field_name . '&NPC=' . $row['id'] . '\', \'_blank\', 900, 900)">
						        <span class="image-wrap " style="width: auto; height: auto;">
						            <img src="includes/img.php?type=weaponimage&id='. $val . '" id="'.  $field_name . '" class="embossed morphing-glowing">
						        </span>
                            </a>
                        ';
						$npc_fields["Visual Texture"][$field_name][1] .=  "<input type='number' title='" . ProcessFieldTitle($field_name) . "'  value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)'>";
					}
					else if($Custom_Select_Fields[$field_name]){
						$npc_fields[$field_category[$field_name]][$field_name][0] = $npc_cols[($n - 1)];
						$npc_fields[$field_category[$field_name]][$field_name][1] = GetFieldSelect($field_name, $val, $row['id']);
					}
					/* Generic catch all */
					else if($field_category[$field_name][0]){
						$npc_fields[$field_category[$field_name]][$field_name][0] = $npc_cols[($n - 1)];
						$npc_fields[$field_category[$field_name]][$field_name][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)' title='" . ProcessFieldTitle($field_name) . "'>";
					}
					else{
						$npc_fields['End'][$field_name][0] = $npc_cols[($n - 1)];
						$npc_fields['End'][$field_name][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)' >";
					}
					$n++;
				}
			}
		}

        $category_order = array("General", "Visual Texture", "Combat", "Appearance", "Statistics", "Misc.");
        $td_content = "";
        $n = 0;

        /* Manually push this field into the Appearance Category */
        $npc_fields["Appearance"]["tint"][0] = "Armor Tint";
        $npc_fields["Appearance"]["tint"][1] = "<div id='armor_tint_selector'></div>";

		foreach ($category_order as $order_val) {
            if($order_val != '') {
                $Content .= '
                    <h2> <span class="label label-success">' . $order_val . '</span></h2>
                ';
            }
			$Content .= '<table class="table-bordered table-striped table-condensed flip-content single_edit_table">';
			foreach ($npc_fields[$order_val] as $key => $val) {
                #print $key . '<br>';
                if($key == "d_melee_texture1" || $key == "d_melee_texture2" || $key == "race"){
                    $td_content .= '
                        <td style="text-align:center;vertical-align:top">' . $val[1] . '<br><b>' .  $val[0] . '</b></td>';
                }
                else {
                    $td_content .= '
                        <td style="text-align:right;"><b>' . $val[0] . '</b>  </td><td> ' . $val[1] . '  </td>';
                }
                $n++;
                if ($n == 3) {
                    $Content .= '<tr>' . $td_content . '</tr>';
                    $td_content = "";
					$n = 0;
				}
			}
            if($td_content != ""){
                $Content .= '<tr>' . $td_content . '</tr>';
                $td_content = "";
                $n = 0;
            }

			$Content .= '</table>';
		}
		$Content .= '</table>';
        echo '<script type="text/javascript" src="modules/NPC/ajax/single_npc_edit.js"></script>';
		echo Modal('NPC Edit', $Content, '');  
	}

?>