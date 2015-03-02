<?php
	
	echo '<div id="JSOP"></div>'; 
	
	echo '<script type="text/javascript" src="modules/Character/ajax/ajax.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="css/main.css">';

	require_once('includes/constants.php');
	require_once('modules/Character/functions.php');

    if(isset($_GET['character_copy'])) {
        PageTitle("Character Copier");

        echo '<h3>Character Copier</h3><hr>';

        echo '
            <table class="table table-striped table-hover table-condensed flip-content table-bordered" style="width:300px">
                <tr>
                    <td>
                        <select onchange="CharacterTool(this.value)" class="form-control">
                            <option value="0">--- Select ---</option>
                            <option value="1">Copy Character</option>
                            <!-- <option value="2">Character Management (BETA)</option> -->
                        </select>
                    </td>
                </tr>
            </table>

            <div id="character_copy_options"></div>
            <div id="character_copy_result"></div>

            <div id="character_search_result"></div>
		';

    }
	
?>