<?php
	/* This is where things are uploaded and stuff */

	/* Upload dbstr_us.txt */
	if(isset($_GET['DoDBSTR'])){ 
		echo '<pre>';
		if($_FILES['file']['name'] == "dbstr_us.txt" && $_FILES['file']['type'] == "text/plain"){
			if (move_uploaded_file($_FILES['file']['tmp_name'], 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt')) { 
				echo "File is valid, and was successfully uploaded.\n"; 
			} 
			else {
				echo "Possible file upload attack!\n";
			}
			echo 'Here is some more debugging info:';
		}
		else{
			echo 'Error, file is not dbstr_us.txt!!!'; 
		}
		print "</pre>";
	}
	
?>