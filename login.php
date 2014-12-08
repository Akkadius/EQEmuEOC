<?php
	require_once('includes/config.php');
	require_once('includes/functions.php');
?>

<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>EOC 2.0 DB Connection</title>
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
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="cust_assets/css/aguilar_animations.css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo fadeIn">
	<a href="index.html"> 
	<img src="cust_assets/eqemu.png" alt="logo" class="logo-default" style="margin: 8px 0 0 0;"><br>
	<img src="cust_assets/eoc-fd.png" alt="logo" class="logo-default" style="width:auto;">
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content slideExpandUp">
	<!-- BEGIN LOGIN FORM -->
	<div class="login-form" action="index.html" method="post" autocomplete="off">
		<center><h3 class="form-title">Login to your MySQL Database </h3><hr><br><br>
		<h4 class="form-title"><i class="fa fa-database" style="font-size:80px;font-color:#666 !important;"></i></h4></center>
		<br> 
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">MySQL Server IP Address</label>
			<div class="input-icon">
				<i class="fa fa-cloud"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="MySQL Server IP Address" name="db_ip" id="db_ip" <?php if($_COOKIE['dbip']){ echo ' value="'. $_COOKIE['dbip'] . '"'; } ?> />
			</div>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">MySQL Server Database Name</label>
			<div class="input-icon">
				<i class="fa fa-database"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="MySQL Server Database Name" name="db_name" id="db_name" <?php if($_COOKIE['dbname']){ echo ' value="'. $_COOKIE['dbname'] . '"'; } ?> />
			</div>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Database Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="MySQL Server Database Username" name="db_user" id="db_user" <?php if($_COOKIE['dbuser']){ echo ' value="'. $_COOKIE['dbuser'] . '"'; } ?> />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Database Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="MySQL Server Database Password" name="db_pass" id="db_pass" <?php if($_COOKIE['dbpass']){ echo ' value="'. $_COOKIE['dbpass'] . '"'; }  ?> />
			</div>
		</div>
		<div class="form-actions">
			<center>
			<button type="button" class="btn btn-default" onclick="VerifyDBAuth()"> Verify Connection <i class="m-icon-swapright m-icon-black"></i>
			</button>
			</center>
		</div>
		<div id="DBAUTH"></div>
		<div id="DB_Login"></div>
		<hr>
		<div class="alert alert-block alert-info fade in">
			<button type="button" class="close" data-dismiss="alert"></button>
			<h4 class="alert-heading"><i class="fa fa-info"></i> Info!</h4><hr>
			<p>
				If this is your first time trying to connect your server to the EOC Tool Suite. You may have some first-time setting up to do.
				<hr>
				<ul>
					<li>Make sure you do NOT hava Javascript disabled or AdBlockers disabling Javascript</li>
					<li>Make sure you have cookies enabled</li>
					<li>Allow port 3306 (MySQL Port) through your Firewall</li>
					<li>Setup a MySQL User to allow connections from the EoC server over the internet (<?php echo $_SERVER['SERVER_ADDR']; ?>) </li>
				</ul>
			</p>
		</div>
	</div>
	<!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright"> 2014 &copy; Metronic. Admin Dashboard Template.
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/login.js" type="text/javascript"></script>
<script src="cust_assets/js/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
		jQuery(document).ready(function() {     
			Metronic.init(); // init metronic core components
			Layout.init(); // init current layout
			QuickSidebar.init() // init quick sidebar
			Login.init();
		});
	</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>