<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/1/2015
 * Time: 11:46 PM
 */

    /* Special Attacks Editor */
    if(isset($_GET['special_abilities_editor'])){
        $special_attacks = array(
            "1" => "Summon",
            "2" => "Enrage",
            "3" => "Rampage",
            "4" => "AE Rampage",
            "5" => "Flurry",
            "6" => "Triple Attack",
            "7" => "Quad Attack",
            "8" => "Dual Wield",
            "9" => "Bane Attack",
            "10" => "Magic Attack",
            "11" => "Ranged Attack",
            "12" => "Unslowable",
            "13" => "Unmezable",
            "14" => "Uncharmable",
            "15" => "Unstunable",
            "16" => "Unsnareable",
            "17" => "Unfearable",
            "18" => "Immune to Dispell",
            "19" => "Immune to Melee",
            "20" => "Immune to Magic",
            "21" => "Immune to Fleeing",
            "22" => "Immune to non-Bane Melee",
            "23" => "Immune to non-Magical Melee",
            "24" => "Will Not Aggro",
            "25" => "Immune to Aggro",
            "26" => "Resist Ranged Spells",
            "27" => "See through Feign Death",
            "28" => "Immune to Taunt",
            "29" => "Tunnel Vision",
            "30" => "Does NOT buff/heal friends",
            "31" => "Unpacifiable",
            "32" => "Leashed",
            "33" => "Tethered",
            "34" => "Destructible Object",
            "35" => "No Harm from Players",
            "36" => "Always Flee",
            "37" => "Flee Percent",
            "38" => "Allow Beneficial",
            "39" => "Disable Melee",
            "40" => "Chase Distance",
            "41" => "Allow Tank",
            "42" => "Ignore Root Aggro",
            "43" => "Casting Resist Diff",
            "44" => "Counter Avoid Damage",
            "45" => "Max Special Attack",
        );

        $special_abilities_params = array(
            1 => array(
                0 => array("Level 1: Summon target to NPC" => "checkbox"),
                1 => array("Level 2: Summon NPC to target" => "checkbox"),
                2 => array("Param 0: Cooldown in ms (default: 6000)" => "checkbox"),
                3 => array("Param 1: HP ratio required to summon (default: 97)" => "checkbox"),
            ),
        );

        $npc_id = $_GET['npc_id'];
        $db_field = $_GET['db_field'];
        $db_val = $_GET['val'];

        /* This is what we're using from the loaded database value */
        $db_special_attacks = array();

        /* Parse out the database value and stuff into $db_special_attacks */
        $special_attacks_parse = preg_split("/\^/", $db_val);
        foreach ($special_attacks_parse as $key => $val) {
            $sub_values = preg_split("/,/", $val);
            # print $val . '<br>';
            $n = 1; $start_index = 0;
            while($sub_values[$n]){
                $db_special_attacks[$sub_values[0]][$start_index] = $sub_values[$n];
                $n++; $start_index++;
            }
        }

        # echo '<pre>';
        # echo var_dump($special_abilities_params);
        # echo '</pre>';

        echo '<style> .ability_check_sub { font-size: 12px !important; height: 25px !important; }</style>';

        /* I could put all of this in fancy arrays, but I think it gets just as unmanagable as you would think it would make it */
        $Content .= '<div style="width:800px;left:20%;position:relative;">';
        $Content .= '<table class="table-bordered table-striped table-condensed flip-content">';
        $td_count = 0;
        $td_data = "";
        foreach ($special_attacks as $key => $val) {
            $inputs = "";
            if($val == "Tunnel Vision") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Aggro modifier on non-tanks" placeholder="75"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Counter Avoid Damage") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="chance to avoid melee via dodge/parray/riposte/block skills " placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Avoidance % (0-100)" placeholder="0-100"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="% Reduction to Riposte" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="% Reduction to Parry" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="% Reduction to Block" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% Reduction to Dodge" placeholder="0"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Casting Resist Diff") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Set an innate resist different to be applied to all spells cast by this NPC (stacks with a spells regular resist difference)." placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';

            }
            else if($val == "Flee Percent") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Percent NPC will flee at" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Percent chance to flee" placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';

            }
            else if($val == "Chase Distance") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Max Chase Distance" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Min Chase Distance" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Ignore line of sight check for chasing" placeholder="0"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';

            }
            else if($val == "Allow Tank") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Allows an NPC the opportunity to take aggro over a client if in melee range" placeholder="1"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Leashed") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Range" placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Tethered") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Aggo Range" placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Ranged Attack") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Number of Attacks" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Max Range (default: 250)" placeholder="250"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Percent Hit Chance Modifier" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Percent Damage Modifier" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Min Range (default: RuleI(Combat, MinRangedAttackDist) = 25)" placeholder="25"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Flurry") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Flurry attack count" placeholder="Combat:MaxFlurryHits"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Percent of a normal attack damage to deal" placeholder="100"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage bonus" placeholder="0"></td>';


                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against attack (100)" placeholder="100"></td>';

                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack" placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Rampage") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Proc chance" placeholder="20"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Rampage target count (default: rule Combat:MaxRampageTargets)" placeholder="Combat:MaxRampageTargets"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage to add" placeholder="0"></td>';

                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against (100)" placeholder="100"></td>';

                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack"  placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "AE Rampage") {
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Rampage target count (1)" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="% of normal attack damage (100)" placeholder="100"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Flat damage bonus to add (0)" placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][4] . '" title="Ignore % armor for this attack (0) " placeholder="0"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][5] . '" title="Ignore flat armor for this attack (0)" placeholder="0""></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][6] . '" title="% NPC Crit against (100)" placeholder="100"></td>';

                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][7] . '" title="Flat crit bonus on top of npc\'s natual crit that can go toward this attack" placeholder="0"></td>';

                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Enrage"){
                $inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="HP % to Enrage (rule NPC:StartEnrageValue)" placeholder="0"></td>';
                $inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="Duration (ms) (10000)" placeholder="10000"></td>';
                $inputs .= '<td ><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][3] . '" title="Cooldown (ms) (360000)" placeholder="360000"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<tr><td style="width:90px"> <small>' . $val . '</small></td> <td style="width:50px; text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>' . $inputs . '</tr>';
                $top_content .= '</table>';
            }
            else if($val == "Summon"){
                $inputs .= '<td style="width:230px"><input type="input" class="ability_check_sub form-control" style="width:230px" ability="' . $key . '" value="' . $db_special_attacks[$key][1] . '" title="Cooldown in ms (default: 6000)"></td>';
                $inputs .= '<td><input type="input" class="ability_check_sub form-control" style="" ability="' . $key . '" value="' . $db_special_attacks[$key][2] . '" title="HP % before summon (default: 97)"></td>';
                $top_content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
                $top_content .= '<td style="width:90px"> <small>' . $val . '</small> </td> <td style="width:210px">
                        <select class="ability_check form-control" ability="' . $key . '" value="' . $db_special_attacks[$key][0] . '" style="width:200px">
                            <option value="0" ' . (($db_special_attacks[$key][0] == 0) ? 'selected' : '') . '>Off</option>
                            <option value="1" ' . (($db_special_attacks[$key][0] == 1) ? 'selected' : '') . '>Summon target to NPC</option>
                            <option value="2" ' . (($db_special_attacks[$key][0] == 2) ? 'selected' : '') . '>Summon NPC to target</option>
                        </select> ' . $inputs . '</td>';
                $top_content .= '</table>';
            }
            else {
                $td_data .= '<td> <small>' . $val . '</small></td> <td style="text-align:center"> <input class="ability_check" ability="' . $key . '" type="checkbox" ' . (($db_special_attacks[$key][0] == 1) ? 'checked' : '') . '></td>';

                $td_count++;
                if($td_count == 3){
                    $bottom_content .= '<tr>' . $td_data . '</tr>';
                    $td_data = "";
                    $td_count = 0;
                }
            }
        }
        $Content .= '</table>';

        // $Content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
        $Content .= '<center><small>Hover over each input to see the descriptions for each special ability </small></center>';
        $Content .= $top_content;
        // $Content .= '</table>';

        $Content .= '<br>';
        $Content .= '<table class="table-bordered table-striped table-condensed flip-content" style="width:100%">';
        $Content .= $bottom_content;
        $Content .= '</table>';

        $Content .= '<br>';
        $Content .= '<table style="width:100%">';
        $Content .= '<tr><td><input type="text" class="form-control m-wrap span6" value="' . $db_val . '" id="special_attacks_result" style="width:100% !important;"></td></tr>';
        $Content .= '<tr><td>&nbsp;</td></tr>';
        $Content .= '<tr><td><a href="javascript:;" class="button btn blue" onclick="update_npc_field(' . $npc_id . ', \'' . $db_field . '\', $(\'#special_attacks_result\').val())"><i class="icon-save"></i>Save to NPC ID:' . $npc_id . '</a></td></tr>';
        $Content .= '</table>';
        $Content .= '</div>';

        echo '<script type="text/javascript" src="modules/NPC/ajax/npc_special_attacks.js"></script>';

        echo Modal('Special Abilites Editor', $Content, '');
    }

?>