<?php


/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/


/////////////////////////////////////////////////

// Turn off all error reporting
error_reporting(0);

// DragonFrugal -- Security fix for RFI / XSS Hacking
$get_key = NULL;
$get_value = NULL;
$security_shutdown = NULL;

// GET data
foreach ( $_GET as $get_key => $get_value ) {

	// Key scanning
	if ( eregi("(.*)/(.*)", $get_key) ) {
	$security_shutdown = 1;
	}
	elseif ( $get_key == "set_depth" ) {
	$security_shutdown = 1;
	}
	
	
	// Value scanning
	elseif ( eregi("(.*)/(.*)", $get_value) ) {
	$security_shutdown = 1;
	}


}


$post_key = NULL;
$post_value = NULL;

// POST data
foreach ( $_POST as $post_key => $post_value ) {

	// Key scanning
	if ( eregi("(.*)/(.*)", $post_key) ) {
	$security_shutdown = 1;
	}
	elseif ( $post_key == "set_depth" ) {
	$security_shutdown = 1;
	}
	

}




if ( $security_shutdown ) {


// Logs, emails, etc can be coded here


exit;
}

/////////////////////////////////////////////////




if ( $admin_key ) {
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.add.php");
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.update.php");
}

if ($_POST['validation_attempt'] && $_SESSION['change_data'] && !$_SESSION['update_complete']) {
$alert_status = "<b><font color='red'>Please fill all fields...</font></b>";
}

if ($_GET['change_data'] == "update"
|| $_POST['validation_attempt'] == "update" && $_SESSION['change_data'] != "get_update"
&& !$_SESSION['update_complete']) {
$alert_status = "<b><font color='red'>Please choose data to update below...</font></b>";
}

// Removes the backslash put in front of quotes by the php interpreter
$new_name = eregi_replace ("\\\\\"", "\"", $new_name);
$update_name = eregi_replace ("\\\\\"", "\"", $update_name);
// Single-quotes too...
$new_name = eregi_replace ("\\\\\'", "'", $new_name);
$update_name = eregi_replace ("\\\\\'", "'", $update_name);

// Format for WYSIWYG editing...
$new_name = eregi_replace("<i><b>", "[ib]", $new_name);
$update_name = eregi_replace("<i><b>", "[ib]", $update_name);

$new_name = eregi_replace("</b></i>", "[/ib]", $new_name);
$update_name = eregi_replace("</b></i>", "[/ib]", $update_name);

$new_name = eregi_replace("<i>", "[i]", $new_name);
$update_name = eregi_replace("<i>", "[i]", $update_name);

$new_name = eregi_replace("</i>", "[/i]", $new_name);
$update_name = eregi_replace("</i>", "[/i]", $update_name);

$new_name = eregi_replace("<b>", "[b]", $new_name);
$update_name = eregi_replace("<b>", "[b]", $update_name);

$new_name = eregi_replace("</b>", "[/b]", $new_name);
$update_name = eregi_replace("</b>", "[/b]", $update_name);

$new_name = eregi_replace("<br />\r\n", "\r\n", $new_name);
$update_name = eregi_replace("<br />\r\n", "\r\n", $update_name);

$new_name = eregi_replace("<br />\r", "\r\n", $new_name);
$update_name = eregi_replace("<br />\r", "\r\n", $update_name);

$new_name = eregi_replace("<br />\n", "\r\n", $new_name);
$update_name = eregi_replace("<br />\n", "\r\n", $update_name);

$new_name = eregi_replace("<br />", "\r\n", $new_name);
$update_name = eregi_replace("<br />", "\r\n", $update_name);

$new_name = eregi_replace("&nbsp;", " ", $new_name);
$update_name = eregi_replace("&nbsp;", " ", $update_name);


?>