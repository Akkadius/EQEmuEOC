<?php
/* Handles Global Common Requests */

require_once('includes/config.php');
require_once('includes/functions.php');

/*
    Displays Modal Spell View
*/

/* Handles Request for toggling UI Style */
if (isset($_GET['ToggleUIStyle'])) {
    if ($_GET['ToggleUIStyle'] == 1) {
        $_SESSION['UIStyle'] = 1;
    }
    if ($_GET['ToggleUIStyle'] == 2) {
        $_SESSION['UIStyle'] = 2;
    }
    exit;
}
/*
    Handles Request for viewing spell data in a Modal
*/
if (isset($_GET['spellview'])) {
    require_once('includes/spell.inc.php');
    require_once('includes/alla_functions.php');
    if ($_GET['spellview'] > 0) {
        $QueryResult = mysql_query("SELECT * FROM spells_new WHERE `id` = " . $_GET['spellview'] . ";");
        while ($row = mysql_fetch_array($QueryResult)) {
            $Content .= BuildSpellInfo($row, 1);
        }
    } else {
        $Content .= 'There is currently no spell data...<br>';
    }
    echo Modal('View Spell Data', '<div class="alert alert-info">' . $Content . '</div>', '');
}
/*
    Displays Item View tooltip
*/
if (isset($_GET['item_view'])) {
    require_once('includes/constants.php');
    require_once('includes/alla_functions.php');
    require_once('modules/ItemEditor/constants_ie.php');
    // echo '<style></style>';
    $QueryResult = mysql_query("SELECT * FROM items WHERE `id` = " . $_GET['item_view'] . ";");
    while ($row = mysql_fetch_array($QueryResult)) {
        // $Content .= BuildSpellInfo($row, 1);
        echo BuildItemStats($row, 1, 'item_view');
    }
}
/*
    Displays Spell View tooltip
*/
if (isset($_GET['spell_view'])) {
    require_once('includes/spell.inc.php');
    require_once('includes/constants.php');
    require_once('includes/alla_functions.php');
    require_once('modules/ItemEditor/constants_ie.php');
    $QueryResult = mysql_query("SELECT * FROM spells_new WHERE `id` = " . $_GET['spell_view'] . ";");
    while ($row = mysql_fetch_array($QueryResult)) {
        $Content .= BuildSpellInfo($row, 1, 'item_view');
        echo $Content;
    }
    if (!$Content) {
        echo 'There is no data available...';
    }
}
/*
    Displays Inline Spell View Data for inline editing as well...
    Requested via tooltip...
*/
if (isset($_GET['spell_view_data_quick'])) {
    require_once('includes/spell.inc.php');
    require_once('includes/constants.php');
    require_once('includes/alla_functions.php');
    require_once('modules/ItemEditor/constants_ie.php');
    require_once('modules/SpellEditor/functions.php');
    $result  = mysql_query("SELECT * FROM spells_new WHERE `id` = " . $_GET['spell_view_data_quick'] . ";");
    $columns = mysql_num_fields($result);
    echo '<div style="width:600px;height:700px;overflow-y;scroll">';
    echo '<h4 class="page-title"><i class="fa fa-database"></i> Spell Inline Editor</h4><hR>';
    echo '<table class="table table-hover">';
    echo '<tr>
			<th style="width:200px">Field</th>
			<th>Data</th>
		</tr>';
    for ($i = 0; $i < $columns; $i++) {
        $FieldName = mysql_field_name($result, $i);
        $FieldData = mysql_result($result, 0, $i);
        echo '<tr><td><b>' . $FieldName . '</b><br><small>' . $spells_new_fields[$FieldName][0] . '</small></td><td> ' . SpellFieldInput($_GET['spell_view_data_quick'], $FieldName, $FieldData) . '</td></tr>';
    }
    echo '</table>';
    echo '</div>';
}

?>
