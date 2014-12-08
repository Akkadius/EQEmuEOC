<?php

	include('./constantes.php');
	include('./config.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'functions.php');
	include($includes_dir.'spell.inc.php');
	$id   = (isset($_GET['id']) ? mysql_real_escape_string($_GET['id']) : '');
	$type = (isset($_GET['type']) ? mysql_real_escape_string($_GET['type']) : '');
	
	if($id != "" && is_numeric($id))
	{
		$TooltipData = "";
		if ($type == "" || $type == "item")
		{
			if ($DiscoveredItemsOnly==TRUE)
			{
				$Query = "SELECT * FROM $tbitems, discovered_items WHERE $tbitems.id='".$id."' AND discovered_items.item_id=$tbitems.id";
			}
			else
			{
				$Query = "SELECT * FROM $tbitems WHERE id='".$id."'";
			}
			$QueryResult = mysql_query($Query) or message_die('item.php','MYSQL_QUERY',$Query,mysql_error());
			if(mysql_num_rows($QueryResult) == 0)
			{
				//exit();
			}
			$item=mysql_fetch_array($QueryResult);
			// Prints all Item data into formatted tables
			$TooltipData .= BuildItemStats($item, 1);
		}
		elseif ($type == "spell")
		{
			$spell=getspell($id);
			if($id > 0){
				if($spell){
					$TooltipData .= BuildSpellInfo($spell, 1);
				}
				else{
					$TooltipData .= "There is no spell info found";
				}
			}
			else{
				$TooltipData .= "There is no spell info found";
			}
		}
		echo '$ToolTip.registerItem({"tooltip":"<div class=\"itemtooltip\">' . $TooltipData . '</div>","id":"' . $id . '"});';
	}
	else
	{
		//exit();
	}

?>
