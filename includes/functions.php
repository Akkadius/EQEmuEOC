<?php

	function PageTitle($PT){
		global $App_Title;  
		echo '<script type="text/javascript">document.title = "[' . $App_Title . '] ' . $PT . '"; </script>';
	}

	function FormStart(){
        return '<div class="portlet-body form form-horizontal form-bordered form-row-stripped" >';
    }
	function FormEnd(){
        return '</div>';
    }
	function FormInput($Label, $Input){ 
		return '<div class="form-group">
			<label class="col-md-2 control-label">' . $Label . '</label>
			<div class="col-md-6"> <div class="input-group"> ' . $Input . ' </div> </div>
		</div>'; 
	}
	/* HREF Initiatior Example:
		<a class="btn blue ajax-modal" modalurl="global.php?spellview=' . $FieldData .  '">View</a>
	*/
	function Modal($Title, $Content, $Buttons){ 
		return '<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h3 class="modal-title">' . $Title . '</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					' . $Content . '
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn default" data-dismiss="modal">Close</button>
			' . $Buttons .'
		</div>';
	}
	function HoverTip($url){
		return " hovertip-url='" . $url . "' hovertip-hidemouseout='1' ";
	}
	function HoverCloseTip($url){
		return " hovertip-close-url='" . $url . "' hovertip-hidemouseout='1' ";
	}
	
	function SectionHeader($data, $desc = ''){
		if($desc != ''){ $a_desc = '<br><small>' . $desc . '</small>'; }
		return '<div class="alert alert-warning alert-dismissable"><h4 style="display:inline;font-weight:bold">' . $data . '</h4>'. $a_desc . '</div>';
	}

	/* Global Scoped Functions */
	
?>