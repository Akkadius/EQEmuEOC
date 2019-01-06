<?php

require_once('modules/TaskEditor/functions.php');
require_once('includes/alla_functions.php');

$id          = (isset($_GET['id']) ? $_GET['id'] : '');
$xmlid       = (isset($_GET['xmlid']) ? $_GET['xmlid'] : '');
$goalid      = (isset($_GET['goalid']) ? $_GET['goalid'] : '');
$type        = (isset($_GET['type']) ? $_GET['type'] : '');
$entry       = (isset($_GET['entry']) ? $_GET['entry'] : '');
$addgoal     = (isset($_GET['addgoal']) ? $_GET['addgoal'] : 0);
$newentry    = (isset($_GET['newentry']) ? $_GET['newentry'] : '');
$GoalList    = (isset($_GET['GoalList']) ? $_GET['GoalList'] : '');
$Value       = (isset($_GET['Value']) ? $_GET['Value'] : '');
$count       = (isset($_GET['count']) ? $_GET['count'] : '');
$activityid  = (isset($_GET['activityid']) ? $_GET['activityid'] : '');
$fieldid     = (isset($_GET['fieldid']) ? $_GET['fieldid'] : '');
$addactivity = (isset($_GET['addactivity']) ? $_GET['addactivity'] : '');
$divname     = (isset($_GET['divname']) ? $_GET['divname'] : '');

$GoalMessage = '0';
$Duplicate   = 0;

$TaskContent = "";

// Used to populate the Task List on the left side of the editor
if ($type === "tasklist") {
    $TaskContent .= GetTaskList($id);
}

// Handles Deletes for Tasks, task_activities, and Proximities
if ($id != "" && is_numeric($id) && $activityid != "" && is_numeric($activityid) && ($type === "delete" || $type === "confirmdelete" || $type === "canceldelete")) {
    if ($type === "confirmdelete") {
        if ($divname === "proximityDeleteDiv") {
            $exploreid = 0;
            $Query     = 'SELECT goalid FROM task_activities WHERE taskid = ' . $id . ' AND activityid = ' . $activityid;
            $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
            if (mysql_num_rows($QueryResult) != 0) {
                $row       = mysql_fetch_array($QueryResult);
                $exploreid = $row["goalid"];
                $Query     = 'DELETE FROM proximities WHERE exploreid = ' . $exploreid;
                $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
            }
        } elseif ($activityid >= 0) {
            $Query = 'DELETE FROM task_activities WHERE taskid = ' . $id . ' AND activityid = ' . $activityid;
            $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        } elseif ($id >= 0) {
            $Query = 'DELETE FROM tasks WHERE id = ' . $id;
            $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
            $Query2 = 'DELETE FROM task_activities WHERE taskid = ' . $id;
            $QueryResult = mysql_query($Query2) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        }
    } else {
        if ($type === "delete") {
            if ($divname === "proximityDeleteDiv") {
                // Delete Activity
                $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'confirmdelete\', \'' . $divname . '\');getTaskContent(' . $id . ');">Confirm Delete</a>';
            } elseif ($activityid >= 0) {
                // Delete Activity
                $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'confirmdelete\', \'' . $divname . '\');getTaskContent(' . $id . ');">Confirm Delete</a>';
            } else {
                // Delete Task
                $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'confirmdelete\', \'' . $divname . '\');getTaskSelectList(' . $id . ');">Confirm Delete</a>';
            }
            $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'canceldelete\', \'' . $divname . '\')">Cancel</a>';
        } elseif ($divname === "proximityDeleteDiv") {
            $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'delete\', \'proximityDeleteDiv\')">Delete Proximity</a>';
        } elseif ($activityid >= 0) {
            $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'delete\', \'activityDeleteDiv\')">Delete Activity</a>';
        } else {
            $TaskContent .= '<a href="javascript:;" class="btn red" onclick="deleteTaskActivity(' . $id . ', ' . $activityid . ', \'delete\', \'taskDeleteDiv\')">Delete Task</a>';
        }
    }
}

