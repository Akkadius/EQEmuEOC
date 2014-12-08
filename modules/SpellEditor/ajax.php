<?php

	/* Save Field Edit */
	if(isset($_GET['DoFieldEditSave'])){
		echo '<pre>';
		echo var_dump($c);
		echo '</pre>';
		/*
			array(5) {
				  ["M"]=>
				  string(11) "SpellEditor"
				  ["DoFieldEditSave"]=>
				  string(0) ""
				  ["Spell_ID"]=>
				  string(3) "597"
				  ["Field"]=>
				  string(4) "name"
				  ["Val"]=>
				  string(28) "Illusion: Air Elemental     "
			}
		*/
		$sql = 'UPDATE `spells_new` SET `' . $c['Field'] .'` = \'' . $c['Val'] . '\' WHERE `id` = ' . $c['Spell_ID'] . ''; 
		$result = mysql_query($sql);
		if($result){ echo 'Success'; }
	}
	/*
		Populate Components Search Field Select since it can be a bit DB intesive we don't always want it to run on page load 
			unless the user is trying to use it...
	*/
	if(isset($_GET['components_select'])){
		echo '<select name="components" class="form-control"> <option value="0">--- Select ---</option>';
		$sql = "SELECT `id`, `Name` FROM `items`";
		$result = mysql_query($sql);
		$item_id_to_name = array(); 
		while($row = mysql_fetch_array($result)){	
			$item_id_to_name[$row['id']] = $row['Name'];
		} 
		$sql = "SELECT `components1`, `components2`, `components3`, `components4`
			FROM `spells_new` 
			WHERE
			`components1` > 0 OR `components2` > 0 OR `components3` > 0 OR `components4` ";
		$result = mysql_query($sql);
		$n = 0;
		while($row = mysql_fetch_array($result)){	
			if($row['components1'] > 0 && $d_components[$row['components1']] != 1){ $components[$n] = $row['components1']; $d_components[$row['components1']] = 1; $n++; }
			if($row['components2'] > 0 && $d_components[$row['components2']] != 1){ $components[$n] = $row['components2']; $d_components[$row['components2']] = 1; $n++; }
			if($row['components3'] > 0 && $d_components[$row['components3']] != 1){ $components[$n] = $row['components3']; $d_components[$row['components3']] = 1; $n++; }
			if($row['components4'] > 0 && $d_components[$row['components4']] != 1){ $components[$n] = $row['components4']; $d_components[$row['components4']] = 1; $n++; }
		} 
		for($key = 0; $key <= $n; $key++){
			if($item_id_to_name[$components[$key]] != ''){
				echo  '<option value="' . $components[$key] . '"' . ($c['val'] == $components[$key] ? ' selected="1"' : '') . '>' . $components[$key] . ': ' . $item_id_to_name[$components[$key]] . '</option>'; 
			}
		}
		echo '</select>';
	}
	/* Icon Selector */
	if(isset($_GET['DoIconSelect'])){
		echo '<div style="width:600px;height:400px;overflow-y;scroll">'; 
		echo '<style>
				.image {   position: relative;  width: 100%; /* for IE 6 */ }
				.image h2 {   position: absolute;  top: 0px;   left: 14px;  width: 100%;   font-size:11px; }
			</style>';
		for($i=1; $i<1000; $i++){ 
			if(file_exists("cust_assets/icons/" . $i . ".gif") || file_exists("cust_assets/icons/gem_" . $i . "b.png")) { 
				if(file_exists("cust_assets/icons/" . $i . ".gif")){
					$img = "<img class='lazy' src='cust_assets/icons/" . $i . ".gif' title='". $i . "' width='35' height='35'/>";
				}
				if(file_exists("cust_assets/icons/gem_" . $i . "b.png")){
					$img .= "<img class='lazy' src='cust_assets/icons/gem_" . $i . "b.png' title='". $i . "' width='35' height='35'/>";
				}
				echo '<div class="image" style="display:inline">' .  
				"<a href='javascript:;' title='' class='btn btn-default' onclick='UpdateParentFieldSpellIcon(\"" . $_GET['p_f'] . "\", " . $i . ")'>" . $img . "<br><br></a> "
				. '<h2>' . $i . '</h2></div>'; 
			}
		}
		echo '</div>';
	}
	/* Spell Anim Select */
	if(isset($_GET['DoSpellAnimSelect'])){
		echo '<div style="width:600px;height:400px;overflow-y;scroll">'; 
		for($i=0; $i < 1000; $i++){
			if(file_exists("cust_assets/spell_animations/" . $i . ".flv")) {  
				echo '<a href="javascript:;" class="btn btn-default" onclick="PreviewSpellAnim(' . $i . ')">' . $i . '</a> ';
			}
		} 
		echo '</div>';
	}
	/* Do Video Preview */
	if($_GET['DoVideoPreview']){
		echo '<object width="360" height="420" data="http://releases.flowplayer.org/swf/flowplayer-3.1.5.swf" type="application/x-shockwave-flash" style="border 5px black;"> 
				<param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.1.5.swf" /> 
				<param name="allowfullscreen" value="true" /> 
				<param name="allowscriptaccess" value="always" /> 
				<param name="flashvars" value=\'config={"plugins":{"pseudo":{"url":"flowplayer.pseudostreaming-3.1.3.swf"},"controls":{"backgroundColor":"#000000","backgroundGradient":"low"}},"clip":{"provider":"pseudo","url":"cust_assets/spell_animations/' . $_GET['DoVideoPreview'] . '.flv"},"playlist":[{"provider":"pseudo","url":"cust_assets/spell_animations/' . $_GET['DoVideoPreview'] . '.flv"}]}\' /> 
			</object> ';
	}
	if($_GET['DoVideoPreview5']){
		for($i=100; $i < 120; $i++){ 
			if(file_exists("cust_assets/spell_animations/" . $i . ".mp4")) {    
				echo '<video controls autoplay>
						<source src="cust_assets/spell_animations/' . $i . '.mp4" />
					</video>';
			}
		}
	}
	
?>