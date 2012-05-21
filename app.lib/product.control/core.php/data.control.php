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



if ( $_SESSION['alert_status'] ) {
$alert_status = $_SESSION['alert_status'];
$_SESSION['alert_status'] = FALSE;
}

if ( !$_SESSION['list_quantity'] ) {
$_SESSION['list_quantity'] = 50;  // Set to default
}


$_SESSION['category'] = 0;  // Set to default

$_SESSION['list_location'] = 0;  // Default


if ( $_GET['category'] ) {
$_SESSION['category'] = $_GET['category'];
}

if ( $_GET['list_location'] ) {
$_SESSION['list_location'] = $_GET['list_location'];
}

if ( $_GET['list_quantity'] ) {
$_SESSION['list_quantity'] = $_GET['list_quantity'];
}


// For GUI summaries in the product lists
$list_start = $_SESSION['list_location'] + 1;
$list_end = $_SESSION['list_location'] + $_SESSION['list_quantity'];


//////////////////////////////////////////////////////////////////////////
// For parsing the desired range of product database rows (determining $_SESSION['split'] value)
if ( $_SESSION['list_location'] < 1 ) {
$sql_row_list = "LIMIT " . $_SESSION['list_quantity'];
}
else {
$sql_row_list = "LIMIT " . $_SESSION['list_location'] . "," . $_SESSION['list_quantity'];
}


// Connect to DB to grab product count total...

if ( $_SESSION['category'] == 0 ) {
$search_formatting = " WHERE ";
}
else {
$search_formatting = " AND ";
}


if ( trim($search_name) ) {

$search_array0 = explode(" ", trim($search_name));

	if ( sizeof($search_array0) > 1 ) {
	
		foreach( $search_array0 as $keyword ) {
		$sql_keywords0 .= " product_name LIKE '%$keyword%' AND ";
		}
	
	$sql_keywords0 = substr_replace($sql_keywords0, '', -4, 4);
	$sql_keywords0 = " product_name LIKE '%".trim($search_name)."%' || " . $sql_keywords0;
	}
	else {
	$sql_keywords0 = " product_name LIKE '%".trim($search_name)."%' ";
	}

}


if (trim($search_name) && $search_price) {
$product_search = $search_formatting . "$sql_keywords0 || product_id LIKE '%".trim($search_name)."%' AND unit_price <= ".sprintf("%01.2f", $search_price)."";
}
elseif ($search_price && !trim($search_name)) {
$product_search = $search_formatting . "unit_price <= ".sprintf("%01.2f", $search_price)."";
}
elseif (!$search_price && trim($search_name)) {
$product_search = $search_formatting . "$sql_keywords0 || product_id LIKE '%".trim($search_name)."%'";
}


$product_data = "SELECT * FROM product_list";

if ( $update_add_id ) {
$product_data .= " WHERE id='$update_add_id'";
}
elseif ( !$update_add_id && $_SESSION['category'] > 0 ) {
$product_data .= " WHERE parent_category_id = '".$_SESSION['category']."'$product_search";
}


if ( $_GET['search_data'] ) {
$search_subcategories_array = all_subcategories($_SESSION['category']);

	if ( $search_subcategories_array ) {
	$include_in_search = 1;
	}

}


// If this category contains subcategories, include all those products in search results too
if ( $include_in_search && $_SESSION['category'] > 0 ) {

	foreach ( $search_subcategories_array as $include_key => $include_value ) {
	
	$product_data .= " OR parent_category_id = '$include_value'$product_search";
	
	}

}
elseif ( $product_search ) {
$product_data .= $product_search;
}


//echo $product_data . "<p></p>";  // Debugging database query string

$database_query = mysql_query($product_data);

$product_num = mysql_numrows($database_query);




// Find the split point to display the appropriate set of numbered nav links
$location_range = $_SESSION['list_quantity'] * $max_number_links;

//$find_link_number = $product_num / $_SESSION['list_quantity'];
//$find_link_number = ceil($find_link_number);

$split_range = $product_num / $location_range;
$split_range = ceil($split_range);

$loop = 0;
$loop_math = 1;
while ( $loop < $split_range ) {
$split_start = $location_range * $loop_math - $location_range;
$split_end = $location_range * $loop_math;

	if ( $_SESSION['list_location'] >=  $split_start
	&& $_SESSION['list_location'] <  $split_end ) {
	$_SESSION['split'] = $loop;
	}

$loop_math = $loop_math + 1;
$loop = $loop + 1;
}

//$_SESSION['split'] = 2;  // Debugging

// Done determining $_SESSION['split'] value
/////////////////////////////////////////////////////////////////////

$_SESSION['previous_location'] = $_SESSION['list_location'] - $_SESSION['list_quantity'];
$_SESSION['next_location'] = $_SESSION['list_location'] + $_SESSION['list_quantity'];


if ( $_GET['search_data'] || $_POST['show_all_data'] || $_GET['delete']
|| $_SESSION['update_complete'] ) {
$_SESSION['change_data'] = FALSE;
}

if ($_GET['change_data']) {
$_SESSION['change_data'] = $_GET['change_data'];
$_SESSION['update_complete'] = FALSE;
}

if ($_GET['update']) {
$_SESSION['change_data'] = "get_update";
$_SESSION['update_complete'] = FALSE;
}

if ($_GET['no_data_change']) {
$_SESSION['change_data'] = FALSE;
$_SESSION['update_complete'] = "yes";
}


?>