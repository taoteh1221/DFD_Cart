<?
/*
	This is PHP file that generates CAPTCHA image for the How to Create CAPTCHA Protection using PHP and AJAX Tutorial

	You may use this code in your own projects as long as this 
	copyright is left in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	
	For the rest of the code visit http://www.WebCheatSheet.com
	
	Copyright 2006 WebCheatSheet.com	

*/

// Start the session so we can store what the security code actually is
if (!$_SESSION)
{ session_start();
}



//Send a generated image to the browser 
create_image(); 
exit(); 

function create_image() 
{ 
    //Let's generate a totally random string using md5 
    $md5_hash = md5(rand(0,999)); 
    //We don't need a 32 character long string so we trim it down to 5 
    $security_code = strtolower(substr($md5_hash, 15, 6)); 
    $security_code = eregi_replace("0", "r", $security_code); // Zero
    $security_code = eregi_replace("1", "z", $security_code); // One
    $security_code = eregi_replace("6", "x", $security_code); // Six
    $security_code = eregi_replace("9", "h", $security_code); // Nine
    $security_code = eregi_replace("i", "f", $security_code); // Letter i
    $security_code = eregi_replace("l", "s", $security_code); // Letter l
    $security_code = eregi_replace("o", "w", $security_code); // Letter o

    //Set the session to store the security code
	if ( $_SESSION["lock_security_code"] ) {
	$_SESSION["security_code"] = $_SESSION["lock_security_code"];
	}
	else {
	$_SESSION["security_code"] = $security_code;
	}

    //Set the image width and height 
    $width = 62; 
    $height = 20;  

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray 
    $white = ImageColorAllocate($image, 255, 255, 255); 
    $black = ImageColorAllocate($image, 0, 0, 0); 
    $grey = ImageColorAllocate($image, 204, 204, 204); 

    //Make the background black 
    ImageFill($image, 0, 0, $black); 

    //Add randomly generated string in white to the image
    ImageString($image, 6, 4, 1, $_SESSION["security_code"], $white); 
	$_SESSION["lock_security_code"] = FALSE;

    //Throw in some lines to make it a little bit harder for any bots to break 
    //ImageRectangle($image,0,0,$width-1,$height-1,$grey); 
    //imageline($image, 0, $height/2, $width, $height/2, $grey); 
    //imageline($image, $width/2, 0, $width/2, $height, $grey); 
 
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/jpeg"); 

    //Output the newly created image in jpeg format 
    ImageJpeg($image); 
    
    //Free up resources
    ImageDestroy($image); 
} 
?>