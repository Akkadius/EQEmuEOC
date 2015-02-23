<?php

	/*
		Author: Akkadius
	*/

	require_once('./includes/constants.php');
	
	$FJS .= '<script type="text/javascript" src="cust_assets/js/lazy-load.js"></script>';
	$FJS .= '<script type="text/javascript" src="modules/RaceViewer/ajax/ajax.js"></script>';

    /* Localized Styles */
    echo '<style>
			.image {
			   position: relative;
			   width: 100%; /* for IE 6 */
			}
			.image_label {
			   position: absolute;
			   bottom: 0px;
			   left: 0px;
			   width: 100%;
			   font-size: 12px !important;
			}
            .search_box{
                border: 1px solid #999999;
                border: 1px solid rgba(0, 0, 0, 0.2);
                border-radius: 6px;
                -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
                box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
            }
    </style>';

	/* Generate _chr.txt file for zone */
    if ($_GET['GenRaceFile']) {
        /* Build Array reference from Master_Race_Data */
        $race_file_chr_data = array();
        foreach ($Master_Race_Data as $i => $val) {
            $race_file_chr_data[$i] = $val;
        }
    }

	/* Floating Forms */
    if ($_GET['RaceView'] == 1) {
        if ($_GET['GenRaceFile'] == 1) {
            echo '
            <table class="table" style="border:1px solid black;background-color: #fff; width:300px !important; position: fixed;left:50px;z-index:999;border-radius:15px;-moz-border-radius: 15px;bottom:50px">
                <tr><td>
                    <h3 style="color:#666">Build Race List File</h3>
                    <hr>
                    Start by selecting any race... When done, you save this into your EverQuest folder as zonename_chr.txt so it loads these race models...
                    <br>
                    <br>
                <textarea rows="10" style="width:100%" id="genracefiledata"></textarea>
            </td></tr></table>';
        }
        else{
            echo '<table class="table search_box" style="background-color: #fff; width:400px !important; position: fixed;right:50px;z-index:999;border-radius:15px;bottom:50px"><tr>
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
            </table>';
        }

    }

    echo '<div id="Races" style="text-align:center">';
    echo '<h4 class="page-title"><br>Race Viewer</h4><hr>';

	/* Show all Models */
	if($_GET['RaceView'] == 1){
		for($i = 0; $i <= 1000; $i++){
            if(file_exists("cust_assets/races/" . $i . ".jpg")) {
                $race_img = "cust_assets/races/" . $i . ".jpg";
            }
            else if (file_exists("cust_assets/races/Race (" . $i . ").png")) {
                $race_img = "cust_assets/races/Race (" . $i . ").png";
            }
            else{
                continue;
            }

            if($_GET['GenRaceFile']){
                if($race_file_chr_data[$i][1] == ""){
                    $race_file_chr_data[$i][0] = "";
                }
                echo '<a href="javascript:;" onclick="DoRaceFileGen(' . $i . ', \'' . $race_file_chr_data[$i][0] . '\', \'' . $race_file_chr_data[$i][1] . '\')">';
            }
            echo '<input type="hidden" id="tracker_' . $i . '" value="0">';
            echo '<input type="hidden" id="char_string_data_' . $i . '" value="' . $race_file_chr_data[$i][0] . ',' . $race_file_chr_data[$i][1] . '">';
            echo '  <span class="image-wrap">
                        <img class="lazy" data-original="' . $race_img . '" id="' . $i . '" style="height:180px;width:auto;">
                        <span class="image_label badge badge-danger">' . $races[$i] . ' (' . $i  . ')</span>
                    </span>
            ';

            if($_GET['GenRaceFile']) {
                echo '</a>';
            }

		}
	}
    echo '</div>';

?>