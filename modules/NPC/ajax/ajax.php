<?php

	require_once("modules/NPC/functions.php");
	require_once('includes/constants.php');
	require_once('includes/functions.php');

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

    /* Display NPC Data in the top right pane */
	if($_GET['load_npc_top_pane_dash']){


		$result = mysql_query("SELECT * FROM `npc_types` WHERE `id` = " . $_GET['load_npc_top_pane_dash']);
		$npc_types = array();
		while($row = mysql_fetch_array($result)){ $npc_types = $row; }
		# echo var_dump($npc_types);

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

        if($_GET['load_npc_top_pane_dash'] <= 0){
            echo 'No loot data present';
        }
		$result = mysql_query("SELECT * FROM `loottable` WHERE `id` = " . $npc_types['loottable_id']);
		$loot_table = array();
		while($row = mysql_fetch_array($result)){ $loot_table = $row; }
		#echo var_dump($loot_table);

		echo '<style>
			#top_right_pane table tbody tr{ height:10px !important; }
			#top_right_pane table tbody td{ padding: 1px !important; text-align:center;  }
			#top_right_pane table tbody td{ padding: 1px !important; text-align:center; }
			#top_right_pane table tbody td input{ text-align:center; }
		</style>';

		/* Loot Table Entries */
		echo '
            <table>
                <tr>
                <td valign="top">

                <table>
                    <tr>
                        <td>' . $race_panel_image . '<br><b>' . $npc_types['name'] . '<br> ' . $npc_types['id'] . '</b></td>
                    </tr>
                </table>
                </td>
                <td valign="top" style="text-align:left">
                <span class="label label-danger" style="font-weight:bold"> Loot Table ID: ' . $npc_types['loottable_id'] . '</span><br><br>
                <table class="table table-condensed table-hover table-bordered loottable_entries ">
                    <thead>
                        <tr>

                            <th>Loot Drop ID</th>
                            <th>Multiplier</th>
                            <th>Probability</th>
                            <th>Droplimit</th>
                            <th>Min Drop</th>
                        </tr>
                    </thead> ';

				$result = mysql_query("SELECT * FROM `loottable_entries` WHERE `loottable_id` = " . $npc_types['loottable_id']);
				while($row = mysql_fetch_array($result)){
					echo '
						<tr loot_table="' . $npc_types['loottable_id'] . '" loot_drop="' . $row['lootdrop_id'] . '">
							<td>' . $row['lootdrop_id'] . '</td>
							<td>' . $row['multiplier'] . '</td>
							<td>' . $row['probability'] . '</td>
							<td>' . $row['droplimit'] . '</td>
							<td>' . $row['mindrop'] . '</td>
						</tr>';
				}

			echo '</table>';

			echo '</td><td valign="top" style="text-align:left;"">';

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
		/* Loot Drop Entries */
        echo '<span class="label label-danger" style="font-weight:bold"> Loot Drop ID: ' . $_GET['show_lootdrop_entries'] . '</span><br><br>';
		echo '<table class="table table-condensed table-hover table-bordered lootdrop_entries">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Equipped</th>
                        <th>% Chance</th>
                        <th>Min LVL</th>
                        <th>Max LVL</th>
                        <th>Multiplier</th>
                    </tr>
                </thead>';
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
		echo '</table> ';
		$FJS .= '<script type="text/javascript" src="modules/NPC/ajax/npc_top_right_pane.js"></script>';
		echo $FJS;
	}

	/* List Zone NPCS :: Grid Editor */
	if($_GET['ShowZone']){
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
	/* Special Attacks Editor */
	if(isset($_GET['special_abilities_editor'])){
		$special_attacks = array(
			"1" => "Summon",
			"2" => "Enrage",
			"3" => "Rampage",
			"4" => "AE Rampage",
			"5" => "Flurry",
			"6" => "Triple Attack",
			"7" => "Quad Attack",
			"8" => "Dual Wield",
			"9" => "Bane Attack",
			"10" => "Magic Attack",
			"11" => "Ranged Attack",
			"12" => "Unslowable",
			"13" => "Unmezable",
			"14" => "Uncharmable",
			"15" => "Unstunable",
			"16" => "Unsnareable",
			"17" => "Unfearable",
			"18" => "Immune to Dispell",
			"19" => "Immune to Melee",
			"20" => "Immune to Magic",
			"21" => "Immune to Fleeing",
			"22" => "Immune to non-Bane Melee",
			"23" => "Immune to non-Magical Melee",
			"24" => "Will Not Aggro",
			"25" => "Immune to Aggro",
			"26" => "Resist Ranged Spells",
			"27" => "See through Feign Death",
			"28" => "Immune to Taunt",
			"29" => "Tunnel Vision",
			"30" => "Does NOT buff/heal friends",
			"31" => "Unpacifiable",
			"32" => "Leashed",
			"33" => "Tethered",
			"34" => "Destructible Object",
			"35" => "No Harm from Players",
			"36" => "Always Flee",
			"37" => "Flee Percent",
			"38" => "Allow Beneficial",
			"39" => "Disable Melee",
			"40" => "Chase Distance",
			"41" => "Allow Tank",
			"42" => "Ignore Root Aggro",
			"43" => "Casting Resist Diff",
			"44" => "Counter Avoid Damage",
			"45" => "Max Special Attack",
		);

		$special_abilities_params = array(
			1 => array(
				0 => array("Level 1: Summon target to NPC" => "checkbox"),
				1 => array("Level 2: Summon NPC to target" => "checkbox"),
				2 => array("Param 0: Cooldown in ms (default: 6000)" => "checkbox"),
				3 => array("Param 1: HP ratio required to summon (default: 97)" => "checkbox"),
			),
		);

		$npc_id = $_GET['npc_id'];
		$db_field = $_GET['db_field'];
		$db_val = $_GET['val'];

		/* This is what we're using from the loaded database value */
		$db_special_attacks = array();

		/* Parse out the database value and stuff into $db_special_attacks */
		$special_attacks_parse = preg_split("/\^/", $db_val);
		foreach ($special_attacks_parse as $key => $val) {
			$sub_values = preg_split("/,/", $val);
			# print $val . '<br>';
			$n = 1; $start_index = 0;
			while($sub_values[$n]){
				$db_special_attacks[$sub_values[0]][$start_index] = $sub_values[$n];
				$n++; $start_index++;
			}
		}

		# echo '<pre>';
		# echo var_dump($special_abilities_params);
		# echo '</pre>';

		echo '<style> .ability_check_sub { font-size: 12px !important; height: 25px !important; }</style>';

		/* I could put all of this in fancy arrays, but I think it gets just as unmanagable as you would think it would make it */
		$Content .= '<div style="width:800px;left:20%;position:relative;">';
		$Content .= '<table class="table-bordered table-striped table-condensed flip-content">';
		$td_count = 0;
		$td_data = "";
		foreach ($special_attacks as $key => $val) {
			$inputs = "";
			if($val == "Tunnel Vision") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Aggro modifier on non-tanks" placeholder="75"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Counter Avoid Damage") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="chance to avoid melee via dodge/parray/riposte/block skills " placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Avoidance % (0-100)" placeholder="0-100"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="% Reduction to Riposte" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="% Reduction to Parry" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="% Reduction to Block" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% Reduction to Dodge" placeholder="0"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Casting Resist Diff") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Set an innate resist different to be applied to all spells cast by this NPC (stacks with a spells regular resist difference)." placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';

			}
			else if($val == "Flee Percent") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Percent NPC will flee at" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Percent chance to flee" placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';

			}
			else if($val == "Chase Distance") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Max Chase Distance" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Min Chase Distance" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Ignore line of sight check for chasing" placeholder="0"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';

			}
			else if($val == "Allow Tank") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Allows an NPC the opportunity to take aggro over a client if in melee range" placeholder="1"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Leashed") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Range" placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Tethered") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Aggo Range" placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Ranged Attack") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Number of Attacks" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Max Range (default: 250)" placeholder="250"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Percent Hit Chance Modifier" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Percent Damage Modifier" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Min Range (default: RuleI(Combat, MinRangedAttackDist) = 25)" placeholder="25"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Flurry") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Flurry attack count" placeholder="Combat:MaxFlurryHits"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Percent of a normal attack damage to deal" placeholder="100"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage bonus" placeholder="0"></td>';


				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against attack (100)" placeholder="100"></td>';

				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack" placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Rampage") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Proc chance" placeholder="20"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Rampage target count (default: rule Combat:MaxRampageTargets)" placeholder="Combat:MaxRampageTargets"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage to add" placeholder="0"></td>';

				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against (100)" placeholder="100"></td>';

				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack"  placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "AE Rampage") {
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Rampage target count (1)" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="% of normal attack damage (100)" placeholder="100"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage bonus to add (0)" placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0""></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against (100)" placeholder="100"></td>';

				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack" placeholder="0"></td>';

				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Enrage"){
				$inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="HP % to Enrage (rule NPC:StartEnrageValue)" placeholder="0"></td>';
				$inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Duration (ms) (10000)" placeholder="10000"></td>';
				$inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Cooldown (ms) (360000)" placeholder="360000"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
				$top_content .= '</table>';
			}
			else if($val == "Summon"){
				$inputs .= '<td style="width:230px"><input type="input" class="ability_check_sub form-control" style="width:230px" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Cooldown in ms (default: 6000)"></td>';
				$inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="HP % before summon (default: 97)"></td>';
				$top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
				$top_content .= '<td style="width:90px"> <small>' . $val . '</small> </td> <td style="width:210px">
					<select class="ability_check form-control" ability="' . $key . '" value="' . $db_special_attacks[$key][0] . '" style="width:200px">
						<option value="0" ' . (($db_special_attacks[$key][0] == 0) ? 'selected' : '') . '>Off</option>
						<option value="1" ' . (($db_special_attacks[$key][0] == 1) ? 'selected' : '') . '>Summon target to NPC</option>
						<option value="2" ' . (($db_special_attacks[$key][0] == 2) ? 'selected' : '') . '>Summon NPC to target</option>
					</select> ' . $inputs . '</td>';
				$top_content .= '</table>';
			}
			else {
				$td_data .= '<td> <small>' . $val . '</small></td> <td style="text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>';

				$td_count++;
				if($td_count == 3){
					$bottom_content .= '<tr>' . $td_data . '</tr>';
					$td_data = "";
					$td_count = 0;
				}
			}
		}
		$Content .= '</table>';

		// $Content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
		$Content .= '<center><small>Hover over each input to see the descriptions for each special ability </small></center>';
		$Content .= $top_content;
		// $Content .= '</table>';

		$Content .= '<br>';
		$Content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
		$Content .= $bottom_content;
		$Content .= '</table>';

		$Content .= '<br>';
		$Content .= '<table style="width:100%">';
		$Content .= '<tr><td><input type="text" class="form-control m-wrap span6" value="' . $db_val . '" id="special_attacks_result" style="width:100% !important;"></td></tr>';
		$Content .= '<tr><td>&nbsp;</td></tr>';
		$Content .= '<tr><td><a href="javascript:;" class="button btn blue" onclick="update_npc_field(' . $npc_id . ', \'' . $db_field . '\', $(\'#special_attacks_result\').val())"><i class="icon-save"></i>Save to NPC ID:' . $npc_id . '</a></td></tr>';
		$Content .= '</table>';
		$Content .= '</div>';

		echo '<script type="text/javascript" src="modules/NPC/ajax/npc_special_attacks.js"></script>';

		echo Modal('Special Abilites Editor', $Content, '');
	}
?>