// Adds new task_activities
if ($id != "" && is_numeric($id) && $type === "" && is_numeric($addactivity) && $addactivity == 1) {
    $ActivityID = 0;

    $Query = "SELECT activityid FROM task_activities WHERE taskid = " . $id . " ORDER BY activityid DESC LIMIT 1";
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) != 0) {
        $row        = mysql_fetch_array($QueryResult);
        $ActivityID = $row["activityid"] + 1;
    }

    $activity["taskid"]               = $id;
    $activity["activityid"]           = $ActivityID;
    $activity["step"]                 = 0;
    $activity["activitytype"]         = 0;
    $activity["description_override"] = "";
    $activity["text2"]                = "";
    $activity["text3"]                = "";
    $activity["goalid"]               = 0;
    $activity["goalmethod"]           = 0;
    $activity["goalcount"]            = 0;
    $activity["delivertonpc"]         = 0;
    $activity["zoneid"]               = 0;
    $activity["optional"]             = 0;

    $Query = "INSERT INTO task_activities (taskid, activityid, step, activitytype, description_override, goalid, goalmethod, goalcount, delivertonpc, zones, optional) 
			VALUES('" . $activity["taskid"] . "','" . $activity["activityid"] . "','" . $activity["step"] . "','" . $activity["activitytype"] . "',
			'" . $activity["description_override"] . "','" . $activity["goalid"] . "'
			,'" . $activity["goalmethod"] . "','" . $activity["goalcount"] . "','" . $activity["delivertonpc"] . "','" . $activity["zoneid"] . "','" . $activity["optional"] . "')";
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
}

// Populates Task Data
if ($id != "" && is_numeric($id) && $type === "") {
    $TaskContent .= GetTaskEditForm($id);
}

// Populates Activity Data
if ($id != "" && is_numeric($id) && $type === "activity" && is_numeric($activityid)) {
    $TaskContent .= GetActivityForm($id, $activityid);
}

// Creates New Goal List and Populates Task/task_activities Goal Fields
if ($id != "" && is_numeric($id) && $activityid != "" && is_numeric($activityid) && $type === "addgoallist") {
    if ($goalid == 0) {
        // Create new Goal ID
        $Query = "SELECT listid FROM goallists ORDER BY listid DESC LIMIT 1";
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        if (mysql_num_rows($QueryResult) != 0) {
            $row    = mysql_fetch_array($QueryResult);
            $goalid = $row["listid"] + 1;
        } else {
            $goalid = 1;
        }
        $Query = 'INSERT INTO goallists ( listid, entry ) VALUES ( ' . $goalid . ',  0 )';
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());

        if ($activityid >= 0) {
            $Query = "UPDATE task_activities SET goalid = '" . $goalid . "' WHERE taskid = '" . $id . "' AND activityid = '" . $activityid . "'";
        } else {
            $Query = "UPDATE tasks SET rewardid = '" . $goalid . "' WHERE id = '" . $id . "'";
        }
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
    }
    $TaskContent = $goalid;
}

