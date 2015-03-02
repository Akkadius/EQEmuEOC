<?php

    function CopyCharacterRecord($source_character_id, $destination_account_id, $destination_character_name){
        $table = "character_data";
        $id_copied_from = $source_character_id;
        $id_field = "id";

        /* Check if destination character already exists */
        $sql = "SELECT * FROM `character_data` WHERE `name` = '" . mysql_real_escape_string($destination_character_name) . "'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            return 0;
        }

        $result = mysql_query("SELECT * FROM {$table} WHERE {$id_field} = {$id_copied_from}");
        $original_record = mysql_fetch_assoc($result);
        /* insert the new record and get the new auto_increment id */
        mysql_query("INSERT INTO {$table} (`{$id_field}`) VALUES (NULL)");
        $new_id = mysql_insert_id();
        echo mysql_error();

        /* generate the query to update the new record with the previous values */
        $query = "UPDATE {$table} SET ";
        foreach ($original_record as $key => $value) {
            if ($key != $id_field) {
                if($key == "name"){
                    $query .= '`' . $key . '` = "' . str_replace('"', '\"', mysql_real_escape_string($destination_character_name)) . '", ';
                }
                else if($key == "account_id"){
                    $query .= '`' . $key . '` = "' . str_replace('"', '\"', $destination_account_id . '", ';
                }
                else {
                    $query .= '`' . $key . '` = "' . str_replace('"', '\"', mysql_real_escape_string($value)) . '", ';
                }
            }
        }
        $query = substr($query, 0, strlen($query) - 2); // lop off the extra trailing comma
        $query .= " WHERE {$id_field} = {$new_id}";
        mysql_query($query);
        # echo '<pre>' . $query . '</pre>';
        echo mysql_error();

        return $new_id;
    }

    $character_tables = array(
        "quest_globals" => "charid",
        "character_activities" => "charid",
        "character_enabledtasks" => "charid",
        "completed_tasks" => "charid",
        "friends" => "charid",
        "mail" => "charid",
        "timers" => "char_id",
        "inventory" => "charid",
        "char_recipe_list" => "charid",
        "adventure_stats" => "player_id",
        "zone_flags" => "charID",
        "titles" => "char_id",
        "player_titlesets" => "char_id",
        "keyring" => "char_id",
        "faction_values" => "char_id",
        "instance_list_player" => "charid",
        "character_skills" => "id",
        "character_languages" => "id",
        "character_bind" => "id",
        "character_alternate_abilities" => "id",
        "character_currency" => "id",
        "character_spells" => "id",
        "character_memmed_spells" => "id",
        "character_disciplines" => "id",
        "character_material" => "id",
        "character_tribute" => "id",
        "character_bandolier" => "id",
        "character_potionbelt" => "id",
        "character_inspect_messages" => "id",
        "character_leadership_abilities" => "id",
        "character_alt_currency" => "char_id",
        "guild_members" => "char_id"
    );


?>