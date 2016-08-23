<?php
	
	echo '<!-- BEGIN FOOTER -->
		<div class="page-footer">
			<div class="page-footer-inner">
				 2014 &copy; Metronic by keenthemes.
			</div>
			<div class="page-footer-tools">
				<span class="go-top">
				<i class="fa fa-angle-up"></i>
				</span>
			</div>
		</div>
		<!-- END FOOTER -->
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
		<script src="assets/global/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script> 
		<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script> 
		<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
		<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
		<!-- END PAGE LEVEL PLUGINS -->
		
		<!-- END CORE PLUGINS -->
		<script src="assets/global/plugins/jquery-notific8/jquery.notific8.min.js"></script> 
		<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
		<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
		<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
		
		<script>
			in_ajax_div = 0;
			jQuery(document).ready(function() {    
				Metronic.init(); // init metronic core components
				Layout.init(); // init current layout
				QuickSidebar.init() // init quick sidebar
			});
			function getAllProperties(obj) {
			  var properties = "";
			  for (property in obj) {
				properties += "\n" + property;
			  }
			 console.log("properties " + properties);
			}
			/* Custom Hover Tips */
			$( "[hovertip-url]" ).mouseover(function(e) {
				url = $(this).attr("hovertip-url");  
				ajax_showTooltip(e, url, $(this), true, 0, 0);
			});
			$( "[hovertip-url]" ).mouseout(function(e) {
				if($(this).attr("hovertip-hidemouseout") == 1){ ajax_hideTooltip(); } 
			});
			/* Custom Hover Tips with Close Button */
			$( "[hovertip-close-url]" ).mouseover(function(e) {
				url = $(this).attr("hovertip-close-url");   
				ajax_showTooltip(e, url, $(this), true, 0, 0);
				$( ".ajax_tooltip_content" ).bind( "mouseenter", function() { in_ajax_div = 1; $("body").css("overflow", "hidden"); });
				$( ".ajax_tooltip_content" ).bind( "mouseleave", function() { in_ajax_div = 0; $("body").css("overflow", "visible"); });
			});
			$( "[hovertip-url]" ).mouseout(function(e) {
				/* Unbind to events that we bound to, because we will get too many bindings after enough calls */
				$( ".ajax_tooltip_content" ).unbind( "mouseenter");
				$( ".ajax_tooltip_content" ).unbind( "mouseleave");
			});
			
		  </script>
		<!-- Begin Custom Global -->
		<script src="cust_assets/js/eoc_global.js" type="text/javascript"></script>
		
		<script src="cust_assets/Tooltip/js/ajax-tooltip.js" type="text/javascript"></script>
		<link href="cust_assets/Tooltip/css/ajax-tooltip.css" rel="stylesheet" type="text/css"/>';

		/* Footer Javascript included from a script... */
		echo $FJS;

		echo '
		<!-- END JAVASCRIPTS -->
		</body>
		<!-- END BODY -->
		</html>';
		
?>