// Handles automatic DB updates for onchange events in Task and Activity fields
if ($id != "" && is_numeric($id) && $type === "dbupdate" && $fieldid != "") {

    // Parse the field name from the field id
    $IsTask      = 0;
    $IsActivity  = 0;
    $TaskIDExist = 0;

    if (strpos($fieldid, "task") === 0) {
        $IsTask  = 1;
        $fieldid = preg_replace("/task/", "", $fieldid, 1);
    }
    if (strpos($fieldid, "activity") === 0) {
        $IsActivity = 1;
        $fieldid    = preg_replace("/activity/", "", $fieldid, 1);
    }

    if ($fieldid === "repeatable") {
        $IsRepeatable = GetFieldByQuery("repeatable", "SELECT repeatable FROM tasks WHERE id = " . $id);
        if ($IsRepeatable) {
            $Value = 0;
        } else {
            $Value = 1;
        }
    }

    if ($fieldid === "optional") {
        $IsOptional = GetFieldByQuery("optional", "SELECT optional FROM task_activities WHERE taskid = " . $id . " AND activityid = " . $activityid);
        if ($IsOptional) {
            $Value = 0;
        } else {
            $Value = 1;
        }
    }

    if ($IsTask && $IsActivity == 0) {
        if ($fieldid === "id") {
            $Query = "SELECT id FROM tasks WHERE id = " . $Value;
            $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
            if (mysql_num_rows($QueryResult) != 0) {
                $TaskIDExist = 1;

            }
        }
        // Verify the field exists
        $result      = mysql_query("SHOW COLUMNS FROM `tasks` LIKE '" . $fieldid . "'");
        $fieldexists = (mysql_num_rows($result)) ? TRUE : FALSE;

        if (!$TaskIDExist) {
            if ($fieldexists) {
                $Query = "SELECT id FROM tasks WHERE id = " . $id;
                $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
                if (mysql_num_rows($QueryResult) != 0) {
                    $Query = "UPDATE tasks SET `" . $fieldid . "` = '" . $Value . "' WHERE id = " . $id;
                    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
                    // If Task ID is change, we need to also change the taskid field for associated task_activities
                    if ($fieldid === "id") {
                        $Query = "UPDATE task_activities SET `taskid` = '" . $Value . "' WHERE taskid = " . $id;
                        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
                    }
                    $ResponseMessage = '<font><b>Task Field - ' . $fieldid . ' - UPDATED!</b></font>';
                } else {
                    $ResponseMessage = '<font color="red"><b>No Update - Task ID ( ' . $id . ') Not Found!</b></font>';
                }
            } else {
                $ResponseMessage = '<font color="red"><b>No Update - Task Field ( ' . $fieldid . ' ) Not Found!</b></font>';
            }
        } else {
            $ResponseMessage = '<font color="red"><b>No Update - Task ID ( ' . $Value . ' ) Already Exists!</b></font>';
        }
    } elseif ($IsTask && $IsActivity && is_numeric($activityid) && $activityid >= 0) {

        if ($fieldid === "activityid") {
            $Query = "SELECT activityid FROM task_activities WHERE activityid = " . $Value . " AND taskid =" . $id;
            $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
            if (mysql_num_rows($QueryResult) != 0) {
                $ActivityIDExist = 1;
            }
        }
        // Verify the field exists
        $result      = mysql_query("SHOW COLUMNS FROM `task_activities` LIKE '" . $fieldid . "'");
        $fieldexists = (mysql_num_rows($result)) ? TRUE : FALSE;

        if (!$ActivityIDExist) {

            if ($fieldexists) {
                $Query       = "SELECT activityid FROM task_activities WHERE activityid = " . $activityid . " AND taskid = " . $id;
                $QueryResult = mysql_query($Query);
                if (mysql_num_rows($QueryResult) != 0) {

                    $Query           = "UPDATE task_activities SET `" . $fieldid . "` = '" . $Value . "' WHERE activityid = " . $activityid . " AND taskid = " . $id;
                    $QueryResult     = mysql_query($Query);
                    $ResponseMessage = '<font ><b>Activity Field - ' . $fieldid . ' - UPDATED!</b></font>';
                } else {
                    $ResponseMessage = '<font color="red"><b>No Update - Activity ID ( ' . $activityid . ') Not Found!</b></font>';
                }
            } else {
                $ResponseMessage = '<font color="red"><b>No Update - Activity Field ( ' . $fieldid . ' ) Not Found!</b></font>';
            }
        } else {
            $ResponseMessage = '<font color="red"><b>No Update - Activity ID ( ' . $Value . ' ) Already Exists!</b></font>';
        }
    } else if ($_GET['proximitydb']) {
        $fieldid = str_replace('proximity', '', $fieldid);
        $Query   = "UPDATE proximities SET `" . $fieldid . "` = '" . $Value . "' WHERE `exploreid` = " . $_GET['exploreid'] . "";
        echo $Query;
        $QueryResult     = mysql_query($Query);
        $ResponseMessage = '<font ><b>Activity Field - ' . $fieldid . ' - UPDATED!</b></font>';
    } else {
        $ResponseMessage = '<font color="red"><b>Update Error!</b></font> ' . mysql_error();
    }

    $TaskContent = $ResponseMessage;
}

