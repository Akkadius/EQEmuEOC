<?php

	$IncludeDir = dirname($_SERVER['SCRIPT_FILENAME']);
	$IncludeDir = str_replace('includes', '', $IncludeDir); #echo $IncludeDir; exit;
	require_once($IncludeDir . '/includes/config.php');
	require_once($IncludeDir . '/includes/functions.php');
	
	if($_GET['M'] == "Items"){ include('modules/ItemEditor/ajax/ajax.php'); }
	if($_GET['M'] == "TaskEditor"){ include('modules/TaskEditor/js/ajax.php'); } 
	if($_GET['M'] == "NPC"){ include('modules/NPC/ajax/ajax.php'); }
	if($_GET['M'] == "CM"){ include('modules/commander/js/ajax.php'); }
	if($_GET['M'] == "DBAuth"){ include('modules/DBAuth/ajax.php'); }
	if($_GET['M'] == "SpellEditor"){ include('modules/SpellEditor/ajax.php'); }   
	if($_GET['M'] == "QueryServ"){ include('modules/QueryServ/js/ajax.php'); }    
	if($_GET['M'] == "dbstr"){ include('modules/dbstr/js/ajax.php'); }   
	if($_GET['M'] == "ZT"){ include('modules/Zone_Tools/ajax/ajax.php'); }   
    /* Lol Test */
?>