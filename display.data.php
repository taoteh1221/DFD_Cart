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





if ( $_GET['product_data'] ) {
$file_depth = 0;
$security_level = 0;
require ("main.config.php");
$product_data = $_GET['product_data'];
}

if ( !$product_data ) {
echo "No product data chosen to display";
}

// Connect to DB if database-contents-request is posted...

$db_data = mysql_query("SELECT * FROM product_list WHERE id='$product_data'");


$display_product_name = mysql_result($db_data, $i, "product_name");
$display_price = mysql_result($db_data, $i, "unit_price");

if ( $_GET['product_data'] ) {
echo "<b>Description:</b> " . $display_product_name . "&nbsp;&nbsp;&nbsp;<b>Price:</b> \$" . number_format($display_price, 2);
}

?>