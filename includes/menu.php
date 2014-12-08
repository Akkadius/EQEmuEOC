<?php
	if($Minified != 1){
		echo '
		<ul class="page-sidebar-menu hidden-sm hidden-xs page-sidebar-menu-closed" data-auto-scroll="false" data-slide-speed="200" >
			<li class="sidebar-search-wrapper">
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
				<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
				<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
				<form class="sidebar-search sidebar-search-bordered" action="extra_search.html" method="POST">
					<a href="javascript:;" class="remove">
					<i class="icon-close"></i>
					</a>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
						<button class="btn submit"><i class="icon-magnifier"></i></button>
						</span>
					</div>
				</form>
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
			</li> 
		</ul>
		';
	}
?>
<!-- BEGIN RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU --> 
		<!-- END RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
	</div>
</div>
<!-- END SIDEBAR -->