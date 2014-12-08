<?php

	/* Minified Page requests
		Author: Akkadius
	*/

	require_once('./includes/constants.php');
	
	$FJS .= '<script type="text/javascript" src="cust_assets/js/lazy-load.js"></script>';
	$FJS .= '<script type="text/javascript" src="modules/RaceViewer/ajax/ajax.js"></script>';

    /* Localized Styles */
	echo '<style>
			.image {   position: relative;   width: 100%; /* for IE 6 */ }
			h4 {   position: absolute;  top: 0px;  left: 30px;   width: 100%;   color: #fff; }
		</style>';
	
	/* Generate _chr.txt file for zone */
    if ($_GET['GenRaceFile']) {
        /* Build Array reference from Master_Race_Data */
        $RaceFileChr = array();
        foreach ($Master_Race_Data as $i => $val) {
            $RaceFileChr[$i] = $val;
        }
    }
	/* Floating Forms */
    if ($_GET['RaceView'] == 1) {
        echo '<h4 class="page-title"><br>Race Viewer</h4><hr>';
        echo '<table class="table" style="background-color: #fff; width:400px !important; position: fixed;right:50px;z-index:999;border-radius:15px;bottom:50px"><tr>
                <tr><td style="text-align:center"><h3><i class="fa fa-search"></i> Search for Race</h3></td></tr>
                <tr><td><input type="text" id="search" onkeyup="if(event.keyCode == 13){ RaceSearch(this.value); }" style="height:24px;" placeholder="Search Race Name or Race ID here... (Press Enter)"></td></tr>
                <tr><td style="text-align:center">Tools</td></tr>
                <tr><td style="text-align:center">
                    <h6>
                        <a class="btn btn-default" href="min.php?Mod=RaceViewer&RaceView=1&GenRaceFile=1">
                            <i class="fa fa-share-square"></i> Generate Zone Race File (zonename_chr.txt)
                        </a>
                    </h6>
                </td>
                </tr>
            </tr>
        </table>
        <br>';
        if ($_GET['GenRaceFile'] == 1) {
            echo '
            <table class="table" style="border:1px solid black;background-color: #fff; width:300px !important; position: fixed;left:50px;z-index:999;border-radius:15px;-moz-border-radius: 15px;bottom:50px">
                <tr><td>
                    <h4 style="color:#666">Generate Zone File Race List</h4>
                    <hr>
                    Start by selecting any race... When done, you save this into your EverQuest folder as zonename_chr.txt so it loads these race models...
                    <br>
                    <br>
                <textarea rows="10" style="width:100%" id="genracefiledata"></textarea>
            </td></tr></table>';
        }
        echo '<div id="Races">';
    }
    /* Ajax Race Model Search */
    if (isset($_GET['DoRaceSearch'])) {
        if ($_GET['DoRaceSearch'] != "") {
            foreach ($Master_Race_Data as $i => $val) {
                if (preg_match('/' . $_GET['DoRaceSearch'] . '/i', $races[$i]) || preg_match('/' . $_GET['DoRaceSearch'] . '/i', $i)) {
                    if (file_exists("cust_assets/races/Race (" . $i . ").png")) {
                        echo '
                            <div class="image" style="display:inline">
                                <img class="lazy btn btn-default" src="' . 'cust_assets/races/' . "Race (" . $i . ").png\"" . ' id="' . $i . '">
                                <h4 class="btn btn-default">' . $races[$i] . ' (' . $i . ')</h4>
                            </div>';
                    }
                }
            }
        } else {
            $do_regular_search = 1;
        }
    }
	/* Show all Models */
	if($_GET['RaceView'] == 1 || $do_regular_search ==  1){
		for($i = 0; $i <= 1000; $i++){
			if(file_exists("cust_assets/races/Race (" . $i . ").png")){
				if($_GET['GenRaceFile']){
					if($RaceFileChr[$i][1] == ""){ $RaceFileChr[$i][0] = ""; }
					echo '<a href="javascript:;" onclick="DoRaceFileGen(' . $i . ', \'' . $RaceFileChr[$i][0] . '\', \'' . $RaceFileChr[$i][1] . '\')">';
				}
				echo '<input type="hidden" id="tracker_' . $i . '" value="0">';
				echo '<input type="hidden" id="char_string_data_' . $i . '" value="' . $RaceFileChr[$i][0] . ',' . $RaceFileChr[$i][1] . '">';
				echo '
				<div class="image" style="display:inline;">
					<img class="lazy btn btn-default lazy" data-original="' . 'cust_assets/races/' . "Race (" . $i . ").png\"" . ' id="' . $i . '">
					<h4 class="btn btn-default" style="font-align:center;width:150px;top: 600%;opacity: .6;">' . $races[$i] . ' (' . $i  . ')</h4>
				</div>';
				if($_GET['GenRaceFile']){
                    echo '</a>';
                }
			}
		}
	}
    if ($_GET['RaceView'] == 1) {
        echo '</div>';
    }

?>