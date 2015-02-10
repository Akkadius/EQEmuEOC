<?php

	include("modules/NPC/functions.php");
	require_once('./includes/constants.php');

	/* Mass Edit Window */
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

	/* Confirm NPC Delete */
	if($_GET['npc_delete_confirm']){
		$Content .= '
			<center>
				<button type="button" class="btn btn-default btn-sm red btn-xs" onclick="do_npc_confirm(' .$_GET['npc_delete_confirm'] . ')"><i class="fa fa-times"></i> Confirm Delete </button>
			</center>';
		echo Modal('NPC Confirm Delete', $Content, '');
	}
	/* Actual Delete */
	if($_GET['delete_npc']){
		mysql_query("DELETE FROM `npc_types` WHERE `id` = " . $_GET['delete_npc']);
		mysql_query("DELETE FROM `spawnentry` WHERE `npcID` = " . $_GET['delete_npc']);
	}

	if($_GET['load_npc_top_pane_dash']){
		$result = mysql_query("SELECT * FROM `npc_types` WHERE `id` = " . $_GET['load_npc_top_pane_dash']);
		$npc_types = array();
		while($row = mysql_fetch_array($result)){ $npc_types = $row; }
		# echo var_dump($npc_types);

		$result = mysql_query("SELECT * FROM `loottable` WHERE `id` = " . $npc_types['loottable_id']);
		$loot_table = array();
		while($row = mysql_fetch_array($result)){ $loot_table = $row; }
		# echo var_dump($loot_table);

		/* Loot Table */
		// echo '
		// 	<table class="table table-striped table-hover table-condensed flip-content">
		// 		<tr>
		// 			<td>Mincash</td><td> ' . $loot_table['mincash'] . '</td></tr>
		// 			<td>Maxcash</td><td> ' . $loot_table['maxcash'] . '</td></tr>
		// 			<td>Average Coin</td><td> ' . $loot_table['avgcoin'] . '</td></tr>
		// 		</tr>
		// 	</table>
		// ';

		echo '<style>
			#top_right_pane table tbody tr{ height:10px !important; }
			#top_right_pane table tbody td{ padding: 1px !important; }
		</style>';

		/* Loot Table Entries */
		echo '

		<table>
			<tr><td valign="top">

			<table class="table table-condensed table-hover table-bordered loottable_entries">
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

			echo '</table>

			<span class="label label-sm label-success"> (' .  $npc_types['id'] . ') ' . $npc_types['name'] . ' Loot Table ID: ' . $npc_types['loottable_id'] . '</span>';

			echo '</td>
				<td>
					<i class="fa fa-arrow-circle-o-right" style="color:#666;font-size:40px;padding:15px"></i>
				</td>
				<td valign="top">';

			echo '<div id="lootdrop_entries" style="display:inline"></div>';

		echo '</td></tr>
			</table>';

		$FJS .= '<script type="text/javascript" src="modules/NPC/ajax/npc_top_right_pane.js"></script>';
		echo $FJS;
	}
	if($_GET['show_lootdrop_entries']){
		/* Loot Drop Entries */
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
				items.`Name`
				FROM
				lootdrop_entries
				INNER JOIN items ON lootdrop_entries.item_id = items.id
				WHERE `lootdrop_id` = " . $_GET['show_lootdrop_entries']);
		while($row = mysql_fetch_array($result)){
			echo '
				<tr loot_table="' . $row['loottable_id'] . '">
					<td>' . $row['item_id'] . '</td>
					<td>' . $row['Name'] . '</td>
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

	/* List Zone NPCS */
	if($_GET['ShowZone']){
		/* Parse Columns */
		$npc_cols = array();
		$npc_a_cols = array();
		$query = "SHOW COLUMNS FROM `npc_types`";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			if($row['Field'] == "hp"){
				$Field = "Hit Points";
			}
			else if($npc_fields[$row['Field']]){
				$Field = $npc_fields[$row['Field']];
			}
			else{ }
			$Field = $row['Field'];
			array_push ($npc_cols, $Field);
			array_push ($npc_a_cols, $row['Field']);
		}

		/* NPC Name Filter */
		$npc_filter = "";
		if($_GET['npc_filter'] != ""){
			$npc_filter = " AND npc_types.`name` LIKE '%" . mysql_real_escape_string($_GET['npc_filter']) . "%' ";
		}

		echo '<style>
			#shownpczone table tbody tr{ height:10px !important; }
			#shownpczone table tbody td{ padding: 1px !important; }
		</style>';

		/* Get NPC List */
		$query = "SELECT
			npc_types.*
			FROM
			npc_types
			Inner Join spawnentry ON npc_types.id = spawnentry.npcID
			Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
			WHERE spawn2.zone = '" . $_GET['Zone'] . "' AND spawn2.version = " . $_GET['inst_version'] . " " . $npc_filter . "
			GROUP BY npc_types.id
			ORDER BY npc_types.id";
		$result = mysql_query($query);

		# echo $query . '<br>' . mysql_error();
		$RowData = array();

		/* Print NPC List Table */
		echo '<table class="npc_data_table table table-striped table-hover table-condensed flip-content dataTable" id="npc_head_table" cellspacing="0" width="100%">';
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
				<button type="button" class="btn btn-default btn-sm red btn-xs" onclick="do_npc_delete(' . $row['id'] . ')"><i class="fa fa-times"></i> </button>
				<button type="button" class="btn btn-default btn-sm green btn-xs" onclick="do_npc_edit(' . $row['id'] . ')"><i class="fa fa-edit"></i> </button>
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
	*/
	if($_GET['SingleNPCEdit'] > 0){
		echo '<style>
			td{ width:50px; } 
			input{ width:150px; } 
		</style>';
		$FJS .= '<script type="text/javascript">
			$("select").each(function() { $(this).tooltip(); }); 
			$(":text, select, input").each(function() {  $(this).css("height", "30px"); $(this).tooltip(); });
			$(".page-content img").each(function() {  
				$(this).css("border", "2px solid #666");
			});
		</script>';
		
		/* Get Col Defs */
		$npc_cols = array();
		$npc_a_cols = array();
		$query = "show columns from npc_types";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){ 
			if($row['Field'] == "hp"){ $Field = "Hit Points"; }
			else if($npc_fields[$row['Field']]){ $Field = $npc_fields[$row['Field']]; }
			else{ $Field = $row['Field']; }
			array_push ($npc_cols, $Field);
			array_push ($npc_a_cols, $row['Field']);
		}
		
		
		
		$NPCFields = array();
		$query = "SELECT * FROM `npc_types` WHERE `id` = " . $_GET['SingleNPCEdit'] . "";
		$result = mysql_query($query);	
		$Content .= '<center>';
		while($row = mysql_fetch_array($result)){
			$Content .= '<h2 class="page-title">' . $row['name'] . '</h2><hr>'; 
			$n=1;
			
			/* Color Picker Default */
			echo '<style>
			#picker {
				margin:0;
				padding:0;
				border:0;
				width:70px;
				height:20px;
				border-right:20px solid rgb(' . $row['armortint_red'] . ', ' . $row['armortint_green'] . ', ' . $row['armortint_blue']  . ');
				line-height:20px;
			}</style>';
			
			echo '<input type="hidden" id="npc_id" value="'. $row['id'] . '">';
			
			foreach ($row as $key => $val){
			
				if(is_numeric($key)){
					/* Races */
					if($npc_a_cols[($n - 1)] == "race"){
						$NPCFields["Visual Texture"][$npc_a_cols[($n - 1)]][0] = $npc_cols[($n - 1)];
						$Field = "" .
						'<img src="includes/img.php?type=race&id=' . $val . '" id="RaceIMG" style="width:200px;height:280px;"><br><br>' . "
						<select title='" . ProcessFieldTitle($npc_a_cols[($n - 1)]) . "' value='" . $val . "' id='" . $row['id'] . "^" . $npc_a_cols[($n - 1)] . "' class='" . $npc_a_cols[($n - 1)] . "' onchange='RaceChange(" . $_GET['SingleNPCEdit'] . ", this.value)'>";
						foreach ($races as $key => $val2){
							if($val == $key){ $Field .= '<option selected value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
							else{ $Field .= '<option value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
						}						
						$Field .= '</select>';
						$NPCFields["Visual Texture"][$npc_a_cols[($n - 1)]][1] = $Field;
					}
					else if($npc_a_cols[($n - 1)] == "d_meele_texture1" || $npc_a_cols[($n - 1)] == "d_meele_texture2"){
						$NPCFields["Visual Texture"][$npc_a_cols[($n - 1)]][0] = $npc_cols[($n - 1)];
						$NPCFields["Visual Texture"][$npc_a_cols[($n - 1)]][1] =  '<a href="javascript:;" onclick="OpenWindow(\'min.php?Mod=IE&prevITfile=1&Field=' . $npc_a_cols[($n - 1)] . '&NPC=' . $row['id'] . '\', \'_blank\', 900, 900)"> <img src="includes/img.php?type=weaponimage&id='. $val . '" id="'.  $npc_a_cols[($n - 1)] . '" style="width:200px;height:280px;"></a><br>';
						$NPCFields["Visual Texture"][$npc_a_cols[($n - 1)]][1] .=  "<br><input type='number' title='" . ProcessFieldTitle($npc_a_cols[($n - 1)]) . "'  value='" . $val . "' id='" . $row['id'] . "^" . $npc_a_cols[($n - 1)] . "' class='" . $npc_a_cols[($n - 1)] . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $npc_a_cols[($n - 1)] . "\", this.value)'>";
					}
					else if($Custom_Select_Fields[$npc_a_cols[($n - 1)]]){
						$NPCFields[$npcfieldscat[$npc_a_cols[($n - 1)]]][$npc_a_cols[($n - 1)]][0] = $npc_cols[($n - 1)];
						$NPCFields[$npcfieldscat[$npc_a_cols[($n - 1)]]][$npc_a_cols[($n - 1)]][1] = GetFieldSelect($npc_a_cols[($n - 1)], $val, $row['id']);
					}
					/* Generic catch all */
					else if($npcfieldscat[$npc_a_cols[($n - 1)]][0]){
						$NPCFields[$npcfieldscat[$npc_a_cols[($n - 1)]]][$npc_a_cols[($n - 1)]][0] = $npc_cols[($n - 1)];
						$NPCFields[$npcfieldscat[$npc_a_cols[($n - 1)]]][$npc_a_cols[($n - 1)]][1] =  "<br><input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $npc_a_cols[($n - 1)] . "' class='" . $npc_a_cols[($n - 1)] . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $npc_a_cols[($n - 1)] . "\", this.value)' title='" . ProcessFieldTitle($npc_a_cols[($n - 1)]) . "'>";
					}
					else{
						$NPCFields['End'][$npc_a_cols[($n - 1)]][0] = $npc_cols[($n - 1)];
						$NPCFields['End'][$npc_a_cols[($n - 1)]][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $npc_a_cols[($n - 1)] . "' class='" . $npc_a_cols[($n - 1)] . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $npc_a_cols[($n - 1)] . "\", this.value)' >";
					}
					$n++;
				}
			}
		}
		$Order = array("General", "Visual Texture", "Combat", "Appearance", "Stats", "Misc.", "<hr>");
		$Content .= '<form class="mainForm" id="mainForm">';
		foreach ($Order as $Orderval){
			$Content .= '<br><h2 class="page-title">' . $Orderval . '</h2><div id="section_' . $Orderval . '"></div>'; 
			$Content .= '<table class="table table-striped" style="width:800px">';
			$n = 0;
			foreach ($NPCFields[$Orderval] as $key => $val){
				if($n == 0){ $Content .= '<tr>'; }
				$Content .= '<td style="text-align:center;"><h6 style="color:black;font-weight:bold;display:inline">' . $val[0] . '</h6>' . $val[1] . '</td>';
				if($n == 4){ $Content .= '</tr>'; $n = 0; }
				$n++;
			}
			$Content .= '</table>';
		}
		$Content .= '</form>';
		$Content .= '</table>';
		
		$Content .= '<script type="text/javascript">
			$(".modal-body input, .modal-body select").each(function() { 
				$(this).addClass("form-control input-circle");
				$(this).css("border", "1px solid #666");
			});
			$("select").each(function() { $(this).tooltip(); });
			$(":text").each(function() { $(this).tooltip(); });
			$( document ).ready(function() {
				$("#section_Appearance").html("Armor Tint <div id=\"picker\" style=\"display:inline\"></div><br><br>"); 
				$("#picker").colpick({ 
					layout: "hex",
					submit:0,
					colorScheme: "dark",
					onChange:function(hsb,hex,rgb,el,bySetColor) {
						// console.log(rgb);
						$(el).css("border-color","#"+hex);
						$("#color_preview").css("background-color","#"+hex);
						// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
						if(!bySetColor) $(el).val("#"+hex); 
						$(".armortint_red").val(rgb.r);
						$(".armortint_green").val(rgb.g);
						$(".armortint_blue").val(rgb.b);
					},
					onHide:function(e){
						// console.log("hidden"); 
						update_npc_field($("#npc_id").val(), "armortint_red", $(".armortint_red").val());
						update_npc_field($("#npc_id").val(), "armortint_green", $(".armortint_green").val());
						update_npc_field($("#npc_id").val(), "armortint_blue", $(".armortint_blue").val());
					}
				}).keyup(function(){
					$(this).colpickSetColor(this.value);
				});
			});
		</script>';
		
		echo Modal('NPC Edit', $Content, '');  
	}
?>