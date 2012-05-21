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




if ($_POST['show_all_data']) {
$show_all_data = $_POST['show_all_data'];
$page = FALSE;
$content = FALSE;
}

if ($_POST['submit_new_data']) {
$submit_new_data = $_POST['submit_new_data'];
$new_name = $_POST['new_name'];
$unit_price = $_POST['unit_price'];
}


if ($_POST['update_current_data']) {
$update_current_data = $_POST['update_current_data'];
$update_price = $_POST['update_price'];
$update_name = $_POST['update_name'];
$update_add_id = $_POST['update_add_id'];
}

if ($_GET['search_data']) {
$search_data = $_GET['search_data'];
$search_price = $_GET['search_price'];
$search_name = remove_backslash($_GET['search_name']);
}

?>