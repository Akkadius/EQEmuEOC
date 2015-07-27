<?php
	/* Akkadius - Ajax Requests from the Zone tools */
	
	if($_COOKIE['dblogin2']){ 
		$db2 = mysql_connect($_COOKIE['dbip2'], $_COOKIE['dbuser2'], $_COOKIE['dbpass2'], true) or die("Impossible to connect to " . $_COOKIE['dbip2'] . " : " . mysql_error());
		mysql_select_db($_COOKIE['dbname2'], $db2) or die("Impossible to select " . $_COOKIE['dbname2'] . " : " . mysql_error());
	}
	
	/* Automatically format and populate the table based on the query */
	function AutoDataTableZone($Query, $db) {
		$result = mysql_query($Query, $db);
		if (!$result) { echo 'Could not run query: ' . mysql_error(); exit; }
		$columns = mysql_num_fields($result);
		echo "<table width='100%' class='table table-bordered table-hover'><thead>";
		for ($i = 0; $i < $columns; $i++) {
			echo "<th>". str_replace('_',' ',mysql_field_name($result, $i)) . " </th>";
		}
		echo "</tr></thead><tbody>";
		while($row = mysql_fetch_array($result)) { 
			echo "<tr class='".$RowClass."'>";
			for($i = 0; $i < $columns; $i++) { echo "<td>" . $row[$i] . "</td>"; }
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
	
	/* Step #2 - Select Tool */
	if($_GET['ZoneID'] && $_GET['Selector'] == 1){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db); $zone_info = array();
		while($row = mysql_fetch_array($QueryResult)){ $zone_info = $row; }

		/* Grab some basic zone information */
		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone` = '" . $zone_info['short_name'] . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){
			$ZI .= 'Zone Instance Version #: ' . $row['version'] . ' Spawn Count: ' . $row['total'] . '<br>';
		}

		$STT =  '<select onchange="ZoneToolCopyZoneSelect('. $_GET['ZoneID'] . ', this.value)" class="form-control">';
		$STT .= '<option value=""> --- Select --- </option>';
		$STT .= '<option value="copyzone">Copy Zone Version</option>';
		$STT .= '<option value="deletezone">Delete Zone Version</option>';
		$STT .= '<option value="importzone">Zone Import Tool (From another Database)</option>';
		$STT .= '</select>';
		
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ 
			echo FormInput('', ' 
			<div class="dashboard-stat purple-plum" style="width:500px">
				<div class="visual">
					<i class="fa fa-globe"></i>
				</div>
				<div class="details">
					<div class="number">
						 Zone:
					</div>
					<div class="desc">
						 ' .  $row['long_name'] . '
					</div>
				</div>
				<a class="more" href="javascript:;" style="height:auto !important">' . $ZI . '</a>
			</div>');
			$ZoneSN = $row['short_name'];
		}

		echo FormInput('Step #2: Select Tool Type', $STT);
		
		/* Print Next Div AJAX Output Stage */
		echo '<div id="ZoneToolCopyZoneSelectOP"></div>';
	}
	/* Step #3 AJAX Result: Delete Zone Version */
	if($_GET['ZoneID'] && $_GET['CopyTool'] == "deletezone"){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }

		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone`  = '". $ZoneSN . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db);
		$VTD = '<select id="VersionToDelete" onchange="DeleteZoneVersion('. $_GET['ZoneID'] . ', this.value)" class="form-control">'; 
		$VTD .= '<option value=""> --- Select ---</option>';
		while($row = mysql_fetch_array($QueryResult)){
			$VTD .= '<option value="'. $row['version'] . '">Version '. $row['version'] . ' (' . $row['total'] . ')</option>';
			$VersionData[$row['version']] = $row['total'];
		}
		$VTD .= '</select>';
		
		echo FormInput('Delete Zone Version', $VTD);
		
		echo FormInput('', '<h2>Options</h2>');		
		echo FormInput('NPC Data', '
			<select id="DeleteOption" class="form-control">
				<option value="partial">Partial: (This zone only had spawn entries from the original)</option>
				<option value="full">Full: Use this when you made a full unique copy from one version to another</option>
			</select>');
		echo FormInput('Delete Objects in this Version?', '
			<select id="objdelete" class="form-control">
				<option value="0">No - Don\'t Delete</option>
				<option value="1">Yes - Delete</option>
			</select>');
		echo FormInput('Delete Doors in this Version?', '
			<select id="doordelete" class="form-control">
				<option value="0">No - Don\'t Delete</option>
				<option value="1">Yes - Delete</option>
			</select>');
		echo FormInput('', '<div id="ZoneToolDeleteData"></div>');
	}
	if($_GET['ZoneID'] && isset($_GET['VersionToDelete']) && !$_GET['Submit']){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
		echo '<input type="button" value="DELETE!" class="btn red" onclick="DeleteZoneVersionSubmit('. $_GET['ZoneID'] . ', document.getElementById(\'VersionToDelete\').value, document.getElementById(\'DeleteOption\').value, document.getElementById(\'objdelete\').value, document.getElementById(\'doordelete\').value)">';
		echo '<h2 style="color:red">Spawn2 Data that will be purged</h2><hr>';
		AutoDataTableZone("SELECT * FROM `spawn2` WHERE `zone` = '". $ZoneSN . "' AND `version` = " . $_GET['VersionToDelete'], $db);
	}
	if($_GET['ZoneID'] && isset($_GET['VersionToDelete']) && $_GET['Submit'] == 1){
		if($_GET['DeleteType'] == "partial"){
			$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
			$QueryResult = mysql_query($Query, $db);
			while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; $ZoneLN = $row['long_name']; }
			mysql_query("DELETE FROM `spawn2` WHERE `zone` = '". $ZoneSN . "' AND `version` = '". $_GET['VersionToDelete'] . "';", $db);
			echo '<h4 style="color:red">' . $ZoneLN . ' with Version ' . $_GET['VersionToDelete'] . ' has been deleted!</h4>';
		}
		if($_GET['DeleteType'] == "full"){
			$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
			$QueryResult = mysql_query($Query, $db);
			while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; $ZoneLN = $row['long_name']; }

			$Query = "SELECT
				spawn2.pathgrid 
				FROM
				(npc_types) 
				INNER JOIN spawnentry ON npc_types.id = spawnentry.npcID
				INNER JOIN spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
				WHERE spawn2.zone = '". $ZoneSN . "' AND spawn2.version = '". $_GET['VersionToDelete'] . "'";
			#echo $Query;
			$QueryAdd1 = ""; $QueryAdd2 = "";	
			$QueryResult = mysql_query($Query, $db);
			while($row = mysql_fetch_array($QueryResult)){
				if($row['pathgrid'] > 0){
					$QueryAdd1 .= " OR id='". $row['pathgrid'] . "'";
					$QueryAdd2 .= " OR gridid='". $row['pathgrid'] . "'";
				}
			}
			# mysql_query("DELETE FROM grid WHERE id='999999999999' (". $QueryAdd1 . ") AND ;", $db);
			# mysql_query("DELETE FROM grid_entries WHERE gridid='999999999999' (". $QueryAdd2 . ");", $db);
			# $query_total_output .= "DELETE FROM grid WHERE id='999999999999' ". $QueryAdd1 . ";" . "\n";
			# $query_total_output .= "DELETE FROM grid_entries WHERE gridid='999999999999' ". $QueryAdd2 . ";" . "\n";
			
			$Query = "SELECT DISTINCT spawnentry.npcID,spawn2.spawngroupID FROM spawnentry, npc_types, spawngroup, spawn2 WHERE (spawnentry.npcID=npc_types.id) AND (spawnentry.spawngroupID=spawngroup.id) AND (spawn2.spawngroupID = spawnentry.spawngroupID) AND (spawn2.zone='" . $ZoneSN . "') AND (spawn2.version='" . $_GET['VersionToDelete'] . "') ORDER BY npc_types.id";
			$QueryResult = mysql_query($Query, $db);
			while($row = mysql_fetch_array($QueryResult)){
				mysql_query("DELETE FROM spawnentry WHERE spawngroupID='" . $row['spawngroupID'] . "';", $db);
				mysql_query("DELETE FROM spawngroup WHERE id='" . $row['spawngroupID'] . "';", $db);
				mysql_query("DELETE FROM npc_types WHERE id='" . $row['npcID'] . "' AND id > 999;", $db);
				$query_total_output .= "DELETE FROM spawnentry WHERE spawngroupID='" . $row['spawngroupID'] . "';" . "\n"; 
				$query_total_output .= "DELETE FROM spawngroup WHERE id='" . $row['spawngroupID'] . "';" . "\n"; 
				$query_total_output .= "DELETE FROM npc_types WHERE id='" . $row['npcID'] . "' AND id > 999;" . "\n"; 
			}
			mysql_query("DELETE FROM spawn2 WHERE zone='" . $ZoneSN . "' AND  spawn2.version='" . $_GET['VersionToDelete'] . "';", $db);
			$query_total_output .= "DELETE FROM spawn2 WHERE zone='" . $ZoneSN . "' AND  spawn2.version='" . $_GET['VersionToDelete'] . "';" . "\n"; 
		}
		if($_GET['ObjectsDelete'] == 1){ 
			mysql_query("DELETE FROM object WHERE zoneid = " . $_GET['ZoneID'] . " AND version = ". $_GET['VersionToDelete'] . ";", $db);
			$query_total_output .= "DELETE FROM object WHERE zoneid = " . $_GET['ZoneID'] . " AND version = ". $_GET['VersionToDelete'] . ";" . "\n"; 			
		}
		if($_GET['DoorsDelete'] == 1){ 
			mysql_query("DELETE FROM doors WHERE zone = '" . $ZoneSN . "' AND version = " . $_GET['VersionToDelete'] . ";", $db); 
			$query_total_output .= "DELETE FROM doors WHERE zone = '" . $ZoneSN . "' AND version = " . $_GET['VersionToDelete'] . ";" . "\n"; 	
		}
		#echo var_dump($_GET);
		echo '<h4 style="color:green">Zone Version has been deleted successfully!</h4>';
		echo '<hr> Continue by selecting another tool...<br>';
		echo FormInput('Zone Delete Query Data', '<textarea style="width:1200px;height:500px" class="form-control">' . $query_total_output . '</textarea>');
	}
	
	/* Step #3 AJAX Result: Copy Zone Version */
	if($_GET['ZoneID'] && $_GET['CopyTool'] == "copyzone"){
		
		/* Fetch Zone SN: We do this over and over but this is old code.... */
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
	
		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone`  = '" . $ZoneSN . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db);

		$SV = '<select id="VersionSource" class="form-control">'; 
		while($row = mysql_fetch_array($QueryResult)){
			$SV .= '<option value="'. $row['version'] . '">Version '. $row['version'] . ' (' . $row['total'] . ')</option>';
			$VersionData[$row['version']] = $row['total'];
		}
		$SV .= '</select>';
		
		$DV = '<select id="VersionDest" class="form-control">';
		for($i = 0; $i <= 100; $i++){
			if($VersionData[$i]){ $VVersion = $VersionData[$i]; } else { $VVersion = 0; }
			$DV .= '<option value="' . $i . '">Version '. $i . ' (' . $VVersion . ')</option>';
		}
		$DV .= '</select>'; 
		
		echo FormInput('Source Version', $SV);
		echo FormInput('Destination Version', $DV);
		echo FormInput('NPC Data', '
			<select id="NPCDATA" onchange="ToggleOtherOptions(this.value)" class="form-control">
				<option value="existing">Use Existing Data</option>
				<option value="copy">Copy NPC\'s into New Data</option>
			</select>');
		echo '<div id="NPCGRIDS"></div>';
		echo FormInput('', '<input type="button" value="Copy!" class="btn green" onclick="CopyZoneVersion('. $_GET['ZoneID'] . ', document.getElementById(\'VersionSource\').value, document.getElementById(\'VersionDest\').value, document.getElementById(\'NPCDATA\').value, document.getElementById(\'NPCGRIDS\').value)">');
		
		/* Set Div for Zone Copy Output... */ 
		echo '<div id="ZoneToolCopyZoneSelectOP2"></div>';
	}
	/* On Submit of copying a zone local to local */
	if($_GET['ZoneID'] && $_GET['CopyToolSubmit']){
		
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
		
		$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `spawn2`', $db);
		$row = mysql_fetch_assoc($result);
		$ID = $row['id'];
		
		if($_GET['NPCDATA'] == "existing"){
			$Query = "SELECT * FROM `spawn2` WHERE `zone` = '". $ZoneSN . "' AND `version` = " . $_GET['Source'] . "";
			$QueryResult = mysql_query($Query, $db);
			while ($row = mysql_fetch_array($QueryResult)) {
				$n = 0;
				$InsertQuery = "INSERT INTO `spawn2` (";
				$InsertQuery2 = "";
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$InsertQuery .= $k . ", ";
						if($k == "version"){ $InsertQuery2 .= "'" . $_GET['Dest'] . "', "; }
						else if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
					}
					$n++;
				}
				$ID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				$query_total_output .= $Query . "\n"; 
				mysql_query($Query, $db);
			}
			echo FormInput('', '<h2 style="color:green">Zone ' . $ZoneSN . ' copied from ' . $_GET['Source'] . ' version to ' . $_GET['Dest'] . ' Version!</h2>');
			echo FormInput('', 'Remember: This only copied zone spawn data and linked it against the existing NPC data in the zone. If you want new NPC\'s created you need to do a copy with "New NPC Data"');
			echo FormInput('Zone Copy Query Data', '<textarea style="width:1200px;height:500px" class="form-control">' . $query_total_output . '</textarea>');
			AutoDataTableZone("SELECT * FROM `spawn2` WHERE `zone` = '". $ZoneSN . "' AND `version` = " . $_GET['Dest'] . "");
		}
		else if($_GET['NPCDATA'] == "copy"){ 
			/* Cache the Data for after reference */

			if($_GET['NPCGRIDS']){
				/* Grids */
				$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `grid` WHERE `zoneid` = "'. $_GET['ZoneID'] . '"', $db);
				$row = mysql_fetch_assoc($result);
				$ID = $row['id'];
				
				$Query = "SELECT * FROM `grid` WHERE `zoneid` = '". $_GET['ZoneID'] . "'";
				$QueryResult = mysql_query($Query, $db);
				##echo $Query . '<br>';
				while ($row = mysql_fetch_array($QueryResult)) { 
					$n = 0;
					$InsertQuery = "INSERT INTO `grid` (";
					$InsertQuery2 = "";
					foreach ($row as $k=>$v){
						if($n > 0 && !is_numeric($k)){
							$InsertQuery .= $k . ", ";
							if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
							else{ $InsertQuery2 .= "'" . $v . "', "; }
						}
						$n++;
					}
					$GridList[$row['id']] = $ID;
					$ID++;
					$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
					mysql_query($Query, $db);
					$query_total_output .= $Query . "\n"; 
					##echo $Query . '<br>';
				}
				
				$Query = "SELECT * FROM `grid_entries` WHERE `zoneid` = '". $_GET['ZoneID'] . "'";
				$QueryResult = mysql_query($Query, $db);
				##echo $Query . '<br>';
				while ($row = mysql_fetch_array($QueryResult)) { 
					$n = 0;
					$InsertQuery = "INSERT INTO `grid_entries` (";
					$InsertQuery2 = "";
					foreach ($row as $k=>$v){
						if($n > 0 && !is_numeric($k)){
							$InsertQuery .= $k . ", ";
							if($k == "gridid"){ $InsertQuery2 .= "'" . $GridList[$row['gridid']] . "', "; }
							else{ $InsertQuery2 .= "'" . $v . "', "; }
						}
						$n++;
					}
					$GridList[$row['id']] = $ID;
					$ID++;
					$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
					mysql_query($Query, $db);
					$query_total_output .= $Query . "\n"; 
					##echo $Query . '<br>';
				}
				
				
			}	
			
			/* npc_types */
			$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `npc_types`', $db);
			$row = mysql_fetch_assoc($result);
			$ID = $row['id'];
			$Query = "SELECT
				npc_types.*
				FROM
				spawnentry
				Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
				Inner Join npc_types ON npc_types.id = spawnentry.npcID WHERE `zone` = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . " GROUP by npc_types.id";
			$QueryResult = mysql_query($Query, $db);
			##echo $Query . '<br>';
			while ($row = mysql_fetch_array($QueryResult)) { 
				// Do Stuff here
				$n = 0;
				$InsertQuery = "INSERT INTO `npc_types` (";
				$InsertQuery2 = "";
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$InsertQuery .= $k . ", ";
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
					}
					$n++;
				}
				$NewReferenceList[$row['id']] = $ID;
				$ID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
				##echo $Query . '<br>';
			}
			
			/* spawngroup */
			$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `spawngroup`', $db);
			$row = mysql_fetch_assoc($result);
			$ID = $row['id'];
			$Query = "SELECT
				spawngroup.*
				FROM
				spawn2
				Inner Join spawngroup ON spawngroup.id = spawn2.spawngroupID
				WHERE spawn2.zone = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . "";
			$QueryResult = mysql_query($Query, $db);
			##echo $Query . '<br>';
			while ($row = mysql_fetch_array($QueryResult)) {
				$n = 0;
				$InsertQuery = "INSERT INTO `spawngroup` (";
				$InsertQuery2 = "";
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$InsertQuery .= $k . ", ";
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else if($k == "name"){ $InsertQuery2 .= "'" . $ZoneSN . '_' . $ID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
					}
					$n++;
				}
				$SpawnGroup[$row['id']] = $ID;
				$ID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				##echo $Query . '<br>';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
			}
			
			/* spawnentry */	
			$Query = "SELECT
				spawnentry.spawngroupID,
				spawnentry.npcID,
				spawnentry.chance
				FROM
				spawnentry
				Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
				WHERE spawn2.zone = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . "";
			$QueryResult = mysql_query($Query, $db);
			##echo $Query . '<br>';
			while ($row = mysql_fetch_array($QueryResult)) { 
				$n = 0;
				$InsertQuery = "INSERT INTO `spawnentry` (";
				$InsertQuery2 = "";
				$NValid = 0;
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						if($row['npcID'] != 0){
							$InsertQuery .= $k . ", ";
							if($k == "spawngroupID"){ $InsertQuery2 .= "'" . $SpawnGroup[$row['spawngroupID']] . "', "; }
							else if($k == "npcID"){ $InsertQuery2 .= "'" . $NewReferenceList[$row['npcID']] . "', "; }
							else{ $InsertQuery2 .= "'" . $v . "', "; }
						} else{ $NValid = 1; }
					}
					$n++;
				}
				$NewSpawnGroupReferenceList[$row['spawngroupID']] = $ID;
				$ID++;
				if($NValid != 1){
					$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
					mysql_query($Query, $db);
					$query_total_output .= $Query . "\n"; 
					#echo $NewSpawnGroupReferenceList[$row['spawngroupID']] . ' ' . $row['spawngroupID'] . '<br>';
					##echo $Query . '<br>';
				}
			}
			
			/* spawn2 */
			$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `spawn2`', $db);
			$row = mysql_fetch_assoc($result);
			$ID = $row['id'];
			
			$Query = "SELECT * FROM `spawn2` WHERE `zone` = '" . $ZoneSN . "' and version = " . $_GET['Source'] . "";
			$QueryResult = mysql_query($Query, $db);
			##echo $Query . '<br>';
			while ($row = mysql_fetch_array($QueryResult)) {
				if($NewSpawnGroupReferenceList[$row['spawngroupID']]){
					#echo $row['spawngroupID'] . ' ' . $NewSpawnGroupReferenceList[$row['spawngroupID']] . '<br>';
				
					$n = 0;
					$InsertQuery = "INSERT INTO `spawn2` (";
					$InsertQuery2 = "";
					foreach ($row as $k=>$v){
						if($n > 0 && !is_numeric($k)){
							$InsertQuery .= $k . ", ";
							if($GridList[$row['pathgrid']]){ $GRID = $GridList[$row['pathgrid']]; } else{ $GRID = 0; } 
							if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
							else if($k == "spawngroupID"){ $InsertQuery2 .= "'" . $SpawnGroup[$row['spawngroupID']] . "', "; }
							else if($k == "version"){ $InsertQuery2 .= "'" . $_GET['Dest'] . "', "; }
							else if($k == "pathgrid" && $_GET['NPCGRIDS']){ $InsertQuery2 .= "'" . $GRID . "', "; }
							else{ $InsertQuery2 .= "'" . $v . "', "; }
						}
						$n++;
					}
					$NewReferenceList[$row['id']] = $ID;
					$ID++;
					$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
					##echo $Query . '<br>';
					mysql_query($Query, $db);
					$query_total_output .= $Query . "\n"; 
				}
			}
			echo FormInput('', '<h2 class="page-title">Copy should be successful! Refresh your zone selection</h2>');
			echo FormInput('Zone Copy Query Data', '<textarea style="width:1200px;height:500px" class="form-control">' . $query_total_output . '</textarea>');
		}
	}

	if($_GET['ZoneID'] && $_GET['ImportSelector'] == 1){ 
		/* 2nd DB Connection Stuff */
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db2);
		while($row = mysql_fetch_array($QueryResult)){
			$ZoneSN = $row['short_name'];
			$ZoneLN = $row['long_name'];
		}
		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone` = '" . $ZoneSN . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db2);
		while($row = mysql_fetch_array($QueryResult)){
			$ZI .= 'Zone Instance Version #: ' . $row['version'] . ' Spawn Count: ' . $row['total'] . '<br>';
		}
		
		echo FormInput('', ' 
			<div class="dashboard-stat purple-plum" style="width:500px">
				<div class="visual">
					<i class="fa fa-globe"></i>
				</div>
				<div class="details">
					<div class="number">
						 Zone ' . $ZoneLN . ' <br><small>(Remote 2nd DB)</small>
					</div>
					<div class="desc">
						 ' .  $row['long_name'] . '
					</div>
				</div>
				<a class="more" href="javascript:;" style="height:auto !important">' . $ZI . '</a>
			</div>');
		
		echo FormInput('', '<h2>Import Options</h2>');
		
		$SVS = '<select id="VersionSourceR" class="form-control">';
		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone`  = '". $ZoneSN . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db2);
		while($row = mysql_fetch_array($QueryResult)){
			$SVS .= '<option value="'. $row['version'] . '">Version '. $row['version'] . ' (' . $row['total'] . ')</option>';
		}
		$SVS .= '</select> <br><br><a href="javascript:;" class="btn btn-default btn-xs blue" onclick="ListZoneRemote('. $_GET['ZoneID'] .', document.getElementById(\'VersionSourceR\').value)">List this zone</a>';
		
		echo FormInput('Source Version (2nd DB)', $SVS);
		
		/* 1st DB Connection Stuff */
		$Query = "SELECT version, COUNT(*) AS total FROM `spawn2` WHERE `zone`  = '". $ZoneSN . "' GROUP by `version`";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){
			echo '<option value="'. $row['version'] . '">Version '. $row['version'] . ' (' . $row['total'] . ')</option>';
			$VersionData[$row['version']] = $row['total'];
		}
		$DVS = '<select id="VersionDestD" class="form-control">';
		for($i = 0; $i <= 100; $i++){
			if($VersionData[$i]){ $VVersion = $VersionData[$i]; } else { $VVersion = 0; }
			$DVS .= '<option value="' . $i . '">Version '. $i . ' (' . $VVersion . ')</option>';
		}
		$DVS .= '</select><a href="javascript:;" class="btn btn-default btn-xs blue" onclick="ListZoneLocal('. $_GET['ZoneID'] .', document.getElementById(\'VersionDestD\').value)">List this zone</a>';
		
		echo FormInput('Destination Version', $DVS);
		echo FormInput('Import Doors?', '
			<select id="importdoors" class="form-control">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>');
		echo FormInput('Import Objects?', '
			<select id="importobjects" class="form-control">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>');
		echo FormInput('<input type="button" value="Import!" class="btn green" onclick="CopyZoneVersionExtToLoc('. $_GET['ZoneID'] . ', document.getElementById(\'VersionSourceR\').value, document.getElementById(\'VersionDestD\').value, document.getElementById(\'importdoors\').value, document.getElementById(\'importobjects\').value)">');
		
		echo '<div id="CopyZoneVersionExtToLoc"></div>';
		
		echo '<table><tr>
			<td>
				<div id="ListZoneRemote" style="width: 800px;"></div>
			</td>
			<td style="width:100px;">&nbsp;</td>
			<td>
				<div id="ListZoneLocal" style="width: 800px;"></div>
			</td>
		</tr></table>';
	}
	if($_GET['ZoneID'] && isset($_GET['Version']) && $_GET['ListZone']){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db2);
		if($_GET['Version']){ $Version = $_GET['Version']; } 
		else{ $Version = 0; }
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
		$Query = "SELECT
			npc_types.id,
			npc_types.name,
			npc_types.lastname,
			npc_types.level,
			npc_types.race,
			spawnentry.chance,
			spawn2.zone,
			spawn2.`version`,
			spawn2.pathgrid
			FROM
			npc_types
			Inner Join spawnentry ON npc_types.id = spawnentry.npcID
			Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
			WHERE spawn2.zone = '". $ZoneSN . "' AND spawn2.version = ". $Version . "
			ORDER BY npc_types.id";
			#echo $Query;
		echo '<div class="widget">
				<div class="head opened" id="opened"><h5>\'' . $ZoneSN . '\' 2nd DB (Remote)</h5></div>
				<div class="body">';
			AutoDataTableZone($Query, $db2);
	}
	if($_GET['ZoneID'] && isset($_GET['Version']) && $_GET['ListZoneLocal']){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
		$Query = "SELECT
			npc_types.id,
			npc_types.name,
			npc_types.lastname,
			npc_types.level,
			npc_types.race,
			spawnentry.chance,
			spawn2.zone,
			spawn2.`version`,
			spawn2.pathgrid
			FROM
			npc_types
			Inner Join spawnentry ON npc_types.id = spawnentry.npcID
			Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
			WHERE spawn2.zone = '". $ZoneSN . "' AND spawn2.version = ". $_GET['Version'] . "
			ORDER BY npc_types.id";
		echo '<div class="widget">
				<div class="head opened" id="opened2"><h5>\'' . $ZoneSN . '\' 1st DB (Primary)</h5></div>
				<div class="body">';
			AutoDataTableZone($Query, $db);
	}
	if($_GET['ZoneID'] && $_GET['CopyTool'] == "importzone"){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){
			$ZoneSN = $row['short_name'];
		}
		$DBS = '<select id="source_database" class="form-control" onchange="DoDBSwitch2nd(this.value, ' . $_GET['ZoneID'] . ');">';
		$DBS .= '<option value="0"> --- Select --- </option>';
		foreach ($_COOKIE as $key => $val){
			if(preg_match('/dbconn/i', $key)){
				$conn = explode(",", $val);
				/*
					IP
					DB_Name
					DB_User
					DB_Pass
				*/
				# print $key . ' ' . $val . '<br>';
				if(preg_match('/' . $dbhost . '/i', $key)){ }
				else{
					$DBS .= ' <option value="' . $key . '">' . $conn[0] . ' - ' . $conn[1] . '</option> '; 
				}
			}
		}
		$DBS .= '</select>';
		
		echo FormInput('Database Connection to Import Content from 
			<br><small style="color:red">This cannot be the source database</small>
			<br><small style="color:red">You cannot copy from database to database using the same host, they need to be two different hosts. This is a restriction I have found with MySQL - Akka</small>
		', $DBS);
		echo '<div id="ZoneSelect2"></div>';
	}	
	/* Displays the Option for copying NPC Grids when doing a Full NPC New Data Copy */
	if($_GET['NPCGRIDSSHOW'] == "copy"){
		echo FormInput('NPC Grids', '
			<select id="NPCGRIDS" class="form-control">
				<option value="existing">Use Existing Data</option>
				<option value="copy">Copy Grids into New ID\'s</option>
			</select> 
		');
	}
	if($_GET['ZoneID'] && $_GET['ImportTool'] && isset($_GET['Source']) && isset($_GET['Dest'])){
		$Query = "SELECT * FROM `zone` WHERE `zoneidnumber` = ". $_GET['ZoneID'] . " LIMIT 1";
		$QueryResult = mysql_query($Query, $db2);
		while($row = mysql_fetch_array($QueryResult)){ $ZoneSN = $row['short_name']; }
		#echo $db1 . ' ' . $db2  . '<br>'; 	
		/* Cache the Data for after reference */
		#echo var_dump($_GET);
		#return;
		
		if($_GET['Doors'] == 1){
			#echo 'Doors is getting dumped yo<br>';
			/* Doors */
			$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `doors`', $db);
			$row = mysql_fetch_assoc($result);
			$ID = $row['id'];
			$result = mysql_query('SELECT MAX(doorid) + 1 AS id FROM `doors` WHERE `zone` = "' . $ZoneSN .'" AND (version = ' . $_GET['Source'] . ' or version = -1);', $db);
			$row = mysql_fetch_assoc($result);
			$DoorID = $row['doorid'];
			
			$Query = "SELECT * FROM `doors` WHERE `zone` = '" . $ZoneSN . "' AND (version = " . $_GET['Source'] . " OR version = -1)";
			$QueryResult = mysql_query($Query, $db2);
			while ($row = mysql_fetch_array($QueryResult)) { 
				// Do Stuff here
				$InsertQuery = "INSERT INTO `doors` (";
				$InsertQuery2 = "";
				$n = 0; $f = 0;
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$type  = mysql_field_type($QueryResult, $f);
						$name  = mysql_field_name($QueryResult, $f);
						#if($type == "int" && $v == ""){ echo 'FOUND INTEGER WITH NO VALUE (' . $f . ': ' . $name . ' - ' . $k .') k ' . $k . ' v '. $v . '<br>'; }
						if($type == "int" && $v == ""){ $v = 0; }
						$InsertQuery .= $k . ", ";
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else if($k == "version"){ $InsertQuery2 .= "'" . $_GET['Dest'] . "', "; }
						else if($k == "doorid"){ $InsertQuery2 .= "'" . $DoorID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
						$f++;
					}
					$n++;
				}
				#$NewReferenceList[$row['id']] = $ID;
				$ID++;
				$DoorID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
				#echo $Query . '<br>';
			}
		}
		
		if($_GET['Objects'] == 1){
			# echo 'Objects is getting dumped yo<br>';
			/* Objects */
			$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `object`', $db);
			$row = mysql_fetch_assoc($result);
			$ID = $row['id'];
			$Query = "SELECT * FROM `object` WHERE `zoneid` = " . $_GET['ZoneID'] . " AND (version = " . $_GET['Source'] . " OR version = -1)";
			$QueryResult = mysql_query($Query, $db2);
			while ($row = mysql_fetch_array($QueryResult)) { 
				// Do Stuff here
				$InsertQuery = "INSERT INTO `object` (";
				$InsertQuery2 = "";
				$n = 0; $f = 0;
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$type  = mysql_field_type($QueryResult, $f);
						$name  = mysql_field_name($QueryResult, $f);
						#if($type == "int" && $v == ""){ echo 'FOUND INTEGER WITH NO VALUE (' . $f . ': ' . $name . ' - ' . $k .') k ' . $k . ' v '. $v . '<br>'; }
						if($type == "int" && $v == ""){ $v = 0; }
						$InsertQuery .= $k . ", ";
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else if($k == "version"){ $InsertQuery2 .= "'" . $_GET['Dest'] . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
						$f++;
					}
					$n++;
				}
				$ID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
				#echo $Query . '<br>';
			}
		}
		
		/* Grids */ 
		$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `grid` WHERE `zoneid` = "'. $_GET['ZoneID'] . '"', $db);
		$row = mysql_fetch_assoc($result);
		$ID = $row['id'];

		$Query = "SELECT * FROM `grid` WHERE `zoneid` = '". $_GET['ZoneID'] . "'";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) { 
			$n = 0;
			$InsertQuery = "INSERT INTO `grid` (";
			$InsertQuery2 = "";
			foreach ($row as $k=>$v){
				if($n > 0 && !is_numeric($k)){
					$InsertQuery .= $k . ", ";
					if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
					else{ $InsertQuery2 .= "'" . $v . "', "; }
				}
				$n++;
			}
			$GridList[$row['id']] = $ID;
			$ID++;
			$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
			mysql_query($Query, $db);
			$query_total_output .= $Query . "\n"; 
			#echo $Query . '<br>';
		}
		
		$Query = "SELECT * FROM `grid_entries` WHERE `zoneid` = '". $_GET['ZoneID'] . "'";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) { 
			$n = 0;
			$InsertQuery = "INSERT INTO `grid_entries` (";
			$InsertQuery2 = "";
			foreach ($row as $k=>$v){
				if($n > 0 && !is_numeric($k)){
					$InsertQuery .= $k . ", ";
					if($k == "gridid"){ $InsertQuery2 .= "'" . $GridList[$row['gridid']] . "', "; }
					else{ $InsertQuery2 .= "'" . $v . "', "; }
				}
				$n++;
			}
			$GridList[$row['id']] = $ID;
			$ID++;
			$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
			mysql_query($Query, $db);
			$query_total_output .= $Query . "\n"; 
			#echo $Query . '<br>';
		}
		
		/* npc_types */
		$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `npc_types`', $db);
		$row = mysql_fetch_assoc($result);
		$ID = $row['id'];
		$Query = "SELECT
			npc_types.*
			FROM
			spawnentry
			Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
			Inner Join npc_types ON npc_types.id = spawnentry.npcID WHERE `zone` = '" . $ZoneSN . "'  AND spawn2.version = " . $_GET['Source'] . " GROUP by npc_types.id";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) { 
			// Do Stuff here
			$InsertQuery = "INSERT INTO `npc_types` (";
			$InsertQuery2 = "";
			$n = 0; $f = 0;
			#$fields = mysql_num_fields($QueryResult);
			#for ($i=0; $i < $fields; $i++) {
			#	$type  = mysql_field_type($QueryResult, $i);
			#	$name  = mysql_field_name($QueryResult, $i);
			#	echo $i . ' ' . $name . ' <br>';
			#}
			foreach ($row as $k => $v){
				if($n > 0 && !is_numeric($k)){
					$type  = mysql_field_type($QueryResult, $f);
					$name  = mysql_field_name($QueryResult, $f);
					#if($type == "int" && $v == ""){ echo 'FOUND INTEGER WITH NO VALUE (' . $f . ': ' . $name . ' - ' . $k .') k ' . $k . ' v '. $v . '<br>'; }
					if($type == "int" && $v == ""){ $v = 0; } 
					if($k != "npcspecialattks" && $k != "isquest"){
						$InsertQuery .= $k . ", ";
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; } 
					}
					$f++;
				}
				$n++;
			}
			$NewReferenceList[$row['id']] = $ID;
			$ID++;
			$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
			mysql_query($Query, $db); # if(mysql_error()){ echo '<b style="color:red">' . mysql_error() . '</b><br>'; }
			$query_total_output .= $Query . "\n"; 
			#echo $Query . '<br>';
		}
		
		/* spawngroup */
		$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `spawngroup`', $db);
		$row = mysql_fetch_assoc($result);
		$ID = $row['id'];
		$Query = "SELECT
			spawngroup.*
			FROM
			spawn2
			Inner Join spawngroup ON spawngroup.id = spawn2.spawngroupID
			WHERE spawn2.zone = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . "";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) {
			$n = 0;
			$InsertQuery = "INSERT INTO `spawngroup` (";
			$InsertQuery2 = "";
			foreach ($row as $k=>$v){
				if($n > 0 && !is_numeric($k)){
					$InsertQuery .= $k . ", ";
					if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
					else if($k == "name"){ $InsertQuery2 .= "'" . $ZoneSN . '_' . $ID . "', "; }
					else{ $InsertQuery2 .= "'" . $v . "', "; }
				}
				$n++;
			}
			$SpawnGroup[$row['id']] = $ID;
			$ID++;
			$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
			##echo $Query . '<br>';
			mysql_query($Query, $db);
			$query_total_output .= $Query . "\n"; 
			#echo $Query . '<br>';
		}
		
		/* spawnentry */	
		$Query = "SELECT
			spawnentry.spawngroupID,
			spawnentry.npcID,
			spawnentry.chance
			FROM
			spawnentry
			Inner Join spawn2 ON spawnentry.spawngroupID = spawn2.spawngroupID
			WHERE spawn2.zone = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . "";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) { 
			$n = 0;
			$InsertQuery = "INSERT INTO `spawnentry` (";
			$InsertQuery2 = "";
			$NValid = 0;
			foreach ($row as $k=>$v){
				if($n > 0 && !is_numeric($k)){
					if($row['npcID'] != 0){
						$InsertQuery .= $k . ", ";
						if($k == "spawngroupID"){ $InsertQuery2 .= "'" . $SpawnGroup[$row['spawngroupID']] . "', "; }
						else if($k == "npcID"){ $InsertQuery2 .= "'" . $NewReferenceList[$row['npcID']] . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
					} else{ $NValid = 1; }
				}
				$n++;
			}
			$NewSpawnGroupReferenceList[$row['spawngroupID']] = $ID;
			$ID++;
			if($NValid != 1){
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
				#echo $NewSpawnGroupReferenceList[$row['spawngroupID']] . ' ' . $row['spawngroupID'] . '<br>';
				#echo $Query . '<br>';
			}
		}
		
		/* spawn2 */
		$result = mysql_query('SELECT MAX(id) + 1 AS id FROM `spawn2`', $db);
		$row = mysql_fetch_assoc($result);
		$ID = $row['id'];
		
		$Query = "SELECT * FROM `spawn2` WHERE `zone` = '" . $ZoneSN . "' AND spawn2.version = " . $_GET['Source'] . "";
		$QueryResult = mysql_query($Query, $db2);
		while ($row = mysql_fetch_array($QueryResult)) {
			if($NewSpawnGroupReferenceList[$row['spawngroupID']]){
				$n = 0;
				$InsertQuery = "INSERT INTO `spawn2` (";
				$InsertQuery2 = "";
				foreach ($row as $k=>$v){
					if($n > 0 && !is_numeric($k)){
						$InsertQuery .= $k . ", ";
						if($GridList[$row['pathgrid']]){ $GRID = $GridList[$row['pathgrid']]; } else{ $GRID = 0; } 
						if($k == "id"){ $InsertQuery2 .= "'" . $ID . "', "; }
						else if($k == "spawngroupID"){ $InsertQuery2 .= "'" . $SpawnGroup[$row['spawngroupID']] . "', "; }
						else if($k == "version"){ $InsertQuery2 .= "'" . $_GET['Dest'] . "', "; }
						else if($k == "pathgrid" && $_GET['NPCGRIDS']){ $InsertQuery2 .= "'" . $GRID . "', "; }
						else{ $InsertQuery2 .= "'" . $v . "', "; }
					}
					$n++;
				}
				$NewReferenceList[$row['id']] = $ID;
				$ID++;
				$Query = substr($InsertQuery, 0, -2) . ') VALUES ('. substr($InsertQuery2, 0, -2) . ');';
				##echo $Query . '<br>';
				mysql_query($Query, $db);
				$query_total_output .= $Query . "\n"; 
				#echo $Query . '<br>';
			}
		}
		
		echo '<br>Copy should be successful! Refresh your zone selection<br>';
		
		echo FormInput('Zone Copy Query Data', '<textarea style="width:1200px;height:500px" class="form-control">' . $query_total_output . '</textarea>');
	}
	
	

	
?>