<?php
/*
    Item Editor Dispatcher Page
    Author: Akkadius
*/

    echo '<div id="JSOP"></div>';
    /* Javascript to be included at the footer after Jquery */
    $FJS .= '<script type="text/javascript" src="modules/ItemEditor/ajax/ajax.js"></script>';
    $FJS .= '<script type="text/javascript" src="cust_assets/js/lazy-load.js"></script>';
    $FJS .= '<script type="text/javascript" src="cust_assets/js/colpick/js/colpick.js"></script>';
    $FJS .= '<link href="cust_assets/js/colpick/css/colpick.css" rel="stylesheet" type="text/css"/>';
    $FJS .= "<script type=\"text/javascript\">
            $('#color').colpick({
                layout:'hex',
                submit:0,
                colorScheme: 'dark',
                onChange:function(hsb,hex,rgb,el,bySetColor) {
                    $(el).css('border-color','#'+hex);
                    $('#color_preview').css('background-color','#'+hex);
                    // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                    if(!bySetColor) $(el).val('#'+hex);
                }
            }).keyup(function(){
                $(this).colpickSetColor(this.value);
            });
        </script>";

    require_once('modules/ItemEditor/functions.php');
    require_once('includes/constants.php');

    PageTitle("Item Editor");

    /* Item Edit */
    if(!isset($_SESSION['IEViewMode'])){ $_SESSION['IEViewMode'] = 1; }
    if($_GET['Edit']){
        echo '<center>';
        echo ItemEditor($_GET['Edit'], $_SESSION['IEViewMode']);
        $FJS .= '<script type="text/javascript">
                $("select").each(function() { $(this).tooltip(); });
                $(":text").each(function() {
                    $(this).css("height", "30px");
                    $(this).tooltip();
                });
                $(":text").each(function() {
                    var value = $(this).val();
                    var size  = value.length;
                    size = size * 2; // average width of a char
                    $(this).css("width", size * 4 + 30);
                    if(size == 0){ $(this).css("width", 150);  }
                });
                $( "input[type=\'text\']" ).change(function() {
                    if($(this).attr("id") == "clickeffect"
                        || $(this).attr("id") == "bardeffect"
                        || $(this).attr("id") == "scrolleffect"
                        || $(this).attr("id") == "focuseffect"
                        || $(this).attr("id") == "proceffect"
                        || $(this).attr("id") == "worneffect"
                    ){
                        $("[data-" + $(this).attr("id") + "]").attr("hovertip-url", "global.php?spell_view=" + $(this).val());
                    }
                });
            </script>';
    }
    else{
        require_once('includes/constants.php');

        /* All of this code is stupid, but I've cleaned most of it up - Akka */

        $isearch       = (isset($_GET[       'isearch']) ? mysql_real_escape_string($_GET[       'isearch']) : '');
        $iname         = (isset($_GET[         'iname']) ? mysql_real_escape_string($_GET[         'iname']) : '');
        $iclass        = (isset($_GET[        'iclass']) ? mysql_real_escape_string($_GET[        'iclass']) : '');
        $irace         = (isset($_GET[         'irace']) ? mysql_real_escape_string($_GET[         'irace']) : '');
        $islot         = (isset($_GET[         'islot']) ? mysql_real_escape_string($_GET[         'islot']) : '');
        $istat1        = (isset($_GET[        'istat1']) ? mysql_real_escape_string($_GET[        'istat1']) : '');
        $istat1comp    = (isset($_GET[    'istat1comp']) ? mysql_real_escape_string($_GET[    'istat1comp']) : '');
        $istat1value   = (isset($_GET[   'istat1value']) ? mysql_real_escape_string($_GET[   'istat1value']) : '');
        $istat2        = (isset($_GET[        'istat2']) ? mysql_real_escape_string($_GET[        'istat2']) : '');
        $istat2comp    = (isset($_GET[    'istat2comp']) ? mysql_real_escape_string($_GET[    'istat2comp']) : '');
        $istat2value   = (isset($_GET[   'istat2value']) ? mysql_real_escape_string($_GET[   'istat2value']) : '');
        $iresists      = (isset($_GET[      'iresists']) ? mysql_real_escape_string($_GET[      'iresists']) : '');
        $iresistscomp  = (isset($_GET[  'iresistscomp']) ? mysql_real_escape_string($_GET[  'iresistscomp']) : '');
        $iresistsvalue = (isset($_GET[ 'iresistsvalue']) ? mysql_real_escape_string($_GET[ 'iresistsvalue']) : '');
        $iheroics      = (isset($_GET[      'iheroics']) ? mysql_real_escape_string($_GET[      'iheroics']) : '');
        $iheroicscomp  = (isset($_GET[  'iheroicscomp']) ? mysql_real_escape_string($_GET[  'iheroicscomp']) : '');
        $iheroicsvalue = (isset($_GET[ 'iheroicsvalue']) ? mysql_real_escape_string($_GET[ 'iheroicsvalue']) : '');
        $imod          = (isset($_GET[          'imod']) ? mysql_real_escape_string($_GET[          'imod']) : '');
        $imodcomp      = (isset($_GET[      'imodcomp']) ? mysql_real_escape_string($_GET[      'imodcomp']) : '');
        $imodvalue     = (isset($_GET[     'imodvalue']) ? mysql_real_escape_string($_GET[     'imodvalue']) : '');
        $itype         = (isset($_GET[         'itype']) ? mysql_real_escape_string($_GET[         'itype']) : -1);
        $iaugslot      = (isset($_GET[      'iaugslot']) ? mysql_real_escape_string($_GET[      'iaugslot']) : '');
        $ieffect       = (isset($_GET[       'ieffect']) ? mysql_real_escape_string($_GET[       'ieffect']) : '');
        $ireqlevel     = (isset($_GET[     'ireqlevel']) ? mysql_real_escape_string($_GET[     'ireqlevel']) : '');
        $iminlevel     = (isset($_GET[     'iminlevel']) ? mysql_real_escape_string($_GET[     'iminlevel']) : '');
        $inodrop       = (isset($_GET[       'inodrop']) ? mysql_real_escape_string($_GET[       'inodrop']) : '');
        $iavailability = (isset($_GET[ 'iavailability']) ? mysql_real_escape_string($_GET[ 'iavailability']) : '');
        $iavaillevel   = (isset($_GET[    'iavailevel']) ? mysql_real_escape_string($_GET[    'iavailevel']) : '');
        $ideity        = (isset($_GET[        'ideity']) ? mysql_real_escape_string($_GET[        'ideity']) : '');
        $itemfield     = (isset($_GET[     'itemfield']) ? mysql_real_escape_string($_GET[     'itemfield']) : '');
        $itemfieldvalue= (isset($_GET['itemfieldvalue']) ? mysql_real_escape_string($_GET['itemfieldvalue']) : '');

        if($isearch != "") {
            $Query  = "SELECT
                    $tbitems.icon,
                    $tbitems.Name,
                    $tbitems.lore,
                    $tbitems.id,
                    $tbitems.itemtype,
                    $tbitems.ac,
                    $tbitems.hp,
                    $tbitems.mana,
                    $tbitems.damage,
                    $tbitems.delay,
                    $tbitems.color
                    FROM ($tbitems";

            if ($DiscoveredItemsOnly==TRUE) { $Query .= ",discovered_items"; }
            if($iavailability == 1) { $Query .= ",$tblootdropentries,$tbloottableentries,$tbnpctypes"; }
            $Query  .= ")";
            $s = " WHERE";
            if($itemfieldvalue && $itemfield){ $Query .= " $s " . $itemfield . " LIKE '%" . $itemfieldvalue . "%'"; $s = "AND"; }
            if($ieffect!="") {
                $effect="%".str_replace(',','%',str_replace(' ','%',mysql_real_escape_string($ieffect)))."%";
                $Query.=" LEFT JOIN $tbspells AS proc_s ON proceffect=proc_s.id";
                $Query.=" LEFT JOIN $tbspells AS worn_s ON worneffect=worn_s.id";
                $Query.=" LEFT JOIN $tbspells AS focus_s ON focuseffect=focus_s.id";
                $Query.=" LEFT JOIN $tbspells AS click_s ON clickeffect=click_s.id";
                $Query.=" WHERE (proc_s.name LIKE '$effect'
                        OR worn_s.name LIKE '$effect'
                        OR focus_s.name LIKE '$effect'
                        OR click_s.name LIKE '$effect') ";
                $s="AND";
            }
            if(($istat1 != "") AND ($istat1value != "")) {
                if($istat1 == "ratio") { $Query .= " $s ($tbitems.delay/$tbitems.damage $istat1comp $istat1value) AND ($tbitems.damage>0)";  $s="AND";  }
                else { $Query .= " $s ($tbitems.$istat1 $istat1comp $istat1value)";  $s="AND";  }
            }
            if(($istat2 != "") AND ($istat2value != "")) {
                if($istat2 == "ratio") { $Query .= " $s ($tbitems.delay/$tbitems.damage $istat2comp $istat2value) AND ($tbitems.damage>0)";  $s="AND";  }
                else { $Query .= " $s ($tbitems.$istat2 $istat2comp $istat2value)";  $s="AND";  }
            }
            if(($imod != "") AND ($imodvalue != "")) { $Query .= " $s ($tbitems.$imod $imodcomp $imodvalue)";  $s="AND";  }
            if($iavailability == 1) {
                $Query .= " $s $tblootdropentries.item_id=$tbitems.id
                        AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
                        AND $tbloottableentries.loottable_id=$tbnpctypes.loottable_id";
                if($iavaillevel > 0) { $Query .= " AND $tbnpctypes.level<=$iavaillevel"; } $s="AND";
            }
            if($iavailability == 2) {
                $Query .= ",$tbmerchantlist $s $tbmerchantlist.item=$tbitems.id";
                $s="AND";
            }
            if ($DiscoveredItemsOnly==TRUE) { $Query .= " $s discovered_items.item_id=$tbitems.id";  $s="AND";  }
            if($iname != "") {
                $name = mysql_real_escape_string(str_replace("_", "%", str_replace(" ","%",$iname)));
                /* For ID Searches */
                if(is_numeric($name)){ $q_add .= "OR $tbitems.id = ". $name . ""; }
                $Query .= "
                            $s ($tbitems.Name like '%".$name."%'
                            OR $tbitems.lore like '%".$name."%'
                            " . $q_add . "
                        )";
                $s="AND";
            }
            if($iclass > 0)    { $Query.=" $s ($tbitems.classes & $iclass) ";    $s="AND"; }
            if($ideity > 0)    { $Query.=" $s ($tbitems.deity   & $ideity) ";    $s="AND"; }
            if($irace > 0)     { $Query.=" $s ($tbitems.races   & $irace) ";     $s="AND"; }
            if($itype >= 0)    { $Query.=" $s ($tbitems.itemtype=$itype) ";      $s="AND"; }
            if($islot > 0)     { $Query.=" $s ($tbitems.slots   & $islot) ";     $s="AND"; }
            if($iaugslot > 0)
            {
                $AugSlot = pow(2, $iaugslot) / 2;
                $Query.=" $s ($tbitems.augtype & $AugSlot) ";  $s="AND";
            }
            if($iminlevel > 0) { $Query.=" $s ($tbitems.reqlevel>=$iminlevel) "; $s="AND"; }
            if($ireqlevel > 0) { $Query.=" $s ($tbitems.reqlevel<=$ireqlevel) "; $s="AND"; }
            if($inodrop)       { $Query.=" $s ($tbitems.nodrop=1)";              $s="AND"; }
            $Query.=" GROUP BY $tbitems.id ORDER BY $tbitems.Name LIMIT " . $mysql_result_limit;
            echo '<code>' . $Query . '</code>';
            $QueryResult = mysql_query($Query);
        }
        else { $iname = ""; }
        echo "<form method='GET' action=''>";
        $itemfields = array("id", "minstatus", "Name", "aagi", "ac", "accuracy", "acha", "adex", "aint", "artifactflag", "asta", "astr", "attack", "augrestrict", "augslot1type", "augslot1visible", "augslot2type", "augslot2visible", "augslot3type", "augslot3visible", "augslot4type", "augslot4visible", "augslot5type", "augslot5visible", "augtype", "avoidance", "awis", "bagsize", "bagslots", "bagtype", "bagwr", "banedmgamt", "banedmgraceamt", "banedmgbody", "banedmgrace", "bardtype", "bardvalue", "book", "casttime", "casttime_", "charmfile", "charmfileid", "classes", "color", "combateffects", "extradmgskill", "extradmgamt", "price", "cr", "damage", "damageshield", "deity", "delay", "augdistiller", "dotshielding", "dr", "clicktype", "clicklevel2", "elemdmgtype", "elemdmgamt", "endur", "factionamt1", "factionamt2", "factionamt3", "factionamt4", "factionmod1", "factionmod2", "factionmod3", "factionmod4", "filename", "focuseffect", "fr", "fvnodrop", "haste", "clicklevel", "hp", "regen", "icon", "idfile", "itemclass", "itemtype", "ldonprice", "ldontheme", "ldonsold", "light", "lore", "loregroup", "magic", "mana", "manaregen", "enduranceregen", "material", "maxcharges", "mr", "nodrop", "norent", "pendingloreflag", "pr", "procrate", "races", "range", "reclevel", "recskill", "reqlevel", "sellrate", "shielding", "size", "skillmodtype", "skillmodvalue", "slots", "clickeffect", "spellshield", "strikethrough", "stunresist", "summonedflag", "tradeskills", "favor", "weight", "UNK012", "UNK013", "benefitflag", "UNK054", "UNK059", "booktype", "recastdelay", "recasttype", "guildfavor", "UNK123", "UNK124", "attuneable", "nopet", "updated", "comment", "UNK127", "pointtype", "potionbelt", "potionbeltslots", "stacksize", "notransfer", "stackable", "UNK134", "UNK137", "proceffect", "proctype", "proclevel2", "proclevel", "UNK142", "worneffect", "worntype", "wornlevel2", "wornlevel", "UNK147", "focustype", "focuslevel2", "focuslevel", "UNK152", "scrolleffect", "scrolltype", "scrolllevel2", "scrolllevel", "UNK157", "serialized", "verified", "serialization", "source", "UNK033", "lorefile", "UNK014", "svcorruption", "UNK038", "UNK060", "augslot1unk2", "augslot2unk2", "augslot3unk2", "augslot4unk2", "augslot5unk2", "UNK120", "UNK121", "questitemflag", "UNK132", "clickunk5", "clickunk6", "clickunk7", "procunk1", "procunk2", "procunk3", "procunk4", "procunk6", "procunk7", "wornunk1", "wornunk2", "wornunk3", "wornunk4", "wornunk5", "wornunk6", "wornunk7", "focusunk1", "focusunk2", "focusunk3", "focusunk4", "focusunk5", "focusunk6", "focusunk7", "scrollunk1", "scrollunk2", "scrollunk3", "scrollunk4", "scrollunk5", "scrollunk6", "scrollunk7", "UNK193", "purity", "evolvinglevel", "clickname", "procname", "wornname", "focusname", "scrollname", "dsmitigation", "heroic_str", "heroic_int", "heroic_wis", "heroic_agi", "heroic_dex", "heroic_sta", "heroic_cha", "heroic_pr", "heroic_dr", "heroic_fr", "heroic_cr", "heroic_mr", "heroic_svcorrup", "healamt", "spelldmg", "clairvoyance", "backstabdmg", "created", "elitematerial", "ldonsellbackrate", "scriptfileid", "expendablearrow", "powersourcecapacity", "bardeffect", "bardeffecttype", "bardlevel2", "bardlevel", "bardunk1", "bardunk2", "bardunk3", "bardunk4", "bardunk5", "bardname", "bardunk7", "UNK214", "UNK219", "UNK220", "UNK221", "UNK222", "UNK223", "UNK224", "UNK225", "UNK226", "UNK227", "UNK228", "UNK229", "UNK230", "UNK231", "UNK232", "UNK233", "UNK234", "UNK235", "UNK236", "UNK237", "UNK238", "UNK239", "UNK240", "UNK241", "UNK242");
        echo '<div style="width:1000px">';

        echo '<h2 class="page-title"><i class="fa fa-search" style="font-size:30px"></i> Item Search <small>Begin your item editing search by specifying search criteria below...</small></h2><hr>';
        echo FormStart();
        echo FormInput('Name', "<input type='hidden' name='M' value='ItemEditor'>" . '<input type="text" placeholder="Name, ID or Lore search here..." style="width:500px" value="' . $iname . '" name="iname" class="form-control"/>', 'Item Type', SelectIType("itype",$itype));
        echo FormInput('Class', SelectIClass("iclass", $iclass));
        echo FormInput('Race', SelectRace  ("irace",   $irace));
        echo FormInput('Slot', SelectSlot  ("islot",   $islot));
        echo FormInput('Stats <br><small>(1st Filter)</small>', SelectStats("istat1", $istat1) . '' .
            "<select name='istat1comp' class='form-control'>
                    <option value='&gt;='" . ($istat1comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($istat1comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($istat1comp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($istat1comp == '<' ? " selected='1'" : "") . ">&lt</option>
                    </select>
                    <input type='text' size='4' name='istat1value' value='".$istat1value."' class='form-control' placeholder='Value here...'/>");
        echo FormInput('Stats <br><small>(2nd Filter)</small>', SelectStats("istat2", $istat2) . '' .
            "<select name='istat2comp' class='form-control'>
                    <option value='&gt;='" . ($istat2comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($istat2comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($istat2comp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($istat2comp == '<' ? " selected='1'" : "") . ">&lt</option>
                    </select>
                    <input type='text' size='4' name='istat2value' value='" . $istat2value . "' class='form-control' placeholder='Value here...'/>");
        echo FormInput('Resists', SelectResists("iresists",$iresists) . '' .
            "<select name='iresistscomp' class='form-control'>
                    <option value='&gt;='" . ($iresistscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($iresistscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($iresistscomp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($iresistscomp == '<' ? " selected='1'" : "") . ">&lt</option>
                    </select>
                    <input type='text' size='4' name='iresistsvalue' value='" . $iresistsvalue . "' class='form-control' placeholder='Value here...'/>");
        echo FormInput('Heroic Stats', SelectHeroicStats("iheroics",$iheroics) . '' .
            "<select name='iheroicscomp' class='form-control'>
                    <option value='&gt;='" . ($iheroicscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($iheroicscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($iheroicscomp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($iheroicscomp == '<' ? " selected='1'" : "") . ">&lt</option>
                    </select>
                    <input type='text' size='4' name='iheroicsvalue' value='" . $iheroicsvalue . "' class='form-control' placeholder='Value here...'/>");
        echo FormInput('Modifiers', SelectModifiers("imod",$imod) . '' .
            "<select name='imodcomp' class='form-control'>
                    <option value='&gt;='" . ($imodcomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>
                    <option value='&lt;='" . ($imodcomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>
                    <option value='='" . ($imodcomp == '='  ? " selected='1'" : "") . ">=</option>
                    <option value='&lt'" . ($imodcomp == '<' ? " selected='1'" : "") . ">&lt</option>
                    </select>
                    <input type='text' size='4' name='imodvalue' value='" . $imodvalue . "' class='form-control' placeholder='Value here...'/>");
        echo FormInput('Item Type', SelectIType("itype",$itype));
        echo FormInput('Augmentation Type', SelectAugSlot("iaugslot",$iaugslot));
        echo FormInput('With Effect', "<input type='text' value='".$ieffect."' style='width:400px' placeholder='Effect Name...' name='ieffect' class='form-control'/>");
        echo FormInput('Min Required Level', SelectLevel("iminlevel", 255,$iminlevel));
        echo FormInput('Max Required Level', SelectLevel("ireqlevel", 255,$ireqlevel));
        echo FormInput('Tradeable Items Only', "<input type='checkbox' name='inodrop'".($inodrop?" checked='1'":"")."/>");
        echo FormInput('Item Availability', " <select name='iavailability' class='form-control'>
                        <option value='0' ".($iavailability==0?" selected='1'":"").">--- Select --- </option>
                        <option value='1' ".($iavailability==1?" selected='1'":"").">Mob Dropped</option>
                        <option value='2' ".($iavailability==2?" selected='1'":"").">Merchant Sold</option>\n
                    </select>");
        echo FormInput('Max Level', SelectLevel("iavaillevel", 255, $iavaillevel));
        echo FormInput('Deity', SelectDeity("ideity",$ideity));
        $ifv = "<select name='itemfield' class='form-control'>";
        $ifv .= "<option value='0'> --- Select --- <option>";
        foreach ($itemfields as $val){
            if($val != ""){
                if($itemfield == $val){ $sel = " selected"; } else{ $sel = ""; }
                $ifv .= "<option value='" . $val . "' " . $sel . ">" . $val . "</option>";
            }
        }
        $ifv .= "</select><br> Contains <br><input type='text' name='itemfieldvalue' value='" . $itemfieldvalue  . "' class='form-control'>";
        echo FormInput('Item Field', $ifv);
        echo FormInput('', "<button type='submit' value='Search' name='isearch' class='btn btn-default green'/><i class='fa fa-search'></i> Search</button>");
        echo FormEnd();
        echo '</div>';

        /* Print Table Results */
        if(isset($QueryResult)){
            /* Scroll to Results */
            $FJS .= "
                    <script type='text/javascript'>
                    $('html, body').animate({
                        scrollTop: $('#result_scroll').offset().top
                    }, 1000);
                    </script>
                ";
            $Tableborder = 0;
            $num_rows = mysql_num_rows($QueryResult);
            $total_row_count = $num_rows;
            if($num_rows > $mysql_result_limit) { $num_rows = $mysql_result_limit; }

            if($num_rows == 0) { echo "<b>No items found...</b><br>"; }
            else {
                $OutOf = "";
                if ($total_row_count > $num_rows) { $OutOf = " (Searches are limited to 100 Max Results)"; }
                echo "<h4 id='result_scroll'><b>" . $num_rows . " " . ($num_rows == 1 ? "item" : "items") . " displayed</b>" . $OutOf . "</h4>";
                echo "<table class='table table-hover'>";
                echo "<tr>
                            <th>Icon & Color</th>
                            <th>Item Name</th>
                            <th>Item ID</th>
                            <th>Item Type</th>
                            <th>AC</th>
                            <th>HPs</th>
                            <th>Mana</th>
                            <th>Damage</th>
                            <th>Delay</th>
                            <th>Ratio</th>
                            ";
                echo "</tr>";
                $RowClass = "lr";
                for( $count = 1 ; $count <= $num_rows ; $count++ ) {


                    $TableData = "";
                    $row = mysql_fetch_array($QueryResult);

                    $colHex = dechex($row['color']);
                    $colHex = '#' . str_pad($colHex, 6 , "0");
                    if(strlen($colHex) == 9){ $colHex = str_replace('#ff', '', $colHex); $colHex = '#' . $colHex; }
                    $color_display = '<div style="background-color:	' . $colHex . ' !important;border: 1px solid #e5e5e5;width:20px;height:20px;display:inline-block"></div>';

                    $TableData .= "<tr valign='top' class='".$RowClass."' style='vertical-align:middle;text-align: center;'><td align='center'>";
                    $TableData .= "<img class='image-wrap-noborder icon-".$row["icon"]."' ". $SizeConstraints . "/>" . $color_display;
                    $TableData .= "</td><td>";
                    $TableData .= " <a href='?M=ItemEditor&Edit=" . $row["id"] . "' id='" . $row["id"] . "' target='" . $row["id"] . "' " . HoverTip("global.php?item_view=" . $row['id']) . " >" . $row["Name"] . "</a><br><small style='color:gray'>" . $row['lore'] . "</small>";
                    $TableData .= "</td>";
                    $TableData .= "<td>" . $row["id"]."</td>";
                    $TableData .= "<td>" . $dbitypes[$row["itemtype"]]."</td>";
                    $TableData .= "<td>" . number_format($row["ac"])."</td>";
                    $TableData .= "<td>" . number_format($row["hp"])."</td>";
                    $TableData .= "<td>" . number_format($row["mana"])."</td>";
                    $TableData .= "<td>" . number_format($row["damage"])."</td>";
                    $TableData .= "<td>" . number_format($row["delay"])."</td>";
                    $TableData .= "<td>" . round($row["damage"] / $row["delay"], 2)."</td>";
                    $TableData .= "</tr>";
                    print $TableData;
                }
                echo "</table></center><br><br>";
            }
        }
    }

?>