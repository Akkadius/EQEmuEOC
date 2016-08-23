<?php

	require_once('../../includes/config.php');
	require_once('../../includes/functions.php');
	require_once('functions.php');
	
	/* Open File */
	
	/* Example Source Data:
		L 16.0000, -19.0000, 0.0000,  10.0000, -22.0000, 0.0000,  153, 153, 153
		L 10.0000, -22.0000, 0.0000,  23.0000, -35.0000, 0.0000,  153, 153, 153
		L 23.0000, -35.0000, 0.0000,  7.0000, -50.0000, 0.0000,  153, 153, 153
		L 7.0000, -50.0000, 0.0000,  -7.0000, -50.0000, 0.0000,  153, 153, 153
		L -7.0000, -50.0000, 0.0000,  -22.0000, -35.0000, 0.0000,  153, 153, 153
		L -22.0000, -35.0000, 0.0000,  -9.0000, -22.0000, 0.0000,  153, 153, 153
		L -9.0000, -22.0000, 0.0000,  -14.0000, -15.0000, 0.0000,  153, 153, 153
		
		x, y, z (Start) x, y, z (End) r, g, b (Color)
	*/
	
	/* bool imageline ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color ) */

	/* Parse out lines - First Run */
	$max_x = 0; $max_y = 0;
	$min_x = 0; $min_y = 0;
	$handle = fopen("Maps/" . $_GET['zone'] . "_1.txt", "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			/* Parse Out Lines */
			if(substr($line, 0, 1) == 'L'){
				$line = str_replace('L ', '', $line);
				$ar = explode(",", $line);				
				/* Look at X coordinates */
				if($ar[0] > $max_x){ $max_x = $ar[0]; } if($ar[3] > $max_x){ $max_x = $ar[3]; }
				if($ar[0] < $min_x){ $min_x = $ar[0]; } if($ar[3] < $min_x){ $min_x = $ar[3]; }
				/* Look at Y coordinates */
				if($ar[1] > $max_y){ $max_y = $ar[1]; } if($ar[4] > $max_y){ $max_y = $ar[4]; }
				if($ar[1] < $min_y){ $min_y = $ar[1]; } if($ar[4] < $min_y){ $min_y = $ar[4]; }
			}
		}
	}
	$difference_x = (abs($max_x) + abs($min_x));
	$difference_y = (abs($max_y) + abs($min_y));
	$left_offset = abs($min_x + $offset);
	$top_offset = abs($min_y + $offset);
	fclose($handle);
	/* End Parse out lines */
	
	
	$padding = 100;
	$img = imagecreatetruecolor($difference_x + $padding, $difference_y + $padding);
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);
	$sand = imagecolorallocate($img, 255, 249, 144);
	$metro_gray = imagecolorallocate($img, 102, 102, 102);
	$metro_gray_2 = imagecolorallocate($img, 51, 51, 51);

	#::: Dark UI Style
	if($_SESSION['UIStyle'] == 2){
		$line_color = $white;
		$marker_color = $white;
		imagefill($img, 0, 0, $metro_gray_2);
	}
	#::: Light UI Style (Default)
	else{
		$line_color = $metro_gray;
		$marker_color = $black;
		imagefill($img, 0, 0, $white);
	}

	
	
	# print "MAX X " . $max_x . "<BR>";
	# print "MAX Y " . $max_y . "<BR>";
	# print "MIN X " . $min_x . "<BR>";
	# print "MIN Y " . $min_y . "<BR>";
	# print "DIFFERENCEX " . $difference_x . "<BR>";
	# print "DIFFERENCEY " . $difference_y . "<BR>";
	# print "Left offset " . $left_offset . "<br>"; 
	# print "Top offset " . abs($min_y + $offset) . "<br>"; 
	# 
	# exit;
	
	/* Parse Out Descriptions 
		P -158.0000, 644.0000, 0.0000,  255, 255, 255,  2,  Succor_Point
		P -114.8470, 163.5500, 84.9460,  127, 0, 0,  2,  Orc_Trainer
		P -118.8650, 212.1990, 80.1880,  0, 0, 0,  3,  Trainer_Hill
		P 95.1710, 78.4270, 0.0010,  0, 0, 0,  3,  North_Wall
		P 316.6940, -94.5960, 0.0010,  0, 0, 0,  2,  Safe_Spot
		P 213.2900, -134.7010, -13.2230,  0, 0, 0,  2,  Slaver_Pits
		P 150.7150, -238.0460, 0.0010,  127, 0, 0,  2,  Orc_Taskmaster
		x,y,z r,g,b ? description
	*/
	
	for($i = 1; $i <= 2; $i++){ 
		/* Parse Out Lines */
		$handle = fopen("Maps/" . $_GET['zone'] . "_" . $i . ".txt", "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				if(substr($line, 0, 1) == 'L'){
					$line = str_replace('L ', '', $line); 
					$ar = explode(",", $line);
					imagesmoothline ($img, $ar[0] + abs($min_x + $offset), $ar[1] + abs($min_y + $offset), $ar[3] + abs($min_x + $offset), $ar[4] + abs($min_y + $offset), $line_color);
					# imageline($img, $ar[0] + abs($min_x + $offset), $ar[1] + abs($min_y + $offset), $ar[3] + abs($min_x + $offset), $ar[4] + abs($min_y + $offset), imagecolorallocate($img, $ar[6], $ar[7], $ar[8]));
				}
				if(substr($line, 0, 1) == 'P'){
					$line = str_replace('P ', '', $line); 
					$ar = explode(",", $line);
					// imagestring($img, 8, $ar[0] + abs($min_x + $offset), $ar[1] + abs($min_y + $offset), $ar[7], $marker_color);
				}
			}
		}
		fclose($handle);
	}
	
	fclose($handle);
	
	header("Content-type: image/png");
	imagepng($img);
	
?>