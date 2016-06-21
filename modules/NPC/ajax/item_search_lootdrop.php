<?php
/**
 * Created by PhpStorm.
 * User: Akkadius
 * Date: 3/2/2015
 * Time: 12:43 AM
 */

    require_once('includes/constants.php');
    require_once('modules/ItemEditor/functions.php');

    /* Display Search Result for Item */
    if(isset($_GET['item_search_lootdrop'])){
        /* All of this code is stupid and was originally from Allaclone, but I've cleaned most of it up - Akka */
        $isearch = (isset($_GET['isearch']) ? mysql_real_escape_string($_GET['isearch']) : '');
        $iname = (isset($_GET['iname']) ? mysql_real_escape_string($_GET['iname']) : '');
        $iclass = (isset($_GET['iclass']) ? mysql_real_escape_string($_GET['iclass']) : '');
        $irace = (isset($_GET['irace']) ? mysql_real_escape_string($_GET['irace']) : '');
        $islot = (isset($_GET['islot']) ? mysql_real_escape_string($_GET['islot']) : '');
        $istat1 = (isset($_GET['istat1']) ? mysql_real_escape_string($_GET['istat1']) : '');
        $istat1comp = (isset($_GET['istat1comp']) ? mysql_real_escape_string($_GET['istat1comp']) : '');
        $istat1value = (isset($_GET['istat1value']) ? mysql_real_escape_string($_GET['istat1value']) : '');
        $istat2 = (isset($_GET['istat2']) ? mysql_real_escape_string($_GET['istat2']) : '');
        $istat2comp = (isset($_GET['istat2comp']) ? mysql_real_escape_string($_GET['istat2comp']) : '');
        $istat2value = (isset($_GET['istat2value']) ? mysql_real_escape_string($_GET['istat2value']) : '');
        $iresists = (isset($_GET['iresists']) ? mysql_real_escape_string($_GET['iresists']) : '');
        $iresistscomp = (isset($_GET['iresistscomp']) ? mysql_real_escape_string($_GET['iresistscomp']) : '');
        $iresistsvalue = (isset($_GET['iresistsvalue']) ? mysql_real_escape_string($_GET['iresistsvalue']) : '');
        $iheroics = (isset($_GET['iheroics']) ? mysql_real_escape_string($_GET['iheroics']) : '');
        $iheroicscomp = (isset($_GET['iheroicscomp']) ? mysql_real_escape_string($_GET['iheroicscomp']) : '');
        $iheroicsvalue = (isset($_GET['iheroicsvalue']) ? mysql_real_escape_string($_GET['iheroicsvalue']) : '');
        $imod = (isset($_GET['imod']) ? mysql_real_escape_string($_GET['imod']) : '');
        $imodcomp = (isset($_GET['imodcomp']) ? mysql_real_escape_string($_GET['imodcomp']) : '');
        $imodvalue = (isset($_GET['imodvalue']) ? mysql_real_escape_string($_GET['imodvalue']) : '');
        $itype = (isset($_GET['itype']) ? mysql_real_escape_string($_GET['itype']) : -1);
        $iaugslot = (isset($_GET['iaugslot']) ? mysql_real_escape_string($_GET['iaugslot']) : '');
        $ieffect = (isset($_GET['ieffect']) ? mysql_real_escape_string($_GET['ieffect']) : '');
        $ireqlevel = (isset($_GET['ireqlevel']) ? mysql_real_escape_string($_GET['ireqlevel']) : '');
        $iminlevel = (isset($_GET['iminlevel']) ? mysql_real_escape_string($_GET['iminlevel']) : '');
        // $inodrop = (isset($_GET['inodrop']) ? mysql_real_escape_string($_GET['inodrop']) : '');
        $iavailability = (isset($_GET['iavailability']) ? mysql_real_escape_string($_GET['iavailability']) : '');
        $iavailevel = (isset($_GET['iavailevel']) ? mysql_real_escape_string($_GET['iavailevel']) : '');
        $ideity = (isset($_GET['ideity']) ? mysql_real_escape_string($_GET['ideity']) : '');
        $itemfield = (isset($_GET['itemfield']) ? mysql_real_escape_string($_GET['itemfield']) : '');
        $itemfieldvalue = (isset($_GET['itemfieldvalue']) ? mysql_real_escape_string($_GET['itemfieldvalue']) : '');

        $Query = "SELECT
            items.icon,
            items.Name,
            items.lore,
            items.id,
            items.itemtype,
            items.ac,
            items.hp,
            items.mana,
            items.damage,
            items.delay,
            items.color
            FROM (items";

        if ($DiscoveredItemsOnly == TRUE) {
            $Query .= ",discovered_items";
        }
        if ($iavailability == 1) {
            $Query .= ",lootdrop_entries,loottable_entries,npc_types";
        }
        $Query .= ")";
        $s = " WHERE";
        if ($itemfieldvalue && $itemfield) {
            $Query .= " $s " . $itemfield . " LIKE '%" . $itemfieldvalue . "%'";
            $s = "AND";
        }
        if ($ieffect != "") {
            $effect = "%" . str_replace(',', '%', str_replace(' ', '%', mysql_real_escape_string($ieffect))) . "%";
            $Query .= " LEFT JOIN $tbspells AS proc_s ON proceffect=proc_s.id";
            $Query .= " LEFT JOIN $tbspells AS worn_s ON worneffect=worn_s.id";
            $Query .= " LEFT JOIN $tbspells AS focus_s ON focuseffect=focus_s.id";
            $Query .= " LEFT JOIN $tbspells AS click_s ON clickeffect=click_s.id";
            $Query .= " WHERE (proc_s.name LIKE '$effect'
                                OR worn_s.name LIKE '$effect'
                                OR focus_s.name LIKE '$effect'
                                OR click_s.name LIKE '$effect') ";
            $s = "AND";
        }
        if (($istat1 != "") AND ($istat1value != "")) {
            if ($istat1 == "ratio") {
                $Query .= " $s (items.delay/items.damage $istat1comp $istat1value) AND (items.damage>0)";
                $s = "AND";
            } else {
                $Query .= " $s (items.$istat1 $istat1comp $istat1value)";
                $s = "AND";
            }
        }
        if (($istat2 != "") AND ($istat2value != "")) {
            if ($istat2 == "ratio") {
                $Query .= " $s (items.delay/items.damage $istat2comp $istat2value) AND (items.damage>0)";
                $s = "AND";
            } else {
                $Query .= " $s (items.$istat2 $istat2comp $istat2value)";
                $s = "AND";
            }
        }
        if (($imod != "") AND ($imodvalue != "")) {
            $Query .= " $s (items.$imod $imodcomp $imodvalue)";
            $s = "AND";
        }
        if ($iavailability == 1) {
            $Query .= " $s lootdrop_entries.item_id=items.id
                                AND loottable_entries.lootdrop_id=lootdrop_entries.lootdrop_id
                                AND loottable_entries.loottable_id=npc_types.loottable_id";
            if ($iavaillevel > 0) {
                $Query .= " AND npc_types.level<=$iavaillevel";
            }
            $s = "AND";
        }
        if ($iavailability == 2) {
            $Query .= ",$tbmerchantlist $s $tbmerchantlist.item=items.id";
            $s = "AND";
        }
        if ($DiscoveredItemsOnly == TRUE) {
            $Query .= " $s discovered_items.item_id=items.id";
            $s = "AND";
        }
        if ($iname != "") {
            $name = mysql_real_escape_string(str_replace("_", "%", str_replace(" ", "%", $iname)));
            /* For ID Searches */
            if (is_numeric($name)) {
                $q_add .= "OR items.id = " . $name . "";
            }
            $Query .= "
                                    $s (items.Name like '%" . $name . "%'
                                    OR items.lore like '%" . $name . "%'
                                    " . $q_add . "
                                )";
            $s = "AND";
        }
        if ($iclass > 0) {
            $Query .= " $s (items.classes & $iclass) ";
            $s = "AND";
        }
        if ($ideity > 0) {
            $Query .= " $s (items.deity   & $ideity) ";
            $s = "AND";
        }
        if ($irace > 0) {
            $Query .= " $s (items.races   & $irace) ";
            $s = "AND";
        }
        if ($itype >= 0) {
            $Query .= " $s (items.itemtype=$itype) ";
            $s = "AND";
        }
        if ($islot > 0) {
            $Query .= " $s (items.slots   & $islot) ";
            $s = "AND";
        }
        if ($iaugslot > 0) {
            $AugSlot = pow(2, $iaugslot) / 2;
            $Query .= " $s (items.augtype & $AugSlot) ";
            $s = "AND";
        }
        if ($iminlevel > 0) {
            $Query .= " $s (items.reqlevel>=$iminlevel) ";
            $s = "AND";
        }
        if ($ireqlevel > 0) {
            $Query .= " $s (items.reqlevel<=$ireqlevel) ";
            $s = "AND";
        }
        if ($inodrop) {
            $Query .= " $s (items.nodrop=1)";
            $s = "AND";
        }
        $Query .= " GROUP BY items.id ORDER BY items.Name LIMIT " . $mysql_result_limit;
        # $content_out .= '<code>' . $Query . '</code>';
        $QueryResult = mysql_query($Query);
        echo mysql_error();
        # echo $Query;

        $iname = "";

        /* Print Table Results */
        if (isset($QueryResult)) {
            /* Scroll to Results */
          $content_out .= "
              <script type='text/javascript'>
                    HookHoverTips();
              </script>
          ";
            $content_out .= '<center><a href="javascript:;" class="btn btn-xs btn-default green" onclick="$(\'#item_search\').show();">Back to Search Form</a>';
            $Tableborder = 0;
            $num_rows = mysql_num_rows($QueryResult);
            $total_row_count = $num_rows;
            if ($num_rows > $mysql_result_limit) {
                $num_rows = $mysql_result_limit;
            }

            if ($num_rows == 0) {
                $content_out .= "<b>No items found...</b><br>";
            }
            else {
                $OutOf = "";
                if ($total_row_count > $num_rows) {
                    $OutOf = " (Searches are limited to 100 Max Results)";
                }
                $content_out .= "<h4 id='result_scroll'><b>" . $num_rows . " " . ($num_rows == 1 ? "item" : "items") . " displayed</b>" . $OutOf . "</h4>";
                $content_out .= "<table class='table table-hover'>";
                $content_out .=
                    "<tr>
                        <th>Icon & Color</th>
                        <th>Item Name</th>
                        <th>Options</th>
                        <th>Item ID</th>
                        <th>Item Type</th>
                        <th>AC</th>
                        <th>HPs</th>
                        <th>Mana</th>
                        <th>Damage</th>
                        <th>Delay</th>
                        <th>Ratio</th>
                    ";
                $content_out .= "</tr>";
                $RowClass = "lr";
                for ($count = 1; $count <= $num_rows; $count++) {
                    $TableData = "";
                    $row = mysql_fetch_array($QueryResult);

                    $colHex = dechex($row['color']);
                    $colHex = '#' . str_pad($colHex, 6, "0");
                    if (strlen($colHex) == 9) {
                        $colHex = str_replace('#ff', '', $colHex);
                        $colHex = '#' . $colHex;
                    }
                    $color_display = '<div style="background-color:	' . $colHex . ' !important;border: 1px solid #e5e5e5;width:20px;height:20px;display:inline-block"></div>';

                    $TableData .= "<tr valign='top' class='" . $RowClass . "' style='vertical-align:middle;text-align: center;'><td align='center'>";
                    $TableData .= "<img class='image-wrap icon-".$row["icon"]."' " . $SizeConstraints . "/>" . $color_display;
                    $TableData .= "</td><td>";
                    $TableData .= " <a href='?M=ItemEditor&Edit=" . $row["id"] . "' id='" . $row["id"] . "' target='" . $row["id"] . "' " . HoverTip("global.php?item_view=" . $row['id']) . " >" . $row["Name"] . "</a><br><small style='color:gray'>" . $row['lore'] . "</small>";
                    $TableData .= "</td>";
                    $TableData .= "<td><a href='javascript:;' class='btn btn-xs red' onclick='add_to_lootdrop(" . $_GET['loot_drop_add_item_stack'] . ", " . $row['id'] . ")'> Add to Lootdrop <i class='fa fa-sign-in'></i></a></td>";
                    $TableData .= "<td>" . $row["id"] . "</td>";
                    $TableData .= "<td>" . $dbitypes[$row["itemtype"]] . "</td>";
                    $TableData .= "<td>" . number_format($row["ac"]) . "</td>";
                    $TableData .= "<td>" . number_format($row["hp"]) . "</td>";
                    $TableData .= "<td>" . number_format($row["mana"]) . "</td>";
                    $TableData .= "<td>" . number_format($row["damage"]) . "</td>";
                    $TableData .= "<td>" . number_format($row["delay"]) . "</td>";
                    $TableData .= "<td>" . round($row["damage"] / $row["delay"], 2) . "</td>";
                    $TableData .= "</tr>";
                    $content_out .= $TableData;
                }
                $content_out .= "</table></center><br><br>";
                echo $content_out;
            }
        }
    }
    /* Display Item Search Form */
    else{
        $content_out .= '<div style="padding-left:150px">';
        $content_out .= "<form method='GET' id='item_search'>";
        $itemfields = array("id", "minstatus", "Name", "aagi", "ac", "accuracy", "acha", "adex", "aint", "artifactflag", "asta", "astr", "attack", "augrestrict", "augslot1type", "augslot1visible", "augslot2type", "augslot2visible", "augslot3type", "augslot3visible", "augslot4type", "augslot4visible", "augslot5type", "augslot5visible", "augtype", "avoidance", "awis", "bagsize", "bagslots", "bagtype", "bagwr", "banedmgamt", "banedmgraceamt", "banedmgbody", "banedmgrace", "bardtype", "bardvalue", "book", "casttime", "casttime_", "charmfile", "charmfileid", "classes", "color", "combateffects", "extradmgskill", "extradmgamt", "price", "cr", "damage", "damageshield", "deity", "delay", "augdistiller", "dotshielding", "dr", "clicktype", "clicklevel2", "elemdmgtype", "elemdmgamt", "endur", "factionamt1", "factionamt2", "factionamt3", "factionamt4", "factionmod1", "factionmod2", "factionmod3", "factionmod4", "filename", "focuseffect", "fr", "fvnodrop", "haste", "clicklevel", "hp", "regen", "icon", "idfile", "itemclass", "itemtype", "ldonprice", "ldontheme", "ldonsold", "light", "lore", "loregroup", "magic", "mana", "manaregen", "enduranceregen", "material", "maxcharges", "mr", "nodrop", "norent", "pendingloreflag", "pr", "procrate", "races", "range", "reclevel", "recskill", "reqlevel", "sellrate", "shielding", "size", "skillmodtype", "skillmodvalue", "slots", "clickeffect", "spellshield", "strikethrough", "stunresist", "summonedflag", "tradeskills", "favor", "weight", "UNK012", "UNK013", "benefitflag", "UNK054", "UNK059", "booktype", "recastdelay", "recasttype", "guildfavor", "UNK123", "UNK124", "attuneable", "nopet", "updated", "comment", "UNK127", "pointtype", "potionbelt", "potionbeltslots", "stacksize", "notransfer", "stackable", "UNK134", "UNK137", "proceffect", "proctype", "proclevel2", "proclevel", "UNK142", "worneffect", "worntype", "wornlevel2", "wornlevel", "UNK147", "focustype", "focuslevel2", "focuslevel", "UNK152", "scrolleffect", "scrolltype", "scrolllevel2", "scrolllevel", "UNK157", "serialized", "verified", "serialization", "source", "UNK033", "lorefile", "UNK014", "svcorruption", "UNK038", "UNK060", "augslot1unk2", "augslot2unk2", "augslot3unk2", "augslot4unk2", "augslot5unk2", "UNK120", "UNK121", "questitemflag", "UNK132", "clickunk5", "clickunk6", "clickunk7", "procunk1", "procunk2", "procunk3", "procunk4", "procunk6", "procunk7", "wornunk1", "wornunk2", "wornunk3", "wornunk4", "wornunk5", "wornunk6", "wornunk7", "focusunk1", "focusunk2", "focusunk3", "focusunk4", "focusunk5", "focusunk6", "focusunk7", "scrollunk1", "scrollunk2", "scrollunk3", "scrollunk4", "scrollunk5", "scrollunk6", "scrollunk7", "UNK193", "purity", "evolvinglevel", "clickname", "procname", "wornname", "focusname", "scrollname", "dsmitigation", "heroic_str", "heroic_int", "heroic_wis", "heroic_agi", "heroic_dex", "heroic_sta", "heroic_cha", "heroic_pr", "heroic_dr", "heroic_fr", "heroic_cr", "heroic_mr", "heroic_svcorrup", "healamt", "spelldmg", "clairvoyance", "backstabdmg", "created", "elitematerial", "ldonsellbackrate", "scriptfileid", "expendablearrow", "powersourcecapacity", "bardeffect", "bardeffecttype", "bardlevel2", "bardlevel", "bardunk1", "bardunk2", "bardunk3", "bardunk4", "bardunk5", "bardname", "bardunk7", "UNK214", "UNK219", "UNK220", "UNK221", "UNK222", "UNK223", "UNK224", "UNK225", "UNK226", "UNK227", "UNK228", "UNK229", "UNK230", "UNK231", "UNK232", "UNK233", "UNK234", "UNK235", "UNK236", "UNK237", "UNK238", "UNK239", "UNK240", "UNK241", "UNK242");
        $content_out .= '<div style="width:1000px">';

        $content_out .= '<h2 class="page-title"><i class="fa fa-search" style="font-size:30px"></i> Item Search <small>Begin your item editing search by specifying search criteria below...</small></h2>';
        $content_out .= FormStart();
        $content_out .= FormInput('Name', '<input type="hidden" id="loot_drop_add_item_stack" value="' . $_GET['loot_drop_add_item'] . '"> <input type="text" placeholder="Name, ID or Lore search here..." style="width:500px" value="' . $iname . '" id="iname" class="form-control"/>', 'Item Type', SelectIType("itype",$itype));
        $content_out .= FormInput('Class', SelectIClass("iclass", $iclass));
        $content_out .= FormInput('Race', SelectRace  ("irace",   $irace));
        $content_out .= FormInput('Slot', SelectSlot  ("islot",   $islot));
        $content_out .= FormInput('Stats <br><small>(1st Filter)</small>', SelectStats("istat1", $istat1) . '' .
            "<select id='istat1comp' class='form-control'>
                            <option value='&gt;='" . ($istat1comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                            <option value='&lt;='" . ($istat1comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                            <option value='='" . ($istat1comp == '='  ? " selected='1'" : "") . ">=</option>
                            <option value='&lt'" . ($istat1comp == '<' ? " selected='1'" : "") . ">&lt</option>
                            </select>
                            <input type='text' size='4' id='istat1value' value='".$istat1value."' class='form-control' placeholder='Value here...'/>");
        $content_out .= FormInput('Stats <br><small>(2nd Filter)</small>', SelectStats("istat2", $istat2) . '' .
            "<select id='istat2comp' class='form-control'>
                            <option value='&gt;='" . ($istat2comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                            <option value='&lt;='" . ($istat2comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                            <option value='='" . ($istat2comp == '='  ? " selected='1'" : "") . ">=</option>
                            <option value='&lt'" . ($istat2comp == '<' ? " selected='1'" : "") . ">&lt</option>
                            </select>
                            <input type='text' size='4' id='istat2value' value='" . $istat2value . "' class='form-control' placeholder='Value here...'/>");
        $content_out .= FormInput('Resists', SelectResists("iresists",$iresists) . '' .
            "<select id='iresistscomp' class='form-control'>
                            <option value='&gt;='" . ($iresistscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                            <option value='&lt;='" . ($iresistscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                            <option value='='" . ($iresistscomp == '='  ? " selected='1'" : "") . ">=</option>
                            <option value='&lt'" . ($iresistscomp == '<' ? " selected='1'" : "") . ">&lt</option>
                            </select>
                            <input type='text' size='4' id='iresistsvalue' value='" . $iresistsvalue . "' class='form-control' placeholder='Value here...'/>");
        $content_out .= FormInput('Heroic Stats',
            SelectHeroicStats("iheroics",$iheroics) . '' .  "
                <select id='iheroicscomp' class='form-control'>
                    <option value='&gt;='" . ($iheroicscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($iheroicscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($iheroicscomp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($iheroicscomp == '<' ? " selected='1'" : "") . ">&lt</option>
                </select>
                <input type='text' size='4' id='iheroicsvalue' value='" . $iheroicsvalue . "' class='form-control' placeholder='Value here...'/>");
        $content_out .= FormInput('Modifiers', SelectModifiers("imod",$imod) . '' .
            "<select id='imodcomp' class='form-control'>
                            <option value='&gt;='" . ($imodcomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                            <option value='&lt;='" . ($imodcomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                            <option value='='" . ($imodcomp == '='  ? " selected='1'" : "") . ">=</option>
                            <option value='&lt'" . ($imodcomp == '<' ? " selected='1'" : "") . ">&lt</option>
                            </select>
                            <input type='text' size='4' id='imodvalue' value='" . $imodvalue . "' class='form-control' placeholder='Value here...'/>");
        $content_out .= FormInput('Item Type', SelectIType("itype",$itype));
        $content_out .= FormInput('Augmentation Type', SelectAugSlot("iaugslot",$iaugslot));
        $content_out .= FormInput('With Effect', "<input type='text' value='".$ieffect."' style='width:400px' placeholder='Effect Name...' id='ieffect' class='form-control'/>");
        $content_out .= FormInput('Min Required Level', SelectLevel("iminlevel", 255,$iminlevel));
        $content_out .= FormInput('Max Required Level', SelectLevel("ireqlevel", 255,$ireqlevel));
        $content_out .= FormInput('Tradeable Items Only', "<input type='checkbox' id='inodrop'".($inodrop?" checked='1'":"")."/>");
        $content_out .= FormInput('Item Availability', " <select id='iavailability' class='form-control'>
                                <option value='0' ".($iavailability==0?" selected='1'":"").">--- Select --- </option>
                                <option value='1' ".($iavailability==1?" selected='1'":"").">Mob Dropped</option>
                                <option value='2' ".($iavailability==2?" selected='1'":"").">Merchant Sold</option>\n
                            </select>");
        $content_out .= FormInput('Max Level', SelectLevel("iavaillevel", 255, $iavaillevel));
        $content_out .= FormInput('Deity', SelectDeity("ideity",$ideity));
        $ifv = "<select id='itemfield' class='form-control'>";
        $ifv .= "<option value='0'> --- Select --- <option>";
        foreach ($itemfields as $val){
            if($val != ""){
                if($itemfield == $val){ $sel = " selected"; } else{ $sel = ""; }
                $ifv .= "<option value='" . $val . "' " . $sel . ">" . $val . "</option>";
            }
        }
        $ifv .= "</select><br> Contains <br><input type='text' id='itemfieldvalue' value='" . $itemfieldvalue  . "' class='form-control'>";
        $content_out .= FormInput('Item Field', $ifv);
        $content_out .= FormInput('', "<button type='submit' value='Search' id='isearch' class='btn btn-default green btn-xs'><i class='fa fa-search'></i> Search</button>");
        $content_out .= FormEnd();
        $content_out .= '</div>';
        $content_out .= '</div>';

        $content_out .= '<div id="item_search_result_lootdrop"></div>';
    }


?>