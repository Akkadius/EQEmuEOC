<?php
/*
 * Created by Akkadius: 8-8-2014
 * Header file - Contains assets for CSS and some template structure
 */
header('P3P: CP="CAO PSA OUR"'); //retarded IE fix for servers with session breaking characters in name.
?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>EOC 2.0</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<link href="cust_assets/css/global.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- Page Specific -->
<link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-notific8/jquery.notific8.min.css"/>
<link rel="stylesheet" type="text/css" href="cust_assets/css/aguilar_animations.css"/>
<link rel="stylesheet" type="text/css" href="cust_assets/css/icons.css"/>
<!-- BEGIN THEME STYLES -->
<link href="assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/> 
<link href="assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link href="cust_assets/css/font-awesome-animation.css" type="text/css" rel="stylesheet">
<link rel="shortcut icon" href="favicon.ico"/>
</head>

<?php
	if(isset($_SESSION['UIStyle']) == 2){
		echo "<style> 
			.page-content, 
			.form-control, 
			input[type=\"text\"], 
			textarea, 
			select, 
			.table-striped>tbody>tr:nth-child(odd)>td, 
			.table th,  
			.form-control div,
			.form-group div,
			.form div,
			blockquote,
			.modal,
			.mega-menu-content,
			.mega-menu-dropdown,
			.table td{ 
				background-color: #333333 !important;
				// color: gray !important;
				color: rgb(232, 228, 228) !important;
			}
			.btn-default{ padding:3px !important; }
			.navbar{
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3), 0 6px 18px rgba(0, 0, 0, 0.2) !important;
			}
			.form-horizontal.form-bordered.form-row-stripped .form-control {
				background-color: #333333 !important; 
				// color: gray !important;
				color: rgb(232, 228, 228) !important;
			}
			h1,  h2,  h3,  h4,  h5,  h6, i{
				// color: gray !important;
				color: rgb(232, 228, 228) !important;
			}
			.page-quick-sidebar-wrapper{
				box-shadow: -20px 20px 22px rgba(0, 0, 0, 0.3), 10px 6px 18px rgba(0, 0, 0, 0.2) !important;
			}
		</style>";
	}
?>

<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->

<body class="page-full-width page-header-fixed page-quick-sidebar-over-content page-sidebar-hide page-sidebar-fixed page-footer-fixed page-sidebar-menu-closed"> 
<!-- BEGIN HEADER -->	
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a href="index.php">
				<img src="cust_assets/eqemu.png" alt="logo" class="logo-default" style="height: 50px;width:auto;margin: 8px 0 0 0;"/>
				<img src="cust_assets/eoc-fd.png" alt="logo" class="logo-default" style="height: 25px;width:auto;position:absolute;top:-5px;left:120px"/>
			</a>
			<?php
				// if($Minified != 1){ echo ' <div class="menu-toggler sidebar-toggler"> </div>'; }
			?>
			<div id="ajax-modal" class="modal container fade" tabindex="-1"> </div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN HORIZANTAL MENU -->
		<!-- DOC: Remove "hor-menu-light" class to have a horizontal menu with theme background instead of white background -->
		<!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) in the responsive menu below along with sidebar menu. So the horizontal menu has 2 seperate versions -->
		

