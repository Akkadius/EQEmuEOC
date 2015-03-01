<?php

	$rewardmethods = array(
	  0   => "Single ID",
	  1   => "Goallist",
	  2   => "Perl"
	);

	$activitytypes = array(
	  1   => "Deliver",
	  2   => "Kill",
	  3   => "Loot",
	  4   => "Speak",
	  5   => "Explore",
	  6   => "Tradeskill",
	  7   => "Fish",
	  8   => "Forage",
	  9   => "Use",
	  10   => "Use",
	  11   => "Touch",
	  100   => "GiveCash",
	  255   => "Custom",
	  999   => "Custom"
	);
	
	$Val = $_GET['Val'];
	session_start();
	#if($_GET['SetCookieSetting'] == "Music"){ $_SESSION['IEMusic'] = $Val; }
	if($_GET['CharTool'] == 1){
		echo '<table><tr>
			<tr><td><b>Search for Player</b></td></tr>
			<tr><td><input type="text" id="search" onkeyup="if(event.keyCode == 13){ CharSearch(this.value); }"></td></tr>
			</tr></table>';
		echo '<div id="charactersearch"></div>';
		echo '<br><h2>Copy Character Settings</h2>';
		echo '<table>';
		echo '<tr><td style="text-align: right;color:#fff000">Character to be Copied</td><td style="text-align: left;"><input type="text" id="origchar" value="Origin Character" disabled></td></tr>';
		echo '<tr><td style="text-align: right;color:#fff000">Destination Account</td><td style="text-align: left;"><input type="text" id="destaccount" value="Destination Account" disabled></td></tr>';
		echo '<tr><td style="text-align: right;color:#fff000"></td><td style="text-align: left;"><input type="button" value="Copy Character" class="btnIconLeft mr10" onclick="CopyCharacter()"></td></tr>';
		echo '</table>';
		echo '<br><div id="copycharresults"></div>';
	}
	else if($_GET['CharTool'] == 2){
		echo '<table><tr>
			<tr><td><b>Search for Player</b></td></tr>
			<tr><td><input type="text" id="search" onkeyup="if(event.keyCode == 13){ CharSearchMGMT(this.value); }"></td></tr> 
			</tr></table>';
		echo '<div id="charactersearch"></div>';
	}
	if($_GET['CharSearch']){
		$sql = "SELECT `account_id`, `id`, `name` FROM `character_` WHERE `name` LIKE '%" . $_GET['CharSearch'] . "%'";
		$result = mysql_query($sql);
		echo '<br><h4>Search Results</h4>';
		echo '<ul>';
		while($row = mysql_fetch_array($result)){ 
			echo '<li>• ' . $row['name'] . ' <a href="javascript:;" onclick="document.getElementById(\'origchar\').value=\'' . $row['id'] . '\'" style="color:#fff000">[Select as Character to be Copied]</a> <a href="javascript:;" onclick="document.getElementById(\'destaccount\').value=\'' . $row['account_id'] . '\'" style="color:#fff000">[Select as Destination Account]</a></li>';
		}
		echo '</ul>';
	} 
	if($_GET['CharSearchMGMT']){
		$sql = "SELECT `account_id`, `id`, `name` FROM `character_` WHERE `name` LIKE '%" . $_GET['CharSearchMGMT'] . "%'";
		$result = mysql_query($sql);
		echo '<br><h4>Search Results</h4>';
		echo '<ul>';
		while($row = mysql_fetch_array($result)){ 
			echo '<li>• ' . $row['name'] . ' 
			
			<a href="javascript:;" onclick="DoCharMGMT(' . $row['id'] . ')" style="color:#fff000">[Select as Character]</a></li>';
		}
		echo '</ul>';
	} 
	if($_GET['DoCharMGMT']){
		$sql = "SELECT * FROM `character_` WHERE `id` LIKE '%" . $_GET['DoCharMGMT'] . "%' LIMIT 1";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){  $CharInfo = $row; }
		echo '<br><h4>Character Management ' . $CharInfo['name'] . '</h4>';
		
		echo '<ul>';
		echo '<li>• ' . $row['name'] . ' 
			<a href="javascript:;" onclick="DoCharMGMT(' . $CharInfo['id'] . ')" style="color:#fff000">[Select as Character]</a></li>';
		echo '</ul>';
		
		if($CharInfo['id']){
		
			echo '<br><br><h4>Tasks</h4>';
			$sql = "SELECT
				character_tasks.*,
				FROM_UNIXTIME(acceptedtime) AS Racceptedtime,
				tasks.*
				FROM
				character_tasks
				Inner Join tasks ON character_tasks.taskid = tasks.id
				WHERE character_tasks.charid = " . $CharInfo['id'] . " ORDER by acceptedtime";
			$result = mysql_query($sql);
			$eDash .= FlexTableHeader("Character Tasks", array("Task ID", "Title", "Duration", "Reward", "Accepted Time"), ' cellpadding="0" cellspacing="0" border="0" class="tableStatic"');
			while($row = mysql_fetch_array($result)){
				$eDash .= FlexTableRow(array(
					$row['taskid'],
					'<a href="javascript:;" onclick="DoCharTaskProgShow(' . $CharInfo['id'] . ', ' . $row['taskid'] . ')" style="color:#fff000">' . $row['title'] . '</a>',
					$row['duration'],
					$row['reward'],
					$row['Racceptedtime']
				), ' class="gradeC"');
			}
			$eDash .= FlexTableEnd(); 
			echo $eDash;
		}
		
	}
	if($_GET['DoCharTaskProgShow']){
	
		/* Connect to Local EoC DB for a second to see if they have telnet enabled */
		$eoc_local=mysql_connect($eoc_host,$eoc_user,$eoc_pass);
		if($eoc_local){ mysql_select_db($eoc_dbname,$eoc_local) or die("Impossible to select $dbname : ". mysql_error()); }
		$sql = "SELECT * FROM `eoc_local`.`server_processes` WHERE `server_user` = '" . mysql_real_escape_string($dbhost) . "' LIMIT 1";
		$result = mysql_query($sql, $eoc_local);
		while($row = mysql_fetch_array($result)){ $FieldStore = $row; }
		if($FieldStore['telnet_user'] && $FieldStore['telnet_pass']){ $TelnetEnabled = 1; }
		/* Return to Normal DB */
		$db=mysql_connect($dbhost,$dbuser,$dbpasswd);
		if($db){ mysql_select_db($dbname,$db) or die("Impossible to select $dbname : ".mysql_error()); }
	
	
		$sql = "SELECT
			character_tasks.*,
			FROM_UNIXTIME(acceptedtime) AS Racceptedtime,
			tasks.*
			FROM
			character_tasks
			Inner Join tasks ON character_tasks.taskid = tasks.id
			WHERE character_tasks.charid = " . $_GET['DoCharTaskProgShow'] . " AND character_tasks.taskid = " . $_GET['TaskID'] . "";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){ $TaskInfo = $row; $TaskInfo2[$row['id']] = $row; }
		
		$sql = "SELECT * FROM `zone`";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){ $ZoneInfo[$row['zoneidnumber']] = $row; }
		
		echo '<br><h4>Character Task Progress - ' . $TaskInfo['title'] . '</h4>';
		
		echo '<li>
			<a href="javascript:;" onclick="DoCharMGMT(' . $_GET['DoCharTaskProgShow'] . ')" style="color:#fff000">Go To Main Page</a></li>';
		echo '</ul>';
		
		$sql = "SELECT
			character_activities.*,
			activities.*
			FROM
			character_activities
			Inner Join activities ON character_activities.taskid = activities.taskid AND character_activities.activityid = activities.activityid
			where character_activities.charid = " . $_GET['DoCharTaskProgShow'] . " AND character_activities.taskid = " . $_GET['TaskID'] . "";
		$result = mysql_query($sql); 
		$eDash .= FlexTableHeader("Character Tasks", 
		array(
			"Step",
			"Text 1", 
			"Text 2", 
			"Text 3", 
			"Activity ID", 
			"Activity Type", 
			"Done Count",
			"Completed",
			"Zone",
			"Actions",
		), ' cellpadding="0" cellspacing="0" border="0" class="tableStatic"');
		$NotReady = 0; $n = 0;
		while($row = mysql_fetch_array($result)){
			$n++;
			if($TelnetEnabled == 1){ 
				$Actions = '<input type="text" id="activity_' . $row['activityid'] . '" value="1" style="display:inline;width:15% !important;"><a href="javascript:;" onclick="SendTaskUpdate(' . $_GET['DoCharTaskProgShow'] . ', ' . $TaskInfo['id'] . ', ' . $row['activityid'] . ', 1)" style="color:#fff000;display:inline;">Send Telnet Update</a>';
			}
			if($row['donecount'] < $row['goalcount'] && $NotReady == 0){ $NotReady = ($row['step'] + 1); }
			if($NotReady == $row['step']){ $Gray = "style='color:#998F8F'"; }
			$eDash .= FlexTableRow(array(
				$row['step'],
				$row['text1'],
				$row['text2'],
				$row['text3'],
				$row['activityid'],
				$activitytypes[$row['activitytype']],
				$row['donecount'] . '/' . $row['goalcount'],
				$row['completed'],
				$ZoneInfo[$row['zoneid']]['long_name'] . ' (' . $row['zoneid'] . ')',
				$Actions
			), ' class="gradeC" ' . $Gray . '');
		}
		$eDash .= FlexTableEnd(); 
		if($n > 0){	echo $eDash; } else{ echo 'This character no longer has this task...<br>';}
	}
	if($_GET['SendTaskUpdate']){
		$sql = "SELECT * FROM `character_` WHERE `id` = " . $_GET['SendTaskUpdate'] . "";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){ $CharInfo = $row; }
		
		/* Connect to Local EoC DB for a second to see if they have telnet enabled */
		$eoc_local=mysql_connect($eoc_host,$eoc_user,$eoc_pass);
		if($eoc_local){ mysql_select_db($eoc_dbname,$eoc_local) or die("Impossible to select $dbname : ". mysql_error()); }
		$sql = "SELECT * FROM `eoc_local`.`server_processes` WHERE `server_user` = '" . mysql_real_escape_string($dbhost) . "' LIMIT 1";
		$result = mysql_query($sql, $eoc_local);
		while($row = mysql_fetch_array($result)){ $FieldStore = $row; }
		if($FieldStore['telnet_user'] && $FieldStore['telnet_pass']){ $TelnetEnabled = 1; }
		/* Return to Normal DB */
		$db=mysql_connect($dbhost,$dbuser,$dbpasswd);
		if($db){ mysql_select_db($dbname,$db) or die("Impossible to select $dbname : ".mysql_error()); }
		
		if($TelnetEnabled == 1){
			$telnetuser = $FieldStore['telnet_user'];
			$telnetpass = $FieldStore['telnet_pass'];
			$cfgServer = $FieldStore['server_ip'];
			$cfgPort = 9000;
			$cfgTimeOut = 10; 
			
			DoConsoleCommands(array(
				"signalcharbyname " . $CharInfo['name'] . " 2001", 
				"signalcharbyname " . $CharInfo['name'] . " " . $_GET['TaskID'] . "", 
				"signalcharbyname " . $CharInfo['name'] . " " . $_GET['ActivityID'] . "", 
				"signalcharbyname " . $CharInfo['name'] . " " . $_GET['Count'] . "", 
				"signalcharbyname " . $CharInfo['name'] . " 2002", 
			));
			echo $CharInfo['name'];
		}
	}
	if($_GET['DoGMCommand']){
		$sql = "SELECT * FROM `character_` WHERE `id` = " . $_GET['DoGMCommand'] . "";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){ $CharInfo = $row; }
		
		/* Connect to Local EoC DB for a second to see if they have telnet enabled */
		$eoc_local=mysql_connect($eoc_host,$eoc_user,$eoc_pass);
		if($eoc_local){ mysql_select_db($eoc_dbname,$eoc_local) or die("Impossible to select $dbname : ". mysql_error()); }
		$sql = "SELECT * FROM `eoc_local`.`server_processes` WHERE `server_user` = '" . mysql_real_escape_string($dbhost) . "' LIMIT 1";
		$result = mysql_query($sql, $eoc_local);
		while($row = mysql_fetch_array($result)){ $FieldStore = $row; }
		if($FieldStore['telnet_user'] && $FieldStore['telnet_pass']){ $TelnetEnabled = 1; }
		/* Return to Normal DB */
		$db=mysql_connect($dbhost,$dbuser,$dbpasswd);
		if($db){ mysql_select_db($dbname,$db) or die("Impossible to select $dbname : ".mysql_error()); }
		
		if($TelnetEnabled == 1){
			$telnetuser = $FieldStore['telnet_user'];
			$telnetpass = $FieldStore['telnet_pass'];
			$cfgServer = $FieldStore['server_ip'];
			$cfgPort = 9000;
			$cfgTimeOut = 10; 
			if($_GET['Action'] == 2){ ### Repop Zone
				DoConsoleCommands(array(
					"signalcharbyname " . $CharInfo['name'] . " 2004", 
				));
				echo 'Zone Repopped';
			}
		}
	}
	
	if($_GET['CopyChar']){
		if($_GET['CharToCopy'] && $_GET['DestAcc']){
			$sql = "INSERT INTO `character_`  (`account_id`, `name`, `profile`, `timelaston`, `x`, `y`, `z`, `zonename`, `alt_adv`, `zoneid`, `instanceid`, `pktime`, `inventory`, `groupid`, `extprofile`, `class`, `level`, `lfp`, `lfg`, `mailkey`, `xtargets`, `firstlogon`, `inspectmessage`)
			SELECT
			" . $_GET['DestAcc'] . ", CONCAT(`name`,  '_copied'), `profile`, `timelaston`, `x`, `y`, `z`, `zonename`, `alt_adv`, `zoneid`, `instanceid`, `pktime`, `inventory`, `groupid`, `extprofile`, `class`, `level`, `lfp`, `lfg`, `mailkey`, `xtargets`, `firstlogon`, `inspectmessage`
			FROM character_ WHERE `id` = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			$NewID = mysql_insert_id();
			#echo $sql . '<br>';
			if(!$result){ echo 'Error made when copying `character_`! Halting Operation...<br>'; exit; }
			### Copy Task Progress ###
			$sql = "INSERT INTO `character_activities` 
				(charid, taskid, activityid, donecount, completed)
				SELECT " . $NewID . ", taskid, activityid, donecount, completed FROM `character_activities`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			if(!$result){ echo 'Error made when copying `character_activities`! Halting Operation...<br>'; exit; }
			#echo $sql . '<br>';
			
			$sql = "INSERT INTO `character_tasks` 
				(charid, taskid, slot, acceptedtime)
				SELECT " . $NewID . ", taskid, slot, acceptedtime FROM `character_tasks`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>';
			if(!$result){ echo 'Error made when copying `character_tasks`! Halting Operation...<br>'; exit; }
			### Copy Inventory ###
			$sql = "INSERT INTO `inventory` 
				(charid, slotid, itemid, charges, color, augslot1, augslot2, augslot3, augslot4, augslot5, instnodrop)
				SELECT " . $NewID . ", slotid, itemid, charges, color, augslot1, augslot2, augslot3, augslot4, augslot5, instnodrop FROM `inventory`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>'; 
			if(!$result){ echo 'Error made when copying `inventory`! Halting Operation...<br>'; exit; }
			### Copy Alt Currency ###
			$sql = "INSERT INTO `character_alt_currency` 
				(char_id, currency_id, amount)
				SELECT " . $NewID . ", currency_id, amount FROM `character_alt_currency`
				WHERE char_id = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>'; 
			if(!$result){ echo 'Error made when copying `character_alt_currency`! Halting Operation...<br>'; exit; }
			### Copy Character Buffs ###
			$sql = "INSERT INTO `character_buffs` 
				(character_id,
				slot_id,
				spell_id,
				caster_level,
				caster_name,
				ticsremaining,
				counters,
				numhits,
				melee_rune,
				magic_rune,
				persistent,
				death_save_chance,
				death_save_aa_chance
				)
				SELECT " . $NewID . ",
				slot_id,
				spell_id,
				caster_level,
				caster_name,
				ticsremaining,
				counters,
				numhits,
				melee_rune,
				magic_rune,
				persistent,
				death_save_chance,
				death_save_aa_chance
				FROM `character_buffs`
				WHERE character_id = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>'; 
			if(!$result){ echo 'Error made when copying `character_buffs`! Halting Operation...<br>'; exit; }
			### Copy QGlobals ### 
			$sql = "INSERT INTO `quest_globals` 
				(charid, npcid, zoneid, name, value, expdate)
				SELECT " . $NewID . ", npcid, zoneid, name, value, expdate FROM `quest_globals`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>'; 
			if(!$result){ echo 'Error made when copying `quest_globals`! Halting Operation...<br>'; exit; }
			### Copy Completed Tasks ### 
			$sql = "INSERT INTO `completed_tasks` 
				(charid, completedtime, taskid, activityid)
				SELECT " . $NewID . ", completedtime, taskid, activityid FROM `completed_tasks`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>'; 
			if(!$result){ echo 'Error made when copying `completed_tasks`! Halting Operation...<br>'; exit; }
			### Copy Instance Lockouts ### 
			$sql = "INSERT INTO `instance_lockout_player` 
				(id, charid)
				SELECT id, " . $NewID . " FROM `instance_lockout_player`
				WHERE charid = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>';
			if(!$result){ echo 'Error made when copying `instance_lockout_player`! Halting Operation...<br>'; exit; }			
			### Copy Character Flags ### 
			$sql = "INSERT INTO `zone_flags` 
				(charID, zoneID)
				SELECT " . $NewID . ", zoneID FROM `zone_flags`
				WHERE charID = " . $_GET['CharToCopy'].  ";";
			$result = mysql_query($sql);
			#echo $sql . '<br>';
			if(!$result){ echo 'Error made when copying `zone_flags`! Halting Operation...<br>'; exit; }			
			echo 'Character copy made successfully! You will need to change the name manually once you enter game or you will have a conflict...';
		}
	}

?>