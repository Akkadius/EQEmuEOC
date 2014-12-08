<?php
	/* Handles all script oriented image requests */

	include('config.php');
	
	$icon = isset($_REQUEST['icon']) ? $_REQUEST['icon'] : null;
	$id   = (isset($_GET['id']) ? mysql_real_escape_string($_GET['id']) : '');
	$type = (isset($_GET['type']) ? mysql_real_escape_string($_GET['type']) : '');
	
	$directory = getcwd(); $directory = str_replace('/includes', '', $directory);
	#echo $directory; exit;
	
	if($id != "" && is_numeric($id)){ 
		if ($type == "" || $type == "weaponimage"){ 
			if(file_exists($directory . "/cust_assets/weapons/" . $id . ".jpg")){
				header('Location: ../cust_assets/weapons/'.$id.'.jpg'); 
			}
			else { header('Location: ../cust_assets/icons/blank.jpg'); }
		}
		else if ($type == "" || $type == "iconimage"){
			if(file_exists($directory . "/cust_assets/icons/item_" . $id . ".png")){
				header('Location: ../cust_assets/icons/item_'.$id.'.png'); 
			}
			else { header('Location: ../cust_assets/icons/blank.jpg'); }
		}
		else if ($type == "" || $type == "spellimage"){
			if($id == 0){ 
				header('Location: ../cust_assets/icons/blank.jpg'); 
			}
			else if(file_exists($directory . "/cust_assets/icons/" . $id . ".gif")){
				header('Location: ../cust_assets/icons/'.$id.'.gif'); 
			}
			else { header('Location: ../cust_assets/icons/blank.jpg'); }
		}
		else if ($type == "" || $type == "race"){
			if($id == 0){
				header('Location: ../cust_assets/unknown.png'); 
			}
			else if(file_exists($directory . "/cust_assets/races/" . $id . ".png")){
				header('Location: ../cust_assets/races/'.$id.'.png'); 
			} 
			else if(file_exists($directory . "/cust_assets/races/Race (" . $id . ").png")){
				header('Location: ../cust_assets/races/Race (' . $id . ').png'); 
			}
			else { header('Location: ../cust_assets/icons/unknown.png'); }
		}
	}

?>