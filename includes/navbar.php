<?php 

	function img_icon($url){ return '<img src="' . $url . '" style="height:20px;width:auto">'; }

	echo '
        <div class="hor-menu hor-menu-light hidden-sm hidden-xs">
            <ul class="nav navbar-nav">
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
                <li class="classic-menu-dropdown active">
                    <a href="index.php?M=Commander"> Commander (In Dev) <span class="selected">
                    </span>
                    </a>
                </li>
                <li class="mega-menu-dropdown">
                    <a data-hover="dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
                        Tools <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- Content container to add padding -->
                            <div class="mega-menu-content">
                                <div class="row">
                                    <ul class="col-md-4 mega-menu-submenu">
                                        <li> <h3>Tools & Editors</h3> </li>
                                        <li class="divider" style="height: 1px; padding:0px !important"> </li>
                                        <li> <a href="index.php?M=ItemEditor"> <i class="fa fa-angle-right"></i> <i class="fa fa-pencil"></i> Item Search & Editor </a> </li>
                                        <li> <a href="index.php?M=SpellEditor"> <i class="fa fa-angle-right"></i> <i class="fa fa-pencil"></i> Spell Search & Editor (In Dev - Functional)</a> </li>
                                        <li> <a href="index.php?M=NPC2"> <i class="fa fa-angle-right"></i> <i class="fa fa-pencil"></i> NPC Editor </a> </li>
                                        <li> <a href="index.php?M=ZT"> <i class="fa fa-angle-right"></i>  <i class="fa fa-cloud-upload"></i> Zone Copy/Import </a> </li>
                                        <li> <a href="index.php?M=dbstr"> <i class="fa fa-angle-right"></i> <i class="fa fa-list"></i> dbstr_us.txt Editor </a> </li>
                                        <li> <a href="index.php?M=TaskEditor"> <i class="fa fa-angle-right"></i> <i class="fa fa-list"></i> Task Editor </a> </li>
                                        <li> <a href="min.php?Mod=RaceViewer&RaceView=1&GenRaceFile=1"> <i class="fa fa-angle-right"></i> <i class="fa fa-file-text"></i> _chr.txt File Generator </a> </li>
                                        <li> <a href="index.php?M=QueryServ"> <i class="fa fa-angle-right"></i> <i class="fa fa-pencil-square"></i> Logging (QueryServ) (In Dev - Not functional)</a> </li>

                                        <li> <h3>Character</h3> </li>
									    <li class="divider" style="height: 1px; padding:0px !important"> </li>
                                        <li> <a href="index.php?M=Character&character_copy">
                                            <i class="fa fa-sign-in"></i> Character Copier </a>
                                        </li>

									    <li> <h3>PEQ Editor(s)</h3> </li>
									    <li class="divider" style="height: 1px; padding:0px !important"> </li>

                                        <li class="divider" style="height: 1px; padding:0px !important"> </li>
                                        <li> <a href="min.php?Mod=PEQEditor&Rev=460"> <i class="fa fa-angle-right"></i> PEQ Editor Rev 460 <br> <img src="cust_assets/images/PEQ_Logo.png" style="height:50px;width:auto"> </a> </li>
                                    </ul>
                                    <ul class="col-md-4 mega-menu-submenu">
                                        <li> <h3>Viewers</h3> </li>
                                        <li class="divider" style="height: 1px; padding:0px !important"> </li>
                                        <li> <a href="min.php?Mod=RaceViewer&RaceView=1"> <i class="fa fa-angle-right"></i> ' . img_icon('cust_assets/icons/item_4485.png') . ' Race Viewer </a> </li>
                                        <li> <a href="min.php?Mod=IE&prevspellicon=1"> <i class="fa fa-angle-right"></i> ' . img_icon('cust_assets/icons/6.gif') . ' Spell Icon Viewer </a> </li>
                                        <li> <a href="min.php?Mod=IE&previcon=1"> <i class="fa fa-angle-right"></i> ' . img_icon('cust_assets/icons/item_513.png') . ' Item Icon Viewer </a> </li>
                                        <li> <a href="min.php?Mod=IE&prevITfile=IT63"> <i class="fa fa-angle-right"></i>  ' . img_icon('cust_assets/icons/item_1173.png') . ' Weapon Viewer </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="classic-menu-dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span id="status_display"></span> <span class="selected"> </span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- END HORIZANTAL MENU -->
        <!-- BEGIN HEADER SEARCH BOX -->
        <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->

        <!-- END HEADER SEARCH BOX -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->';

	echo '<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>';

	echo '
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->';
			
			/* Toggle Options */
			echo '

			<li class="dropdown dropdown-user" >
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<span class="username" style="height:30px;padding-top:5px">
					<i class="fa fa-wrench animated-hover" ></i> <b style="color:#fff">Options</b></span>
				<i class="fa fa-angle-down"></i>
				</a>

				<ul class="dropdown-menu">
					<li class="divider" style="height: 1px; padding:0px !important"> </li>	
					<li> <a href="javascript:;" style="cursor: default !important;">UI Skin Styles</a> </li>
					<li>  <a href="javascript:;" onclick="ToggleUIStyle(1)"> <i class="fa fa-css3"></i> Light (Default) </a> </li>
					<li>  <a href="javascript:;" onclick="ToggleUIStyle(2)"> <i class="fa fa-css3"></i> Dark </a> </li>
				</ul>
			</li>';


            /* Hosted Navbar Piece */
            if(file_exists("login.php")) {
                /* Database Connections */
                echo '<li class="dropdown dropdown-user" >
					    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					        <span class="username" style="height:30px;padding-top:5px">
					            <i class="fa fa-database" ></i>
					            <b style="color:#fff">' . $dbhost_name . '</b>
                            </span>
                            <i class="fa fa-angle-down"></i>
					    </a>
					<ul class="dropdown-menu">';

                foreach ($_COOKIE as $key => $val) {
                    if (preg_match('/dbconn/i', $key)) {
                        $conn = explode(",", $val);
                        /*
                            IP
                            DB_Name
                            DB_User
                            DB_Pass
                        */
                        # print $key . ' ' . $val . '<br>';
                        echo '
								<li>
									<a href="javascript:;" onclick="DoDBSwitch(\'' . $key . '\')"> <i class="fa fa-database"></i>' . $conn[0] . ' - ' . $conn[1] . '</a> 
								</li>';
                    }
                }

                echo '
                    <li class="divider" style="height: 1px; padding:0px !important"> </li>
                    <li>
                        <a href="javascript:;" onclick="DoDBSwitch(\'Local_EOC\')"> <i class="fa fa-database"></i>Local EoC Test</a>
                    </li>
                    <li class="divider" style="height: 1px; padding:0px !important"> </li>
                        <li>
                                <a href="login.php">
                                <i class="fa fa-database"></i> Connection page </a>
                            </li>
                        </ul>
                    </li>';
                /* END Database */
            }

			/* Menu Options */
			if($Minified != 1 && $_GET['M'] == "Commander"){ 
				echo '<li class="dropdown dropdown-quick-sidebar-toggler">
						<a href="javascript:;" class="dropdown-toggle">
						    <i class="icon-logout"></i>
						</a>
					</li>
				';
			}
			
	echo '
        <!-- END QUICK SIDEBAR TOGGLER -->
        </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <div class="clearfix">
        </div>
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
	';

?>