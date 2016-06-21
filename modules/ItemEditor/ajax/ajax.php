<?php

    /* AJAX Icon Search Result */
	if($_GET['IconSearch']){
		echo '<style>
			.image {
               position: relative;
               width: 100%; /* for IE 6 */
            }
            .image_label {
                position: absolute;
                bottom: 0px;
                left: 0px;
                font-size: 10px !important;
                background-color:black;
                height: 12px !important;
                padding: 1px 2px 1px 2px !important;
            }
		</style>';

        /* Search by Item Type */
		if($_GET['Type'] == 1){
			$query_result = mysql_query(
                "SELECT
                items.id,
                replace(items.idfile, 'IT', '') AS WeaponList,
                items.icon FROM items
                WHERE itemtype = " . $_GET['IconSearch'] . "
                GROUP BY `icon`
                ORDER BY `WeaponList` ASC"
            );
			while($row = mysql_fetch_array($query_result)){
                $img_url = "cust_assets/icons/item_" . $row['icon'] . ".png";
				if(file_exists($img_url)) {
                    echo '
                        <a href="javascript:;" title="' . $row['icon'] . '" onClick="FinishIcon(' . $row['icon'] . ')" style="border-style: none">
                            <span class="image-wrap">
                                <img class="icon-'.$row['icon'].' image-icon cut-out"/>
                            </span>
                        </a>
                    ';
				}
			}
		}
        /* Search by Item Slot */
		else if($_GET['Type'] == 2){
			$query_result = mysql_query("
			    SELECT
			    items.id,
			    slots AS WeaponList,
			    items.icon
			    FROM
			    items
			    WHERE slots = " . $_GET['IconSearch'] . "
			    AND augtype = 0
			    GROUP BY `icon`
			    ORDER BY `WeaponList` ASC
            ");
			while($row = mysql_fetch_array($query_result)){
                $img_url = "cust_assets/icons/item_" . $row['icon'] . ".png";
				if(file_exists($img_url)) {
                    echo '
                        <a href="javascript:;" title="' . $row['icon'] . '" onClick="FinishIcon(' . $row['icon'] . ')" style="border-style: none">
                            <span class="image-wrap">
                                <img class="icon-'.$row['icon'].' image-icon cut-out"/>
                            </span>
                        </a>
                    ';
				}
			}
		}
	}
?>