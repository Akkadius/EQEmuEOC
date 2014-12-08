<?php
	if($_GET['IconSearch']){
		echo '<style>
			.image { 
			   position: relative; 
			   width: 100%; /* for IE 6 */
			}
			h2 { 
			   position: absolute; 
			   top: 0px; 
			   left: 15px; 
			   width: 100%; 
			   font-size:11px;
			}
		</style>';
		if($_GET['Type'] == 1){
			$QueryResult = mysql_query('SELECT items.id, replace(items.idfile, "IT", "") AS WeaponList, items.icon FROM items WHERE itemtype = '. $_GET['IconSearch'] . '  GROUP BY `icon` ORDER BY `WeaponList` ASC;');
			while($row = mysql_fetch_array($QueryResult)){
				if(file_exists("cust_assets/icons/item_" . $row['icon'] . ".png")) { 
					$img_url = "cust_assets/icons/item_" . $row['icon'] . ".png";
					echo '<div class="image" style="display:inline">' .  
					"<a href='javascript:;' title='' class='btn btn-default' onClick='FinishIcon(" . $row['icon'] . ")'><img src='" . $img_url . "' title='". $row['icon'] . "' width='35' height='35'/></a> "
					. '<h2>' . $row['icon'] . '</h2></div>';
				}
			}
		}
		else if($_GET['Type'] == 2){
			$QueryResult = mysql_query('SELECT items.id, slots AS WeaponList, items.icon FROM items WHERE slots = '. $_GET['IconSearch'] . '  AND augtype = 0 GROUP BY `icon` ORDER BY `WeaponList` ASC;');
			while($row = mysql_fetch_array($QueryResult)){
				if(file_exists("cust_assets/icons/item_" . $row['icon'] . ".png")) { 
					$img_url = "cust_assets/icons/item_" . $row['icon'] . ".png";
					echo '<div class="image" style="display:inline">' .  
					"<a href='javascript:;' title='' class='btn btn-default' onClick='FinishIcon(" . $row['icon'] . ")'><img src='" . $img_url . "' title='". $row['icon'] . "' width='35' height='35'/></a> "
					. '<h2>' . $row['icon'] . '</h2></div>';
				}
			}
		}
	}
?>