<?php

	/* 
		Spell Editor: Akkadius
		Requires - Main File 
	*/
	
	require_once('includes/config.php');
	require_once('includes/constants.php');
	require_once('includes/alla_functions.php');
	require_once('includes/spell.inc.php'); 
	require_once('modules/SpellEditor/functions.php'); 
	
	/* Include JS */
	$FJS .= '<script type="text/javascript" src="modules/SpellEditor/js/js.js"></script>'; 
	$FJS .= '<script type="text/javascript" src="cust_assets/js/lazy-load.js"></script>';

	/* 
		Edit Spell Data 
	*/
	
	if($_GET['Edit']){
	
		# echo '<center>';
	
		$result = mysql_query("SELECT * FROM spells_new WHERE `id` = ". $_GET['Edit'] . ";");
		$columns = mysql_num_fields($result);
	
		PageTitle('Spell Edit :: ID ' . mysql_result($result, 0, 0) . ' :: ' . mysql_result($result, 0, 1) . '');
	
		echo '<h2 class="page-title">Spell Editor :: ID ' . mysql_result($result, 0, 0) . ' :: ' . mysql_result($result, 0, 1) . '</h2><hr>';
		
		/*
			We want to be able to dynamically iterate through any users database so that the editor does not break...
			The trick is to still make sense of it in code...
			So we will track with arrays...
		*/
		
		$spell_ef_acf = array(); /* Will dynamically keep track of called out fields so that any left over from the table can be printed at the end */
		$spell_editor_fields = array();
		/* spell_editor_fields structure 
			[0] => Actual Input Fields
			[1] => Description of the Field itself (if it is available of course)
		*/
		
		for ($i = 0; $i < $columns; $i++){
			$FieldName = mysql_field_name($result, $i);
			$FieldData = mysql_result($result, 0, $i);
			$spell_editor_fields[$FieldName] = array(SpellFieldInput($_GET['Edit'], $FieldName, $FieldData), $spells_new_fields[$FieldName][0]);
		}
		
		// echo '<code>';
		foreach ($layout_map as $key => $val){
			if($key == 13){ $width = 'width:100%;'; } else { $width = 'width:auto;'; } 
			
			if($key == 0){ echo SectionHeader( 'Basics'); }
			if($key == 3){ echo SectionHeader( 'Messages'); }
			if($key == 4){ echo SectionHeader( 'Attributes'); }
			if($key == 8){ echo SectionHeader( 'Effects', 'Determines what type of effect the effect ID has'); }
			if($key == 9){ echo SectionHeader( 'Base Value', 'The amount that is directly relational to the effect ID that is set'); }
			if($key == 10){ echo SectionHeader('Limit Value', 'The maximum amount that is used, directly relational to the effect ID'); }
			if($key == 11){ echo SectionHeader('Max Value', 'The maximum effect base value'); }
			if($key == 12){ echo SectionHeader('Formulas', 'The formula used to determine the way the effect base value scales'); }
			if($key == 13){ echo SectionHeader('Classes', 'Setting levels of classes that can use this spell'); }
			if($key == 14){ echo SectionHeader('Deities'); }
			if($key == 15){ echo SectionHeader('Reagents', 'Settings related to spell reagents'); }
			if($key == 18){ echo SectionHeader('Settings'); }
			
			echo '<table class="table table-striped table-bordered table-hover dataTable no-footer" style="' . $width . 'margin-bottom:5px !important;">';
				/* 
					We will walk as many members of the array is available for the row...
					This will allow us to print as many members there are for fields...
				*/ 
				echo '<tr>';
				$n = 0;
				while($val[$n]){
					$spell_ef_acf[$val[$n]] = 1; /* Account for fields from the map, then we will iterate through them all again below... */
					
					if($val[$n] == "spellanim"){ $max_width = '500'; } 
					else if($val[$n] == "you_cast"){ $max_width = '400'; } 
					else if($val[$n] == "other_casts"){ $max_width = '400'; } 
					else if($val[$n] == "cast_on_you"){ $max_width = '400'; } 
					else if($val[$n] == "cast_on_other"){ $max_width = '400'; } 
					else if($val[$n] == "spell_fades"){ $max_width = '400'; } 
					else{ $max_width = '200'; }
					
					echo '<td style="text-align:center;min-width:100px;max-width:' . $max_width . 'px"><center><b>' . $val[$n] . '</b>' . $spell_editor_fields[$val[$n]][0] . '<small> ' . $spell_editor_fields[$val[$n]][1] . '</td>'; 
					$n++; 
				} 
				echo '</tr>'; 
			echo '</table>';	
		}
		
		/*
			Loop through the database fields in the table one more time so that any that haven't been printed from the map can be printed... 
		*/
		echo SectionHeader('Remaining Fields');
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer" style="width:0%">';
		for ($i = 0; $i < $columns; $i++){
			$FieldName = mysql_field_name($result, $i);
			$FieldData = mysql_result($result, 0, $i);
			if($spell_ef_acf[$FieldName] != 1){ 
				echo '<td style="text-align:center">' . $FieldName  . '<br>' . $spell_editor_fields[$FieldName][0] . '</td>'; 
			}
		}
		echo '</table>';	
		
		$FJS .= '
			<script type="text/javascript">
				$("select").each(function() { $(this).tooltip(); });
				$(":text").each(function() {  $(this).tooltip({ placement: \'bottom\' }); });
			</script>';
	}
	
	/* 
		Searching functionality below
	*/
	else{
		PageTitle('Spell Search');
		/* Variables */
		$opt = (isset($_GET['opt']) ? $_GET['opt'] : ''); /* Radio Buttons - Only - And Higher - And Lower */
		$c['level'] = (isset($c['level']) ? $c['level'] : 0);
		$c['type'] = (isset($c['type']) ? $c['type'] : 0);
		$c['effect_type'] = (isset($c['effect_type']) ? $c['effect_type'] : -1); 
		$c['spell_category'] = (isset($c['spell_category']) ? $c['spell_category'] : 0); 
		$c['field_filter_1'] = (isset($c['field_filter_1']) ? $c['field_filter_1'] : ''); 
		$c['search_string'] = (isset($c['search_string']) ? $c['search_string'] : ''); 
		$c['npc_category'] = (isset($c['npc_category']) ? $c['npc_category'] : -1); 
		$c['target_type'] = (isset($c['target_type']) ? $c['target_type'] : -1); 
		$c['zone_type'] = (isset($c['zone_type']) ? $c['zone_type'] : -2); 
		$c['resist_type'] = (isset($c['resist_type']) ? $c['resist_type'] : -1); 
		
		if($Value > 0) { $opt = 2; }
		
		// echo '<pre>'; echo var_dump($c); echo '</pre>';
		
		$FJS .= '<script type="text/javascript">
				do_comp_sel = 0;
				$( "#comp_select" ).mouseover(function(e) {
					if(do_comp_sel != 1){
						DoComponentsSelect(\'' . $c['components'] . '\');
					}
					do_comp_sel = 1;
				});
		</script>'; 
		
		/* Check Level */
		if(!$c['level'])	{ $c['level'] = 0; }
		/* Check Options */
		if($opt == 1) 		{ $check1 = "checked"; $OpDiff = 0; 	$ClassOper = "="; }
		elseif($opt == 2) 	{ $check2 = "checked"; $OpDiff = -1; 	$ClassOper = ">="; }
		elseif($opt == 3) 	{ $check3 = "checked"; $OpDiff = 1;		$ClassOper = "<="; }
		else { $check2 = "checked"; $OpDiff = 0; $ClassOper = ">="; }
		
		
		/* Display Spell Form */
		echo  '
		<center>
			<h2 class="page-title"><i class="fa fa-search"></i> Spell Search</h2><hr>
			<table border="0"><tr align="left"><td> 
				<form action="index.php">
					<input type="hidden" name="M" value="SpellEditor" class="form-control">
				
					<table class="table"> 
					<tr><td>Search For</td>
						<td><input type="text" class="form-control" name="search_string" size="40" value="' . $_GET['search_string'] . '"> 
							<small><i>Searches name, description and casting messages</i></small>
						</td>
					</tr>
					<tr><td>Class</td>
						<td>
							<select name="type" class="form-control">
								<option value="0"' . ($c['type'] == 0 ? ' selected="1"' : '') . '>--- Select ---</option>';
								foreach ($EditOptions['classes'] as $key => $val){ 
									if($c['type'] == $key){ $sel = " selected"; } else { $sel = ""; }
									echo '<option value="' . $key . '" ' . $sel . '>' . $val . '</option>';
								}
					
					echo '	</select>
						</td>
					</tr>
					<tr>
						<td>Level</td>
						<td><select name="level" class="form-control">
							<option value="-1">--- Select ---</option>'; 
							for($i=1; $i <= 250; $i++) { 
								echo  '<option value="' . $i . '"' . ($c['level'] == $i ? ' selected="1"' : '') . '>' . $i . '</option>'; 
							}
				echo  '</select> 
						<br>
						<label><input type="radio" name="opt" value="1" '.$check1.' />Only</label> 
						<label><input type="radio" name="opt" value="2" '.$check2.' />And Higher</label> 
						<label><input type="radio" name="opt" value="3" '.$check3.' />And Lower</label>
					</td>
				</tr>
				
				<!-- Spell Effect Type -->
				<tr>
					<td>Spell Effect Type <br><small>Spells with this effect in effectid1-12</small></td>
					<td>
					<select name="effect_type" class="form-control">
						<option value="-1">--- Select ---</option>
						';
						foreach($Spell_Effects as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['effect_type'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val[0] . ' --- ' . $val[1] . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Target Type -->
				<tr>
					<td>Target Type <br></td>
					<td>
					<select name="target_type" class="form-control">
						<option value="-1">--- Select ---</option>
						';
						foreach($target_type_enums as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['target_type'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val[0] . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Components/Reagents -->
				<tr>
					<td>Components/Reagents</td>
					<td> 
						<div id="comp_select"> 
							<select name="components" class="form-control"> <option value="0">--- Select ---</option></select>
						</div>
					</td>
				</tr>
				
				<!-- Spell Category -->
				<tr>
					<td>Spell Category</td>
					<td>
					<select name="spell_category" class="form-control">
						<option value="0">--- Select ---</option>
						';
						foreach($Spell_Categories as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['spell_category'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- NPC Category -->
				<tr>
					<td>NPC Category</td>
					<td>
					<select name="npc_category" class="form-control">
						<option value="-1">--- Select ---</option>
						';
						foreach($npc_category as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['npc_category'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Base Formula -->
				<tr>
					<td>Base Formula <br><small>Spells with this Base formula in formula1-12</small></td>
					<td>
					<select name="base_formula" class="form-control">
						<option value="0">--- Select ---</option> ';
						foreach($Base_Formulas as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['base_formula'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Resist Type -->
				<tr>
					<td>Resist Type</small></td>
					<td>
					<select name="resist_type" class="form-control">
						<option value="-1">--- Select ---</option> ';
						foreach($dbspellresists as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['resist_type'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Zone Type -->
				<tr>
					<td>Zone Type</td> 
					<td>
					<select name="zone_type" class="form-control">
						<option value="-2">--- Select ---</option> ';
						foreach($zone_type as $key => $val){
							echo  '<option value="' . $key . '"' . ($c['zone_type'] == $key ? ' selected="1"' : '') . '>' . $key . ': ' . $val . '</option>'; 
						}			
					
				echo '</select>
					</td>
				</tr>
				
				<!-- Field Filter #1 -->
				<tr>
					<td>Field Filter <br><small>Use a DB field to filter on</small></td>
					<td>
					<select name="field_filter_1" class="form-control" style="width:300px; display:inline">
						<option value="0">--- Select ---</option>';
						/* Dynamically Get Fields */
						$result = mysql_query("SELECT * FROM `spells_new` WHERE `id` > 1 LIMIT 1;");
						$columns = mysql_num_fields($result);
						for ($key = 0; $key < $columns; $key++){
							$val = mysql_field_name($result, $key); 
							echo  '<option value="' . $val . '"' . ($c['field_filter_1'] == $val ? ' selected="1"' : '') . '>' . $val . ' </option>'; 
						}		
				echo '</select>
					<select name="field_filter_1_compare_type" class="form-control" style="width:300px; display:inline">
						' . FieldFilterOptions($c['field_filter_1_compare_type']) . '
					</select>
					<input type="text" class="form-control" style="width:300px; display:inline" name="field_filter_1_val" value="' . $_GET['field_filter_1_val'] . '">
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<button type="submit" value="Search" class="btn btn-default green"/><i class="fa fa-search"></i> Search</button>
						<button type="reset" value="Reset" class="btn btn-default blue" onclick="window.location.assign(\'index.php?M=SpellEditor\')"><i class="fa fa-refresh"></i>  Reset Form</button>
					</td>
				</tr>
			</table>
		</form>';

		/* Query Logic */
		$p_f = 0; /* Set Previous Filter Bit */
		
		if (!$c['level']) { $c['level'] = 0; $ClassOper = ">"; }
		$sql = 'SELECT * FROM spells_new WHERE ';
		$sv = '';
		if (isset($c['type']) && $c['type'] > 0) { 
			$sql .= ' ' . 'spells_new' .'.classes' . $c['type'] . " " . $ClassOper .  " " . $c['level'] . '  AND ' . 'spells_new' .'.classes' . $c['type'] . ' <= '. '250';
			$p_f = 1; /* Set Filter on Bit */
		} 
		
		/*
			All of the Query Filters
		*/
		
		/* If $c['search_string'] is numeric then we will parse it as an ID and pass it to the search query... */
		if(is_numeric($c['search_string'])){ 
			$qadd = '	OR spells_new.id LIKE \'%' . mysql_real_escape_string($c['search_string']) . '%\''; 
		}
		
		/* Spell Name Filter */
		if($c['search_string'] != ''){  
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (spells_new.name LIKE \'%' . mysql_real_escape_string($c['search_string']) . '%\'  ' . $qadd . ') '; 
			$p_f = 1; 
		}
		
		/* Spell Effect Types */
		if($c['effect_type'] > 0){  
			for($i = 2; $i <= 12; $i++){
				$e_ids .= 'OR `effectid' . $i . '` = ' . $c['effect_type'] . ' '; 
			}
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`effectid1` = \'' . $c['effect_type'] . '\' ' . $e_ids . ')';
			$p_f = 1; /* Set Filter on Bit */
		}
		
		/* Base Formulas */
		if($c['base_formula'] > 0){  
			for($i = 2; $i <= 12; $i++){
				$f_ids .= 'OR `formula' . $i . '` = ' . $c['base_formula'] . ' '; 
			} 
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`formula1` = \'' . $c['base_formula'] . '\' ' . $f_ids . ')';   
			$p_f = 1; /* Set Filter on Bit */
		}
		
		/* Components */
		if($c['components'] > 0){  
			$c_ids = "";
			for($i = 2; $i <= 4; $i++){
				$c_ids .= 'OR `components' . $i . '` = ' . $c['components'] . ' '; 
			} 
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`components1` = \'' . $c['components'] . '\' ' . $c_ids . ')';   
			$p_f = 1; /* Set Filter on Bit */		
		}
		
		/* target_type */
		if($c['target_type'] >= 0){
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`targettype` = ' . $c['target_type'] . ' )'; 
			$p_f = 1; /* Set Filter on Bit */		
		}
		
		/* resist_type */
		if($c['resist_type'] >= 0){
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`resisttype` = ' . $c['resist_type'] . ' )'; 
			$p_f = 1; /* Set Filter on Bit */		
		}
		
		/* Spell Category Filter */
		if($c['spell_category'] > 0){  
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`spell_category` = \'' . $c['spell_category'] . '\')';
			$p_f = 1; /* Set Filter on Bit */		
		}  
		
		/* zone_type Filter */
		if($c['zone_type'] >= -1){  
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`zonetype` = ' . $c['zone_type'] . ')';  
			$p_f = 1; /* Set Filter on Bit */
		} 
		
		/* npc_category Filter */ 
		if($c['npc_category'] > 0){  
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			$sql .= ' (`npc_category` = \'' . $c['npc_category'] . '\')';
			$p_f = 1; /* Set Filter on Bit */		
		} 
		
		/* Field Filter 1 */ 
		if($c['field_filter_1'] != '' && $c['field_filter_1_compare_type'] > 0){ 
			if($p_f == 1){ $sql .= ' AND '; } /* If a previous filter is set */
			if($c['field_filter_1_compare_type'] < 4){ /* Number Comparisons or Equals */
				$sql .= ' (`' . $c['field_filter_1'] . '` ' . $FieldFilterOptions[$c['field_filter_1_compare_type']][0] . ' \'' . $c['field_filter_1_val'] . '\')';  
			}
			else if($c['field_filter_1_compare_type'] == 4){ /* Like */
				$sql .= ' (`' . $c['field_filter_1'] . '` LIKE \'%' . $c['field_filter_1_val'] . '%\')';  
			}
			$p_f = 1; /* Set Filter on Bit */
		}  
		/* Class Filters */
		if ($c['type'] != 0) { $sql .= ' ORDER BY ' . 'spells_new' .'.classes' . $c['type'] . ', ' . 'spells_new' . '.name'; }
		else { $sql .= ' ORDER BY ' . 'spells_new' . '.name '; }
		$Minimal = 0;  

		/* 
			Fallback query for default form 
			Each Fields would be default is checked here
		*/
		if($c['type'] == 0 
			&& $c['level'] == 0 
			&& $c['search_string'] == "" 
			&& $c['effect_type'] == -1  
			&& $c['spell_category'] == 0
			&& $c['field_filter_1'] == ''
			&& $c['npc_category'] == -1
			&& $c['target_type'] == -1
			&& $c['zone_type'] == -2
			&& $c['resist_type'] == -1
		){ 
			$sql = "select * from `spells_new`  ORDER BY `spells_new`.id LIMIT 100 "; 
			$Minimal = 1; 
		} 
		
		echo '<h4>Query</h4><code>' . $sql . '</code>';
		$result = mysql_query($sql); if (!$result) { die('Invalid query: ' . mysql_error()); } 
		echo  '<hr> <center><table class="table table-striped table-bordered table-hover dataTable no-footer" style="width:100%">';
		$levelcheck = $c['level'] + $OpDiff;
		$Class = 'classes' . $c['type'];
		$ClassName = $dbclasses[$c['type']];
		
		if($Minimal == 1){
			echo  '<tr>
					<th>ID</th>
					<th>Name</th>
				</tr>';
				
		}else{
			/* Scroll to for Spells on Search Submit */
			echo '<div id="spell_search_result"></div>';
			
			/* Scroll to Results */
			$FJS .= "
				<script type='text/javascript'>
					$('html, body').animate({
						scrollTop: $('#spell_search_result').offset().top 
					}, 2000);   
				</script>
			";  
		}
		
		/* Show Results */
		while($row = mysql_fetch_array($result)) { 
			/* Show Classes */ 
			$v = ""; $classes_string = "";
			$minlvl = 70;
			for ($i = 1; $i <= 16; $i++) {
				if ($row["classes$i"] > 0 && $row["classes$i"] < 255) {
					$classes_string .= "$v ".$dbclasses[$i]." (".$row["classes$i"].")";
					$v = "<br>";
					if ($row["classes$i"] < $minlvl) { $minlvl = $row["classes$i"]; }
				}
			}
		
			/* This will only come through when the Level Changes */
			if($levelcheck != $row[$Class]) {
				$levelcheck = $row[$Class];
				if($c['level'] > 0){ $l_display = '<tr><th style="border-top: 0px solid #ddd;" colspan="12"><h2>Level: ' . $row['classes'. $c['type']] . '</h2></th></tr>'; } else{ $l_display = ''; }
				echo $l_display;
				echo  '<tr>
							<th style="width:40px">ID</th> 
							<th style="width:100px"></th> 
							<th style="width:300px">Name & Recourse</th>
							<th style="width:50px">Tools</th> 
							<th style="width:100px">Classes</th>  
							<th style="width:100px">Category</th> 
							<th style="width:30px">Mana</th> 
							<th style="width:30px">Endurance</th> 
							<th style="width:30px">End Timer Index</th> 
							<th style="width:20px">Skill</th>
							<th style="width:20px">Resist Type</th>
							<th></th>  
						</tr>';
			}
			
			/* Determine Styles */
			if($dbspellresists_color[$row['resisttype']][2] != ''){ $dbr_btn_ico = $dbspellresists_color[$row['resisttype']][2]; } else { $dbr_btn_ico = ''; }
			if($row['IsDiscipline'] > 0 || ($row['EndurCost'] > 0 || $row['EndurUpkeep'] > 0)){ $spell_type_ico = '<button type="button" class="btn btn-default"><img src="cust_assets/icons/item_778.png" title="504" width="30" height="30" title="Discipline"></button>'; }
			// else{ $spell_type_ico = '<img src="cust_assets/icons/item_504.png" title="504" width="30" height="30" title="Spell">'; } 
			
			$rec_data = array(); $rec_string = '';
			if($row['RecourseLink'] > 0){ 
				$sql2 = "select * FROM `spells_new` WHERE `id` = " . $row['RecourseLink'] . "";
				$result2 = mysql_query($sql2);
				while($row2 = mysql_fetch_array($result2)) { $rec_data[$row['RecourseLink']] = $row2; }
				$rec_string = '<br><a href="javascript:;" class="btn btn-default btn-xs ' . $target_type_enums[$rec_data[$row['RecourseLink']]["targettype"]][1] . '" target='. $row['id'] . ' ' . HoverTip("global.php?spell_view=" . $row['RecourseLink']) . '>' . $rec_data[$row['RecourseLink']]['id'] . ' :: ' . $rec_data[$row['RecourseLink']]['name'] . ' <i class="fa fa-arrow-circle-right"></i></a> ';
			} 
			
			echo  '<tr>
				<td valign="top" style="text-align:center">' . $row['id'] . '</td>
				<td valign="top" style="text-align:center">
					<button type="button" class="btn btn-default">
						<img src="includes/img.php?type=spellimage&id='. $row['new_icon'] . '" style="width:30px;height:30px"> 
					</button>
					' . $spell_type_ico . '   
					<br><small class="btn btn-default btn-xs ' . $target_type_enums[$row["targettype"]][1] . '">' . $dbspelltargets[$row["targettype"]] . '</small>
				</td>
				<td valign="top" style="text-align:center">
					<a href="index.php?M=SpellEditor&Edit=' . $row['id'] . '" class="btn btn-default ' . $target_type_enums[$row["targettype"]][1] . '" target='. $row['id'] . ' ' . HoverTip("global.php?spell_view=" . $row['id']) . '> '. $row['name'] . ' <i class="fa fa-arrow-circle-right"></i></a> 
					' . $rec_string . '
				</td>
				<td valign="top" style="text-align:center">
					<a href="javascript:;" class="btn btn-default btn-xs" target='. $row['id'] . ' ' . HoverCloseTip("global.php?spell_view_data_quick=" . $row['id']) . '><i class="fa fa-database"></i> Data </a> 
				</td>
				<td valign="top" style="text-align:center"> <center><small>' . $classes_string . '</small></center></td>
				<td style="text-align:center"><small>'. $Spell_Categories[$row['spell_category']] . '</small></td>
				<td style="text-align:center"><small>'. $row['mana'] . '</small></td>
				<td style="text-align:center"><small>'. $row['EndurCost'] . '</small></td>
				<td style="text-align:center"><small>'. $row['EndurTimerIndex'] . '</small></td>
				<td style="text-align:center"><small>'. ucwords(strtolower($dbskills[$row["skill"]])) . '</small></td> 
				<td style="text-align:center"><small class="btn btn-xs btn-default ' . $dbspellresists_color[$row['resisttype']][1] . ' ">' .  $dbr_btn_ico . ' ' . $dbspellresists[$row['resisttype']] . '</small></td> 
				</tr>';	
		}
		echo  '</tr></table>';
		echo  '</tr></table>';
	}
?>