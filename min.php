<?php
    /* Dispatching Page for minimal requests */

	require_once('includes/config.php');
	require_once('includes/functions.php');
	require_once('includes/header.php');
	require_once('includes/navbar.php');
	require_once('includes/menu.php');

	require_once('includes/content_start.php');

	if($_GET['Mod'] != "RaceViewer" && isset($_GET['IconSearch']) && $_GET['prevITfile'] != 1){
		require_once('./includes/functions.php');
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

	if($_GET['Mod'] == "IE"){ require_once('modules/ItemEditor/min.php'); } 
	if($_GET['Mod'] == "RaceViewer"){ include('modules/RaceViewer/min.php'); } 
	if($_GET['Mod'] == "NPC"){ include('modules/NPC/NPC.php'); }
	if($_GET['M'] == "TaskEditor"){ include('modules/TaskEditor/min.php'); }
	if($_GET['Mod'] == "PEQEditor"){
		echo '
		<style>
		    html, body { height:100%; }
		    .page-footer, page-footer-inner{ display: none;  }
		    .page-content-wrapper .page-content {
                margin-left: 0px;
                margin-top: 0px;
                min-height: 0px;
                padding: 0px !important;
            }
            .page-container{
                height: 100% !important;
            }
		 </style>';
		if($_GET['Rev'] == "460"){ echo '<iframe src="modules/PEQEditor460/index.php" width="100%" style="position:absolute;max-height:100%; height:100% !important; width:100%;" scrolling="auto" id="peqframe"></iframe><br><br><br><br>'; }
		else if($_GET['Rev'] == "501"){ echo '<iframe src="modules/PEQEditor501/index.php" width="100%" style="position:absolute;max-height:100%; height:100% !important; width:100%;" scrolling="auto" id="peqframe"></iframe><br><br><br><br>'; }
		else{ echo '<iframe src="modules/PEQEditor/index.php" width="100%" style="max-height:100%; height:100%; width:100%;" scrolling="auto" id="peqframe"></iframe>'; }
		$FJS .= '<script type="text/javascript">
			setInterval(DOPEQURLUPDATE, 1000);
			function DOPEQURLUPDATE(){ 
				document.getElementById("status_display").innerHTML = "<a href=\'" + document.getElementById("peqframe").src + "\'>Direct Access URL " + document.getElementById("peqframe").src + "</a>";
			}
		</script>';
	}

	require_once('includes/content_end.php');
	require_once('includes/quick_sidebar.php'); 
	require_once('includes/footer.php');
?>
