<?php

$FJS .= '<script type="text/javascript" src="modules/TaskEditor/js/js.js"></script>';
$FJS .= '<script type="text/javascript">window.onload = getTaskSelectList(1);</script>';

echo '<a href="javascript:;" class="btn green" onclick="getTaskSelectList(0)"><i class="fa fa-database"></i> New Task</a>';
echo '<style>.task_display td{ vertical-align:top; padding:2px }</style>';
echo '<table class="task_display">
		<tr><td>
			<div id="TaskListSelect"></div>
		</td>
		<td style="width:30px;vertical-align:middle"><i class="fa fa-arrow-circle-o-right" style="color:#666;font-size:40px"></i></td>
		<td>
			<div id="updateNoteDiv"></div>
		</td>
		
		<td style="width:1000px">
			<div id="taskDiv">
		</td>
		
		</tr>
	</table>';
