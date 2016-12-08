<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 12/5/2016
 * Time: 3:22 PM
 */

if(isset($_GET['do_translate'])){
    $translation_type = $_POST['type'];
    $translation_text = $_POST['text'];
    $translate_lines = $_POST['translate_lines'];

    $debug = 0;

    if($translation_type == "npc" || $translation_type == "auto"){
        $query = "SELECT `id`, `name`, `lastname` FROM `npc_types`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $npc_names[$row[0]][0] = ($row[1] . ($row[2] != "" ? ', ' . $row[2] : ''));
        }
    }
    if($translation_type == "faction" || $translation_type == "auto"){
        $query = "SELECT `id`, `name` FROM `faction_list`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $faction_names[$row[0]][0] = $row[1];
        }
    }
    if($translation_type == "items" || $translation_type == "auto"){
        $query = "SELECT `id`, `Name` FROM `items`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $item_names[$row[0]][0] = $row[1];
        }
    }
     if($translation_type == "items_lore" || $translation_type == "auto"){
        $query = "SELECT `id`, `Name`, `lore` FROM `items`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $item_names_lore[$row[0]][0] = $row[1] . ($row[2] != "" ? ' Lore: ' . $row[2] : '');
        }
    }
    if($translation_type == "tasks" || $translation_type == "auto"){
        $query = "SELECT `id`, `title` FROM `tasks`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $task_names[$row[0]][0] = $row[1];
        }
    }
    if($translation_type == "spells" || $translation_type == "auto"){
        $query = "SELECT `id`, `name` FROM `spells_new`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $spell_names[$row[0]][0] = $row[1];
        }
    }
    if($translation_type == "tasks_with_activities" || $translation_type == "auto"){
        $query = "SELECT `id`, `title` FROM `tasks`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $task_names[$row[0]][0] = $row[1];
        }

        $query = "SELECT * FROM `activities`";
        $result = mysql_query($query);
        while($row = mysql_fetch_array($result)){
            $description = "";
            $description .= $row['text1'];
            $description .= $row['text2'];
            $description .= $row['text3'];
            $task_activities[$row['taskid']][0] .= "\n # Activity: " . $row['activityid']  . " Step: " . $row['step'] . " Type: " . $row['activitytype'] . " Goalcount: " . $row['goalcount'] . " Desc: " . $description;
        }
        # var_dump($task_activities);
    }


    $lines = explode("\n", $translation_text);
    foreach ($lines as $key => $line){
        if(preg_match("/#/i", $line)){
            /* Skip */
        }
        else {
            $working_line = $line;
            $strip_characters = array(";", "(", ")", "[", "]");
            $working_line = str_replace($strip_characters, "", $working_line);

            $process_translate = 0;

            $split_data = array();
            /* Look for comma split */
            if(preg_match("/ => /i", $line)) {
                $split_data = explode(" ", $line);
                $process_translate = 4;
            }
            else if(preg_match("/,/i", $working_line)) {
                $split_data = explode(",", $working_line);
                $process_translate = 1;
            }
            else if(preg_match("/ /i", $working_line)) {
                $split_data = explode(" ", $working_line);
                $process_translate = 2;
            }
            else if(is_numeric(trim($line))){
                array_push($split_data, trim($line));
                $process_translate = 3;
            }

            if($process_translate){
                if($debug)
                    print "doing translate " . $process_translate . "\n";

                $replace_string = "";
                foreach ($split_data as $key => $split_id){
                    $id = trim($split_id);
                    $name_translate = "";
                    if($id == ""){ continue; }
                    if($translation_type == "npc") {
                        if ($npc_names[$id][0]) {
                            $name_translate = $npc_names[$id][0];
                        }
                    }
                    if($translation_type == "faction") {
                        if ($faction_names[$id][0]) {
                            $name_translate = $faction_names[$id][0];
                        }
                    }
                    if($translation_type == "items") {
                        if ($item_names[$id][0]) {
                            $name_translate = $item_names[$id][0];
                        }
                    }
                    if($translation_type == "items_lore") {
                        if ($item_names_lore[$id][0]) {
                            $name_translate = $item_names_lore[$id][0];
                        }
                    }
                    if($translation_type == "tasks") {
                        if ($task_names[$id][0]) {
                            $name_translate = $task_names[$id][0];
                        }
                    }
                    if($translation_type == "spells") {
                        if ($spell_names[$id][0]) {
                            $name_translate = $spell_names[$id][0];
                        }
                    }
                    if($translation_type == "tasks_with_activities") {
                        if ($task_names[$id][0]) {
                            $name_translate = $task_names[$id][0];
                            $name_translate .= $task_activities[$id][0];
                        }
                    }
                    if($name_translate) {
                        if($translate_lines == "comment_own_line"){
                            $replace_string .= "\n# (" . $id . ") " . $name_translate . "";
                        }
                        else {
                            $replace_string .= "(" . $id . ") " . $name_translate . " ";
                        }
                    }
                }
                if($replace_string != ""){
                    if($translate_lines == "comment_own_line") {
                        $line = str_replace($line, $line . $replace_string, $line);
                    }
                    else {
                        $line = str_replace($line, $line . " # " . $replace_string, $line);
                    }
                }
            }
        }

        print $line . "\n";
    }

}

?>