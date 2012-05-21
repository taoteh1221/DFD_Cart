<?php

$file_depth = 2;
$security_level = 0;
require("../../main.config.php");

// Set the content-type
header("Content-type: image/png");

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
$font = $set_depth . 'fonts/' . $_GET['style'];

// Add some shadow to the text
imagettftext($im, $text_size, 0, 11, 21, $grey, $font, $text);

// Add the text
imagettftext($im, $text_size, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);
?>
