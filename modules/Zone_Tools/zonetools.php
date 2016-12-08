<?php

	echo '<script type="text/javascript" src="modules/Zone_Tools/ajax/ajax.js"></script>';	
	PageTitle('Zone Copy/Import/Export');
	
	
	/* When Connected to a second database... */
	if($_GET['ImportMode'] == 1){
		echo '<center><br><hr>';
		echo '<select onchange="ZoneToolZoneSelect2nd(this.value)">';
		$Query = "SELECT `long_name`, `zoneidnumber`, `short_name` FROM `zone` ORDER BY `zoneidnumber`";
		$QueryResult = mysql_query($Query, $db2); 
		while($row = mysql_fetch_array($QueryResult)){
			echo '<option value="'. $row['zoneidnumber'] . '">' . $row['long_name'] . ' - (' .  $row['zoneidnumber'] .  ': ' . $row['short_name'] . ')' . '</option>';
		}
		echo '</select>';
		echo '<div id="ZoneSelect2"></div>';
		echo '<br><br></div> </fieldset>';
	}
	/* Initial Zone Selector */ 
	else{		
		$ZS = '<select onchange="ZoneToolZoneSelect(this.value)" class="form-control">';
		$Query = "SELECT `long_name`, `zoneidnumber`, `short_name` FROM `zone` ORDER BY `zoneidnumber`";
		$QueryResult = mysql_query($Query, $db);
		while($row = mysql_fetch_array($QueryResult)){
			$ZS .= '<option value="'. $row['zoneidnumber'] . '">' . $row['long_name'] . ' - (' .  $row['zoneidnumber'] .  ': ' . $row['short_name'] . ')' . '</option>';
		}
		$ZS .= '</select>';
		
		echo FormStart();
		echo FormInput('', '<h2 class="page-title"><i class="fa fa-cloud-upload" style="font-size:30px"></i> Zone Copy/Import<h2><hr>');
		echo FormInput('Step #1: Select Zone', $ZS);
		echo '<div id="ZoneSelect"></div>';
		echo FormEnd();
	}

?>