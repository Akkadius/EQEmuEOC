<?php
	// Item Editor Dispatcher Page
	// Author: Akkadius
	
	echo '<div id="JSOP"></div>'; 
	
	echo '<script type="text/javascript" src="modules/Character/ajax/ajax.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="css/main.css">';
	include('../../includes/constants2.php'); 
	include('functions.php');
	
	echo '<style>
		td{ font-size:15px; }
		td input{ } 
	</style>';
	
	PageTitle("Character Tools");
	
	echo StartContent();

	echo CBoxStart("Character Tools", "", "text-shadow: 1px 0 0 #000, 0 -1px 0 #000, 0 1px 0 #000, -1px 0 0 #000;");
	
	echo '
	<center><table width="90%" class="mainForm"><tr><td> 
		<br>
		<table>
			<tr><td><h2> • Select Tool • </h2></td></tr>
			<tr><td></td></tr>
			<tr>
				<td>
					<select onchange="CharacterTool(this.value)">
					<option value="0">-</option>
					<option value="1">Copy Character</option>
					<option value="2">Character Management (BETA)</option>
				</td>
			</tr>
		</table>
		<br>
		<div id="charactertool"></div>
		
		
	</td></tr></table></center>';
	
	echo '<br>';
	echo CBoxEnd();
	
	echo EndContent();
	
?>