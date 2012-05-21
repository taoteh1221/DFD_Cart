<?php

// Grab company name data, as this script is usually called before it's grabbed (on purpose, to refresh the admin's configure page properly)
$company_name = db_data('template_config', 'config_id', 'template', 'company_name');

// If GD and freetype libraries are installed on the server, print fancy text to an image file, otherwise we'll just use plain text to render the company name
if (function_exists("imagettftext")) {
$text_size = 20;

$image_width = round(strlen($company_name) * $text_size);

$image_height = $text_size + 10;

// Create the image
$im = imagecreatetruecolor($image_width, $image_height);

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
$text = $company_name;
// Replace path by your own font path
$font = $set_depth . 'fonts/' . $_POST['company_font_p'];

// Add some shadow to the text
imagettftext($im, $text_size, 0, 11, 21, $grey, $font, $text);


// Add the text
imagettftext($im, $text_size, 0, 10, 20, $black, $font, $text);
// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im, $set_depth . "images/custom/company.name.png", 6);

				if (function_exists("chmod")) {
				chmod ($set_depth . "images/custom/company.name.png", 0666);
				}
			
		// Free the system RAM afterwards
		imagedestroy($im);
		
}

?>