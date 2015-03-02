<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/1/2015
 * Time: 11:55 PM
 */

    /*
		Single NPC Edit Form
	    Spawned from Modal
	*/
    if($_GET['SingleNPCEdit'] > 0){
        /* Get Col Defs */
        $npc_cols = array();
        $npc_a_cols = array();
        $query = "SHOW COLUMNS FROM `npc_types`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            if ($row['Field'] == "hp") {
                $field = "Hit Points";
            }
            else if ($npc_fields[$row['Field']]) {
                $field = $npc_fields[$row['Field']];
            }
            else {
                $field = $row['Field'];
            }
            array_push ($npc_cols, $field);
            array_push ($npc_a_cols, $row['Field']);
        }

        $npc_fields = array();
        $query = "SELECT * FROM `npc_types` WHERE `id` = " . $_GET['SingleNPCEdit'] . "";
        $result = mysql_query($query);
        $Content .= '<center>';
        while($row = mysql_fetch_array($result)){
            $n = 1;

            echo '<input type="hidden" id="npc_id" value="'. $row['id'] . '">';

            echo '<style>
                    .single_edit_table table tbody tr{ height:10px !important; }
                    .single_edit_table table tbody td{ padding: 1px !important; text-align:center;  }
                    .single_edit_table table tbody td input{
                        text-align:center;
                        font-size: 12px !important;
                        height: 25px !important;
                    }
                    .single_edit_table td{ width:50px; }
                    .single_edit_table table input{ min-width:150px !important; }
                    #picker {
                        margin:0;
                        padding:0;
                        border:0;
                        width:70px;
                        height:20px;
                        border-right:20px solid rgb(' . $row['armortint_red'] . ', ' . $row['armortint_green'] . ', ' . $row['armortint_blue']  . ');
                        line-height:20px;
                    }
                </style>';

            foreach ($row as $key => $val){
                if(is_numeric($key)){
                    /* Races */
                    $field_name = $npc_a_cols[($n - 1)];
                    if($field_name == "race"){
                        $npc_fields["Visual Texture"][$field_name][0] = $npc_cols[($n - 1)];
                        $field = "" .
                            '<span class="image-wrap " style="width: auto; height: auto;">
                                    <img src="includes/img.php?type=race&id=' . $val . '" id="RaceIMG" style="width:auto;height:280px;" class="soft-embossed">
                                </span>' . "
                                <select title='" . ProcessFieldTitle($field_name) . "' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='RaceChange(" . $_GET['SingleNPCEdit'] . ", this.value)'>";
                        foreach ($races as $key => $val2) {
                            if ($val == $key) {
                                $field .= '<option selected value="' . $key . '">' . $key . ': ' . $val2 . ' </option>';
                            }
                            else {
                                $field .= '<option value="' . $key . '">' . $key . ': ' . $val2 . ' </option>';
                            }
                        }
                        $field .= '</select>';
                        $npc_fields["Visual Texture"][$field_name][1] = $field;
                    }
                    else if($field_name == "d_melee_texture1" || $field_name == "d_melee_texture2"){
                        $npc_fields["Visual Texture"][$field_name][0] = $npc_cols[($n - 1)] . '';
                        $npc_fields["Visual Texture"][$field_name][1] =
                            '<a href="javascript:;" onclick="OpenWindow(\'min.php?Mod=IE&prevITfile=1&Field=' . $field_name . '&NPC=' . $row['id'] . '\', \'_blank\', 900, 900)">
                                    <span class="image-wrap " style="width: auto; height: auto;">
                                        <img src="includes/img.php?type=weaponimage&id='. $val . '" id="'.  $field_name . '" class="embossed morphing-glowing">
                                    </span>
                                </a>
                            ';
                        $npc_fields["Visual Texture"][$field_name][1] .=  "<input type='number' title='" . ProcessFieldTitle($field_name) . "'  value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)'>";
                    }
                    else if($Custom_Select_Fields[$field_name]){
                        $npc_fields[$field_category[$field_name]][$field_name][0] = $npc_cols[($n - 1)];
                        $npc_fields[$field_category[$field_name]][$field_name][1] = GetFieldSelect($field_name, $val, $row['id']);
                    }
                    /* Generic catch all */
                    else if($field_category[$field_name][0]){
                        $npc_fields[$field_category[$field_name]][$field_name][0] = $npc_cols[($n - 1)];
                        $npc_fields[$field_category[$field_name]][$field_name][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)' title='" . ProcessFieldTitle($field_name) . "'>";
                    }
                    else{
                        $npc_fields['End'][$field_name][0] = $npc_cols[($n - 1)];
                        $npc_fields['End'][$field_name][1] =  "<input type='text' value='" . $val . "' id='" . $row['id'] . "^" . $field_name . "' class='" . $field_name . "' onchange='update_npc_field(" . $row['id'] . ", \"" . $field_name . "\", this.value)' >";
                    }
                    $n++;
                }
            }
        }

        $category_order = array("General", "Visual Texture", "Combat", "Appearance", "Statistics", "Misc.");
        $td_content = "";
        $n = 0;

        /* Manually push this field into the Appearance Category */
        $npc_fields["Appearance"]["tint"][0] = "Armor Tint";
        $npc_fields["Appearance"]["tint"][1] = "<div id='armor_tint_selector'></div>";

        foreach ($category_order as $order_val) {
            if($order_val != '') {
                $Content .= '
                        <h2> <span class="label label-success">' . $order_val . '</span></h2>
                    ';
            }
            $Content .= '<table class="table-bordered table-striped table-condensed flip-content single_edit_table">';
            foreach ($npc_fields[$order_val] as $key => $val) {
                #print $key . '<br>';
                if($key == "d_melee_texture1" || $key == "d_melee_texture2" || $key == "race"){
                    $td_content .= '
                            <td style="text-align:center;vertical-align:top">' . $val[1] . '<br><b>' .  $val[0] . '</b></td>';
                }
                else {
                    $td_content .= '
                            <td style="text-align:right;"><b>' . $val[0] . '</b>  </td><td> ' . $val[1] . '  </td>';
                }
                $n++;
                if ($n == 3) {
                    $Content .= '<tr>' . $td_content . '</tr>';
                    $td_content = "";
                    $n = 0;
                }
            }
            if($td_content != ""){
                $Content .= '<tr>' . $td_content . '</tr>';
                $td_content = "";
                $n = 0;
            }

            $Content .= '</table>';
        }
        $Content .= '</table>';
        echo '<script type="text/javascript" src="modules/NPC/ajax/single_npc_edit.js"></script>';
        echo Modal('NPC Edit', $Content, '');
    }

?>