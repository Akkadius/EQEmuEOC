<?php

	$FJS .= '<script type="text/javascript" src="modules/TaskEditor/js/js.js"></script>';
	
	require_once('modules/TaskEditor/functions.php');
	require_once('includes/alla_functions.php');
	
	$id				= (isset($_GET['id']) ? $_GET['id'] : '');
	$xmlid			= (isset($_GET['xmlid']) ? $_GET['xmlid'] : '');
	$goalid			= (isset($_GET['goalid']) ? $_GET['goalid'] : '');
	$type			= (isset($_GET['type']) ? $_GET['type'] : '');
	$entry			= (isset($_GET['entry']) ? $_GET['entry'] : '');
	$addgoal		= (isset($_GET['addgoal']) ? $_GET['addgoal'] : 0);
	$newentry		= (isset($_GET['newentry']) ? $_GET['newentry'] : '');
	$GoalList		= (isset($_GET['GoalList']) ? $_GET['GoalList'] : '');
	$Value			= (isset($_GET['Value']) ? $_GET['Value'] : ''); 
	$count			= (isset($_GET['count']) ? $_GET['count'] : '');
	$activityid		= (isset($_GET['activityid']) ? $_GET['activityid'] : '');
	$fieldid		= (isset($_GET['fieldid']) ? $_GET['fieldid'] : '');
	$addactivity	= (isset($_GET['addactivity']) ? $_GET['addactivity'] : '');
	$divname		= (isset($_GET['divname']) ? $_GET['divname'] : '');

	$GoalMessage = '0';
	$Duplicate = 0;

	$TaskContent = "";
	
	// Builds the list portion of the Goals web page for goal lists
	if($GoalList === "taskrewardid" || $GoalList === "taskactivitygoalid")
	{
		$rewardid = $Value;

		$TaskContent .= '<div>';
		$TaskContent .= '<form class="mainForm" action="tasks.php">';
		$TaskContent .= '<table align="left" style="margin-left:25px;">';
		
		$TaskContent .= '<tr><td align="right"><b>Reward/Goal ID : </b></td><td><input size="64" name="taskrewardid" type="text" value="' . $rewardid . '"/></td></tr>';
		$Query = "";
		if ($GoalList == "taskrewardid")
		{
			$Query = "SELECT goallists.listid, tasks.id, tasks.title, tasks.rewardid FROM goallists INNER JOIN tasks ON goallists.listid = tasks.rewardid GROUP BY goallists.listid";
		}
		if ($GoalList == "taskactivitygoalid")
		{
			$Query = "SELECT goallists.listid, tasks.id, tasks.title, activities.taskid, activities.goalid, activities.goalmethod FROM goallists 
						INNER JOIN activities ON goallists.listid = activities.goalid 
						INNER JOIN tasks ON tasks.id = activities.taskid 
						WHERE activities.goalmethod = 1 
						GROUP BY goallists.listid";
		}
		$QueryResult = mysql_query($Query) or message_die('tasks.php','MYSQL_QUERY',$Query,mysql_error());
		
		//$Query2 = "SELECT listid FROM goallists GROUP BY listid";
		//$QueryResult2 = mysql_query($Query2) or message_die('tasks.php','MYSQL_QUERY',$Query2,mysql_error());
		
		if(mysql_num_rows($QueryResult) != 0)
		{
			
			$TaskContent = '
					<!-- Task List -->
					<fieldset>
						<div class="widget">	
							<div class="head"><h5 class="iCoverflow">Goal Lists</h5></div>';
			//$TaskContent .= '<a href="javascript:;" class="custBtnIconLeft" onclick="getGoalContent(0, \'' . $GoalList . '\', 1)">New Goal List</a>';
			$TaskContent .= '<div class="rowElem dualBoxes">
								<!-- <label>Select a Task :</label> -->
								<div class="floatleft">';
			if ($GoalList == "taskrewardid") {
				$TaskContent .= '<select multiple="multiple" class="form-control" onclick="getGoalContent(this.value, \'rewardid\')" title="Click to Select a Goal List">';
			}
			if ($GoalList == "taskactivitygoalid") {
				$TaskContent .= '<select multiple="multiple" class="form-control" onclick="getGoalContent(this.value, \'taskactivitygoalid\')" title="Click to Select a Goal List">';
			}
			
			while ($row=mysql_fetch_array($QueryResult))
			{
				
				$CurGoalID = $row["listid"];
				$CurTaskID = $row["id"];
				$CurTaskTitle = $row["title"];
				if ($CurGoalID == $taskid)
				{
					$TaskContent .= '<option value="' . $CurGoalID . '" selected="selected">' . $CurGoalID . ' - ' . $CurTaskTitle . ' (Task: ' . $CurTaskID . ')</option>';
				}
				else
				{
					$TaskContent .= '<option value="' . $CurGoalID . '">' . $CurGoalID . ' - ' . $CurTaskTitle . ' (Task: ' . $CurTaskID . ')</option>';
				}
			}
			
							$TaskContent .= '</select>
							
						</div>';
						
			$TaskContent .= '
						<!-- Task Details -->
						<div id="goalDiv" class="floatRight">';

			$TaskContent .= '</div>';

			$TaskContent .= '<div class="fix"></div>
							</div>
						</div>
					</fieldset>
			';	
		}
			
		$TaskContent .= '</table>';
		$TaskContent .= '<input type="button" value="Close Window" class="btn btn-default" onclick="window.close()"/>';
		$TaskContent .= '</form>';
		$TaskContent .= '</div><div class="fix"></div>';

					
		$ExtraJavaScript .= '
			<script> 

			var xmlhttp;
			function loadXMLDoc(url,cfunc)
			{
				if (window.XMLHttpRequest)
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=cfunc;
				xmlhttp.open("GET",url,true);
				xmlhttp.send();
			}

			function getGoalContent(goalid, type, addgoal)
			{
				if (!addgoal && addgoal != 0)
				{ 
					addgoal=0;
				}
				if (goalid.length==0)
				{ 
					document.getElementById("goalDiv").innerHTML="";
					return;
				}
				else
				{
					loadXMLDoc("taskbuild.php?goalid="+goalid+"&type="+type+"&addgoal="+addgoal,function()
						{
							if (xmlhttp.readyState==4 && xmlhttp.status==200)
							{
								document.getElementById("goalDiv").innerHTML=xmlhttp.responseText;
							}
						}
					);
				}
			}
			
			function changeGoal(goalid, type, entry, newentry)
			{
				if (!newentry && newentry != 0)
				{ 
					newentry=0;
				}
				if (goalid.length==0)
				{ 
					document.getElementById("goalDiv").innerHTML="";
					return;
				}
				else
				{
					loadXMLDoc("taskbuild.php?goalid="+goalid+"&type="+type+"&entry="+entry+"&newentry="+newentry,function()
						{
							if (xmlhttp.readyState==4 && xmlhttp.status==200)
							{
								document.getElementById("goalDiv").innerHTML=xmlhttp.responseText;
							}
						}
					);
				}
			}
			</script>';
			$TaskContent .= '<script type="text/javascript" src="../jquery/eqemutooltip.js"></script>';
	}
	
	echo $TaskContent;

?>