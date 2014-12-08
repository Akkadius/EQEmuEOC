<?php

	/* 
		dbstr: Akkadius
		Requires - Main File 
		
		Users will be able to upload their own session oriented db_str strings file that may or may not be referenced in other tools
		Here users will be able to individually load up their dbstr_us.txt file, search, edit strings as they see fit like a database and
			be able to export them
	*/
	
	require_once('includes/config.php');
	require_once('includes/constants.php');
	require_once('includes/alla_functions.php');
	require_once('includes/spell.inc.php'); 
	require_once('modules/SpellEditor/functions.php');  
	
	$FJS .= '<script src="modules/dbstr/js/js.js"></script>';
	// $FJS .= '<script src="cust_assets/js/jquery.autosize.min.js"></script>';
	
	/* User dbstr file that is stored based on session ID */
	$user_dbstr = 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt';
	
	if(isset($_GET['dbstr'])){ 
		$file_handle = fopen("l/dbstr_us/dbstr_us.txt", "r");
		while (!feof($file_handle)) {
			$line_of_text = fgets($file_handle);
			/* split or explode on ^ since that is our delimiter */
			$args = explode ("^", $line_of_text);
			
			print "<pre>";
			print print_r($args);
			// foreach ($args as $key => $val){ 
			// 	print trim($val) . " ";
			// }
			print "</pre>";
		}
		fclose($file_handle);
	}

	/* User has dbstr_us.txt uploaded */
	if(file_exists($user_dbstr)){
		echo '<h4 class="page-title"><i class="fa fa-file-text-o"></i> dbstr_us.txt Management</h4><hr>';
		echo FormStart();
		// echo FormInput('Search', "<input type='hidden' name='M' value='ItemEditor'>" . '<input type="text" placeholder="Name, ID or Lore search here..." style="width:500px" value="' . $iname . '" name="iname" class="form-control input-circle"/>', 'Item Type', SelectIType("itype",$itype));
		echo FormInput('Search <i class="fa fa-file-text-o"></i> dbstr_us.txt', '<div class="input-icon">
										<i class="fa fa-search"></i>
										<input type="text" class="form-control" id="search_str" placeholder="Search string" style="width:500px !important" onkeyup="if(event.keyCode == 13){ DoDBStrSearch(); } ">
									</div>');
		echo FormInput('', '
			<a href="javascript:;" class="btn btn-default" onclick="DoDBStrSearch()"><i class="fa fa-search"></i> Search</a><br>
			
			
		');
		echo FormInput('', '<a href="javascript:;" class="btn btn-default green" onclick="DoZipDBStrDownload()"><i class="fa fa-cloud-download"></i> Download</a>');
		echo FormInput('', '<a href="javascript:;" class="btn btn-default red" onclick="DoDBStrDelete()"><i class="fa fa-times"></i> Delete dbstr_us.txt from Server and Upload New</a>');
		echo FormInput('', '<ul>
				<li> <b>::: Spells ::: </b>                          </li>
				<li> #1-12 = Min Value for Spell Effect ID    </li>
				<li> @1-12 = Max Value for Spell Effect ID    </li>
				<li> %z = Duration based on level             </li>
			</ul>');
		echo FormEnd();
		echo '<div id="search_res"></div>'; 
		
	}
	/* User does not have dbstr_us.txt, proceed with upload */
	else{
	
		echo '<link href="assets/global/plugins/dropzone/css/dropzone.css" rel="stylesheet"/>';
		/* $FJS prints at footer in footer.php */ 
		$FJS .= '<script src="assets/global/plugins/dropzone/dropzone.js"></script>';
	
		echo '<h1 class="page-title"><i class="fa fa-file-text-o"></i> dbstr_us.txt Management</h1><hr>';
		echo '<center><h2 class="page-title"> You do not have a dbstr_us.txt uploaded... <br><br>Upload your dbstr_us.txt here<hr><br><br><i class="fa fa-cloud-upload" style="font-size:80px"></i></h2><br></center>';
		
		echo '<form action="upload.php?DoDBSTR" class="dropzone dz-clickable" id="my-dropzone">
			<div class="dz-default dz-message"><span>Drop files here to upload</span></div>
		</form>';
		
		
		$FJS .= '<script type="text/javascript">
			Dropzone.options.myDropzone = {
					maxFiles: 1,
					complete: function() { location.reload(); }, 
					init: function() {
						this.on("addedfile", function(file) {
							if(file.name == "dbstr_us.txt"){ 
								// Create the remove button
								var removeButton = Dropzone.createElement("<button class=\'btn btn-sm btn-block\'>Remove file</button>");
								
								// Capture the Dropzone instance as closure.
								var _this = this;

								// Listen to the click event
								removeButton.addEventListener("click", function(e) { 
								  // Make sure the button click doesn\'t submit the form:
								  e.preventDefault(); 
								  e.stopPropagation();

								  // Remove the file preview.
								  _this.removeFile(file);
								  // If you want to the delete the file on the server as well,
								  // you can do the AJAX request here.
								});

								// Add the button to the file preview element.
								file.previewElement.appendChild(removeButton);
							}else{
								alert("Only dbstr_us.txt is allowed");
								this.removeFile(file); 
							}
						});
					}            
				}
		</script>';
	}
	
?>