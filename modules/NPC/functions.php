<?php

	/* Field Titles or Descriptors */
	function ProcessFieldTitle($field){	
		$field_desc = array(
			"id" => array("The ID of the NPC."),
			"name" => array("The name of the NPC."),
			"lastname" => array("The last name of the NPC."),
			"level" => array("The level of the NPC."),
			"race" => array("The race ID of the NPC.  Races"),
			"class" => array("The class ID of the NPC.  Classes"),
			"bodytype" => array("The body type of the NPC.  Body Types"),
			"hp" => array("The health of the NPC."),
			"mana" => array("The mana of the NPC."),
			"gender" => array("The gender of the NPC.  Genders"),
			"texture" => array("The texture of the NPC."),
			"helmtexture" => array("The helmet texture of the NPC."),
			"size" => array("The size of the NPC."),
			"hp_regen_rate" => array("The health regeneration rate of the NPC."),
			"mana_regen_rate" => array("The mana regeneration rate of the NPC."),
			"loottable_id" => array("The loottable ID used by the NPC."),
			"merchant_id" => array("The merchant ID used by the NPC, must be Merchant class.  Classes"),
			"alt_currency_id" => array("The alternate currency ID used in the merchant's shop.  Alternate Currency"),
			"npc_spells_id" => array("The NPC spell list ID used by the NPC."),
			"npc_spells_effects_id" => array("The NPC spell effect list ID used by the NPC.  npc_spells_effects"),
			"npc_faction_id" => array("The faction ID of the NPC."),
			"adventure_template_id" => array("The adventure template ID of the NPC."),
			"trap_template" => array("The trap template ID of the NPC."),
			"mindmg" => array("The minimum damage of the NPC."),
			"maxdmg" => array("The maximum damage of the NPC."),
			"attack_count" => array("The attack count of the NPC."),
			"npcspecialattks" => array("DEPRECATED: The special attacks of the NPC.  NPC Special Attacks"),
			"special_abilities" => array("The special abilities of the NPC.  NPC Special Attacks"),
			"aggroradius" => array("The aggro radius of the NPC."),
			"assistradius" => array("The assist radius of the NPC."),
			"face" => array("The face ID of the NPC."),
			"luclin_hairstyle" => array("The hair ID of the NPC."),
			"luclin_haircolor" => array("The hair color ID of the NPC."),
			"luclin_eyecolor" => array("The eye one color ID of the NPC."),
			"luclin_eyecolor2" => array("The eye two color ID of the NPC."),
			"luclin_beardcolor" => array("The beard color ID of the NPC."),
			"luclin_beard" => array("The beard ID of the NPC."),
			"drakkin_heritage" => array("The Drakkin heritage ID of the NPC."),
			"drakkin_tattoo" => array("The Drakkin tattoo ID of the NPC."),
			"drakkin_details" => array("The Drakkin details ID of the NPC."),
			"armortint_id" => array("The armor tint ID of the NPC."),
			"armortint_red" => array("The amount of Red coloring on the NPCs armor."),
			"armortint_green" => array("The amount of Green coloring on the NPCs armor."),
			"armortint_blue" => array("The amount of Blue coloring on the NPCs armor."),
			"d_meele_texture1" => array("The model in the primary hand of the NPC."),
			"d_meele_texture2" => array("The model in the secondary hand of the NPC."),
			"prim_melee_type" => array("The primary melee type of the NPC."),
			"sec_melee_type" => array("The secondary melee type of the NPC."),
			"runspeed" => array("The run speed of the NPC."),
			"MR" => array("The Magic Resistance of the NPC."),
			"CR" => array("The Cold Resistance of the NPC."),
			"DR" => array("The Disease Resistance of the NPC."),
			"FR" => array("The Fire Resistance of the NPC."),
			"PR" => array("The Posion Resistance of the NPC."),
			"Corrup" => array("The Corruption Resistance of the NPC."),
			"PhR" => array("The Physical Resistance of the NPC."),
			"see_invis" => array("Determines whether or not the NPC can see players using Invisibility. 0 = No, 1 = Yes"),
			"see_invis_undead" => array("Determines whether or not the NPC can see players using Invisibility VS. Undead. 0 = No, 1 = Yes"),
			"qglobal" => array("Determines whether or not the NPC can load quest globals. 0 = No, 1 = Yes"),
			"AC" => array("The Armor Class of the NPC."),
			"npc_aggro" => array("Determines whether or not the NPC can aggro on other NPCs. 0 = No, 1 = Yes"),
			"spawn_limit" => array("The spawn limit of the NPC."),
			"attack_speed" => array("The attack speed of the NPC. The lower the number, the faster the NPC hits."),
			"findable" => array("Determines whether or not the NPC is findable. 0 = No, 1 = Yes"),
			"STR" => array("The Strength of the NPC."),
			"STA" => array("The Stamina of the NPC."),
			"DEX" => array("The Dexterity of the NPC."),
			"AGI" => array("The Agility of the NPC."),
			"_INT" => array("The Intelligence of the NPC."),
			"WIS" => array("The Wisdom of the NPC."),
			"CHA" => array("The Charisma of the NPC."),
			"see_hide" => array("Determines whether or not the NPC can see players using Hide. 0 = No, 1 = Yes"),
			"see_improved_hide" => array("Determines whether or not the NPC can see players using Improved Hide. 0 = No, 1 = Yes"),
			"trackable" => array("Determines whether or not the NPC is trackable. 0 = No, 1 = Yes"),
			"isbot" => array("Determines whether or not the NPC is a bot. 0 = No, 1 = yes"),
			"exclude" => array("Leave this at 1."),
			"ATK" => array("The Attack of the NPC."),
			"Accuracy" => array("The Accuracy of the NPC."),
			"slow_mitigation" => array("The slow mitigation of the NPC."),
			"version" => array("The version of the NPC."),
			"maxlevel" => array("The max level of the NPC. This makes the mob capable of spawning between levels 'level' to 'maxlevel'."),
			"scalerate" => array("The rate at which the NPC scales based on level. 100 = 100%, 50 = 50%, 25 = 25%, etc."),
			"private_corpse" => array("Determines whether or not the NPC, when killed, is a private corpse. 0 = No, 1 = Yes"),
			"unique_spawn_by_name" => array("Determines whether or not the NPC is a unique spawn. 0 = No, 1 = Yes"),
			"underwater" => array("Determines whether or not the NPC is underwater. 0 = No, 1 = Yes"),
			"isquest" => array("Determines whether or not the NPC is a quest NPC. 0 = No, 1 = Yes"),
			"emoteid" => array("The emote ID of the NPC. On aggro, the NPC will aggro this message rather than something from a quest"),
			"spellscale" => array("The rate at which the NPC spells scale based on level. 100 = 100%, 50 = 50%, 25 = 25%, etc."),
			"healscale" => array("The rate at which the NPC heals scale based on level. 100 = 100%, 50 = 50%, 25 = 25%, etc."),
			"no_target_hotkey" => array("Determines whether or not players can target the NPC with their target hotkey. 0 = Yes, 1 = No"),
		);
		return $field_desc[$field][0];
	}
	function GetZoneListSelect($zone_name){
		$sql = "SELECT `long_name`, `zoneidnumber`, `short_name` FROM `zone` ORDER BY `zoneidnumber`";
		$result = mysql_query($sql);
		$ret .= '<select class="form-control" id="zoneselect" title="Select the Zone you wish to list NPCs For">';
		while($row = mysql_fetch_array($result)){
			if($zone_name == $row['short_name']){ $sel = "selected"; } else { $sel = ""; }
			$ret .= '<option value="'. $row['short_name'] . '" ' . $sel . '>' . $row['short_name'] . ' - ' .  $row['long_name'] . ' - (' .  $row['zoneidnumber'] .  ': ' . $row['short_name'] . ')' . '</option>';
		}
		$ret .= '</select>';
		return $ret;
	}
	function NPCTableHeader($TableName, $HeaderArray, $TableData = ""){ 
		$return .= '<table '. $TableData . '><tbody><thead class="header">';	
		$n = 1;
		foreach($HeaderArray as $HeaderArray){ 
			if($n == 1 || $n == 2 || $n == 3){ $FixedCol = 'class="headcol' . $n . '"'; } else{ $FixedCol = ""; }
			$return .= '<td style="font-size:12px;text-align:center;vertical-align:bottom;" '. $FixedCol . '> '. $HeaderArray . '</td>'; 
			$n++;
		}
		$return .= '</thead>'; 
		return $return;
	} 
	function NPCTableRow($RowArray, $RowData = ""){
		$return = "";
		$return .= '<tr '. $RowData . '>';
			$n = 1;
			foreach($RowArray as $RowArray){
				if($n == 1 || $n == 2 || $n == 3){ $FixedCol = 'class="headcol' . $n . '"'; } else{ $FixedCol = "class='npc_col' "; }
				$return .= '<td '. $FixedCol . '>'. $RowArray . ' </td>';
				$n++;
			}
		$return .= '</tr>';
		return $return;
	} 
	function NPCTableRowCenter($RowArray, $RowData = ""){
		$return .= '<tr '. $RowData . '>';
			foreach($RowArray as $RowArray){
				$return .= '<td align="center">'. $RowArray . ' </td>';
			}
		$return .= '</tr>';
		return $return;
	} 
	function NPCTableEnd(){ $return .= '</tbody></table>'; return $return; }  
	
	$npc_fields = array(
		"level" => "Level",
		"race" => "Race<br>",
		"class" => "Class",
		"bodytype" => "Body Type",
		"mana" => "Mana",
		"gender" => "Gender",
		"texture" => "Texture",
		"helmtexture" => "Helm Texture",
		"size" => "Size",
		"hp_regen_rate" => "HP Regen",
		"mana_regen_rate" => "Mana Regen",
		"loottable_id" => "Loottable ID",
		"merchant_id" => "Merchant ID",
		"alt_currency_id" => "Alt Currency ID",
		"npc_spells_id" => "Spellset ID",
		"npc_faction_id" => "Faction ID",
		"adventure_template_id" => "Adventure Template ID",
		"trap_template" => "Trap Template ID",
		"mindmg" => "Min_DMG",
		"maxdmg" => "Max_DMG",
		"attack_count" => "Attack Count",
		"npcspecialattks" => "Special Attacks (Deprecated) ",
		"special_abilities" => "Special Abilities",
		"aggroradius" => "Aggro Radius",
		"face" => "Face",
		"luclin_hairstyle" => "Luclin Hairstyle",
		"luclin_haircolor" => "Luclin Haircolor",
		"luclin_eyecolor" => "Luclin Eye Color",
		"luclin_eyecolor2" => "Luclin Eye Color2",
		"luclin_beardcolor" => "Luclin Beard Color",
		"luclin_beard" => "Luclin Beard",
		"drakkin_heritage" => "Drakkin Heritage",
		"drakkin_tattoo" => "Drakkin Tattoo",
		"drakkin_details" => "Drakkin Details",
		"armortint_id" => "Armor Tint ID",
		"armortint_red" => "Armor Tint Red",
		"armortint_green" => "Armor Tint Green",
		"armortint_blue" => "Armor Tint Blue",
		"d_meele_texture1" => "Melee Weapon 1",
		"d_meele_texture2" => "Melee Weapon 2",
		"prim_melee_type" => "Melee Skill Primary",
		"sec_melee_type" => "Melee Skill Secondary",
		"runspeed" => "Run Speed",
		"see_invis" => "See Invis",
		"see_invis_undead" => "See Invis Undead",
		"qglobal" => "QGlobals Enabled",
		"npc_aggro" => "NPC Aggro",
		"spawn_limit" => "Spawn Limit",
		"attack_speed" => "Attack Speed",
		"findable" => "Findable",
		"see_hide" => "See Hide",
		"see_improved_hide" => "See Improved Hide",
		"trackable" => "Trackable",
		"isbot" => "Is Bot?",
		"exclude" => "Exclude",
		"slow_mitigation" => "Slow Mitigation",
		"version" => "Version (Unused)",
		"maxlevel" => "Max Level",
		"scalerate" => "Scale Rate",
		"private_corpse" => "Private Corpse",
		"unique_spawn_by_name" => "Unique Spawn By Name",
		"underwater" => "Underwater",
		"isquest" => "Is Quest NPC?",
		"emoteid" => "Emote ID",
		"spellscale" => "Spell Scale",
		"healscale" => "Heal Scale",
		"MR" => "Magic Resist",
		"CR" => "Cold Resist",
		"DR" => "Disease Resist",
		"FR" => "Fire Resist",
		"PR" => "Poison Resist",
		"AC" => "Armor Class",
		"id" => "NPC ID",
		"name" => "NPC Name",
		"lastname" => "NPC Lastname",
	);
	
	$npcfieldscat = array(
		"level" => "General",
		"race" => "Appearance",
		"class" => "General",
		"bodytype" => "General",
		"mana" => "Vitals",
		"gender" => "General",
		"texture" => "Appearance",
		"helmtexture" => "Appearance",
		"size" => "Appearance",
		"hp_regen_rate" => "Vitals",
		"mana_regen_rate" => "Vitals",
		"loottable_id" => "Combat",
		"merchant_id" => "General",
		"alt_currency_id" => "General",
		"npc_spells_id" => "Combat",
		"npc_faction_id" => "Combat",
		"adventure_template_id" => "Misc.",
		"trap_template" => "Misc.",
		"mindmg" => "Combat",
		"maxdmg" => "Combat",
		"attack_count" => "Combat",
		"npcspecialattks" => "Combat",
		"aggroradius" => "Combat",
		"special_abilities" => "Combat",
		"face" => "Appearance",
		"luclin_hairstyle" => 	"Appearance",
		"luclin_haircolor" => 	"Appearance",
		"luclin_eyecolor" => 	"Appearance",
		"luclin_eyecolor2" => 	"Appearance",
		"luclin_beardcolor" => 	"Appearance",
		"luclin_beard" => 		"Appearance",
		"drakkin_heritage" => 	"Appearance",
		"drakkin_tattoo" => 	"Appearance",
		"drakkin_details" => 	"Appearance",
		"armortint_id" => 		"Appearance",
		"armortint_red" => 		"Appearance",
		"armortint_green" => 	"Appearance",
		"armortint_blue" => 	"Appearance",
		"d_meele_texture1" => 	"Visual Texture",
		"d_meele_texture2" => 	"Visual Texture",
		"prim_melee_type" => 	"Appearance",
		"sec_melee_type" => 	"Appearance",
		"runspeed" => "Vitals",
		"see_invis" => "Vitals",
		"see_invis_undead" => "Vitals",
		"qglobal" => "Misc.",
		"npc_aggro" => "Misc.",
		"spawn_limit" => "Misc.",
		"attack_speed" => "Combat",
		"findable" => "Misc.",
		"see_hide" => "Combat",
		"see_improved_hide" => "Combat",
		"trackable" => "Misc.",
		"isbot" => "Misc.",
		"exclude" => "Misc.",
		"slow_mitigation" => "Combat",
		"version" => "Misc.",
		"maxlevel" => "Vitals",
		"scalerate" => "Vitals",
		"private_corpse" => "Private Corpse",
		"unique_spawn_by_name" => "Unique Spawn By Name",
		"underwater" => "Underwater",
		"isquest" => "Misc.",
		"emoteid" => "Misc.",
		"spellscale" => "Combat",
		"healscale" => "Combat",
		"MR" => "Stats",
		"CR" => "Stats",
		"DR" => "Stats",
		"FR" => "Stats",
		"PR" => "Stats",
		"AC" => "Stats",
		"id" => "General",
		"name" => "General",
		"lastname" => "General",
		"Corrup" => "Stats",
		"ATK" => "Stats",
		"Accuracy" => "Stats",
		"CHA" => "Stats",
		"STA" => "Stats",
		"STR" => "Stats",
		"DEX" => "Stats",
		"AGI" => "Stats",
		"WIS" => "Stats",
		"_INT" => "Stats",
		"hp" => "Vitals",
	);
	
	function GetNPCTypesSelector(){
		$query = "show columns from npc_types";
		$result = mysql_query($query);
		$ret .= '<select id="npctypesfield" class="form-control">';
		while($row = mysql_fetch_array($result)){ 
			if($row[0] != "id"){
				$ret .= '<option value="'. $row[0] . '">' . $row[0] . '</option>';
			}
		}
		$ret .= '</select>';
		return $ret;
	}
	
	$Custom_Select_Fields = array(
		"prim_melee_type" => 1,
		"sec_melee_type" => 1,
		"size" => 1,
		"npc_aggro" => 1,
		"qglobal" => 1,
		"isbot" => 1,
		"trackable" => 1,
		"findable" => 1,
		"isquest" => 1,
		"emoteid" => 1,
		"trap_template" => 1,
		"adventure_template_id" => 1,
		"bodytype" => 1,
		"gender" => 1,
		"alt_currency_id" => 1,
		"armortint_id" => 1,
		"class" => 1,
		"see_improved_hide" => 1,
		"npc_spells_id" => 1,
		"see_hide" => 1,
		"npc_faction_id" => 1,
	);
	
	$trap_types = array(
		1 => "(Mechanical)",
		2 => "(Magical)",
		3 => "(Cursed)",
	);
	
	$adventure_templates = array(
		1 => "ldon_points_guk",
		2 => "ldon_points_mir",
		3 => "ldon_points_mmc",
		4 => "ldon_points_ruj",
		5 => "ldon_points_tak",
	);
	
	$genders = array(
		0 => "Male",
		1 => "Female",
		2 => "Monster",
	);
	
	$yes_no = array(0 => "No", 1 => "Yes");
	
	function GetFieldSelect($field_name, $value, $npc_id, $from_npc_grid_tool = 0){
		global $EditOptions, $yes_no, $trap_types, $adventure_templates, $bodytypes, $genders, $dbclasses;
		$found_select = 0;
		$ret .= "<select class='form-control' title='" . ProcessFieldTitle($field_name) . "'  value='" . $value . "' id='" . $npc_id . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $npc_id . ", \"" . $field_name . "\", this.value)'>";
		if($field_name == "prim_melee_type" || $field_name == "sec_melee_type"){
			foreach ($EditOptions['extradmgskill'] as $key => $val){
				if($key == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $key . '" ' . $sel . '>'. $key . ': ' . $val . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "adventure_template_id"){
			$ret .= '<option value="0">0: None</option>';
			foreach ($adventure_templates as $key => $val){
				if($key == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $key . '" ' . $sel . '>'. $key . ': ' . $val . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "class"){
			$ret .= '<option value="0">0: None</option>';
			foreach ($dbclasses as $key => $val){
				if($key == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $key . '" ' . $sel . '>'. $key . ': ' . $val . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "gender"){
			$ret .= '<option value="0">0: None</option>';
			foreach ($genders as $key => $val){
				if($key == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $key . '" ' . $sel . '>'. $key . ': ' . $val . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "bodytype"){
			$ret .= '<option value="0">0: None</option>';
			foreach ($bodytypes as $key => $val){
				if($key == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $key . '" ' . $sel . '>'. $key . ': ' . $val . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "size"){
			for($i=1;$i<=255;$i++){
				if($i == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $i . '" ' . $sel . '>'. $i . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "emoteid"){
			$query = "SELECT * FROM `npc_emotes`";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['emoteid'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['emoteid'] . '" ' . $sel . '>'. $row['emoteid'] . ': ' . (strlen($row['text']) > 100 ? (substr($row['text'], 0, 100) . '...') : $row['text']) . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "armortint_id"){
			$query = "SELECT * FROM `npc_types_tint` order by `id`";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['id'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['id'] . '" ' . $sel . '>'. $row['id'] . ': ' . $row['tint_set_name'] . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "npc_spells_id"){
			$query = "SELECT * FROM `npc_spells` order by `id`";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['id'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['id'] . '" ' . $sel . '>'. $row['id'] . ': ' . $row['name'] . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "npc_faction_id"){
			$query = "SELECT * FROM `npc_faction` order by `id`";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['id'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['id'] . '" ' . $sel . '>'. $row['id'] . ': ' . $row['name'] . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "trap_template"){
			$query = "SELECT
				ldon_trap_templates.id,
				ldon_trap_templates.type,
				ldon_trap_templates.spell_id,
				ldon_trap_templates.skill,
				ldon_trap_templates.locked,
				spells_new.`name`
				FROM
				ldon_trap_templates
				INNER JOIN spells_new ON ldon_trap_templates.spell_id = spells_new.id
				order by ldon_trap_templates.id
				";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['id'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['id'] . '" ' . $sel . '>'. $row['id'] . ': ' . $trap_types[$row['type']] . ' Spell: ' . $row['name'] . '</option>';
			}
			$found_select = 1;
		} 
		else if($field_name == "alt_currency_id"){
			$query = "SELECT
				alternate_currency.id,
				alternate_currency.item_id,
				items.`Name`
				FROM
				alternate_currency
				INNER JOIN items ON alternate_currency.item_id = items.id
				ORDER BY `id`
				";
			$result = mysql_query($query); $eid_data = array();
			$ret .= '<option value="0">0: None</option>';
			while($row = mysql_fetch_array($result)){ 
				if($row['id'] == $value){ $sel = "selected"; } else { $sel = ""; } 
				$ret .= '<option value="'. $row['id'] . '" ' . $sel . '>'. $row['id'] . ': ' . $row['Name'] . '</option>';
			}
			$found_select = 1;
		}
		else if($field_name == "npc_aggro"
			|| $field_name == "qglobal"
			|| $field_name == "isbot"
			|| $field_name == "trackable"
			|| $field_name == "findable"
			|| $field_name == "isquest"
			|| $field_name == "see_improved_hide"
			|| $field_name == "see_hide"
		){
			for($i=0;$i<=1;$i++){
				if($i == $value){ $sel = "selected"; } else { $sel = ""; }
				$ret .= '<option value="'. $i . '" ' . $sel . '>'. $i . ': ' . $yes_no[$i] . '</option>';
			}
			$found_select = 1;
		}
		if($found_select == 0 && $from_npc_grid_tool){
			return "";
		}
		$ret .= '</select>';
		return $ret;
	}
	
?>