<?php
	echo '
	<!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>
        <div class="page-quick-sidebar-wrapper ">
            <div class="page-quick-sidebar">
                <div class="nav-justified">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#quick_sidebar_tab_1" data-toggle="tab">
                                Actions
                            </a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                More<i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu" id="quick_sidebar_menu_options">

                            </ul>
                        </li>
                    </ul>
                    <div  class="tab-content slimScrollDiv">
                        <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                            <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                                <div id="quick_sidebar_content"></div>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END QUICK SIDEBAR -->
        </div>';
?>