<?php
		
		// Grab logo image data, as this script is usually called before it's grabbed (on purpose, to refresh the admin's configure page properly)
		$logo_image = $set_depth . "images/custom/" . db_data('template_config', 'config_id', 'template', 'logo_image');
		
		
		$new_logo = $set_depth . "images/custom/" . $_FILES['import_file']['name'];
		
		echo $new_logo."<br>";
		echo $logo_image."<br>";
		
			if ( $new_logo != $logo_image ) {
			unlink($logo_image);
			}
		
		$max_width_dimension = 240;
		$max_height_dimension = 170;
		$jpeg_quality = 93;
		
		
		$filename = $_FILES['import_file']['tmp_name'];
		
		// Get new dimensions
		list($width, $height) = getimagesize($filename);
		
		
		if ( $width > $max_width_dimension || $height > $max_height_dimension ) {
		
			if ( $width > $height ) {
			
			$find_height = $width / $max_width_dimension;
			$new_height = round($height / $find_height);
			
			$new_width = $max_width_dimension;
			
				if ( $new_height > $max_height_dimension ) {
				
				$find_width = $height / $max_height_dimension;
				$new_width = round($width / $find_width);
				
				$new_height = $max_height_dimension;
				
				}
			
			}
			
			
			else {
			
			$find_width = $height / $max_height_dimension;
			$new_width = round($width / $find_width);
			
			$new_height = $max_height_dimension;
			
				if ( $new_width > $max_width_dimension ) {
				
				$find_height = $width / $max_width_dimension;
				$new_height = round($height / $find_height);
				
				$new_width = $max_width_dimension;
				
				}
			
			}
			
		}
		else {
		$new_width = $width;
		$new_height = $height;
		}
		
			// Calculate centering of image in slideshow
			$from_top = round($max_height_dimension - $new_height);
			if ( $from_top > 0 ) {
			$from_top = round($from_top / 2) - 1;
			}
			
			
			$from_left = round($max_width_dimension - $new_width);
			if ( $from_left > 0 ) {
			$from_left = round($from_left / 2) - 1;
			}
			
			
			// Output
			
			if ( $filename ) {
			
			// Resample
			$image_p = imagecreatefromjpeg($set_depth . 'images/php/new.jpg');
			$black = imagecolorallocate($image_p, 0, 0, 0);
			$white = imagecolorallocate($image_p, 255, 255, 255);
			
			
			if ( eregi("(.*).jpg", $_FILES['import_file']['name']) ) {
			$image = imagecreatefromjpeg($filename);
			}
			if ( eregi("(.*).png", $_FILES['import_file']['name']) ) {
			$image = imagecreatefrompng($filename);
			}
			if ( eregi("(.*).gif", $_FILES['import_file']['name']) ) {
			$image = imagecreatefromgif($filename);
			}
			
			
			imagecopyresampled($image_p, $image, $from_left, $from_top, 0, 0, $new_width, $new_height, $width, $height);
			
			
			
			imagejpeg($image_p, $new_logo, $jpeg_quality);
			
				if (function_exists("chmod")) {
				chmod ($new_logo, 0666);
				}
			
			}
		
		mysql_query("UPDATE template_config SET logo_image = '".$_FILES['import_file']['name']."' WHERE config_id = 'template'");
		
		$logo_image = $new_logo;
		
		// Free the system RAM afterwards
		imagedestroy($image_p);
		imagedestroy($image);
?>