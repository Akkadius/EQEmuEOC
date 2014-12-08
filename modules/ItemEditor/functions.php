<?php
	/* Item Editor functions
		Author: Akkadius
	*/
	
	require_once('includes/functions.php');
	function dec2hex($number) {
		$hexvalues = array('0','1','2','3','4','5','6','7',
				   '8','9','A','B','C','D','E','F');
		$hexval = '';
		 while($number != '0')
		 {
			$hexval = $hexvalues[bcmod($number,'16')].$hexval;
			$number = bcdiv($number,'16',0);
		}
		return $hexval;
	}
	
	function ItemEditor($ItemID, $ViewMode){
		global $_COOKIE, $_SESSION, $PageTitle, $ItemSection, $ItemEditorSections, $resists, $root_url, $Return, $ShowTitle, $icons_dir, $SpecIncludes, $ItemEditorSectionsDesc;
		require_once('./modules/ItemEditor/ajax/js.php');
		require_once('modules/ItemEditor/constants_ie.php'); 
		$PageTitle = "Item Edit";  
		$Return .= $SpecIncludes. '<title>'.$PageTitle.'</title>';
		// Editor Music Options
		if($_SESSION['IEMusic'] == 1){	$Return .= "<div id='Music'><embed src=\"images/001130031538.wav\" hidden=\"true\" autostart=\"true\" loop=\"true\" type=\"application/x-mplayer2\"/></div>"; } else{ $Return .= "<div id='Music'></div>"; }
		$result = mysql_query("SELECT * FROM items WHERE id = '" . $ItemID . "';");
		if(!$result){ $Return .= 'Could not run query: ' . mysql_error(); exit; } 

		echo '<style>
				.section_header{
					background-color: #fcf8e3;
					border-color: #faebcc;
					color: #8a6d3b;
				}
			</style>';
		
		### Loop through item fields
		### Automatically Generate the column names from the Table
		$columns = mysql_num_fields($result);
		$Item_Name = mysql_result($result, 0, 2);
		$Return .= '<h2>' . $Item_Name . '</h2><small>All changes are not saved until the "Save" button has been pressed.<br>Items can be copied by simple changing the Item ID to a free unassigned ID in the database using the form below</small><hr>';
		$Return .= '
			<form method="POST" id="frmMain" action="min.php?Mod=IE" class="customForm" style="display:inline;">
			<table><tr>
			<td><button type="submit" value="Save" 			class="btn green"><i class="fa fa-save"></i> Save</button></td>
			<td><button type="button" value="Preview Item" 	class="btn yellow" ' . HoverTip("global.php?item_view=" . $ItemID) . '><i class="fa fa-check-square-o"></i> Preview</button></td>
			<td><button type="button" value="Reload Page" 	class="btn blue" onclick="location.reload(false);"><span aria-hidden="true" class="icon-reload"></span> Reload Page</button></td>
			<td><button type="button" value="Auto Scaler" 	class="btn red" onclick="AutoScaler();"><span aria-hidden="true" class="icon-plus"></span> Auto Scaler</button></td>
			</tr></table> ';
		
		for ($i = 0; $i < $columns; $i++){
			if($Debug){ echo 'dbg1<br>'; }
			$FClass = "";
			$FieldName = mysql_field_name($result, $i);
			$FieldNameLabel = $ITD[$FieldName][1];
			
			
			if($FieldNameLabel == False){ $FieldNameLabel = $FieldName; } //Exchange the Field name for the Array field name

			/* Field Data */
			$FieldData = mysql_result($result, 0, $i);
			if($FieldData == ""){  $FieldData = $ITD[$FieldName][0]; }
			if($FieldName == "Name"){ PageTitle($ItemID . ": "  . $FieldData); }
			$CharCount = strlen($FieldData); if($CharCount < 5){ $CharCount = 5;  }
			
			if($ITD[$FieldName][2] == False){ $InputTitle = ""; }
			else{ $InputTitle = "title='". $ITD[$FieldName][2] ."' class='form-control '";  }
			
			/* Define the Array to Store all Input Fields */
			$FieldKey = $ItemSection[$FieldName][0];
			if($FieldKey == ''){ $FieldKey = 'misc'; }
			$FI[$FieldKey][$FieldName][0] = $FieldNameLabel;
			$FI[$FieldKey][$FieldName][1] = '<input type="text" name="' . $FieldName . '" id="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' >';
			if($FieldName == "color"){
				$colHex = dechex($FieldData);
				$colHex = '#' . str_pad($colHex, 6 , "0"); 
				if(strlen($colHex) == 9){ $colHex = str_replace('#ff', '', $colHex); }
				$FI[$FieldKey][$FieldName][1] = '
				<input type="text" name="' . $FieldName . '" id="' . $FieldName . '" placeholder="#' . $colHex . '" value="#' . $colHex . '" size="'. $CharCount .'" '. $InputTitle . ' >
				<div id="color_preview" style="background-color:#' . $colHex . ' !important;border: 1px solid #e5e5e5;width:40px;height:40px"></div>'; 
			}
			
			$FI[$FieldKey][$FieldName][2] = $FieldName;
			 
			$SpellData = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 1200, 700);'><i class='fa fa-edit'></i> EDIT" . '</a><input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' id="'. $FieldName . '"><a class="btn blue" data-' . $FieldName . '="1" ' . HoverTip("global.php?spell_view=" . $FieldData . "") . '>View</a>';
			$FieldData1 = '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' >';
			if($ItemSection[$FieldName][2] == 0){ $FClass = ' class="'. $FieldKey .'" style="display: none"';  }
			if($ItemSection[$FieldName][1] != "NO" &&  $ItemSection[$FieldName][1] != ""){ $FI[$FieldKey][$FieldName][1] = ItemInputFromArray($FieldName, $ItemSection[$FieldName][1], $FieldData, $InputTitle); continue; }
			if($ItemSection[$FieldName][1] != "NO"){ $CustData = ItemInputFromArray($FieldName, $ItemSection[$FieldName][1], $FieldData, $InputTitle); }
			
			if($FieldKey == "topinfo"){ $FI[$FieldKey][$FieldName][1] = '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . '>'; continue; }
			if($FieldName == "id"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 500, 600);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " id=".$FieldName.">"; continue; }
			if($FieldName == "icon"){
				if(file_exists("cust_assets/icons/item_" . $FieldData . ".png")){
					$FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' title='' class='btn btn-default' onClick='IconEdit(". $FieldData. ")'><img src='includes/img.php?type=iconimage&id=" . $FieldData ."'  name='MyIcon' title='Click here to Edit your Icon' valgin='top' align='right'></a>"; 
					$FI[$FieldKey][$FieldName][1] .= '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' id=icon onchange="UpdateIcon('. $FieldData . ')" >';
				} continue;
			}
			if($FieldName == "idfile"){
				$FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' title='' class='btn btn-default ' onClick='IDFileEdit(". preg_replace('/IT/i', '', $FieldData) . ")'><img src='includes/img.php?type=weaponimage&id=" . preg_replace('/IT/i', '', $FieldData) ."' name='MyIDFile' title='Click here to Edit your Graphic' class=weapongraphic></a>"; 
				$FI[$FieldKey][$FieldName][1] .= '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' id=idfile onchange="UpdateIDFile('. $FieldData . ')">';
				continue;
			}
			if($FieldName == "classes"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 1000, 750);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " id=".$FieldName.">"; continue; }
			if($FieldName == "races"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 320, 720);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " id=".$FieldName.">"; continue; }
			if($FieldName == "slots"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 450, 725);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " id=".$FieldName.">"; continue;  }
			if($FieldName == "price"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 400, 500);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " id=".$FieldName.">"; continue; }
			if($FieldName == "scrolleffect"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 1200, 700);'>" . '<i class="fa fa-edit"></i> EDIT' . '</a><input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' id="' . $FieldName . '"><a class="btn blue" data-' . $FieldName . '="1" ' . HoverTip("global.php?spell_view=" . $FieldData . "") . '>View</a>'; continue; } 
			if($FieldName == "casttime"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "casttime_"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; } 
			if($FieldName == "recastdelay"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; } 
			if($FieldName == "recasttype"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; } 
			if($FieldName == "maxcharges"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; } 
			if($FieldName == "scrolleffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "scrolltype"){ $FI[$FieldKey][$FieldName][1] = $CustData; continue; }
			if($FieldName == "scrolllevel"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "scrolllevel2"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "scrollname"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "clickeffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "clicklevel"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "clicklevel2"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "clicktype"){ $FI[$FieldKey][$FieldName][1] = $CustData; continue; }
			if($FieldName == "proceffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "proclevel"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "proclevel2"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "procrate"){ $FI[$FieldKey][$FieldName][1] = $CustData; continue; }
			if($FieldName == "proctype"){ $FI[$FieldKey][$FieldName][1] = $CustData; continue; }
			if($FieldName == "worneffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "wornlevel"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "wornlevel2"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "worntype"){ $FI[$FieldKey][$FieldName][1] = $CustData; continue; }
			if($FieldName == "focuseffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "focuslevel"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "focuslevel2"){ $FI[$FieldKey][$FieldName][1] = $FieldData1; continue; }
			if($FieldName == "focustype"){ $FI[$FieldKey][$FieldName][1] =$CustData; continue; }
			if($FieldName == "bardeffect"){ $FI[$FieldKey][$FieldName][1] = $SpellData; continue; }
			if($FieldName == "proceffect" || $FieldName == "focuseffect" || $FieldName == "clickeffect" || $FieldName == "worneffect" || $FieldName == "bardeffect"){ $FI[$FieldKey][$FieldName][1] ="<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 1200, 700);'><i class='fa fa-edit'></i> EDIT</a>" . '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' id="' . $FieldName . '"><a class="btn blue" data-' . $FieldName . '="1" ' . HoverTip("global.php?spell_view=" . $FieldData . "") . '>View</a>'; continue; }
			$FDA = '<input type="text" id="' . $FieldName . '" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="'. $CharCount .'" '. $InputTitle . ' >';
			if($FieldName == "banedmgrace"){ $FI[$FieldKey][$FieldName][1] = "<a href='javascript:;' class='btn green' onClick='FieldEditGet(\"". $FieldName . "\", 500, 600);'><i class='fa fa-edit'></i> EDIT</a><input type='text' name='" . $FieldName . "' placeholder='". $FieldData . "' value='". $FieldData . "' size='". $CharCount ."' ". $InputTitle . " >"; continue; }
			
			if($FieldKey == ""){ $FI[''][$FieldName][1] = '<input type="text" name="' . $FieldName . '" placeholder="'. $FieldData . '" value="'. $FieldData . '" size="' . $CharCount . '" '. $InputTitle . ' >'; continue;  }
		}

		/* Array Documentation 
			$FI
				Key1 [0] = Field Name Label
				Key2 [1] = Input Field
				Key3 [2] = Field Name - Unused?
			Keyed by:
				$FieldName][0] = Mysql Field name
			
			$ItemEditorSections - Comes from includes/constants.php
				Used to determine section types 
		*/
		
		/* This area handles all of the display of the Item Editor */
		
		$Tabs = array();

		$Rem = array(); $n = 1;
		foreach ($ItemEditorSectionsDesc as $val){
			if(!$Rem[$val]){
				$Rem[$val] = 1;
				$SID = md5($val); 
				$n++;
			}
		}
		
		$t = 1; $TSection = array();
		
		ksort($FI); // Sort the iteration of sections alphabetically... 
		foreach($FI as $value){
			if($Debug){ echo 'dbg2<br>'; }
			ksort ($value); // Sort the iteration of fields aplhabetically...
			#print var_dump($value) . '<br>';
			foreach($value as $value2){
				if($Debug){ echo 'dbg5<br>'; }
				$FClass = ""; $Style = ""; $DSection2 = "";
				
				// The following two IF checks fall under the Misc. category by having no index and are miscellaneous uncategorized fields
				if($ItemSection[$value2[2]][2] == 0){ $FClass = $ItemSection[$value2[2]][0];  $Style = 'style="display: none"'; }
				if($ItemSection[$value2[2]][0] == "misc"){ $FClass = "Unknown"; $Style = 'style="display: none"'; }
				
				// $DSection is a temp selection identifier
				if($DSection != $ItemSection[$value2[2]][0]){ 
					if($ItemSection[$value2[2]][0] == "misc"){ $DSection2 = "Misc."; } 
					$FClass = $DSection; $FClass .= ' itemheader'; 
					$DSection = $ItemSection[$value2[2]][0];
					$Section = $ItemEditorSections[$DSection]; if($Section == ""){ $Section = "Misc."; }
					$SID = md5($Section);
					
					if(!$ItemEditorSectionsDesc[$DSection]){ $Tab = "Misc."; } else{ $Tab = $ItemEditorSectionsDesc[$DSection]; }
					$Tabs[$SID][0] .= '<tr><th class="section_header"><h4><i class="fa fa-angle-right"></i> ' . $Tab . '<h4></th><th class="section_header"></th></tr>';
				}
				
				$FClass .= $DSection;
				$Tabs[$SID][0] .= '<tr class="'. $FClass . '" '. $Style . '><td style="text-align: right;">' .  $value2[0] . '</td><td> ' . $value2[1] . '</td></tr>';
			}
			
			$t++; ### Increment Tab Counts
		}
		
		
		// Let's print out these Tabs
		$Return .= '
			  <div class="widget">
				<ul class="tabs">'; 
				$Rem = array(); $n = 1;
				foreach ($ItemEditorSections as $val){
					if($Debug){ echo 'dbg3<br>'; }
					if(!$Rem[$val]){
						$Rem[$val] = 1;
						if($ViewMode == 0){	$Return .= '<li><a href="#tab' . $n . '">' . $val . '</a></li>'; }
						$n++;
					}
				} 
				$Return .= '</ul>
					<div class="tab_container">';
					
					$Rem = array(); $n = 1;
					if($ViewMode == 1){ $Return .= '<table><tr><td>'; }
					foreach ($ItemEditorSections as $val){
						if($Debug){ echo 'dbg4<br>'; }
						if(!$Rem[$val]){
							$Rem[$val] = 1;
							if($ViewMode == 0){ $Return .= '<div id="tab' . $n . '" class="tab_content"> <table class="table table-hover table-bordered">' . $Tabs[md5($val)][0] . '</table> </div>'; }
							if($ViewMode == 1){ 
								$DP[$val] = '<table class="table ">' . $Tabs[md5($val)][0] . '</table>';
								#$Return .= $val . '<table class="table table-hover">' . $Tabs[md5($val)][0] . '</table>'; 
								
							}
							$n++;
						}
					}
					if($ViewMode == 1){ 
						$Return .= 
							"<td style='vertical-align: top !important;padding-right: 10px;'>" . $DP['Item Info'] . $DP['Bag Info'] . $DP['Restrictions'] . '</td>' . 
							"<td style='vertical-align: top !important;padding-right: 10px;'>" . $DP['Resists'] . $DP['Statistics'] . '</td>' . 
							"<td style='vertical-align: top !important;padding-right: 10px;'>" . $DP['Weapon'] . $DP['Augmentation']. $DP['Faction'] . '</td>' . 
							"<td style='vertical-align: top !important;padding-right: 10px;'>" . $DP['Spell Related Data'] . $DP['Misc.'] . '</td>'
							;
						$Return .= '</td></tr></table>'; 
					}
					
					
		$Return .= ' </div> 
			<div class="fix"></div>
		</div>';
		
		return $Return;
	}
	function SelectIClass($name, $selected)
	{
		global $dbiclasses;
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n";
		for ($i = 1; $i <= 32768; $i*= 2) {
			$ret .= "<option value='" . $i . "'";
			if ($i == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">" . $dbiclasses[$i] . "</option>\n";
		}

		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectRace($name, $selected)
	{
		global $dbraces;
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n";
		for ($i = 1; $i < 32768; $i*= 2) {
			$ret .= "<option value='" . $i . "'";
			if ($i == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">" . $dbraces[$i] . "</option>\n";
		}
		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectSlot($name, $selected)
	{
		global $dbslots;
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n";
		reset($dbslots);
		do {
			$key = key($dbslots);
			$ret .= "<option value='" . $key . "'";
			if ($key == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">" . current($dbslots) . "</option>\n";
		}

		while (next($dbslots));
		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectSpellEffect($name, $selected)
	{
		global $dbspelleffects;
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value=-1>-</option>\n";
		reset($dbspelleffects);
		do {
			$key = key($dbspelleffects);
			$ret .= "<option value='" . $key . "'";
			if ($key == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">" . current($dbspelleffects) . "</option>\n";
		}

		while (next($dbspelleffects));
		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectAugSlot($name, $selected)
	{
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n";
		for ($i = 1; $i <= 25; $i++) {
			$ret .= "<option value='" . $i . "'";
			if ($i == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">Slot $i</option>\n";
		}

		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectLevel($name, $maxlevel, $selevel)
	{
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n";
		for ($i = 1; $i <= $maxlevel; $i++) {
			$ret .= "<option value='" . $i . "'";
			if ($i == $selevel) {
				$ret .= " selected='1'";
			}

			$ret .= ">$i</option>\n";
		}

		$ret .= "</SELECT>";
		return $ret;
	}

	function SelectTradeSkills($name, $selected)
	{
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= WriteIt("0", "-", $selected);
		$ret .= WriteIt("59", "Alchemy", $selected);
		$ret .= WriteIt("60", "Baking", $selected);
		$ret .= WriteIt("63", "Blacksmithing", $selected);
		$ret .= WriteIt("65", "Brewing", $selected);
		$ret .= WriteIt("55", "Fishing", $selected);
		$ret .= WriteIt("64", "Fletching", $selected);
		$ret .= WriteIt("68", "Jewelery making", $selected);
		$ret .= WriteIt("56", "Poison making", $selected);
		$ret .= WriteIt("69", "Pottery making", $selected);
		$ret .= WriteIt("58", "Research", $selected);
		$ret .= WriteIt("61", "Tailoring", $selected);
		$ret .= WriteIt("57", "Tinkering", $selected);
		$ret .= "</SELECT>";
		return $ret;
	}

	function WriteIt($value, $name, $sel)
	{
		$ret .="  <option value='" . $value . "'";
		if ($value == $sel) {
			$ret .=" selected='1'";
		}

		$ret .=">$name</option>\n";
		return $ret;
	}

	function SelectStats($name, $stat)
	{
		$ret .= "<select name=\"$name\" class='form-control '>\n";
		$ret .=  "<option value=''> -- Select -- </option>\n";
		$ret .= WriteIt("hp", "Hit Points", $stat);
		$ret .= WriteIt("mana", "Mana", $stat);
		$ret .= WriteIt("ac", "AC", $stat);
		$ret .= WriteIt("attack", "Attack", $stat);
		$ret .= WriteIt("aagi", "Agility", $stat);
		$ret .= WriteIt("acha", "Charisma", $stat);
		$ret .= WriteIt("adex", "Dexterity", $stat);
		$ret .= WriteIt("aint", "Intelligence", $stat);
		$ret .= WriteIt("asta", "Stamina", $stat);
		$ret .= WriteIt("astr", "Strength", $stat);
		$ret .= WriteIt("awis", "Wisdom", $stat);
		$ret .= WriteIt("damage", "Damage", $stat);
		$ret .= WriteIt("delay", "Delay", $stat);
		$ret .= WriteIt("ratio", "Ratio", $stat);
		$ret .= WriteIt("haste", "Haste", $stat);
		$ret .= WriteIt("regen", "HP Regen", $stat);
		$ret .= WriteIt("manaregen", "Mana Regen", $stat);
		$ret .= WriteIt("enduranceregen", "Endurance Regen", $stat);
		$ret .= "</select>\n";
		return $ret;
	}

	function SelectHeroicStats($name, $heroic)
	{
		$ret .= "<select name=\"$name\" class='form-control '>\n";
		$ret .= "  <option value=''> -- Select -- </option>\n";
		$ret .= WriteIt("heroic_agi", "Heroic Agility", $stat);
		$ret .= WriteIt("heroic_cha", "Heroic Charisma", $stat);
		$ret .= WriteIt("heroic_dex", "Heroic Dexterity", $stat);
		$ret .= WriteIt("heroic_int", "Heroic Intelligence", $stat);
		$ret .= WriteIt("heroic_sta", "Heroic Stamina", $stat);
		$ret .= WriteIt("heroic_str", "Heroic Strength", $stat);
		$ret .= WriteIt("heroic_wis", "Heroic Wisdom", $stat);
		$ret .= WriteIt("heroic_mr", "Heroic Resist Magic", $heroic);
		$ret .= WriteIt("heroic_fr", "Heroic Resist Fire", $heroic);
		$ret .= WriteIt("heroic_cr", "Heroic Resist Cold", $heroic);
		$ret .= WriteIt("heroic_pr", "Heroic Resist Poison", $heroic);
		$ret .= WriteIt("heroic_dr", "Heroic Resist Disease", $heroic);
		$ret .= WriteIt("heroic_svcorrup", "Heroic Resist Corruption", $heroic);
		$ret .= "</select>\n";
		return $ret;
	}

	function SelectResists($name, $resist)
	{
		$ret .= "<select name=\"$name\" class='form-control '>\n";
		$ret .= "  <option value=''> -- Select -- </option>\n";
		$ret .= WriteIt("mr", "Resist Magic", $resist);
		$ret .= WriteIt("fr", "Resist Fire", $resist);
		$ret .= WriteIt("cr", "Resist Cold", $resist);
		$ret .= WriteIt("pr", "Resist Poison", $resist);
		$ret .= WriteIt("dr", "Resist Disease", $resist);
		$ret .= WriteIt("svcorruption", "Resist Corruption", $resist);
		$ret .= "</select>\n";
		return $ret;
	}

	function SelectModifiers($name, $mod)
	{
		$ret .= "<select name=\"$name\" class='form-control '>\n";
		$ret .= "  <option value=''> -- Select -- </option>\n";
		$ret .= WriteIt("avoidance", "Avoidance", $mod);
		$ret .= WriteIt("accuracy", "Accuracy", $mod);
		$ret .= WriteIt("backstabdmg", "Backstab Damage", $mod);
		$ret .= WriteIt("clairvoyance", "Clairvoyance", $mod);
		$ret .= WriteIt("combateffects", "Combat Effects", $mod);
		$ret .= WriteIt("damageshield", "Damage Shield", $mod);
		$ret .= WriteIt("dsmitigation", "Damage Shield Mit", $mod);
		$ret .= WriteIt("dotshielding", "DoT Shielding", $mod);
		$ret .= WriteIt("extradmgamt", "Extra Damage", $mod);
		$ret .= WriteIt("healamt", "Heal Amount", $mod);
		$ret .= WriteIt("purity", "Purity", $mod);
		$ret .= WriteIt("shielding", "Shielding", $mod);
		$ret .= WriteIt("spelldmg", "Spell Damage", $mod);
		$ret .= WriteIt("spellshield", "Spell Shielding", $mod);
		$ret .= WriteIt("strikethrough", "Strikethrough", $mod);
		$ret .= WriteIt("stunresist", "Stun Resist", $mod);
		$ret .= "</select>\n";
		return $ret;
	}

	function SelectIType($name, $selected)
	{
		global $dbitypes;
		$return .= "<SELECT name='" . $name . "' class='form-control '>";
		$return .= "<option value='-1'> --- Select --- </option>\n";
		reset($dbitypes);
		do {
			$key = key($dbitypes);
			$return .= "<option value='" . $key . "'";
			if ($key == $selected) {
				$return .= " selected='1'";
			}

			$return .= ">" . current($dbitypes) . "</option>\n";
		}
		while (next($dbitypes));
		$return .= "</SELECT>";
		return $return;
	}

	function SelectDeity($name, $selected)
	{
		global $dbideities;
		$ret .= "<SELECT name=\"$name\" class='form-control '>";
		$ret .= "<option value='0'> --- Select --- </option>\n"; 
		for ($i = 2; $i <= 65536; $i*= 2) {
			$ret .= "<option value='" . $i . "'";
			if ($i == $selected) {
				$ret .= " selected='1'";
			}

			$ret .= ">" . $dbideities[$i] . "</option>\n";
		}

		$ret .=  "</SELECT>";
		return $ret;
	}
?>