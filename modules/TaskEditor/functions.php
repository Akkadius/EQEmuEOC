<?php

	function GetTaskFormString($taskid, $activityid, $id, $name, $value, $size, $align, $tr)
	{
		$TaskContent = '';
		if ($tr) {
			$TaskContent .= '<tr>';
		}
		$TaskContent .= '<td align="' . $align . '" nowrap="nowrap"><b>' . $name . '</b></td>
							<td><input class="form-control" autocomplete="off" size="' . $size . '" id="' . $id . '" name="' . $id . '" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $value . '"/></td>';
		if ($tr) {
			$TaskContent .= '</tr>';
		}
		return $TaskContent;
	}

	function GetZoneSelect($ZoneID, $FieldName, $taskid, $activityid)
	{
		$ZoneSelect .= '<div class="customSelect">';
		$ZoneSelect .= '<select class="form-control" id="' . $FieldName . '" name="' . $FieldName . '" autocomplete="off" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)">';

		if ($ZoneID == 0) {
			$ZoneSelect .= '<option value="0" selected="selected">0 - (any) Any Zone</option>';
		} else {
			$ZoneSelect .= '<option value="0">0 - (any) Any Zone</option>';
		}

		$Query = "SELECT zoneidnumber, short_name, long_name FROM zone ORDER BY zoneidnumber";
		$QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
		while ($row = mysql_fetch_array($QueryResult)) {
			$CurZoneID    = $row["zoneidnumber"];
			$CurShortName = $row["short_name"];
			$CurLongName  = $row["long_name"];
			if ($CurZoneID == $ZoneID) {
				$ZoneSelect .= '<option value="' . $CurZoneID . '" selected="selected">' . $CurZoneID . ' - (' . $CurShortName . ') ' . $CurLongName . '</option>';
			} else {
				$ZoneSelect .= '<option value="' . $CurZoneID . '">' . $CurZoneID . ' - (' . $CurShortName . ') ' . $CurLongName . '</option>';
			}
		}

		$ZoneSelect .= '</select>';
		$ZoneSelect .= '</div><div class="fix"></div>';
		return $ZoneSelect;
	}

	function GetActivityTypeSelect($selected, $taskid, $activityid)
	{
		$TaskContent .= '<div class="customSelect">';
		$TaskContent .= '<select class="form-control" id="taskactivityactivitytype" name="taskactivityactivitytype" autocomplete="off" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)">';

		// Task Activity Type Array
		$ActivityTypeArray = array(
			"--- Select Activity Type ---",
			"Deliver",
			"Kill",
			"Loot",
			"Speak With",
			"Explore",
			"TradeSkill",
			"Fish",
			"Forage",
			"Use Type 1",
			"Use Type 2",
			"Touch",
			"Give Cash",
			"Custom"
		);
		for ($i = 0; $i < 14; $i++) {
			$CurType = $i;
			if ($CurType == 12) {
				$CurType = 100;
			}
			if ($CurType == 13) {
				$CurType = 255;
			}
			if ($CurType == $selected) {
				$TaskContent .= '<option value="' . $CurType . '" selected="selected">' . $CurType . ' - ' . $ActivityTypeArray[$i] . '</option>';
			} else {
				$TaskContent .= '<option value="' . $CurType . '">' . $CurType . ' - ' . $ActivityTypeArray[$i] . '</option>';
			}
		}

		$TaskContent .= '</select>';
		$TaskContent .= '</div><div class="fix"></div>';

		return $TaskContent;
	}

	function GetActivityStepDesc($ActivityType, $GoalCount, $description_override, $Text2, $StepDescription)
	{
		if (!$StepDescription) {
			switch ($ActivityType) {
				case 1:
					// Deliver
					$StepDescription = "Deliver " . $GoalCount . " " . $Text2 . " to " . $description_override;
					break;
				case 2:
					// Kill
					$StepDescription = "Kill " . $GoalCount . " " . $description_override;
					break;
				case 3:
					// Loot
					$StepDescription = "Loot " . $GoalCount . " " . $Text2 . " from " . $description_override;
					break;
				case 4:
					// SpeakWith
					$StepDescription = "Speak with " . $description_override;
					break;
				case 5:
					// Explore
					$StepDescription = "Explore " . $description_override;
					break;
				case 6:
					// TradeSkill
					$StepDescription = "Create " . $GoalCount . " " . $description_override;
					break;
				case 7:
					// Fish
					$StepDescription = "Fish " . $GoalCount . " " . $description_override;
					break;
				case 8:
					// Forage
					$StepDescription = "Forage " . $GoalCount . " " . $description_override;
					break;
				case 9:
					// ActivityUse1
					$StepDescription = "Use " . $GoalCount . " " . $description_override;
					break;
				case 10:
					// ActivityUse2
					$StepDescription = "Use " . $GoalCount . " " . $description_override;
					break;
				case 11:
					// ActivityTouch
					$StepDescription = "Touch " . $description_override;
					break;
				case 100:
					// ActivityGiveCash
					$StepDescription = "Give " . $GoalCount . " " . $description_override . " to " . $Text2;
					break;
				case 255:
					// Custom Task Activity Type
					$StepDescription = "None";
					break;
				default:
					// Custom Task Activity Type
					$StepDescription = "None";
					break;
			}
		}

		return $StepDescription;
	}

	function GetActivityForm($taskid, $activityid)
	{
		global $tbtasktask_activities;

		$TaskID = $taskid;

		$TaskContent .= '<form id="activityform" class="customForm" action="tasks.php" method="post">';

		$TaskContent .= '<table style="margin-left:0px;">';

		$Query = "SELECT * FROM task_activities WHERE taskid='" . $taskid . "' AND activityid='" . $activityid . "' LIMIT 1";
		$QueryResult = mysql_query($Query) or message_die('task.php', 'MYSQL_QUERY', $Query, mysql_error());

		while ($row = mysql_fetch_array($QueryResult)) {
			$ActivityID      = $row["activityid"];
			$Step            = $row["step"];
			$ActivityType    = $row["activitytype"];
			$description_override           = $row["description_override"];
			$Text2           = $row["text2"];
			$StepDescription = $row["text3"];
			$GoalID          = $row["goalid"];
			$GoalMethod      = $row["goalmethod"];
			$GoalCount       = $row["goalcount"];
			$DeliverToNPC    = $row["delivertonpc"];
			$ZoneID          = $row["zoneid"];
			$Optional        = $row["optional"];

			$ItemID     = 0;
			$NPCID      = 0;
			$SingleGoal = 0;
			$ItemName   = "";
			$NPCName    = "";
			$GoalType   = "";

			if ($GoalMethod == 0) {
				// Single Goal == 0
				// Goal List == 1
				// Perl Controlled Goals == 2
				$SingleGoal = $GoalID;
			}

			if (!$StepDescription) {

				switch ($ActivityType) {
					case 1:
						// Deliver
						$ItemID   = $SingleGoal;
						$ItemName = $Text2;
						$NPCID    = $DeliverToNPC;
						$NPCName  = $description_override;
						$GoalType = "ItemID";
						break;
					case 2:
						// Kill
						$NPCID    = $SingleGoal;
						$NPCName  = $description_override;
						$GoalType = "NPCID";
						break;
					case 3:
						// Loot
						$ItemID   = $SingleGoal;
						$ItemName = $Text2;
						$GoalType = "ItemID";
						break;
					case 4:
						// SpeakWith
						$NPCID    = $SingleGoal;
						$NPCName  = $description_override;
						$GoalType = "NPCID";
						break;
					case 5:
						// Explore
						break;
					case 6:
						// TradeSkill
						$ItemID   = $SingleGoal;
						$ItemName = $description_override;
						$GoalType = "ItemID";
						break;
					case 7:
						// Fish
						$ItemID   = $SingleGoal;
						$ItemName = $description_override;
						$GoalType = "ItemID";
						break;
					case 8:
						// Forage
						$ItemID   = $SingleGoal;
						$ItemName = $description_override;
						$GoalType = "ItemID";
						break;
					case 9:
						// ActivityUse1
						break;
					case 10:
						// ActivityUse2
						break;
					case 11:
						// ActivityTouch
						break;
					case 100:
						// ActivityGiveCash
						$NPCID    = $DeliverToNPC;
						$NPCName  = $Text2;
						$GoalType = "NPCID";
						break;
					case 255:
						// Custom
						break;
					default:
						break;
				}
			}

			$TaskContent .= '<tr><td colspan="2" align="center"><div id="activityDeleteDiv"><br><a href="javascript:;" class="btn red btn-xs" onclick="deleteTaskActivity(' . $taskid . ', ' . $activityid . ', \'delete\', \'activityDeleteDiv\')"> <i class="fa fa-trash-o"></i> Delete Activity</a><br><br></div></td></tr>';

			// Activity ID
			//$TaskContent .= GetTaskFormString($taskid, $activityid, "taskactivityactivityid", "Activity ID", $ActivityID, 1, "right", 1);
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Activity ID</b></td>';
			// Begin Activity ID and Step Table
			$TaskContent .= '<td><table><tr>';
			$TaskContent .= '<td><input class="form-control" autocomplete="off" size="1" id="taskactivityactivityid" name="taskactivityactivityid" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $ActivityID . '"/></td>';

			// Step Number
			$TaskContent .= GetTaskFormString($taskid, $activityid, "taskactivitystep", "Step", $Step, 1, "right", 0);

			// End Activity ID and Step Table
			$TaskContent .= '</tr></table></td></tr>';

			// Text Field Descriptions
			$TaskContent .= GetTaskFormString($taskid, $activityid, "taskactivitydescription_override", "Description", $description_override, 64, "right", 1);

			// Activity Type
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Activity Type</b></td><td>';

			// Activity Type Select Box
			$TaskContent .= GetActivityTypeSelect($ActivityType, $taskid, $activityid);

			$TaskContent .= '</td></tr>';

			$StepDescription = (
				$description_override ?
				$description_override :
				GetActivityStepDesc($ActivityType, $GoalCount, $description_override, $Text2, $StepDescription)
			);

			// Description
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Description: </b></td><td>' . $StepDescription . '</td></tr>';

			// Goal ID
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Goal ID</b></td>
				<td nowrap="nowrap"><input class="form-control" autocomplete="off" size="11" id="taskactivitygoalid" name="taskactivitygoalid" type="text" value="' . $GoalID . '" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)"/>';
			$TaskContent .= '<a href="javascript:;" class="btn green btn-xs" onclick="addGoal(' . $taskid . ', ' . $activityid . ', \'taskactivitygoalid\')"><i class="fa fa-plus"></i> New</a> ';
			$TaskContent .= '<a href="javascript:;" class="btn blue btn-xs" onclick="getGoalList(\'taskactivitygoalid\', 1200, 800)"><i class="fa fa-edit"></i> Edit</a> ';

			$ProximityExists = 0;
			$Query2          = "SELECT exploreid FROM proximities WHERE exploreid = '" . $GoalID . "'";
			$QueryResult2 = mysql_query($Query2) or message_die('tasks.php', 'MYSQL_QUERY', $Query2, mysql_error());
			if (mysql_num_rows($QueryResult2) != 0) {
				$ProximityExists = 1;
			}

			if ($GoalID == 0 || $GoalMethod != 0 || $ActivityType != 5 || $ProximityExists == 0) {
				$TaskContent .= '<a href="javascript:;" class="btn green btn-xs" onclick="getProximityContent(' . $taskid . ', ' . $activityid . ', 1)"><i class="fa fa-database"></i> New Proximity</a>';
			}

			$TaskContent .= '</td></tr>';

			// Goal Count
			$TaskContent .= GetTaskFormString($taskid, $activityid, "taskactivitygoalcount", "Goal Count", $GoalCount, 11, "right", 1);

			// Goal Method
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Goal Method</b></td><td>
					<div class="customSelect">
						<select class="form-control" id="taskactivitygoalmethod" name="taskactivitygoalmethod" autocomplete="off" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)">';

			$GoalMethodArray = array(
				"Single Goal",
				"Goal List",
				"Perl"
			);
			for ($i = 0; $i < 3; $i++) {
				if ($i == $GoalMethod) {
					$TaskContent .= '<option value="' . $i . '" selected="selected">' . $i . ' - ' . $GoalMethodArray[$i] . '</option>';
				} else {
					$TaskContent .= '<option value="' . $i . '">' . $i . ' - ' . $GoalMethodArray[$i] . '</option>';
				}
			}
			$TaskContent .= '</select></div><div class="fix"></div></td></tr>';

			// Deliver to NPC
			$TaskContent .= GetTaskFormString($taskid, $activityid, "taskactivitydelivertonpc", "Deliver To", $DeliverToNPC, 11, "right", 1);

			// Zone
			$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Zone</b></td><td>';
			// Zone List Select Box
			$TaskContent .= GetZoneSelect($ZoneID, "taskactivityzoneid", $taskid, $activityid);
			$TaskContent .= '</td></tr>';

			// Optional Step
			$CheckBoxChecked = '';
			if ($Optional != 0) {
				$CheckBoxChecked = 'checked="checked" ';
			}
			$TaskContent .= '<tr><td align="right"><b>Optional</b></td>
					<td><input autocomplete="off" type="checkbox" id="taskactivityoptional" name="taskactivityoptional" class="customCheckbox" ' . $CheckBoxChecked . ' onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)"/>
					<label for="taskactivityoptional" class="customCheckbox-label"></label></td></tr>';
		}

		$TaskContent .= '<tr><td colspan="2">';
		$TaskContent .= '<div id="proximityDiv">';

		if ($GoalID > 0 && $GoalMethod == 0 && $ActivityType == 5) {
			$TaskContent .= GetProximityContent($taskid, $activityid, 0);
		}

		$TaskContent .= '</div>';
		$TaskContent .= '</td></tr>';

		$TaskContent .= '</table>';

		$TaskContent .= '</form>';



		return $TaskContent;
	}

	function GetProximityContent($taskid, $activityid, $newentry)
	{
		if ($newentry == 1) {
			$NextID = 1;
			$Query  = "SELECT exploreid FROM proximities ORDER BY exploreid DESC LIMIT 1";
			$QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());
			if (mysql_num_rows($QueryResult) != 0) {
				$row    = mysql_fetch_array($QueryResult);
				$NextID = $row["exploreid"] + 1;
			}

			$ZoneID = 0;
			$Query  = "SELECT zoneid FROM task_activities WHERE activityid = " . $activityid . " AND taskid = " . $taskid . "  LIMIT 1";
			$QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());
			if (mysql_num_rows($QueryResult) != 0) {
				$row    = mysql_fetch_array($QueryResult);
				$ZoneID = $row["zoneid"];
			}

			$Query = "INSERT INTO proximities ( zoneid, exploreid, minx, maxx, miny, maxy, minz, maxz ) VALUES ( " . $ZoneID . ", " . $NextID . ", 0, 0, 0, 0, 0, 0 )";
			$QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());

			$Query = "UPDATE task_activities SET goalid = " . $NextID . ", activitytype = 5, goalcount = 1, goalmethod = 0 WHERE activityid = " . $activityid . " AND taskid = " . $taskid . "";
			$QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());
		}

		$TaskContent .= '<form class="customForm" action="tasks.php">';
		$TaskContent .= '<table>';

		$Query = "SELECT proximities.exploreid, proximities.zoneid, proximities.minx, proximities.maxx, proximities.miny, proximities.maxy, proximities.minz, proximities.maxz, 
						task_activities.taskid, task_activities.activityid, task_activities.goalid, task_activities.goalmethod, task_activities.activitytype FROM proximities, task_activities 
						WHERE proximities.exploreid = task_activities.goalid AND task_activities.goalmethod = 0 AND task_activities.activitytype = 5 
						AND task_activities.activityid = " . $activityid . " AND task_activities.taskid = " . $taskid . " 
						LIMIT 1";

		$QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());

		if (mysql_num_rows($QueryResult) != 0) {
			while ($proximity = mysql_fetch_array($QueryResult)) {
				$TaskContent .= '<tr><td colspan="2" align="center"><div id="proximityDeleteDiv"><a href="javascript:;" class="custBtnIconLeft" onclick="deleteTaskActivity(' . $taskid . ', ' . $activityid . ', \'delete\', \'proximityDeleteDiv\')">Delete Proximity</a></div></td></tr>';

				$TaskContent .= '<tr><td colspan="2"><center><b>Proximity Details</b></center></td></tr>';
				$TaskContent .= GetTaskFormString($taskid, $activityid, "proximityexploreid", "Explore ID", $proximity["exploreid"], 3, "right", 1);
				$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Zone ID</b></td><td align="left" nowrap="nowrap">' . GetZoneSelect($proximity["zoneid"], "proximityzoneid", $taskid, $activityid) . '</td></tr>';

				// Min/Max X
				$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Min X</b></td>';
				$TaskContent .= '<td><table><tr>';
				$TaskContent .= '<td><input class="form-control" autocomplete="off" size="11" id="proximityminx" name="proximityminx" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $proximity["minx"] . '"/></td>';
				$TaskContent .= GetTaskFormString($taskid, $activityid, "proximitymaxx", "Max X", $proximity["maxx"], 11, "right", 0);
				$TaskContent .= '</tr></table></td></tr>';

				// Min/Max Y
				$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Min Y</b></td>';
				$TaskContent .= '<td><table><tr>';
				$TaskContent .= '<td><input class="form-control" autocomplete="off" size="11" id="proximityminy" name="proximityminy" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $proximity["miny"] . '"/></td>';
				$TaskContent .= GetTaskFormString($taskid, $activityid, "proximitymaxy", "Max Y", $proximity["maxy"], 11, "right", 0);
				$TaskContent .= '</tr></table></td></tr>';

				// Min/Max Z
				$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Min Z</b></td>';
				$TaskContent .= '<td><table><tr>';
				$TaskContent .= '<td><input class="form-control" autocomplete="off" size="11" id="proximityminz" name="proximityminz" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $proximity["minz"] . '"/></td>';
				$TaskContent .= GetTaskFormString($taskid, $activityid, "proximitymaxz", "Max Z", $proximity["maxz"], 11, "right", 0);
				$TaskContent .= '</tr></table></td></tr>';
			}
		}

		$TaskContent .= '</table>';
		$TaskContent .= '</form>';

		return $TaskContent;
	}

	function GetActivityList($taskid)
	{
		global $tbtasktask_activities;

		$TaskID = $taskid;

		$TaskContent .= '
		
		<table align="left" style="width:500px"><tr><td colspan="2" style="vertical-align:top">';

		$Query = "SELECT * FROM task_activities WHERE taskid='" . $taskid . "' ORDER BY activityid";
		$QueryResult = mysql_query($Query) or message_die('task.php', 'MYSQL_QUERY', $Query, mysql_error());

		$TaskContent .= '<a href="javascript:;" class="btn green btn-xs" onclick="addActivity(' . $taskid . ')"><i class="fa fa-database"></i> New Activity</a><hr>';
		$TaskContent .= '<select multiple="multiple" class="form-control" onclick="getActivityContent(' . $taskid . ', this.value)" title="Click to Select an Activity" style="height:200px">';

		while ($row = mysql_fetch_array($QueryResult)) {
			$ActivityID      = $row["activityid"];
			$ActivityType    = $row["activitytype"];
			$description_override           = $row["description_override"];
			$Text2           = $row["text2"];
			$StepDescription = $row["text3"];
			$GoalCount       = $row["goalcount"];


			$StepDescription
				= ($description_override ? $description_override : GetActivityStepDesc($ActivityType, $GoalCount, $description_override, $Text2, $StepDescription));

			if ($CurTaskID == $taskid) {
				$TaskContent .= '<option value="' . $ActivityID . '" selected="selected">' . $ActivityID . ' - ' . $StepDescription . '</option>';
			} else {
				$TaskContent .= '<option value="' . $ActivityID . '">' . $ActivityID . ' - ' . $StepDescription . '</option>';
			}
		}

		$TaskContent .= '</select> ';

		$TaskContent .= '
					<!-- Task Details -->
						<div id="activityDiv" class="floatright">';

		// Activity Data Form will be put here by AJAX from taskbuild.php

		$TaskContent .= '</div>';

		$TaskContent .= '<div class="fix"></div>
							</div>';

		$TaskContent .= '</td></tr></table>';

		return $TaskContent;
	}

	function GetTaskList($taskid)
	{
		global $tbtasks;

		$TaskID = $taskid;

		$Query = "SELECT `id`, `title` FROM tasks ORDER BY `id`";
		$QueryResult = mysql_query($Query);
		if (mysql_num_rows($QueryResult) == 0) {
			//header("Location: index.php");
			//exit();
		}

		$TaskContent .= '<select multiple="multiple" class="form-control" onclick="getTaskContent(this.value)" title="Click to Select a Task" style="width:400px;height:800px">';

		while ($row = mysql_fetch_array($QueryResult)) {
			$CurTaskID    = $row["id"];
			$CurTaskTitle = $row["title"];
			if ($CurTaskID == $taskid) {
				$TaskContent .= '<option value="' . $CurTaskID . '" selected="selected">' . $CurTaskID . ' - ' . $CurTaskTitle . '</option>';
			} else {
				$TaskContent .= '<option value="' . $CurTaskID . '">' . $CurTaskID . ' - ' . $CurTaskTitle . '</option>';
			}
		}
		$TaskContent .= '</select>';

		return $TaskContent;
	}

	function GetTaskEditForm($id)
	{
		global $tbtasktask_activities, $tbtasks, $icons_url, $root_url;

		$taskid     = $id;
		$activityid = -1;

		if ($id != "" && is_numeric($id)) {
			if ($id == 0) {
				$MaxTaskID            = GetFieldByQuery("MAXID", "SELECT MAX(id) AS MAXID FROM `tasks`");
				$id                   = $MaxTaskID + 1;
				$taskid               = $id;
				$task["id"]           = $id;
				$task["minlevel"]     = 0;
				$task["maxlevel"]     = 0;
				$task["duration"]     = 0;
				$task["repeatable"]   = 1;
				$task["startzone"]    = 0;
				$task["rewardmethod"] = 0;
				$task["title"]        = "Default Description";
				$Query                = "INSERT INTO `tasks` (id, minlevel, maxlevel, duration, repeatable, rewardmethod, title, description) 
					VALUES('" . $task["id"] . "','" . $task["minlevel"] . "','" . $task["maxlevel"] . "','" . $task["duration"] . "','" . $task["repeatable"] . "','" . $task["rewardmethod"] . "','" . $task["title"] . "','" . $task["title"] . "')";
				$QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
				if ($QueryResult) {
					$Query = "SELECT * FROM `tasks` WHERE id='" . $id . "'";
					$QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
					if (mysql_num_rows($QueryResult) == 0) {
						//exit();
					}
					$task = mysql_fetch_array($QueryResult);
				}
			} else {
				$Query = "SELECT * FROM `tasks` WHERE id='" . $id . "'";
				$QueryResult = mysql_query($Query);
				if (mysql_num_rows($QueryResult) == 0) {
					// No task data - Return nothing
					return;
				}
				$task = mysql_fetch_array($QueryResult);
			}
		}

		$TaskContent = "";
		$TaskContent .= '<div>';

		// Outter Table that includes the task data table on the left and activity table on the right
		$TaskContent .= '<table align="left" style="margin-left:15px;" width="100%"><tr><td style="vertical-align:top">';

		// Task Data Table
		$TaskContent .= '<form id="taskform" class="customForm" action="tasks.php" method="post">';

		$TaskContent .= '<table align="left" width="0%">';

		$TaskContent .= '<tr><td align="center"><div id="taskDeleteDiv"><a href="javascript:;" class="btn btn-xs red" onclick="deleteTaskActivity(' . $taskid . ', ' . $activityid . ', \'delete\', \'taskDeleteDiv\')"><i class="fa fa-trash-o"></i> Delete Task</a><hr></div></td></tr>';

		// Task ID
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskid", "Task ID", $task["id"], 2, "right", 1);

		// Task Title
		$TaskContent .= GetTaskFormString($taskid, $activityid, "tasktitle", "Task Title", $task["title"], 64, "right", 1);

		// Min Level
		//$TaskContent .= GetTaskFormString($taskid, $activityid, "taskminlevel", "Min Level", $task["minlevel"], 1, "right", 0);
		$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Min Level</b></td>';
		// Begin Min/Max Level and Duration Table
		$TaskContent .= '<td><table><tr>';
		$TaskContent .= '<td><input class="form-control" autocomplete="off" size="1" id="taskminlevel" name="taskminlevel" type="text" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" value="' . $task["minlevel"] . '"/></td>';

		// Max Level
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskmaxlevel", "Max Level", $task["maxlevel"], 1, "right", 0);
		// Duration
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskduration", "Duration", $task["duration"], 1, "right", 0);

		// End Min/Max Level and Duration Table
		$TaskContent .= '</tr></table></td></tr>';

		$ZoneID = $task["startzone"];

		// Start Zone
		// $TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Starts In</b></td><td>';
		// // Zone List Select Box
		// $TaskContent .= GetZoneSelect($ZoneID, "taskstartzone", $taskid, $activityid);
		// $TaskContent .= '</td></tr>';

		// Reward Method
		$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Reward Method</b></td><td>
				<div class="customSelect">
					<select class="form-control" id="taskrewardmethod" name="taskrewardmethod" autocomplete="off" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)">';

		$RewardMethodArray = array(
			"Single Item",
			"Item Goal List",
			"Quest Reward"
		);
		for ($i = 0; $i < 3; $i++) {
			if ($i == $task["rewardmethod"]) {
				$TaskContent .= '<option value="' . $i . '" selected="selected">' . $i . ' - ' . $RewardMethodArray[$i] . '</option>';
			} else {
				$TaskContent .= '<option value="' . $i . '">' . $i . ' - ' . $RewardMethodArray[$i] . '</option>';
			}
		}
		$TaskContent .= '</select></div><div class="fix"></div></td></tr>';

		// Reward Description
		$Reward = $task["reward"];
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskreward", "Reward Description", $Reward, 64, "right", 1);

		$TaskContent .= '<tr><td align="right" nowrap="nowrap">
				<b>Reward ID/Goal List</b></td><td><table width="100%"><tr>
				<td><input class="form-control" size="11" id="taskrewardid" name="taskrewardid" type="text" value="' . $task["rewardid"] . '" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)" />';
		$TaskContent .= '<a href="javascript:;" class="btn green btn-xs" onclick="addGoal(' . $taskid . ', ' . $activityid . ', \'taskrewardid\')"><i class="fa fa-plus-square"></i> New</a> ';
		$TaskContent .= '<a href="javascript:;" class="btn blue btn-xs" onclick="getGoalList(\'taskrewardid\', 1200, 800)"><i class="fa fa-pencil-square-o"></i> Edit</a></td>';

		if ($task["rewardmethod"] == 0 && is_numeric($task["rewardid"])) {
			$ItemName = GetFieldByQuery("Name", "SELECT Name FROM items WHERE id = " . $task["rewardid"]);
			if ($ItemName) {
				$TaskContent .= '<td align="right" nowrap="nowrap"><a id="taskrewarditemlink" href="' . $root_url . 'item.php?id=' . $task["rewardid"] . '"> ' . $ItemName . '<a/></td>';
			}
		}
		$TaskContent .= '</tr></table></td></tr>';

		$TaskCash  = $task["cashreward"];
		$ItemValue = "";
		$Platinum  = 0;
		$Gold      = 0;
		$Silver    = 0;
		$Copper    = 0;

		if ($TaskCash > 1000) {
			$Platinum = ((int) ($TaskCash / 1000));
		}
		if (($TaskCash - ($Platinum * 1000)) > 100) {
			$Gold = ((int) (($TaskCash - ($Platinum * 1000)) / 100));
		}
		if (($TaskCash - ($Platinum * 1000) - ($Gold * 100)) > 10) {
			$Silver = ((int) (($TaskCash - ($Platinum * 1000) - ($Gold * 100)) / 10));
		}
		if (($TaskCash - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10)) > 0) {
			$Copper = ($TaskCash - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10));
		}

		// Cash Reward
		$ItemValue .= '<tr><td align="right" nowrap="nowrap"><b>Cash Reward</b></td><td><table>';
		$ItemValue .= '<tr><td nowrap="nowrap"> <img src="' . $icons_url . 'item_644.gif" width="14" height="14"/> <input autocomplete="off" size="11" id="taskplatinum" name="taskplatinum" type="text" value="' . $Platinum . '"/> ' . '</td><td nowrap="nowrap"> <img src="' . $icons_url . 'item_645.gif" width="14" height="14"/> <input autocomplete="off" size="2" id="taskgold" name="taskgold" type="text" value="' . $Gold . '"/> ' . '</td><td nowrap="nowrap"> <img src="' . $icons_url . 'item_646.gif" width="14" height="14"/> <input autocomplete="off" size="2" id="tasksilver" name="tasksilver" type="text" value="' . $Silver . '"/> ' . '</td><td nowrap="nowrap"> <img src="' . $icons_url . 'item_647.gif" width="14" height="14"/> <input autocomplete="off" size="2" id="taskcopper" name="taskcopper" type="text" value="' . $Copper . '"/> ';
		$ItemValue .= '</td></tr></table></td></tr>';
		// Disabled Split Coin Fields for Cash Reward
		//$TaskContent .= $ItemValue;

		// Cash Reward
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskcashreward", "Cash Reward", $task["cashreward"], 11, "right", 1);

		// Experience Reward
		$TaskContent .= GetTaskFormString($taskid, $activityid, "taskxpreward", "Experience Reward", $task["xpreward"], 11, "right", 1);

		// Repeatable
		$Repeatable      = $task["repeatable"];
		$CheckBoxChecked = '';
		if ($Repeatable != 0) {
			$CheckBoxChecked = 'checked="checked" ';
		}
		$TaskContent .= '<tr><td align="right" nowrap="nowrap"><b>Repeatable</b></td><td>
				<input autocomplete="off" type="checkbox" id="taskrepeatable" name="taskrepeatable" class="" ' . $CheckBoxChecked . ' onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)"/>
				<label for="taskrepeatable" class="customCheckbox-label"></label></td></tr>';

		// Task description
		$TaskContent .= '<tr><td><b>Task Description</b>
							</td><td><textarea class="form-control" rows="8" cols="64" id="taskdescription" name="taskdescription" autocomplete="off" onchange="UpdateDBField(' . $taskid . ', ' . $activityid . ', this.id, this.value)">' . $task["description"] . '</textarea></td></tr>';

		// End the task data table and start the task_activities table
		$TaskContent .= '</table></td><td style="vertical-align:top">';

		$TaskContent .= '</form>';
		$TaskContent .= '<div id="activityListDiv">';

		$TaskContent .= GetActivityList($id);

		$TaskContent .= '</div>';

		// End the outter table
		$TaskContent .= '</td></tr></table>';

		$TaskContent .= '</div><div class="fix"></div>';

		return $TaskContent;
	}
?>
