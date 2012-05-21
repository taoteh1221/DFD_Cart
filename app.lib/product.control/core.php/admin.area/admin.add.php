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



security_check($admin_key); // Verify this appears to be an administrator editing the database

require ("".$set_depth."app.lib/product.control/core.php/globals.php");


// Adding a new product

if ($_POST['submit_new_data'] && $_POST['new_name'] && $_POST['unit_price']) {


$new_name = eregi_replace("	", "", $new_name); // Failsafe TAB removal
// Format xhtml tags into WYSIWYG editor...
$new_name = eregi_replace("\[ib]", "<i><b>", $new_name);
$new_name = eregi_replace("\[/ib]", "</b></i>", $new_name);
$new_name = eregi_replace("\[b]", "<b>", $new_name);
$new_name = eregi_replace("\[/b]", "</b>", $new_name);
$new_name = eregi_replace("\[i]", "<i>", $new_name);
$new_name = eregi_replace("\[/i]", "</i>", $new_name);
$new_name = eregi_replace("\r\n\r\n", "<p></p>", $new_name);
$new_name = eregi_replace("\r\n", "<br />\r\n", $new_name);
$new_name = eregi_replace("  ", "&nbsp;&nbsp;", $new_name);

$product_id = strip_name_format($_POST['product_id_p']);

$unit_price = eregi_replace("\\\$", "", $unit_price); // Remove any dollar sign
$unit_price = eregi_replace(" ", "", $unit_price); // Remove spaces

// Connect to DB if database-contents-request is posted...


// To-be-added form post variables into this sting, to put in DB
/*
Format XX.XX for price search compatibility, but with no commas above 3 digits to the left of the decimal point, ***so order calculations aren't ruined***
*/

// Interact with DB
mysql_query("INSERT INTO product_list VALUES ('', '$new_name', '$product_id', '".sprintf("%01.2f", $unit_price)."', '".$_POST['category']."', '')");


$added_product_data = mysql_query("SELECT * FROM product_list WHERE product_name='$new_name' AND unit_price='".sprintf("%01.2f", $unit_price)."'");

$update_add_id = mysql_result($added_product_data, 0, "id");  // Include listing new products as a summary afterwards, like when updating them

$alert_status = "<b><font color='red'>Record Added...</font></b>";
$_SESSION['update_complete'] = "yes";


$new_name = NULL;
$unit_price = NULL;


}



// Adding a new category

elseif ( $_POST['submit_new_data'] && $_POST['create_category_name'] ) {


// Interact with DB
mysql_query("INSERT INTO category_structure VALUES ('', '".$_POST['create_category_name']."', '".$_POST['category']."', '".date('m / d / Y')."')") or die('Invalid query: ' . mysql_error());


$alert_status = "<b><font color='red'>Category Added...</font></b>";
$_SESSION['update_complete'] = "yes";

header("location: $set_depth" . "admin/index.php?key=".$_SESSION['sec_key']."&category=" . $_POST['return_category']);

}


?>