// Handles DB updates for goal entries from onchange events.
if ($goalid != "" && is_numeric($goalid) && is_numeric($entry) && is_numeric($newentry) && $type === "updategoal") {
    $Query = 'SELECT listid, entry FROM goallists WHERE listid = ' . $goalid . ' AND entry = ' . $newentry;
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        $Query = 'UPDATE goallists SET entry = ' . $newentry . ' WHERE listid = ' . $goalid . ' AND entry = ' . $entry;
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        $GoalMessage = '<font ><b>UPDATED!</b></font>';
    } else {
        $GoalMessage = '<font color="red"><b>Duplicate Entry Exists!</b></font>';
        $Duplicate   = 1;
    }
    // Row updated - Change $type so it will reload the current div below
    $type = 'taskactivitygoalid';
}

// Handles new inserts for goal entries
if ($goalid != "" && is_numeric($goalid) && is_numeric($entry) && is_numeric($newentry) && $type === "insertgoal") {
    $Query = 'SELECT listid, entry FROM goallists WHERE listid = ' . $goalid . ' AND entry = ' . $newentry;
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        $Query = 'INSERT INTO goallists ( listid, entry ) VALUES ( ' . $goalid . ',  ' . $newentry . ' )';
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        $GoalMessage = '<font ><b>SAVED!</b></font>';
    } else {
        $GoalMessage = '<font color="red"><b>Duplicate Entry Exists!</b></font>';
        $Duplicate   = 1;
    }
    // Row inserted - Change $type so it will reload the current div below
    $type = 'taskactivitygoalid';

}

// Handles deletes for goal entries
if ($goalid != "" && is_numeric($goalid) && is_numeric($entry) && $type === "deletegoal") {
    $Query = 'DELETE from goallists WHERE listid = ' . $goalid . ' AND entry = ' . $entry;
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
    // Row Deleted - Change $type so it will reload the current div below
    $type = 'taskactivitygoalid';
}

// Builds the entries portion of the Goals web page for reward goal lists
if ($goalid != "" && is_numeric($goalid) && ($type === "taskactivitygoalid" || $type === "rewardid")) {
    if ($goalid == 0) {
        // Create new Goal ID
        $Query = "SELECT listid FROM goallists ORDER BY listid DESC LIMIT 1";
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        if (mysql_num_rows($QueryResult) != 0) {
            $row    = mysql_fetch_array($QueryResult);
            $goalid = $row["activityid"] + 1;
        }
        $Query = 'INSERT INTO goallists ( listid, entry ) VALUES ( ' . $goalid . ',  0 )';
        $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());
        $GoalMessage = '<font ><b>New Entry!</b></font>';
    }

    $Query = 'SELECT listid, entry FROM goallists WHERE listid = ' . $goalid . ' ORDER BY entry';
    $QueryResult = mysql_query($Query) or message_die('taskbuild.php', 'MYSQL_QUERY', $Query, mysql_error());

    $GoalTypeValue = "taskactivitygoalid";
    if ($type == "rewardid") {
        $GoalTypeValue = "rewardid";
    }
    $TaskContent .= '<table align="left" style="margin-left:25px;">';
    while ($row = mysql_fetch_array($QueryResult)) {
        $CurGoalID    = $row["listid"];
        $CurGoalEntry = $row["entry"];
        $NPCName      = ReadableNpcName(GetFieldByQuery("name", "SELECT name FROM npc_types WHERE id=" . $CurGoalEntry . ""));
        $ItemName     = GetFieldByQuery("Name", "SELECT Name FROM items WHERE id=" . $CurGoalEntry . "");
        $TaskContent  .= '<tr><td align="right"><b>Goal List ' . $CurGoalID . ' : </b></td><td>
			<input size="11" id="goalentry' . $CurGoalEntry . '" name="goalentry' . $CurGoalEntry . '" type="text" value="' . $CurGoalEntry . '" onchange="changeGoal(' . $CurGoalID . ', \'updategoal\', ' . $CurGoalEntry . ', this.value)"/>';

        $TaskContent .= '<a href="javascript:;" class="btn red btn-xs" onclick="changeGoal(' . $CurGoalID . ', \'deletegoal\', ' . $CurGoalEntry . ')"><i class="fa fa-trash-o"></i> Delete</a>';
        $TaskContent .= ' ';

        if ($ItemName) {
            $TaskContent .= '<a href="' . $root_url . 'item.php?id=' . $CurGoalEntry . '"> ' . $ItemName . '<a/>';
        }
        if ($ItemName && $NPCName && $type == "taskactivitygoalid") {
            $TaskContent .= '<font color="red"><b> OR </b><font>';
        }
        if ($NPCName && $type == "taskactivitygoalid") {
            $TaskContent .= '<a href="' . $root_url . 'npc.php?id=' . $CurGoalEntry . '"> ' . $NPCName . '<a/>';
        }
        if (!$ItemName && !$NPCName) {
            if ($type == "taskactivitygoalid") {
                $TaskContent .= 'No Items or NPCs Found';
            }
            if ($type == "rewardid") {
                $TaskContent .= 'No Items Found';
            }
        }
        if ($message != "0" && is_numeric($newentry) && $newentry == $CurGoalEntry) {
            $TaskContent .= ' - ' . $GoalMessage;
        }
        $TaskContent .= '</td></tr>';
    }
    if ($addgoal != 0) {
        $TaskContent .= '<tr><td align="right"><b>Goal List ' . $CurGoalID . ' : </b></td><td>
			<input size="11" id="newgoalentry" name="newgoalentry" type="text" value="0" onchange="changeGoal(' . $CurGoalID . ', \'insertgoal\', 0, this.value)"/>';

        if ($message != "0" && is_numeric($newentry) && $newentry == $CurGoalEntry) {
            $TaskContent .= ' - ' . $GoalMessage;
        }
        $TaskContent .= '</td></tr>';
    }
    $TaskContent .= '<tr><td>';
    $TaskContent .= '<a href="javascript:;" class="btn btn-default" onclick="getGoalContent(' . $CurGoalID . ', \'' . $GoalTypeValue . '\', 1)">Add Goal</a>';
    $TaskContent .= '</td><td></td></tr>';
    $TaskContent .= '</table>';
}

