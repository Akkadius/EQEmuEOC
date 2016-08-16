<?php

	require_once('includes/config.php');
	require_once('includes/functions.php');
	require_once('includes/header.php');
	require_once('includes/navbar.php');
	require_once('includes/menu.php');
	require_once('includes/content_start.php');

	/* This is where the Content Scripts are included and parsed */

	/* Index Variable */
	if(isset($_GET['M'])){
        $Mod = $_GET['M'];
    }
	else if(isset($_GET['Module'])){
        $Mod = $_GET['Module'];
    }

	if($Mod == "Commander"){ include('modules/commander/commander.php'); } 
	if($Mod == "ItemEditor"){ include('modules/ItemEditor/ItemEditor.php'); }
	if($Mod == "TaskEditor"){ include('modules/TaskEditor/TaskEditor.php'); } 
	if($Mod == "Character"){ include('modules/Character/Character.php'); }
	if($Mod == "NPC"){ include('modules/NPC/NPC.php'); }
	if($Mod == "NPC2"){ include('modules/NPC/NPC2.php'); }
	if($Mod == "ServerManager"){ include('modules/ServerManager/ServerManager.php'); }
	if($Mod == "QueryServ"){ include('modules/QueryServ/QueryServ.php'); }
	if($Mod == "SpellEditor"){ include('modules/SpellEditor/SpellEditor.php'); } 
	if($Mod == "ZT"){ include('modules/Zone_Tools/zonetools.php'); } 
	if($Mod == "dbstr"){ include('modules/dbstr/dbstr.php'); }  
	else if($Mod == ""){
		echo '
		<center>
		<div class="logo fadeIn">
			<a href="index.html">
			<img src="cust_assets/eqemu.png" alt="logo" class="logo-default" style="margin: 8px 0 0 0;"><br>
			<img src="cust_assets/eoc-fd.png" alt="logo" class="logo-default" style="width:auto;">
			</a>
		</div>
		<hr>
		<h2 class="page-title">Welcome to EQEmu Operations Center</h2><hr>
		</center>
		<h4 class="page-title">Information Regarding EOC and Tools</h4><hr>
		'; 
		echo '<iframe src="http://wiki.eqemulator.org/p?EQEmu_Operations_Center_for_Development&frm=Main#tools" width="100%" style="position:absolute;max-height:100%; height:100% !important; width:98%;left:20px" scrolling="auto"></iframe>';
	}

	require_once('includes/content_end.php');
	require_once('includes/quick_sidebar.php');  
	require_once('includes/footer.php');

?>