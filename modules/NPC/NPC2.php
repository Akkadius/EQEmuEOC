<?php
	/*
		Author: Akkadius
	*/

	require_once('./includes/constants.php');
	require_once('./includes/functions.php');
	require_once('functions.php');

	$FJS .= '<script type="text/javascript" src="modules/NPC/ajax/ajax.js"></script>';
	$FJS .= '<script type="text/javascript" src="cust_assets/js/double_scroll.js"></script>';
	$FJS .= '<script type="text/javascript" src="cust_assets/js/colpick/js/colpick.js"></script>';
	$FJS .= '<link href="cust_assets/js/colpick/css/colpick.css" rel="stylesheet" type="text/css"/>';
	$FJS .= ' <script src="cust_assets/js/context/jquery.contextmenu.js"></script>
			<link rel="stylesheet" href="cust_assets/js/context/jquery.contextmenu.css">';
	$FJS .= '<script src="cust_assets/js/jquery_context/jquery.contextmenu.js"></script>
    		 <link rel="stylesheet" href="cust_assets/js/jquery_context/jquery.contextmenu.css">';
	$FJS .= '<link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css">';
	echo '<style>
			/* Ensure that the demo table scrolls */
			th, td { white-space: nowrap; }
			div.dataTables_wrapper { margin: 0 auto; width:100%; }
			div.ColVis { float: left; }
			.dataTable{ margin-top: 0px; }
			.DTFC_Cloned{ background-color: white; }
			.DTFC_LeftBodyWrapper{ padding-top:2px;  }
			.DTFC_LeftBodyLiner{ overflow-x: hidden !important;  }
			table td{ }
			#npc_head_table input, #npc_head_table select, #npc_head_table option, .DTFC_Cloned input {
				padding: 0px !important;
				height:20px !important;
			}
			table.dataTable thead th, table.dataTable thead td {
				border-bottom: 0px solid #111 !important;
			}
			.DTFC_LeftBodyWrapper, .DTFC_LeftHeadWrapper{
				-webkit-box-shadow: 3px 1px 4px rgba(0,0,0,0.065);
				-moz-box-shadow: 3px 1px 4px rgba(0,0,0,0.065);
				box-shadow: 3px 1px 4px rgba(0,0,0,0.065);
			}
			html, body {
				overflow: hidden;
			}
		</style>';

	echo '<link href="cust_assets/js/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>';
	echo '<link href="cust_assets/js/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css" rel="stylesheet" type="text/css"/>';

	PageTitle('NPC/Zone Editor');

	echo '';
	echo '<table>
		<tr>
		<td>
			<table class="table table-striped table-hover table-condensed flip-content table-bordered" style="width:200px !important">
				<tr><td style="text-align:right">Zone</td>			<td>' . GetZoneListSelect($_GET['zone']) . '</td></tr>
				<tr><td style="text-align:right">Instance <br>Version</td>	<td><input class="form-control span2" type="text" value="' . ($_GET['version'] > 0 ? $_GET['version'] : 0) . '" id="zinstid" title="The Instance Version you wish to see"></td></tr>
				<tr><td style="text-align:right">NPC<br>Name</td>		<td><input type="text" value="' . $_GET['npc_filter'] . '" class="form-control" id="npcname" title="Name of the NPC to Search For" onkeyup="if(event.keyCode == 13){ ShowZone();}"></td></tr>
				<tr><td style="text-align:right"></td>				<td><button type="button" value="Search!" class="btn btn-default green btn-xs" onclick="ShowZone()" title="Click this when you are ready to execute"><i class="fa fa-search"></i> Search</button> <button type="button" value="Mass Field Editor" class="btn btn-default blue btn-xs" onclick="DoModal(\'ajax.php?M=NPC&MassEdit\')" title="Mass Field Editor"><i class="fa fa-edit"></i> Mass Field Editor</button></td></tr>
			</table>
		</td>
		<td valign="top" style="padding-left:20px">
			<div id="top_right_pane" style="display:inline; width:100%; height:100%"></div>
		</td>
		</tr>
	</table>';

	echo '<div id="shownpczone"></div>';

	if(isset($_GET['zone'])){
		$FJS .= '<script type="text/javascript">ShowZoneFromURL(\'' . $_GET['zone'] . '\', ' . $_GET['version'] . ', \'' . $_GET['npc_filter'] . '\', "")</script>';
	}

	/* Hook Right click events */
	// $FJS .= '<script type="text/javascript">
	// 	$(document).ready(function(){
	// 		  document.oncontextmenu = function() {return false;};
	// 		  $(document).mousedown(function(e){
	// 			if( e.button == 2 ) {
	//
	// 		  		return false;
	// 			}
	// 			return true;
	// 		  });
	// 		});
	// </script>';


?>