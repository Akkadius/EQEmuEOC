var xmlhttp;

function loadXMLDoc(url, cfunc) {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = cfunc;
    xmlhttp.open("GET", url, true);
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
		loadXMLDoc("ajax.php?M=TaskEditor&goalid="+goalid+"&type="+type+"&addgoal="+addgoal,function()
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
		loadXMLDoc("ajax.php?M=TaskEditor&goalid="+goalid+"&type="+type+"&entry="+entry+"&newentry="+newentry,function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("goalDiv").innerHTML=xmlhttp.responseText;
				}
			}
		);
	}
}

function getTaskSelectList(taskid) {
    loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&type=tasklist", function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("TaskListSelect").innerHTML = xmlhttp.responseText;
            getTaskContent(taskid);
        }
    });
}

function getTaskContent(taskid) {
    if (typeof taskid === "undefined" || taskid.length == 0) {
        document.getElementById("taskDiv").innerHTML = "";
        return;
    } else {
        loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid, function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("taskDiv").innerHTML = xmlhttp.responseText;
                document.getElementById("updateNoteDiv").innerHTML = "";
                document.title = "[EOC 2.0] Task (" + taskid + ")";
            }
        });
    }
}

function addActivity(taskid) {
    if (taskid.length == 0) {
        document.getElementById("taskDiv").innerHTML = "";
        return;
    } else {
        loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&addactivity=1", function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("taskDiv").innerHTML = xmlhttp.responseText;
                document.getElementById("updateNoteDiv").innerHTML = "";
            }
        });
    }
}

function getActivityContent(taskid, activityid) {
    if (typeof taskid === "undefined" || taskid.length == 0) {
        document.getElementById("activityDiv").innerHTML = "";
        return;
    } else {
        loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&type=activity&activityid=" + activityid, function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("activityDiv").innerHTML = xmlhttp.responseText;
                document.getElementById("updateNoteDiv").innerHTML = "";
            }
        });
    }
}

function getProximityContent(taskid, activityid, newentry) {
    if (typeof taskid === "undefined" || taskid.length == 0) {
        document.getElementById("proximityDiv").innerHTML = "";
        return;
    } else {
        loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&activityid=" + activityid + "&type=proximitylist&newentry=" + newentry, function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("proximityDiv").innerHTML = xmlhttp.responseText;
                document.getElementById("updateNoteDiv").innerHTML = "";
            }
        });
    }
}

function UpdateDBField(taskid, activityid, fieldid, fieldvalue) {
    if (taskid.length == 0) {
        document.getElementById("updateNoteDiv").innerHTML = "Update Failed - No Task ID Set!";
        return;
    } else {
		proxfield = 0;
		extra_data = "";
		if(fieldid == "proximityzoneid" || fieldid == "proximityexploreid" || fieldid == "proximityminx"){ proxfield = 1; }
		if(fieldid == "proximityminy"){ proxfield = 1; }
		if(fieldid == "proximityminz"){ proxfield = 1; }
		if(fieldid == "proximitymaxx"){ proxfield = 1; }
		if(fieldid == "proximitymaxy"){ proxfield = 1; }
		if(fieldid == "proximitymaxz"){ proxfield = 1; }
		if(proxfield == 1){ extra_data = "&proximitydb=1&exploreid=" + $('#proximityexploreid').val() + ""; }
		
        loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&activityid=" + activityid + "&type=dbupdate&fieldid=" + fieldid + "&Value=" + fieldvalue + extra_data, function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert("Updated Task "+taskid+" : "+fieldid+" = "+fieldvalue);
                //document.getElementById("updateNoteDiv").innerHTML="Update DONE!";
                // document.getElementById("updateNoteDiv").innerHTML = xmlhttp.responseText;
				$.notific8(xmlhttp.responseText, {
					heading: "Item Editor",
					theme: "ruby",
					life: 3000
				});
            }
        });
    }
}

function deleteTaskActivity(taskid, activityid, deletetype, divname) {
    loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&activityid=" + activityid + "&type=" + deletetype + "&divname=" + divname, function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById(divname).innerHTML = xmlhttp.responseText;
        }
    });
}

function addGoal(taskid, activityid, divname) {
    loadXMLDoc("ajax.php?M=TaskEditor&id=" + taskid + "&activityid=" + activityid + "&goalid=0&type=addgoallist", function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById(divname).value = xmlhttp.responseText;
        }
    });
}

function getGoalList(fieldid, width, height) { 
    window.open("min.php?M=TaskEditor&GoalList=" + fieldid + "&Value=" + document.getElementsByName(fieldid)[0].value, fieldid, "width=" + width + ",height=" + height + ",toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
}

function getProximityList(fieldid, width, height) { 
    window.open("min.php?M=TaskEditor&type=proximitylist", fieldid, "width=" + width + ",height=" + height + ",toolbar=0,location=no,status=0,menubar=0,resizable=0,scrollbars=1");
}

// Load the task data after the page loads to prevent JS from running on the fields


$(document).ready(function() { 
    $("input").change(function() {
        $(this).css("border-color", "rgba(82, 168, 236, 0.8)");
    });
    $("select").change(function() {
        $(this).css("border-color", "rgba(82, 168, 236, 0.8)");
    });
    $("select").each(function() {
        $(this).addClass("form-control input-circle");
    });
    $("input").each(function() {
        $(this).addClass("form-control input-circle");
    });
	$(".btn").each(function() { 
        $(this).addClass("btn-xs"); 
    }); 
});