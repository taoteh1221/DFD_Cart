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




if (!$_SESSION)
{ session_start();
}



$file_depth = 0;
$security_level = 0;
require("main.config.php");



// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");


if ( $_REQUEST['db_id'] && $_SESSION['order_alert'] != 'empty' ) {

$_REQUEST["custom_1"] = preg_replace("/ESCAPE_AMPERSAND/i", "&", $_REQUEST["custom_1"]);
$_REQUEST["custom_2"] = preg_replace("/ESCAPE_AMPERSAND/i", "&", $_REQUEST["custom_2"]);

$product_control_class -> update_products($_REQUEST['db_id'], $_REQUEST['dyn_prod_qty'], '', $_REQUEST["custom_1"], $_REQUEST["custom_2"]);
echo $_SESSION['order_total'];
}
elseif ( $_SESSION['order_alert'] == 'empty' ) {
$_SESSION['product_orders'] = FALSE;
echo '***PRODUCTS WERE JUST MASS-UPDATED BY ADMIN - WE ARE VERY SORRY, BUT ALL ITEMS MUST BE RE-ADDED***';
$_SESSION['order_alert'] = FALSE;
}


?>