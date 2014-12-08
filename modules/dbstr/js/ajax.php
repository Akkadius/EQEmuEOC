<?php

	/* Search DB Str Function */
	if(isset($_GET['search_dbstr'])){ 
		$user_dbstr = 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt';
		$file_handle = fopen($user_dbstr, "r");
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer" style="width:100%;">';
		$line = 0;
		while (!feof($file_handle)) {
			$line_of_text = fgets($file_handle);
			if(preg_match('/' . $_GET['search_str'] . '/i', $line_of_text)){
				echo '<tr>';
				$args = explode ("^", $line_of_text);
				foreach ($args as $key => $val){ 
					print '<td line-id="' . $line . '" arg-num="' . $key . '">' . trim($val) . '</td>';
				}
				echo '</tr>';
				// print $line_of_text . '<br>';
			}
			$line++;
		}
		echo '</table>';
		fclose($file_handle);
		
		echo '<script type="text/javascript">
			$( "table td" ).unbind("mouseenter"); 
			$( "table td" ).bind("mouseenter", function() {
				// console.log("Hovering in");
				lineid = $(this).attr("line-id");
				argnum = $(this).attr("arg-num");
				width = $(this).css("width");
				height = $(this).css("height");
				data = $(this).html();
				$(this).html(\'<textarea type="text" class="form-control" onchange="DoDBStrEdit(this.value, lineid, argnum)">\' + data + \'</textarea>\');
				$(this).children("textarea").attr(\'size\', $(this).children("textarea").val().length);
				$(this).children("textarea").css(\'width\', (parseInt(width) + 10));
				$(this).children("textarea").css(\'height\', (parseInt(height) - 2));
				// $(\'textarea\').autosize();   
				data = ""; 
			});
			$( "table td" ).unbind("mouseleave");
			$( "table td" ).bind("mouseleave", function() {
				data = $(this).children("textarea").val(); 
				$(this).html(data); 
				data = ""; 
			});
		</script>';
	}
	if(isset($_GET['dbstr_line_edit'])){ 
		$user_dbstr = 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt';
		// echo var_dump($_GET);
		// array(4) { ["M"]=> string(5) "dbstr" ["dbstr_line_edit"]=> string(0) "" ["line"]=> string(3) "588" ["argnum"]=> string(1) "2"}
		$reading = fopen($user_dbstr, 'r');
		$writing = fopen($user_dbstr . '.tmp', 'w'); 

		$replaced = false;

		$n = 0;
		while (!feof($reading)) {
			$line = fgets($reading);
			$args = explode ("^", $line);
			if($n == $_GET['line']){
				// echo $args[$_GET['argnum']];
				$line = str_replace($args[$_GET['argnum']], $_GET['replace_text'] , $line); 
			}
			$replaced = true; 
			fputs($writing, $line);
			$n++;
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename($user_dbstr . '.tmp', $user_dbstr);
		} 
		else {
			unlink($user_dbstr . '.tmp');
		}
	}
	if(isset($_GET['del_dbstr'])){
		$user_dbstr = 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt';
		unlink($user_dbstr);
	}
	/* Create Zip Download Delete */
	if(isset($_GET['ZipDownloadD'])){
		unlink('l/dbstr_us/dbstr_us.zip'); 
	}
	/* Create Zip Download */
	if(isset($_GET['ZipDownload'])){
		$user_dbstr = 'l/dbstr_us/dbstr_us_' . $_COOKIE['SESS_ID'] . '.txt';
		/*
			* PHP ZIP - Create a ZIP archive
		*/

		$zip = new ZipArchive;
		if ($zip->open('l/dbstr_us/dbstr_us.zip',  ZipArchive::CREATE)) {
			$zip->addFile($user_dbstr, 'dbstr_us.txt');
			$zip->close();
			echo 'Archive created!';
		} 
		else {
			echo 'Failed!';
		}
	}
?>