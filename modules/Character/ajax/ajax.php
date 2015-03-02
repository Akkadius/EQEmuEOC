<?php

    require_once('includes/constants.php');
    require_once('modules/Character/functions.php');

    /* Character Copier */
    if (isset($_GET['character_copier'])) {

        /* Search for Player */
        echo '<table class="table table-striped table-hover table-condensed flip-content table-bordered" style="width:300px">
            <tr>
                <tr><td><b>Search for Player</b></td></tr>
                <tr><td><input type="text" id="character_search" onkeyup="if(event.keyCode == 13){ CharacterSearch(this.value); }" class="form-control"></td></tr>
            </tr>
        </table>';

        /* Display Source/Destination */
        echo '<h3>Copy Character Settings</h3><hr>';
        echo '<table class="table table-striped table-hover table-condensed flip-content table-bordered" style="width:400px">';
            echo '<tr>
                <td style="text-align:right">Character to be Copied</td>
                <td><input type="text" id="origchar" value="Origin Character" class="form-control" disabled></td>
            </tr>';
            echo '<tr>
                <td style="text-align:right">Destination Account</td>
                <td><input type="text" id="dest_account" value="Destination Account" class="form-control" disabled></td>
            </tr>';
            echo '<tr>
                <td style="text-align:right">New Character Name</td>
                <td><input type="text" id="new_character_name" value="" class="form-control"></td>
            </tr>';
            echo '<tr>
                <td></td>
                <td><input type="button" value="Copy Character" class="btn btn-xs green btn-default" onclick="CopyCharacter()" class="form-control"></td>
            </tr>';
        echo '</table>';
    }

    /* Character Search */
    if ($_GET['character_search']) {
        $sql = "SELECT
            character_data.account_id,
            character_data.id as character_id,
            character_data.`name`,
            character_data.class,
            character_data.race,
            account.id,
            account.`name` as account_name
            FROM
            character_data
            INNER JOIN account ON character_data.account_id = account.id
            WHERE character_data.`name` LIKE '%" . $_GET['character_search'] . "%'
        ";

        $result = mysql_query($sql);
        echo '<h3>Search Results</h3><hr>';
        echo '<table class="table table-striped table-hover table-condensed flip-content table-bordered" style="width:1100px">';

        echo '
            <thead>
                <th>Account ID</th>
                <th>Account</th>
                <th>Character ID</th>
                <th>Character</th>
                <th>Class</th>
                <th>Race</th>
                <th>Options</th>
            </thead>
        ';

        while ($row = mysql_fetch_array($result)) {
            echo
            '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['account_name'] . '</td>
                <td>' . $row['character_id'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $dbclasses_names[$row['class']] . '</td>
                <td>' . $dbiracenames[$row['race']] . '</td>
                <td>
                    <a href="javascript:;" onclick="handle_source_character(' . $row['character_id'] . ')" class="btn btn-xs blue">
                        <i class="fa fa-sign-in"></i>
                        Select as Character to be Copied
                    </a>
                    <a href="javascript:;" onclick="handle_destination_account(' . $row['account_id'] . ')" class="btn btn-xs blue">
                        <i class="fa fa-sign-in"></i>
                        Select as Destination Account
                    </a>
                </td>
            </tr>';
        }
        echo '</table>';
    }

    /* Perform entire character copy */
    if (isset($_GET['do_copy_character'])) {
        if ($_GET['source_character'] && $_GET['destination_account'] && $_GET['new_character_name']) {
            $source_character = $_GET['source_character'];
            $destination_account = $_GET['destination_account'];
            $destination_character_name = $_GET['new_character_name'];

            # $new_character_id = DuplicateMySQLRecord("character_data", "id", $source_character);
            $new_character_id = CopyCharacterRecord($source_character, $destination_account, $destination_character_name);

            /* Check if destination character already exists */
            if($new_character_id == 0){
                echo 'Destination character name already exists...';
                die;
            }

            echo 'New Character is ID: ' . $new_character_id . '<hr>';

            $total_query_data = "";
            foreach ($character_tables as $table => $val){
                # print $table . ' => ' . $val . '<br>';
                $result = mysql_query("SELECT * FROM " . $table . " WHERE `" . $val . "` = " . $source_character);

                /* Build query structure */
                $data_exists = 0;
                $original_record = mysql_fetch_assoc($result);
                $query_structure = "REPLACE INTO " . $table . " (";
                foreach ($original_record as $key => $value) {
                    $query_structure .= '' . $key . ', ';
                    $data_exists = 1;
                }
                /* If there is no data to be copied, continue iteration... */
                if($data_exists == 0){
                    continue;
                }
                $query_structure = substr($query_structure, 0, strlen($query_structure) - 2);

                /* Build entries from result */
                $query_data = "";

                /* Yes we have to query again because some of the resource was used just above */
                $result = mysql_query("SELECT * FROM " . $table . " WHERE `" . $val . "` = " . $source_character);
                $field_count = mysql_num_fields($result);
                while($row = mysql_fetch_array($result)){
                    $query_data .= "(";

                    /* Iterate through the row entries */
                    for($data_index = 0; $data_index < $field_count; $data_index++){
                        # p_var_dump($row);
                        $field_value = "";
                        $field_value = $row[$data_index];

                        if(mysql_field_name($result, $data_index) == $val){
                            $field_value = $new_character_id;
                        }
                        /* If field data is null, properly set it to null for SQL markup */
                        else if(is_null($field_value)){
                            $field_value = "NULL";
                        }
                        else{
                            $field_value = "'" . mysql_real_escape_string($field_value) . "'";
                        }

                        $query_data .= "" . $field_value . "" . ", ";
                    }
                    /* Trim comma after data */
                    $query_data = substr($query_data, 0, strlen($query_data) - 2);
                    $query_data .= "), ";
                }
                /* Trim comma after data */
                $query_data = substr($query_data, 0, strlen($query_data) - 2);

                $query_structure .= ") VALUES " . $query_data;
                # print $query_structure .= "<hr>";

                $total_query_data .= "-- " . $table . " -- \n" . $query_structure . ";\n\n";

                /* Perform query to copy data */
                $copy_result = mysql_query($query_structure);
                if(!$copy_result){
                    echo $query_structure . ' <br> ' . mysql_error() . '<br>';
                }
            }

            echo '<textarea style="width:100%;height:600px">' . $total_query_data . '</textarea>';

        }
    }

?>