<?php
	/*
		Author: Akkadius
	*/
	
	echo '<div id="JSOP"></div>'; 
	
	$FJS .= '<script type="text/javascript" src="modules/NPC/ajax/ajax.js"></script>';    
	$FJS .= '<script type="text/javascript" src="cust_assets/js/double_scroll.js"></script>';     
	$FJS .= '<script type="text/javascript" src="cust_assets/js/colpick/js/colpick.js"></script>';
	$FJS .= '<link href="cust_assets/js/colpick/css/colpick.css" rel="stylesheet" type="text/css"/>';
	require_once('./includes/constants.php');   
	require_once('functions.php'); 
	require_once('./includes/functions.php'); 
	
	PageTitle("NPC Editor");
	
	if($_GET['MassFieldEdit'] == 1 || count($_GET) == 1){ 
		$FJS .= '<link href="modules/NPC/mass_edit.css" rel="stylesheet" type="text/css"/>';
	}
	
	/* Single NPC Edit Form */
	if($_GET['SingleNPCEdit'] > 0){
		$FJS .= '<script type="text/javascript">
			$("select").each(function() { $(this).tooltip(); }); 
			$(":text, select, input").each(function() {  $(this).css("height", "30px"); $(this).tooltip(); });
			$(".page-content img").each(function() {  
				$(this).css("border", "2px solid #666");
			});
		</script>';
		
		/* Get Col Defs */
		$NPCCols = array();
		$NPCACols = array();
		$query = "show columns from npc_types";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){ 
			if($row['Field'] == "hp"){ $Field = "Hit Points"; }
			else if($npcfields[$row['Field']]){ $Field = $npcfields[$row['Field']]; }
			else{ $Field = $row['Field']; }
			array_push ($NPCCols, $Field); 
			array_push ($NPCACols, $row['Field']);
		}
		
		$NPCFields = array();
		$query = "SELECT * FROM `npc_types` WHERE `id` = " . $_GET['SingleNPCEdit'] . "";
		$result = mysql_query($query);	
		$Content .= '<center>';
		while($row = mysql_fetch_array($result)){
			$Content .= '<h2 class="page-title">' . $row['name'] . '</h2><hr>'; 
			$n=1;
			foreach ($row as $key => $val){
				if(is_numeric($key)){
					/* Races */
					if($NPCACols[($n - 1)] == "race"){
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$Field = "" .
						'<img src="includes/img.php?type=race&id=' . $val . '" id="RaceIMG" style="width:200px;height:280px;"><br>' . "
						<select value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='RaceChange(" . $_GET['SingleNPCEdit'] . ", this.value)'>";
						foreach ($races as $key => $val2){
							if($val == $key){ $Field .= '<option selected value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
							else{ $Field .= '<option value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
						}						
						$Field .= '</select>';
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] = $Field;
					}
					else if($NPCACols[($n - 1)] == "d_meele_texture1" || $NPCACols[($n - 1)] == "d_meele_texture2"){
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] =  '<a href="javascript:;" onclick="OpenWindow(\'min.php?Mod=IE&prevITfile=1&Field=' . $NPCACols[($n - 1)] . '&NPC=' . $row['id'] . '\', \'_blank\', 900, 900)"> <img src="../images/weapons/'. $val . '.jpg" id="'.  $NPCACols[($n - 1)] . '" style="width:200px;height:280px;"></a><br>';
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] .=  "<input type='number' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)'>";
					}
					else if($npcfieldscat[$NPCACols[($n - 1)]][0]){
						#echo $npcfieldscat[$NPCACols[($n - 1)]] . '<br>';
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)'>";
					}
					else{
						$NPCFields['End'][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields['End'][$NPCACols[($n - 1)]][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)'>";
					}
					$n++;
				}
			}
		}
		$Order = array("General", "Visual Texture", "Appearance", "Stats", "Misc.", "<hr>");
		$Content .= '<form class="mainForm" id="mainForm">';
		foreach ($Order as $Orderval){
			$Content .= '<br><h2 class="page-title">' . $Orderval . '</h2>';
			$Content .= '<table class="table" style="width:500px">';
			$n = 0;
			foreach ($NPCFields[$Orderval] as $key => $val){
				if($n == 0){ $Content .= '<tr>'; }
				$Content .= '<td style="text-align:center;"><h2 style="display:inline;color:black;font-weight:bold">' . $val[0] . '</h2><br>' . $val[1] . '</td>';
				if($n == 4){ $Content .= '</tr>'; $n = 0; }
				$n++;
			}
			$Content .= '</table>';
			
		}
		$Content .= '</form>';
		$Content .= '</table>'; 
		echo Modal('NPC Edit', $Content, '');  
	}
	else if($_GET['MassFieldEdit'] == 1){
		echo '<h2 class="page-title">Mass Field Editor</h2><hr>';
		echo 'This very powerful tool will edit entire columns based on all of the NPC\'s shown in the parent window...<br> 1st, select the field you want to mass edit, and then select your method of mass value changing, you can either use \'Set all Fields to Value\' or use the \'Min/Max\' function below.<br><br>';
		echo '<table width="450" class="table">
			<tr><td style="text-align:right;">Field</td><td style="width:50px">' . GetNPCTypesSelector() . '</td></tr>
			<tr><td style="text-align:right;"><h6 class="page-title">Set All Fields to Value</h6></td><td><input class="btn btn-default" type="text" id="massfieldvalue"><input type="button" value="Execute" class="btn btn-default green" onclick="SetFieldMassValue()" title="Click this when you are ready to execute"></td></tr>
			<tr><td style="text-align:right;">OR</td><td><h6 class="page-title">Set Field Values with a Random Min/Max Range</h6></td></tr>
			<tr><td style="text-align:right;font-size:13px;">Min </td><td><input class="form-control btn btn-default" type="text" id="minfieldvalue"></td></tr>
			<tr><td style="text-align:right;font-size:13px;">Max </td><td><input class="form-control btn btn-default" type="text" id="maxfieldvalue"></td></tr>
			<tr><td style="text-align:right;"></td><td><input class="form-control btn btn-default green" type="button" value="Execute" onclick="SetFieldMassValueMinMax()" title="Click this when you are ready to execute"></td></tr>
		</table>';
	} 
	else{
		echo '<h3 class="page-title">NPC Editor</h3><hr><small>Filter your NPC List selection using the fields below, then you can edit by either using the table fields or using the mass field editor to make changes...<br>The less NPC\'s you have in your selection list, the faster browser performance will be</small><br><br>';
		echo '<table><tr><td>'; 
		echo '<form>';
		echo '<table class="table" style="width:600px">
			<tr><td style="text-align:right">Zone</td>			<td>' . GetZoneListSelect() . '</td></tr>
			<tr><td style="text-align:right">Instance ID</td>	<td><input class="form-control span6" type="text" value="0" id="zinstid" title="The Instance Version you wish to see"></td></tr>
			<tr><td style="text-align:right">NPC Name</td>		<td><input type="text" value="" class="form-control" id="npcname" title="Name of the NPC to Search For" onkeyup="if(event.keyCode == 13){ ShowZone();}"></td></tr>
			<tr><td style="text-align:right"></td>				<td><button type="button" value="Search!" class="btn btn-default green" onclick="ShowZone()" title="Click this when you are ready to execute"><i class="fa fa-search"></i> Search</button> <button type="button" value="Mass Field Editor" class="btn btn-default blue" onclick="MassFieldEditor();" title="Mass Field Editor"><i class="fa fa-edit"></i> Mass Field Editor</button></td></tr>
		</table>';
		echo '<div id="shownpczone"></div>';
		echo '</form>';
		
		echo '</td></tr></table><br><br>';
	} 
	
?>