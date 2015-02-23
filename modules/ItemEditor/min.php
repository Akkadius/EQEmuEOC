<?php
	/*
		Author: Akkadius
		A lot of this code sucks, but its too old, it works and doesn't make sense to rewrite
	*/
	
	require_once('modules/ItemEditor/ajax/js.php'); 
	require_once('modules/ItemEditor/constants_ie.php');
	
	$PreviewID = isset($_GET["previd"]) ? mysql_real_escape_string($_GET["previd"]) : '';
	$PrevIcon = isset($_GET["previcon"]) ? mysql_real_escape_string($_GET["previcon"]) : '';
	$EditItem = isset($_GET["EditItem"]) ? mysql_real_escape_string($_GET["EditItem"]) : '';
	$EditField = isset($_GET["EditField"]) ? mysql_real_escape_string($_GET["EditField"]) : '';		 
	
	$FJS .= '<script type="text/javascript" src="modules/ItemEditor/ajax/ajax.js"></script>';
	$FJS .= '<script type="text/javascript" src="cust_assets/js/lazy-load.js"></script>';
	$FJS .= '
		<script type="text/javascript">
			function GetRandomInt (min, max) {
				return Math.floor(Math.random() * (max - min + 1)) + min;
			} 
		</script>
	';
	
	if($_GET['AutoScaler']){
		echo FormStart();
		echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<h2>Auto Scaler (BETA)</h2>
			<br>
			<a href="javascript:;" class="btn green" onclick="WriteAutoScalerFields()">
				<i class="fa fa-pencil"></i> 
				Write Fields to Parent Form
			</a>
			<hr>
			<div id="written" style="display:inline;"></div>';
		$fields = array(
			" • HP/Mana/Endur/AC •",
			"ac",
			"hp",
			"mana",
			"endur",
			" • Stats •",
			"aagi",
			"acha",
			"adex",
			"aint",
			"asta",
			"astr",
			"awis",
			" • Heroic Stats • ",
			"heroic_str",
			"heroic_int",
			"heroic_wis",
			"heroic_agi",
			"heroic_dex",
			"heroic_sta",
			"heroic_cha",
			" • Resists •",
			"cr",
			"dr",
			"fr",
			"mr",
			"pr",
			" • Mod 2 •",
			"accuracy",
			"avoidance",
			"dotshielding", 
			"shielding",
			"spellshield",
			"strikethrough",
			"stunresist",
			"dsmitigation", 
			"enduranceregen",
			"healamt",
			"manaregen",
			"regen",
			"clairvoyance", 
			"attack",
			"combateffects",
			"damageshield",
			" • Weapon Mods •",
			"damage", 
			"haste");
		$reversed = array_reverse($fields);
		$reversedfields = array();
		foreach ($reversed as $val){
			if(substr($val, 0, 1) == " "){
				$JSMagic = "";
				$JSMagic2 = "";
				foreach ($reversedfields as $val2){
					$JSMagic .= 'document.getElementById(\'' . $val2 . '\').value=Math.floor(document.getElementById(\'' . $val2 . '\').value * document.getElementById(\'perc_' . $val . '\').value);';
					$JSMagic2 .= 'document.getElementById(\'' . $val2 . '\').value=parseInt(GetRandomInt(parseInt(document.getElementById(\'' . $val . 'min_val\').value), parseInt(document.getElementById(\'' . $val . 'max_val\').value)));';
				} 
				$JSButtons[$val] = FormInput('<a href="javascript:;" class="btn green" onclick="' . $JSMagic . '"><i class="fa fa-times"></i> Multiply</a><br><small>Multiplies all below fields by percentage (1 = 100%)</small>', '<input type="text" value="1" id="perc_' . $val . '" size="2">');
				
				$JSButtons[$val] .= '<br> 
					' . FormInput('<a href="javascript:;" class="btn blue" onclick="' . $JSMagic2 . '"><i class="fa fa-sort"></i> Min Max</a><br>Top Field - Min Bottom Field - Max', 
						'<input type="text" value="1" id="' . $val . 'min_val" size="2">
						 <input type="text" value="1" id="' . $val . 'max_val" size="2">'
					);
				$reversedfields = ""; 
				$reversedfields = array();
			}else{
				array_push($reversedfields, $val);
			}
		} 
		
		foreach ($fields as $val){
			if(substr($val, 0, 1) == " "){  echo '<h1>' . $val . ' </h1> ' . $JSButtons[$val] . '<br> ';  }
			else{ echo FormInput($ITD[$val][1], ' <input type="text" id="' . $val . '" class="actualfields">'); }
		}
		echo FormEnd();
		
		echo '<script type="text/javascript">';
		foreach ($fields as $val){
			if(substr($val, 0, 1) == " "){ }else{
				echo 'if(opener.document.getElementsByName("' . $val . '")){ document.getElementById("' . $val . '").value = opener.document.getElementsByName("' . $val . '")[0].value; }';
			}
		}
		echo '</script>';
	} 
	/* Preview Item */
	if(isset($_GET["previd"])){
		require_once('includes/constants.php');
		require_once('includes/alla_functions.php');
		require_once('modules/ItemEditor/constants_ie.php');
		$PageTitle = "Preview Item"; echo $SpecIncludes. '<title>'.$PageTitle.'</title>';
		$query_result = mysql_query("SELECT * FROM items WHERE id = ". $PreviewID . ";");
		while($row = mysql_fetch_array($query_result)){
			echo '<center><div class="alert alert-block alert-warning fade in" style="width:500px">';
			echo BuildItemStats($row, 1, 'item_view');
			echo '</div>';
		}
	}
	/* Preview Spell Icon */
	if(isset($_GET['prevspellicon'])){
		echo '<style>
				.image {   position: relative;  width: 100%; /* for IE 6 */ }
				h2 {   position: absolute;  top: 0px;   left: 14px;  width: 100%;   font-size:11px; }
			</style>';
		for($i=0; $i<1000; $i++){ 
			if(file_exists("cust_assets/icons/" . $i . ".gif")) { 
				$img_url = "cust_assets/icons/" . $i . ".gif";
				echo '<div class="image" style="display:inline">' .  
				"<a href='javascript:;' title='' class='btn btn-default'><img class='lazy' data-original='" . $img_url . "' title='". $i . "' width='35' height='35'/><br><br></a> "
				. '<h2>' . $i . '</h2></div>';
			}
		}
	}
	/* Preview Icon */
	if(isset($_GET['previcon'])){
        require_once('includes/constants.php');
        echo '
            <style>
                .image {
                   position: relative;
                   width: 100%; /* for IE 6 */
                }
                .image_label {
                    position: absolute;
                    bottom: 0px;
                    left: 0px;
                    font-size: 10px !important;
                    background-color:black;
                    height: 12px !important;
                    padding: 1px 2px 1px 2px !important;
                }
            </style>';

        echo "<embed src='images/chest_op.wav' hidden='true' autostart='true' loop='false' />";

        echo '<table class="table table-striped table-hover table-condensed flip-content dataTable table-bordered dataTable" style="width:400px">
            <tr>
                <td style="text-align:right">Item Type: </td>
                <td>
                    <select name="IconSearch" onchange="GetIconResult(this.value, 1)" class="form-control">
                    <option value="x" >Select...</option>';
                    foreach ($edit_options['itemtype'] as $key => $value){
                        echo '<option value="'. $key . '">'. $key . ': '. $value . ' </option>';
                    }
        echo '</select>';
        echo '</tr>';

        echo '<tr>
            <td style="text-align:right">Item Slot</td>
            <td>
                <select name="IconSearch" onchange="GetIconResult(this.value, 2)"  class="form-control">
                <option value="x" >Select...</option>';
                $n = 0;
                while ($ItemSlots[$n]){
                    echo '<option value="'. $ItemSlots[$n][1] . '">'. $ItemSlots[$n][1] . ': '. $ItemSlots[$n][0] . ' </option>';
                    $n++;
                }
        echo '</select></td></tr>';
        echo '</td></tr></table>';
        echo '<hr>';
        echo '<div id="IconResult">';

        for ($i = 0; $i < 6700; $i++) {
            $img_url = "cust_assets/icons/item_" . $i . ".png";
            if (file_exists($img_url)) {
                echo '
                    <div class="image" style="display:inline">
                        <a href="javascript:;" title="' . $i . '" onClick="FinishIcon(' . $i . ')">
                            <span class="image-wrap">
                                <img class="lazy" data-original="' . $img_url . '"  width="35" height="35" class="cut-out"/>
                            </span>
                        </a>
                    </div>';
            }
        }

        echo '</div>';
	}
	/* Preview Weapon Graphics */
	if(isset($_GET['prevITfile'])){
		echo '<style>
			.image { 
			   position: relative; 
			   width: 100%; /* for IE 6 */
			} 
			.image_label {
			   position: absolute; 
			   bottom: 0px;
			   left: 0px;
			   width: 100%;
			   font-size:14px !important;
			}
		</style>';

		if(count($_GET) == 1){
			echo "<embed src=\"images/swordblk.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
			$PageTitle = "Weapons Preview & Selection"; echo $SpecIncludes. '<title>'.$PageTitle.'</title>';
			echo '<center><table class="itemedit"><tr><td class="itemtd"><center>';
				echo '<br><h1>Narrow Search Criteria...</h1> (Results Vary)<hr>';
				echo '<table><tr><td><select onchange="GetITFileResult(this.value);"><option value="" >Select...</option>';
				foreach ($WeaponTypes as $key => $value){
					echo '<option value="'. $key . '">'. $key . ': '. $value . ' </option>';
				}
				echo '</select></td></tr></table>';
			echo '<br><br>';
			echo '<div id="ITFileResult">';
		}
		if($_GET['WeaponType']){
			$query_result = GetQueryResult('SELECT items.id, replace(items.idfile, "IT", "") AS WeaponList, items.icon FROM items WHERE itemtype = '. $_GET['WeaponType'] . '  GROUP BY `idfile` ORDER BY `WeaponList` ASC;');
			while($row = mysql_fetch_array($query_result)){
				if(file_exists($weapons_dir . "/" . $row['WeaponList'] . ".jpg")) {
					echo '<div class="image" style="display:inline">' .
                            "<a href='javascript:;' class='btn btn-default' onClick='FinishIDFile(" . $row['WeaponList'] . ")'>
                                <span class='image-wrap'>
                                    <img src='" . $weapons_url . $row['WeaponList'] . ".jpg' title='IT". $row['WeaponList'] . "' width='120' height='180' class='cut-out'/>
                                    <span class='image_label badge badge-danger'>IT" . $row['WeaponList'] . "</span>
                                </span>
                            </a>
                        </div>";
				}
			}
		}
		else{
			for($i = 1; $i < 100000; $i++){
				if($i == 1){ echo '<h1> EQ Classic </h1><hr>'; }
				if($i == 104){ echo '<h1> Kunark </h1><hr>'; }
				if($i == 161){ echo '<h1> Velious </h1><hr>'; }
				if($i == 10000){ echo '<h1> Luclin </h1><hr>'; }
				if($i == 10200){ echo '<h1> Planes of Power </h1><hr>'; }
				if($i == 10735){ echo '<h1> Omens of War </h1><hr>'; }
				if($i == 10782){ echo '<h1> Dragons of Norrath </h1><hr>'; }
				if($i == 10810){ echo '<h1> Depths of Darkhallow </h1><hr>'; }
				if($i == 10843){ echo '<h1> Prophecy of Ro </h1><hr>'; }
				if($i == 10866){ echo '<h1> The Serpents Spine </h1><hr>'; }
				if($i == 11085){ echo '<h1> The Buried Sea </h1><hr>'; }
				if($i == 11128){ echo '<h1> Secrets of Faydwer </h1><hr>'; }
				if($i == 11257){ echo '<h1> Seeds of Destruction </h1><hr>'; }
				if($i == 11274){ echo '<h1> LoN </h1><hr>'; }
				if($i == 11311){ echo '<h1> Underfoot </h1><hr>'; }
				if($i == 11680){ echo '<h1> House of Thule </h1><hr>'; }
				if($i == 12100){ echo '<h1> VoA </h1><hr>'; }
				if(file_exists("cust_assets/weapons/" . $i . ".jpg")) {
					echo '<div class="image" style="display:inline">' .
					    "<a href='javascript:;' onClick='FinishIDFile(" . $i . ")'>
					        <span class='image-wrap'>
                                <img class='lazy cut-out' data-original='cust_assets/weapons/" .  $i . ".jpg' title='IT". $i . "' width='120' height='180'/>
                                <span class='image_label badge badge-danger'>IT" . $i . "</span>
					        </span>
                        </a> "
					. '</div>'
					;
				}
			}
		}
		echo '</div><br><br>';
		if(count($_GET) == 1){
			echo '</tr></td></table>';
		}
		
	}
	if(isset($_GET['EditField'])){
		$Value = isset($_GET["Value"]) ? mysql_real_escape_string($_GET["Value"]) : '';
		$PageTitle = "Field Edit: " . $ITD[$_GET['EditField']][1]; echo $SpecIncludes. '<title>'.$PageTitle.'</title>';
		$OutPut .= "<br><center><h2>". $ITD[$_GET['EditField']][1] . "</h2><br>";
		if($EditField == "banedmgrace"){
			$OutPut .= '</td><td><select name="' . $EditField . '" '. $InputTitle . ' onchange="UpdateField(this.name, this.value);UpdateImage(this.value);" >';
			foreach ($races as $key => $value){
				if($_GET['Value'] == $key){
					$OutPut .= '<option selected value="'. $key . '">'. $key . ': '. $value . ' </option>';
				}
				else{
					$OutPut .= '<option value="'. $key . '">'. $key . ': '. $value . ' </option>';
				}
			}
			$OutPut .= '</select></td></tr>';
			$OutPut .= '<br><br>';
			if(file_exists($races_dir . "/" . $_GET['Value'] . ".jpg")){
				$OutPut .= '<img src="' . $races_url . $_GET['Value'] .'.jpg" id="MyRaceID" name=MyRaceID>';
			}
			else{
				$OutPut .= '<img src="" id="MyRaceID" name=MyRaceID>';
			}
			echo $OutPut;
		}
		
		if($EditField == "price"){
			$PV = 0; $GV = 0; $SV = 0; $CV = 0;
			// Break out Integer values from the parent field
			if(!$PV = substr($Value, 0, -3)){ $PV = 0; }
			if(!$GV = substr($Value, -3, 1)){ $GV = 0; }
			if(!$SV = substr($Value, -2, 1)){ $SV = 0; }
			if (!$CV = substr($Value, -1, 1)){ $CV = 0; }

			$OutPut .=  "<script type=\"text/javascript\">
				function UpdatePriceTotal(){
					var total = parseInt(0);
					total += (parseInt(document.getElementById('Platinum').value) * 1000);
					total += (parseInt(document.getElementById('Gold').value) * 100);
					total += (parseInt(document.getElementById('Silver').value) * 10);
					total += parseInt(document.getElementById('Copper').value);
					opener.document.getElementById('price').value = total;
					report(\"<b>Price</b> set to <b>\" + total + \"</font>\");
				}
				function report(str){
					document.querySelector('#report').innerHTML = str;
				}
			</script>";
			$OutPut .= '<table><tr><td>';
			$OutPut .= '<tr><td><img src="cust_assets/icons/item_644.png" width="25" height="25"> Platinum:</td><td> <input type="number" id="Platinum" min="0" max="999999999999999999" value="'. $PV . '" onchange="UpdatePriceTotal();"></td>';
			$OutPut .= '<tr><td><img src="cust_assets/icons/item_645.png" width="25" height="25"> Gold:</td><td> <input type="number" id="Gold" min="0" max="999999999999999999" value="'. $GV . '" onchange="UpdatePriceTotal();"</td>';
			$OutPut .= '<tr><td><img src="cust_assets/icons/item_646.png" width="25" height="25"> Silver:</td><td> <input type="number" id="Silver" min="0" max="999999999999999999" value="'. $SV . '" onchange="UpdatePriceTotal();"></td>';
			$OutPut .= '<tr><td><img src="cust_assets/icons/item_647.png" width="25" height="25"> Copper:</td><td> <input type="number" id="Copper" min="0" max="999999999999999999" value="'. $CV . '" onchange="UpdatePriceTotal();"></td>';
			$OutPut .= '</td></tr></table><br><div id="report"></div>';
			echo $OutPut;
		}
		if($EditField == "races"){
			$Races = array(
				0 => array("None", 0, 0), 
				1 => array("Human", 1, 1), 
				2 => array("Barbarian", 2, 2), 
				3 => array("Erudite", 4, 3), 
				4 => array("Wood-Elf", 8, 4), 
				5 => array("High-Elf", 16, 5), 
				6 => array("Dark-Elf", 32, 6), 
				7 => array("Half-Elf", 64, 7), 
				8 => array("Dwarf", 128, 8), 
				9 => array("Troll", 256, 9), 
				10 => array("Ogre", 512, 10), 
				11 => array("Halfling", 1024, 11), 
				12 => array("Gnome", 2048, 12), 
				13 => array("Iksar", 4096, 128), 
				14 => array("Vah-Shir", 8192, 130), 
				15 => array("Froglok", 16384, 330), 
				16 => array("Shroud", 32768, 0), 
			);
			for($i=1; $i<=16; $i++){
				$Races2[$i][0] = $Races[$i][0];
				$Races2[$i][1] = $Races[$i][1];
				$Races2[$i][2] = $Races[$i][2];
			}
			echo $OutPut;
			echo '<br><center><form id="myform"><table><tr><td>';
			$BitValue = 32768;
			for($i=16; $i >= 1; $i--){
				$Checked = 0; $checkedstatus = ""; $NoCheckFade = "";
				if($Value >= $BitValue){ $Value -= $BitValue; $Checked = 1; } 
				if($Checked == 1){ $checkedstatus = "checked"; } else{ $NoCheckFade = " style='opacity:0.50; filter:alpha(opacity=50);'";  }
				if($Value >= $BitValue){ $Value -= $BitValue; $Checked = 1; } 
				echo '<tr><td><div id="race'. $i . '" '. $NoCheckFade . '>' . $Races[$i][0] . '</div></td><td> <input type="checkbox" value="'. $BitValue . '" onclick="HighlightField(\'race'. $i .'\'); " '. $checkedstatus . ' id="'. $i .'"></td></tr>';
					if($Checked == 1){ $FJS .= '<script type="text/javascript">SetFieldCheckTrue(\'race'. $i .'\');</script>'; } else{ $FJS .= '<script type="text/javascript">SetFieldCheckFalse(\'race'. $i .'\');</script>'; }
				$BitValue /= 2;
			}
			echo '</td></tr></table></form>';
			echo '<br>Check All <input type="checkbox" onclick="CheckAll(\'All\');"id="All">';
			echo '<div id="result" style="font-size:13px;"></div>';
			$FJS .= '<script type="text/javascript">document.getElementById("All").checked = false;</script>';
			$FJS .= '<script type="text/javascript" src="modules/ItemEditor/ajax/race_selector.js"></script>';
			echo $FJS;
			
		}
		if($EditField == "slots"){ 
			$Slots = array(
				0 => array("CHARM"), 
				1 => array("EAR01"), 
				2 => array("HEAD"), 
				3 => array("FACE"), 
				4 => array("EAR02"), 
				5 => array("NECK"), 
				6 => array("SHOULDER"), 
				7 => array("ARMS"), 
				8 => array("BACK"), 
				9 => array("BRACER01"), 
				10 => array("BRACER02"), 
				11 => array("RANGE"), 
				12 => array("HANDS"), 
				13 => array("PRIMARY"), 
				14 => array("SECONDARY"), 
				15 => array("RING01"), 
				16 => array("RING02"), 
				17 => array("CHEST"), 
				18 => array("LEGS"), 
				19 => array("FEET"), 
				20 => array("WAIST"), 
				21 => array("POWERSOURCE"), 
				22 => array("AMMO"), 
			);
			$OutPut .= '<style>
				body {font: normal 0.8em verdana,arial; margin: 2em;} 
				.wrapped {display: none;}';
			
			for($i=0;$i<=22;$i++) {
                $OutPut .= '.' . $Slots[$i][0] . 'UC {
					background: url(cust_assets/inventory/slot_' . $i . '.gif) no-repeat;
					padding: 20px;
					cursor: pointer; 
					opacity:0.50; 
					filter:alpha(opacity=50);
				}
				.' . $Slots[$i][0] . 'C {
				  background: url(cust_assets/inventory/slot_' . $i . '.gif) no-repeat;
					padding: 20px;
					cursor: pointer;
				}
				
				';
            }
			$OutPut .=  '</style>';
			
			$OutPut .=  '<center>
			<table><tr><td>
			<center><br><br>';
			$BitWise2 = 4194304;
			for($i=22;$i>=0;$i--){
				$C = ""; $Checked ="";
				if($Value >= $BitWise2){ $Value -= $BitWise2; $Checked = "checked='checked'"; $C = "C"; } else{ $C = "UC"; }
				$Slots[$i][1] = '<input id="cb'. $i . '" name="'. $Slots[$i][0] . '" class="wrapped" '. $Checked .' value="'. $BitWise2 . '"><span id="cb'. $i . '_wrap" class="wrap '. $Slots[$i][0] . $C . '"></span>';
				$BitWise2 /= 2;
			}
			$N = 1;
			for($i=0;$i<=22;$i++){ 
				$OutPut .=  $Slots[$i][1];
				if($N == 4){ $OutPut .=  '<br><br><br><br>'; $N = 0;}
				$N++;
			}
			$OutPut .=  '<br><br><br><br><hr><center><div id="report"></div></center>';
			$OutPut .=  '</td></tr></table>'; 
			
			$OutPut .=  "<script type=\"text/javascript\">
			var wrappedCBs = document.querySelectorAll('.wrap');

				for (var i=0;i<wrappedCBs.length;(i=i+1)){
					wrappedCBs[i].onclick = cbClickHandler;
				}

				function cbClickHandler(e){
					  var relatedCheckBox = document.querySelector('#'+this.id.substr(0,this.id.indexOf('_')));
					  relatedCheckBox.checked = !relatedCheckBox.checked;";
					
					for($i=0;$i<=22;$i++){
					$OutPut .=  "
						if(this.className == \"wrap ". $Slots[$i][0] . "UC\" || this.className == \"wrap ". $Slots[$i][0] . "C\"){
							this.className = 'wrap '+ (relatedCheckBox.checked ? '". $Slots[$i][0] . "C' : '". $Slots[$i][0] . "UC');
						}
					";
					}
					 
				   $OutPut .=  "   // report checkstate
					  
					var wrappedCBs = document.querySelectorAll('.wrapped');
					var total = parseInt(0);
					for (var i=0;i<wrappedCBs.length;(i=i+1)){
						if(wrappedCBs[i].checked){
							total += parseInt(wrappedCBs[i].value);
						}
					}
					if(total >= 0){
						opener.document.getElementById(\"slots\").value = total;
						// ChildStatusUpdate(\"<font color=yellow><b>Slots</b></font> set to <font color=#66FF00><b>\" + total + \"</font></b>\");
						report(\"<b>Slots</b> set to <b>\" + total + \"</b>\");
					}
					else{
						opener.document.getElementById(\"slots\").value = 0;
					}
				}

				function report(str){
					document.querySelector('#report').innerHTML = str;
				}
			</script>";
			echo $OutPut;
		}
		if($EditField == "classes"){
			$PlayerClass = array(
				 1  => 'Warrior',
				 2  => 'Cleric',
				 3  => 'Paladin',
				 4  => 'Ranger',
				 5  => 'Shadowknight',
				 6  => 'Druid',
				 7  => 'Monk',
				 8  => 'Bard',
				 9  => 'Rogue',
				 10 => 'Shaman',
				 11 => 'Necromancer',
				 12 => 'Wizard',
				 13 => 'Magician',
				 14 => 'Enchanter',
				 15 => 'Beastlord',
				 16 => 'Berserker',
			);
			
			echo '<script type="text/javascript" src="modules/ItemEditor/ajax/class_selector.js"></script>';
			$OutPut .= '<form id=myform><center><table class="itemedit"><tr><td class="itemtd"><center>';
			$BitValue = 32768; $Input = ""; $Plate = "";
            for ($i = 16; $i >= 1; $i--) {
                $Checked = 0;
                $checkedstatus = "";
                $NoCheckFade = "";
                if ($Value >= $BitValue) {
                    $Value -= $BitValue;
                    $Checked = 1;
                }
                if ($Checked == 1) {
                    $checkedstatus = "checked";
                } else {
                    $NoCheckFade = " style='opacity:0.50; filter:alpha(opacity=50);'";
                }
                $Input = '<td><center><img src="cust_assets/monograms/' . $i . '.gif" id="class' . $i . '" ' . $NoCheckFade . '" width=50 height=100><br>' . $PlayerClass[$i] . ' <br><input type="checkbox" value="' . $BitValue . '" onclick="HighlightField(\'class' . $i . '\')" ' . $checkedstatus . ' id="' . $i . '"> </center></td>';
                if ($Checked == 1) {
                    $FJS .= '<script type="text/javascript">SetFieldCheckTrue(\'class' . $i . '\');</script>';
                } else {
                    $FJS .= '<script type="text/javascript">SetFieldCheckFalse(\'class' . $i . '\');</script>';
                }
                if ($i == 1 || $i == 2 || $i == 3 || $i == 5 || $i == 8) {
                    $Plate .= $Input;
                }
                if ($i == 4 || $i == 9 || $i == 10 || $i == 16) {
                    $Chain .= $Input;
                }
                if ($i == 6 || $i == 7 || $i == 15) {
                    $Leather .= $Input;
                }
                if ($i == 11 || $i == 12 || $i == 13 || $i == 14) {
                    $Silk .= $Input;
                }
                $BitValue /= 2;
            }
			$OutPut .= '<tr><td valign=center><center><h1>Plate:</h1>Check All Plate<br><input type="checkbox" onclick="CheckAll(\'Plate\');" id="Plate"></center></td><td>' . $Plate . '</td></tr>';
			$OutPut .= '<tr><td><center><h1>Chain:</h1>Check All Chain<br><input type="checkbox" onclick="CheckAll(\'Chain\');" id="Chain"></center></td><td>' . $Chain . '</td></tr>';
			$OutPut .= '<tr><td><center><h1>Leather:</h1>Check All Leather<br><input type="checkbox" onclick="CheckAll(\'Leather\');" id="Leather"></center></td><td>' . $Leather . '</td></tr>';
			$OutPut .= '<tr><td><center><h1>Silk:</h1>Check All Silk<br><input type="checkbox" onclick="CheckAll(\'Silk\');" id="Silk"></center></td><td>' . $Silk . '</td></tr>';
			$OutPut .= '</form><center><br></tr></td></table><br>Check All <input type="checkbox" onclick="CheckAll(\'All\');" id="All">';
			$FJS .= '<script type="text/javascript">
				document.getElementById("Plate").checked = false;
				document.getElementById("Chain").checked = false;
				document.getElementById("Leather").checked = false;
				document.getElementById("Silk").checked = false;
				document.getElementById("All").checked = false;
				</script>';
			echo $OutPut . $FJS;
			
		}
		if($EditField == "id"){
			$query = "SELECT t1.ID + 1 AS nextID
				FROM items t1
				LEFT JOIN items t2
				ON t1.ID + 1 = t2.ID
				WHERE t2.ID IS NULL;";
				$result = mysql_query($query); if (!$result) {die('Invalid query: ' . mysql_error());}
				$OutPut .= '</td><td>If you wish, please select a free ID from your items table below:<br><br>
				<select name="' . $EditField . '" '. $InputTitle . ' onchange="UpdateField(this.name, this.value);UpdateImage(this.value);" >';
				$OutPut .= '<option value="0">--- Select ---</option>';
				while($row = mysql_fetch_array($result)){
					$OutPut .= '<option value="'. $row["nextID"] . '">'. $row["nextID"] . ' </option>';
				}
				$OutPut .= '</select>';
				echo $OutPut . '<br>';		
		}
		if($EditField == "proceffect" || $EditField == "focuseffect" || $EditField == "clickeffect" || $EditField == "worneffect" || $EditField == "bardeffect" || $EditField == "scrolleffect"){
			require_once('includes/config.php');
			require_once('includes/constants.php');
			require_once('includes/alla_functions.php');
			require_once('includes/spell.inc.php'); 
			$opt = (isset($_GET['opt']) ? $_GET['opt'] : '');
			$namestring = (isset($_GET['name']) ? $_GET['name'] : '');
			$level = (isset($_GET['level']) ? $_GET['level'] : 0);
			$type = (isset($_GET['type']) ? $_GET['type'] : 0);
			if(!$namestring && (count($_GET) == 2)){$namestring = $Value;} 
				if($Value > 0) { $opt = 2; }
		
			
			echo '<script type="text/javascript" language="JavaScript"> 
				function UpdateFieldSpell(fieldid, valuefromchild, spellname){
					opener.document.getElementsByName(fieldid)[0].value = valuefromchild;		
					opener.document.getElementById("spellurl" + fieldid).href="'. $root_url . 'spell.php?id=" + valuefromchild;
					ChildStatusUpdate("Changed field \'<font color=yellow>" + fieldid + "</font>\' to spell: <font color=#66FF00><br>" + valuefromchild + ": \'" + spellname + "\'</font>");
				}
			</script>';
			
			echo "<br><center><h1>". $ITD[$_GET['EditField']][1] . "</h1><br>";

			$check1 = "";
			$check2 = "";
			$check3 = "";
			
			if(!$level){ $level = 0; }
			if($opt == 1) {
				$check1 = "checked";
				$OpDiff = 0;
				$ClassOper = "=";
			}
			elseif($opt == 2) {
				$check2 = "checked";
				$OpDiff = -1;
				$ClassOper = ">=";
			}
			elseif($opt == 3) {
				$check3 = "checked";
				$OpDiff = 1;
				$ClassOper = "<=";
			}
			else {
				$check2 = "checked";
				$OpDiff = 0;
				$ClassOper = ">=";
			}
			/* Display Spell Form */
			echo  '<center><table border="0"><tr align="left"><td>'; 
			echo  '
					<form name="f" action="">
					<input type="hidden" name="Mod" value="IE">
					<table border="0" cellspacing="0" cellpadding="3"> 
					<tr><td>Search For:</td><td><input type="text" name="name" size="40" id="search"/> <small><i>Searches name, description and casting messages</i></small></td></tr>
					<tr><td>Class:</td><td><select name="type">
					<option value="0"' . ($type == 0 ? ' selected="1"' : '') . '>------</option>
					<option value="8"' . ($type == 8 ? ' selected="1"' : '') . '>Bard</option>
					<option value="15"' . ($type == 15 ? ' selected="1"' : '') . '>Beastlord</option>
					<option value="16"' . ($type == 16 ? ' selected="1"' : '') . '>Berserker</option>
					<option value="2"' . ($type == 2 ? ' selected="1"' : '') . '>Cleric</option>
					<option value="6"' . ($type == 6 ? ' selected="1"' : '') . '>Druid</option>
					<option value="14"' . ($type == 14 ? ' selected="1"' : '') . '>Enchanter</option>
					<option value="13"' . ($type == 13 ? ' selected="1"' : '') . '>Magician</option>
					<option value="7"' . ($type == 7 ? ' selected="1"' : '') . '>Monk</option>
					<option value="11"' . ($type == 11 ? ' selected="1"' : '') . '>Necromancer</option>
					<option value="3"' . ($type == 3 ? ' selected="1"' : '') . '>Paladin</option>
					<option value="4"' . ($type == 4 ? ' selected="1"' : '') . '>Ranger</option>
					<option value="9"' . ($type == 9 ? ' selected="1"' : '') . '>Rogue</option>
					<option value="5"' . ($type == 5 ? ' selected="1"' : '') . '>Shadowknight</option>
					<option value="10"' . ($type == 10 ? ' selected="1"' : '') . '>Shaman</option>
					<option value="1"' . ($type == 1 ? ' selected="1"' : '') . '>Warrior</option>
					<option value="12"' . ($type == 12 ? ' selected="1"' : '') . '>Wizard</option>
					</select></td></tr>

					<tr><td>Level:</td><td><select name="level">
					<option value="">-----</option>';
				
			for($i=1; $i <= 250; $i++) {
				echo  '<option value="' . $i . '"' . ($level == $i ? ' selected="1"' : '') . '>' . $i . '</option>';
			}
				
			echo  '</select>
					<label><input type="radio" name="opt" value="1" '.$check1.' />Only</label> 
					<label><input type="radio" name="opt" value="2" '.$check2.' />And Higher</label> 
					<label><input type="radio" name="opt" value="3" '.$check3.' />And Lower</label></td></tr>
					<tr>
					<td colspan="2">
					<input type="submit" value="     Search     " class="btnIconLeft mr10"/>
					<input type="hidden" name="action" value="search" class="btnIconLeft mr10"/>
					<input type="hidden" name="EditField" value="'. $EditField .'" class="btnIconLeft mr10"/>
					<input type="hidden" name="Value" value="'. $Value .'" class="btnIconLeft mr10"/>
					<input type="reset" value="Reset" class="btnIconLeft mr10">
					</td>
					</td></tr>
					</table>
					</form>';
				/* End Display Spell Form */
			
			/* Start Data Pull */

				if (!$level) { $level = 0; $ClassOper = ">"; }
				$sql = 'SELECT
					' . 'spells_new' .'.*
					FROM
					' . 'spells_new' .'
					WHERE';
					$sv = '';
					
				if ($type) {
					$sql .= ' ' . 'spells_new' .'.classes' . $type . " " . $ClassOper .  " " . $level . ' 
							AND ' . 'spells_new' .'.classes' . $type . ' <= '. '250';
							$sv = 'AND';
				}
				$sql .= ' ' . $sv . ' (' . 'spells_new' .'.name LIKE \'%' . mysql_real_escape_string($namestring) . '%\' OR ' . 'spells_new' .'.id LIKE \'%' . mysql_real_escape_string($namestring) . '%\') ';
				if ($UseSpellGlobals==TRUE) {
					$sql .= ' AND (ISNULL((SELECT ' . 'spell_globals' . '.spellid FROM ' . 'spell_globals' .  ' 
						WHERE ' . 'spell_globals' . '.spellid = ' . 'spells_new' .'.id)) 
						OR (SELECT quest_globals.name FROM ' . 'spell_globals' . ' , quest_globals
						WHERE ' . 'spell_globals' . '.spellid = ' . 'spells_new' .'.id 
						AND ' . 'spell_globals' . '.qglobal = quest_globals.name 
						AND ' . 'spell_globals' . '.value = quest_globals.value 
						LIMIT 1))';
				} 

				if ($type != 0) { $sql .= ' ORDER BY ' . 'spells_new' .'.classes' . $type . ', ' . 'spells_new' . '.name'; }
				else { $sql .= ' ORDER BY ' . 'spells_new' . '.name '; }
				$Minimal = 0;
				if($type == 0 && $level == 0 && $namestring == ""){
					$sql = "select * from `spells_new` LIMIT 100 ";
					$Minimal = 1;
				} 
				$result = mysql_query($sql); if (!$result) {die('Invalid query: ' . mysql_error()); } 
				echo  '<hr> <center><table border="0" cellpadding="5" cellspacing="0" class="table table-hover">';
					$LevelCheck = $level + $OpDiff;
					$Class = 'classes' . $type;
					$ClassName = $dbclasses[$type];
				 
				while($row = mysql_fetch_array($result)) { 
					if($Minimal == 1){
						/* This will only come through when the Level Changes */
						$DBSkill = $dbskills[$row["skill"]];
						if($LevelCheck != $row[$Class]) {
							$LevelCheck = $row[$Class];
							echo  '<tr>
								<td>ID</td>
								<td>Name</td>
							  </tr>';
						}
						echo  '<tr>
							<td valign="top"> '. $row['id'] . ' </a></td>
							<td valign="top"> <a href="'. $root_url .'spell.php?id='. $row['id'] . '" target='. $row['id'] . '> '. $row['name'] . '</a> <a href="javascript:;" onclick="UpdateFieldSpell(\'' . $EditField . '\', ' . $row['id']  . ', \'' . preg_replace("/'/i", "\'", $row['name']) . '\');">(Select)</a></td>
							</tr>';
					}
					else{
						/* This will only come through when the Level Changes */
						$DBSkill = $dbskills[$row["skill"]];
						if($LevelCheck != $row[$Class])
						{
							$LevelCheck = $row[$Class];
							echo  '<tr><th style="border-top: 0px solid #ddd;"><h2>Level: ' . $row['classes'. $type] . '</h2></th></tr>';
							echo  '<tr>
								<td colspan="2" >Name</td> 
								<td>Select Spell</td>
								<td>Class</td>
								<td>Effect(s)</td>
								<td>Mana</td>
								<td>Skill</td>
								<td>Target Type</td>
							  </tr>';
						}
						echo  '<tr>
							<td valign="top"> <a href="'. $root_url . 'spell.php?id='. $row['id'] . '" target='. $row['id'] . '><img src="includes/img.php?type=spellimage&id='. $row['new_icon'] . '" align="center" border="1" width=25 height=25></a></td>
							<td valign="top"> <a href="'. $root_url .'spell.php?id='. $row['id'] . '" target='. $row['id'] . '> '. $row['name'] . '</a> <td><input type="button" class="btn btn-default" value="Select Spell" class="btnIconLeft mr10" onclick="UpdateFieldSpell(\'' . $EditField . '\', ' . $row['id']  . ', \'' . preg_replace("/'/i", "\'", $row['name']) . '\');"></td></td>
							<td valign="top"><center>' . $ClassName . " " . $LevelCheck . '</center></td>
							<td valign="top"><small>';  
							for ($n=1; $n<=12; $n++) { SpellDescription($row, $n); } 
							echo  '</small></td>
							<td>'. $row['mana'] . '</td>
							<td>'. ucwords(strtolower($DBSkill)) . '</td>
							<td>';
							if ($dbspelltargets[$row["targettype"]]!="") { print $dbspelltargets[$row["targettype"]]; }
							echo  '</td></tr>';
					}		
				}
				echo  '</tr></table>';
			echo  '</tr></table>';
			}
		echo '<br><br><input type="button" value="Close" class="btnIconLeft mr10" onclick="window.close();">';
	}
	
	if(isset($_GET['EditorOptions'])){
		if(isset($_GET['Save'])){
			if($_GET['Music'] == "LQ"){ setcookie("ItemEditorMusic", "LQ", time() + (20 * 365 * 24 * 60 * 60)); }
			else if($_GET['Music'] == "HQ"){ setcookie("ItemEditorMusic", "HQ", time() + (20 * 365 * 24 * 60 * 60)); }
			else if($_GET['Music'] == "NO"){ setcookie("ItemEditorMusic", "NO", time() + (20 * 365 * 24 * 60 * 60)); }
			echo '<script type="text/javascript">window.opener.location.reload();window.close();</script>';
		}
		else{
			if(isset($_COOKIE["ItemEditorMusic"])){
				if($_COOKIE["ItemEditorMusic"] == "LQ"){ $LQ = "checked"; }
				else if($_COOKIE["ItemEditorMusic"] == "HQ"){ $HQ = "checked"; }
				else if($_COOKIE["ItemEditorMusic"] == "NO"){ $NO = "checked"; }
			}
			else{ echo "<embed src=\"images/0001130031538.wav\" hidden=\"true\" autostart=\"true\" loop=\"true\" />"; }
			
			$PageTitle = "Item Editor Options"; echo $SpecIncludes . '<title>'.$PageTitle.'</title>'; 
			echo "<center><br><h1>Item Editor Options</h1><br>";
			echo '<form method="GET">';
			echo "<table><tr><td>";
			echo '<tr><td><b>Music Options:</b></td><td>
				<input type="radio" name="Music" value="LQ" '. $LQ .'>Low Quality<br>
				<input type="radio" name="Music" value="HQ" '. $HQ . '>High Quality<br>
				<input type="radio" name="Music" value="NO" '. $NO . '>No Music </td></tr>';
			echo '<input type="hidden" name="Save">';
			echo '<input type="hidden" name="EditorOptions">';
			echo "</td></tr></table>";
			echo '<br><br><input type="submit" value="Save" class="btnIconLeft mr10">';
			echo '</form>';
		}
	}	

	/* Save Function, dynamically build save query based on POST data */
	if(count($_POST) > 20){ 
		require_once('includes/constants.php');
		require_once('includes/alla_functions.php');
		require_once('modules/ItemEditor/constants_ie.php');
		$PageTitle = "Item Save"; echo $SpecIncludes. '<title>'.$PageTitle.'</title>';
		$C = 1; $Query = "REPLACE INTO items (";
		$Fields = ""; $Values = ""; 
		foreach($_POST as $FV => $value){
			if($FV == "color"){ $value = hexdec ( $value); }
			/* Escape strings with comma */
			if (strpos($value,'\'') !== false) {
				$value = mysql_real_escape_string($value);
			}
			if($C != 1){ $Values .= ", "; $Fields .= ", "; } 
			$Values .= "'" . $value . "'"; $Fields .= "`" . $FV . "`";
			$C++; 
		}
		$Result = $Query . $Fields . ") VALUES (". $Values . ");"; 
		if(mysql_query($Result)){
			echo '<center><h1>Item Saved Successfully!</h1><br>';
			$query_result = mysql_query("SELECT * FROM items WHERE id = ". $_POST['id'] . ";");
			while($row = mysql_fetch_array($query_result)){
				echo '<div class="alert alert-block alert-warning fade in" style="width:500px">';
				echo BuildItemStats($row, 1);
				echo '</div>';
			}
			echo "<hr><h1>Query Result</h1><br><textarea rows='30' cols='200'> " . $Result . "</textarea></center>";
		}
		else{
			echo '<h2>ERROR</h2>';
			echo '<pre>';
			echo mysql_error();
			echo '<br><br>';
			echo $Result;
			echo '</pre>';
		}
		LogUserEvent($_SERVER['REMOTE_ADDR'], "Item Save", $Result);
	}		
	echo '<div id="childstatus"></div>';
		
	echo '<script type="text/javascript" src="cust_assets/eqemu_tooltip.js"></script></body>';
?>