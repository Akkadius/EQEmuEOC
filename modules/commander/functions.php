<?php
	
	function CalcEQHeadingToBrowser($heading){ 
		$heading = (256 - $heading) * 1.40625;  
		return $heading; 
	}
	
	function Draw2DMap($zone, $offline = 0){		
		/* Parse out lines - First Run */
		$offset = 0;
		$max_x = 0; $max_y = 0;
		$min_x = 0; $min_y = 0;
		$handle = fopen("modules/commander/Maps/" . $zone . "_1.txt", "r");
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
		
		/* Topical Additional Offset */
		$top_offset += 65;
		$left_offset += 63;

		$ret = '';

		/* Zoom Icons */
		/*$ret .= '
			<div id="map_controls" style="position:fixed;left:20px;top:100px">
				<button class="btn btn-default Zoom_In"><i class="fa fa-plus"></i></button><br>
				<button class="btn btn-default Zoom_Out"><i class="fa fa-minus"></i></button>
			</div>';*/
		
		$ret .= '<div id="map_canvas">';
		$ret .= '<img src="modules/commander/map.php?zone=' . $zone . '">';
		$sql = "SELECT
				  npc_types.`name`,
				  npc_types.lastname,
				  npc_types.race,
				  spawn2.y,
				  spawn2.x,
				  spawn2.z,
				  spawn2.heading
				FROM
				  (npc_types ,
				  spawn2)
				INNER JOIN spawnentry ON npc_types.id = spawnentry.npcID AND spawnentry.spawngroupID = spawn2.spawngroupID
				where spawn2.zone = '" . mysql_real_escape_string($zone) . "';";
		$result = mysql_query($sql);
		if($offline == 1){ $data = array(); }
		while($row = mysql_fetch_array($result)){
			$ret .= '
				<span style="position:absolute;left:' . ($left_offset - $row['x']) . 'px;top:' . ($top_offset - $row['y']) . 'px" >
					<i class="fa fa-chevron-circle-up" style="color: #333;-webkit-transform:rotate(' . CalcEQHeadingToBrowser($row['heading']) . 'deg);"></i>
					<a href="javascript:;" class="btn btn-default btn-xs entity_name">' . $row['name'] . '</a>
				</span>
			';
		}
		
		/* Parse out map files again */
		for($i = 1; $i <= 2; $i++){  
			/* Parse Out Lines */
			$handle = fopen("modules/commander/Maps/" . $zone . "_" . $i . ".txt", "r");
			if ($handle) {
				while (($line = fgets($handle)) !== false) {
					if(substr($line, 0, 1) == 'P'){
						$line = str_replace('P ', '', $line); 
						$ar = explode(",", $line); 
						$marker_text = $ar[7]; $marker_text = str_replace('_', ' ', $marker_text);
						$marker_text = ucwords($marker_text); 
						$ret .= '<span class="entity_name landmark" style="position:absolute;left:' . ($left_offset + $ar[0])  . 'px;top:' . ($top_offset + $ar[1]) . 'px"><i class="fa fa-map-marker pulse" style="color:#FF0000 !important; font-size:20px"></i>' . $marker_text . '</span>';
						// imagestring($img, 8, $ar[0] + abs($min_x + $offset), $ar[1] + abs($min_y + $offset), $ar[7], $marker_color);
					}
				}
			}
			fclose($handle);
		}
		
		$ret .= '<span class="entity_name" style="position:absolute;left:' . ($left_offset + 0)  . 'px;top:' . ($top_offset + 0) . 'px"><i class="fa fa-map-marker pulse" style="color:#FF0000 !important; font-size:20px"></i>(0, 0) x, y</span>';
		
		$ret .= '<div>';

		$ret .= "<script type='text/javascript'>
			var difference_x = '" . $difference_x . "';
			var difference_y = '" . $difference_y . "';
			var left_offset = '" . $left_offset . "';
			var top_offset = '" . $top_offset . "';
		</script>";
		return $ret;
	}
	
	function imagesmoothline ( $image , $x1 , $y1 , $x2 , $y2 , $color ) {   
		$colors = imagecolorsforindex ( $image , $color );
		if ( $x1 == $x2 ) {
			imageline ( $image , $x1 , $y1 , $x2 , $y2 , $color ); // Vertical line
		}
		else {
			$m = ( $y2 - $y1 ) / ( $x2 - $x1 );
			$b = $y1 - $m * $x1;
			if ( abs ( $m ) <= 1 ) {
				$x = min ( $x1 , $x2 );
				$endx = max ( $x1 , $x2 );
				while ( $x <= $endx ) {
					$y = $m * $x + $b;
					$y == floor ( $y ) ? $ya = 1 : $ya = $y - floor ( $y );
					$yb = ceil ( $y ) - $y;
					$tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , floor ( $y ) ) );
					$tempcolors['red'] = $tempcolors['red'] * $ya + $colors['red'] * $yb;
					$tempcolors['green'] = $tempcolors['green'] * $ya + $colors['green'] * $yb;
					$tempcolors['blue'] = $tempcolors['blue'] * $ya + $colors['blue'] * $yb;
					if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
					imagesetpixel ( $image , $x , floor ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
					$tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , $x , ceil ( $y ) ) );
					$tempcolors['red'] = $tempcolors['red'] * $yb + $colors['red'] * $ya;
					$tempcolors['green'] = $tempcolors['green'] * $yb + $colors['green'] * $ya;
					$tempcolors['blue'] = $tempcolors['blue'] * $yb + $colors['blue'] * $ya;
					if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
					imagesetpixel ( $image , $x , ceil ( $y ) , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
					$x ++;
				}
			}
			else {
				$y = min ( $y1 , $y2 );
				$endy = max ( $y1 , $y2 );
				while ( $y <= $endy ) {
					$x = ( $y - $b ) / $m;
					$x == floor ( $x ) ? $xa = 1 : $xa = $x - floor ( $x );
					$xb = ceil ( $x ) - $x;
					$tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , floor ( $x ) , $y ) );
					$tempcolors['red'] = $tempcolors['red'] * $xa + $colors['red'] * $xb;
					$tempcolors['green'] = $tempcolors['green'] * $xa + $colors['green'] * $xb;
					$tempcolors['blue'] = $tempcolors['blue'] * $xa + $colors['blue'] * $xb;
					if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
					imagesetpixel ( $image , floor ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
					$tempcolors = imagecolorsforindex ( $image , imagecolorat ( $image , ceil ( $x ) , $y ) );
					$tempcolors['red'] = $tempcolors['red'] * $xb + $colors['red'] * $xa;
					$tempcolors['green'] = $tempcolors['green'] * $xb + $colors['green'] * $xa;
					$tempcolors['blue'] = $tempcolors['blue'] * $xb + $colors['blue'] * $xa;
					if ( imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) == -1 ) imagecolorallocate ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] );
					imagesetpixel ( $image , ceil ( $x ) , $y , imagecolorexact ( $image , $tempcolors['red'] , $tempcolors['green'] , $tempcolors['blue'] ) );
					$y ++;
				}
			}
		}
	}
?>