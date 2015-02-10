<?php
	#error_reporting(E_ALL);
	#ini_set('display_errors', '1');
	include("modules/NPC/functions.php");
	require_once('./includes/constants.php');

	/* List Zone NPCS */
	if($_GET['ShowZone']){
		echo '<style>
		.suwala-doubleScroll-scroll-wrapper{
			position: fixed;
			left:20%;
			bottom:5px;
			z-index:100000000;
		}
		.table td{ font-size:15px; width:70px;}
		</style>';
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
		if($_GET['NPC']){ $QA = " AND npc_types.name LIKE '%" . $_GET['NPC'] . "%'"; }
		$query = "SELECT
				npc_types.*
				FROM
				npc_types
				Inner Join spawnentry ON npc_types.id = spawnentry.npcID
				Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
				WHERE spawn2.zone = '". $_GET['Zone'] . "' AND spawn2.version = ". $_GET['Inst'] . " " . $QA . "
				GROUP BY npc_types.id
				ORDER BY npc_types.id";
				#echo $query; 
		$result = mysql_query($query);	
		
		$RowData = array();
		$ret .= '<div id="dofieldupdate"></div>
			<div class="npctable">
			';
		$ret .= NPCTableHeader("NPC Edit", $NPCCols, ' cellpadding="0" cellspacing="0" border="0" class="table-fixed-header npc_data_table table table-striped table-hover"');
		
		while($row = mysql_fetch_array($result)){
			$n=1;
			foreach ($row as $key => $val){
				if(is_numeric($key)){
					$FieldSize = 15;
					if($n == 1){ $style = "style='width:30%;'"; } else{ $style = ""; $FieldSize = 5; }
					if($n == 1 || $n == 2 || $n == 3){ $FieldSize = 13; }
					$Field = "<input type='text' value='" . $val . "' size='" . $FieldSize . "' " . $style . " id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "'>";
					if($n == 1){ $Field .= '<button type="button" class="btn btn-default btn-sm green" onclick="DoNPCEdit(' . $row['id'] . ')"><i class="fa fa-edit"></i> EDIT</button>'; }
					array_push ($RowData, $Field);
					$n++;
				}
			}
			$ret .= NPCTableRow($RowData, ' class="gradeC"');
			unset($RowData); $RowData = array();
		}
		$ret .= NPCTableEnd(); 
		$ret .= ' </div> </div>  </div>';
		$ret .= '</div>'; 
		print $ret;
		echo '
		<script type="text/javascript">
			$(".npctable").doubleScroll();
			$(document).ready(function() { 
				// npctable
				// $(".npctable").scrollTop(500); 
				$("html, body").animate({
					scrollTop: $(".npctable").offset().top - 50
				}, 500);
			});  
			$(".npctable input").change(function(){ 
				//alert("The text has been changed.");
				$(this).css("border", "1px solid rgb(64,153,255)");
				DoFieldUpdate($(this).attr(\'id\')); 
			});		
		</script>';
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
					if($NPCACols[($n - 1)] == "race"){
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$Field = "" .
						'<img src="includes/img.php?type=race&id=' . $val . '" id="RaceIMG" style="width:200px;height:280px;"><br><br>' . "
						<select title='" . ProcessFieldTitle($NPCACols[($n - 1)]) . "' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='RaceChange(" . $_GET['SingleNPCEdit'] . ", this.value)'>";
						foreach ($races as $key => $val2){
							if($val == $key){ $Field .= '<option selected value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
							else{ $Field .= '<option value="'. $key . '">'. $key . ': '. $val2 . ' </option>'; }
						}						
						$Field .= '</select>';
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] = $Field;
					}
					else if($NPCACols[($n - 1)] == "d_meele_texture1" || $NPCACols[($n - 1)] == "d_meele_texture2"){
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] =  '<a href="javascript:;" onclick="OpenWindow(\'min.php?Mod=IE&prevITfile=1&Field=' . $NPCACols[($n - 1)] . '&NPC=' . $row['id'] . '\', \'_blank\', 900, 900)"> <img src="includes/img.php?type=weaponimage&id='. $val . '" id="'.  $NPCACols[($n - 1)] . '" style="width:200px;height:280px;"></a><br>';
						$NPCFields["Visual Texture"][$NPCACols[($n - 1)]][1] .=  "<br><input type='number' title='" . ProcessFieldTitle($NPCACols[($n - 1)]) . "'  value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)'>";
					}
					else if($Custom_Select_Fields[$NPCACols[($n - 1)]]){
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][1] = GetFieldSelect($NPCACols[($n - 1)], $val, $row['id']);  
					}
					/* Generic catch all */
					else if($npcfieldscat[$NPCACols[($n - 1)]][0]){
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields[$npcfieldscat[$NPCACols[($n - 1)]]][$NPCACols[($n - 1)]][1] =  "<br><input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)' title='" . ProcessFieldTitle($NPCACols[($n - 1)]) . "'>";
					}
					else{
						$NPCFields['End'][$NPCACols[($n - 1)]][0] = $NPCCols[($n - 1)];
						$NPCFields['End'][$NPCACols[($n - 1)]][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $NPCACols[($n - 1)] . "' class='" . $NPCACols[($n - 1)] . "' onchange='UpdateSingleNPCEdit(" . $row['id'] . ", \"" . $NPCACols[($n - 1)] . "\", this.value)' >";
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
						UpdateSingleNPCEdit($("#npc_id").val(), "armortint_red", $(".armortint_red").val());
						UpdateSingleNPCEdit($("#npc_id").val(), "armortint_green", $(".armortint_green").val());
						UpdateSingleNPCEdit($("#npc_id").val(), "armortint_blue", $(".armortint_blue").val());
					}
				}).keyup(function(){
					$(this).colpickSetColor(this.value);
				});
			});
		</script>';
		
		echo Modal('NPC Edit', $Content, '');  
	}
?>