// Builds the list portion of the Goals web page for goal lists
if ($GoalList === "taskrewardid" || $GoalList === "taskactivitygoalid") {
    $rewardid = $Value;

    $TaskContent .= '<div>';
    $TaskContent .= '<form class="mainForm" action="tasks.php">';
    $TaskContent .= '<table align="left" style="margin-left:25px;">';

    $TaskContent .= '<tr><td align="right"><b>Reward/Goal ID : </b></td><td><input size="64" name="taskrewardid" type="text" value="' . $rewardid . '"/></td></tr>';
    $Query       = "";
    if ($GoalList == "taskrewardid") {
        $Query = "SELECT goallists.listid, tasks.id, tasks.title, tasks.rewardid FROM goallists INNER JOIN tasks ON goallists.listid = tasks.rewardid GROUP BY goallists.listid";
    }
    if ($GoalList == "taskactivitygoalid") {
        $Query = "SELECT goallists.listid, tasks.id, tasks.title, task_activities.taskid, task_activities.goalid, task_activities.goalmethod FROM goallists 
						INNER JOIN task_activities ON goallists.listid = task_activities.goalid 
						INNER JOIN tasks ON tasks.id = task_activities.taskid 
						WHERE task_activities.goalmethod = 1 
						GROUP BY goallists.listid";
    }
    $QueryResult = mysql_query($Query) or message_die('tasks.php', 'MYSQL_QUERY', $Query, mysql_error());

    //$Query2 = "SELECT listid FROM goallists GROUP BY listid";
    //$QueryResult2 = mysql_query($Query2) or message_die('tasks.php','MYSQL_QUERY',$Query2,mysql_error());

    if (mysql_num_rows($QueryResult) != 0) {
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

        while ($row = mysql_fetch_array($QueryResult)) {
            $CurGoalID    = $row["listid"];
            $CurTaskID    = $row["id"];
            $CurTaskTitle = $row["title"];
            if ($CurGoalID == $taskid) {
                $TaskContent .= '<option value="' . $CurGoalID . '" selected="selected">' . $CurGoalID . ' - ' . $CurTaskTitle . ' (Task: ' . $CurTaskID . ')</option>';
            } else {
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
    $TaskContent     .= '<script type="text/javascript" src="../jquery/eqemutooltip.js"></script>';
}

// Builds the Proximities table
if ($id != "" && is_numeric($id) && $activityid != "" && is_numeric($activityid) && $type === "proximitylist") {
    $TaskContent .= GetProximityContent($id, $activityid, $newentry);
}

// Return all content created from this file
echo $TaskContent;
