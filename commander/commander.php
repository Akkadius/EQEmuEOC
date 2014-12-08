<?php

	echo '<link rel="stylesheet" type="text/css" href="modules/commander/commander.css">';
	require_once('modules/commander/functions.php');
	
	if(isset($_GET['zone'])){
		echo Draw2DMap($_GET['zone'], 1);  
	}
	else{
		/* Main */
		echo '<center>';
		echo '<h1 class="page-title slideDown anim_entrance"> <i class="fa fa-cloud" style="font-size:20px"></i> Zone Servers </h1><hr>';
		echo '<table id="zone_servers_list" class="table table-hover table-striped table-bordered slideUp" style="width:600px"> 
			<tr>
				<td style="text-align:center">
					<span id="zone_server_count"></span> 
					Players Online <span id="total_players"></span>
				</td>
				<td>Players</td>
				<td>Port</td>
				<td>Short Name</td>
				<td>Zone ID</td>
				<td>Instance ID</td>
				</tr>
		</table>'; 
	}
	
	#::: Dark Style UI
	if($_SESSION['UIStyle'] == 2){
	
	}
	else{
		echo '<style>
			.btn-default {
				color: #333333; 
				border-color: #cccccc;
			}
			body {
				background-color: #fff !important;
			}
		</style>';
	}
	
	// echo '<script src="modules/commander/js/jquery.min.js"></script>'; 
	$FJS .= '<script src="modules/commander/js/jquery.ba-throttle-debounce.min.js"></script>';

	$FJS .= '<script src="modules/commander/js/eqmap_ws_core.js"></script>';
	$FJS .= '<script src="modules/commander/js/eqmap_ws_sidebar.js"></script>';
	$FJS .= '<script src="modules/commander/js/eqmap_ws_server_to_client.js"></script>';
	$FJS .= '<script src="modules/commander/js/eqmap_ws_client_to_server.js"></script>';


	$FJS .= '<script src="assets/admin/pages/scripts/components-jqueryui-sliders.js"></script>'; 
	$FJS .= '<script src="assets/global/plugins/jquery-knob/js/jquery.knob.js"></script>';
	$FJS .= ' <script src="cust_assets/js/context/jquery.contextmenu.js"></script>
			<link rel="stylesheet" href="cust_assets/js/context/jquery.contextmenu.css">';
			
	$FJS .= '<script type="text/javascript" src="cust_assets/js/colpick/js/colpick.js"></script>';
	$FJS .= '<link href="cust_assets/js/colpick/css/colpick.css" rel="stylesheet" type="text/css"/>';

?>