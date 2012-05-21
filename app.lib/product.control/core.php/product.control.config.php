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



// Version of DFD Cart
$dfd_cart_version = "1.22";


// Make a MySQL Connection
$dfd_db_connect = mysql_connect($product_db_host, $product_db_username, $product_db_password) or die(mysql_error());
@mysql_select_db($product_db_database) or die ("<b><font color='red'>Unable to select database...<br>Please contact the webmaster</font></b>");


// Let the session start now...
if (!$_SESSION)
{ session_start();
}

$max_imported = $max_imported + 1;  // Make room for possible spreadsheet header

// MUST BE BELOW "file_depth" CONFIGURATION...



// Check that the version of php on this server supports DFD Cart
$php_build = substr_replace(phpversion(), '', 3);
if ( $php_build < "4.2" ) {
echo "<div align='center' style='padding: 75px;'><font color='#FF0000'><b>Sorry, PHP 4.2 or greater is required to run DFD Cart.<br />(Your are running version $php_build)</b></font></div>";
exit;
}



// Grab the classes and stand-alone functions

require ("".$set_depth."app.lib/product.control/core.php/functions.php");

require ("".$set_depth."app.lib/product.control/core.php/classes/product.ordering.php");

require ("".$set_depth."app.lib/product.control/core.php/classes/form.mail.php");



// Fire up the classes

$product_control_class = & new product_control;

$form_mail_class = & new form_mail;


require("".$set_depth."app.lib/security/security.php"); // ABOVE ALL APPLICATION FILES

$page_parse = strrev($_SERVER['PHP_SELF']); 
$page_parse = strstr($page_parse, "/");
$page_parse = strrev($page_parse); 
$page_parse = preg_replace("/\/admin/i","", $page_parse); 
$url_base = find_host().$page_parse;

//echo $url_base;

// Refresh when on admin configure page
if ( eregi("(.*)/configure.php", $_SERVER['PHP_SELF']) ) {
require ("".$set_depth."app.lib/product.control/core.php/admin.area/update.configuration.php");
}



// Database values


// Load admin config, adjust anything needed
$admin_config = db_connect('admin_config', 'select', '', '', "WHERE config_id='general_config'");

$delivery_earliest = $admin_config['delivery_earliest'] * 24;





// Font sizes
$selected_font_size = db_data('template_config', 'config_id', 'template', 'font_size');
$font_1 = db_data('template_config', 'config_id', 'template', 'font_1') + $selected_font_size;
$font_2 = db_data('template_config', 'config_id', 'template', 'font_2') + $selected_font_size;
$font_3 = db_data('template_config', 'config_id', 'template', 'font_3') + $selected_font_size;
$font_4 = db_data('template_config', 'config_id', 'template', 'font_4') + $selected_font_size;
$font_5 = db_data('template_config', 'config_id', 'template', 'font_5') + $selected_font_size;
$font_6 = db_data('template_config', 'config_id', 'template', 'font_6') + $selected_font_size;
$font_7 = db_data('template_config', 'config_id', 'template', 'font_7') + $selected_font_size;
$font_8 = db_data('template_config', 'config_id', 'template', 'font_8') + $selected_font_size;
$font_9 = db_data('template_config', 'config_id', 'template', 'font_9') + $selected_font_size;

// Business name
$company_name = db_data('template_config', 'config_id', 'template', 'company_name');

// Business font
$company_font = db_data('template_config', 'config_id', 'template', 'company_font');

// Business logo
$logo_image = $set_depth . "images/custom/" . db_data('template_config', 'config_id', 'template', 'logo_image');

// Template wrap width
$template_wrap = db_data('template_config', 'config_id', 'template', 'template_wrap');


 


//////////////////////////////

// Build the arrays needed to parse the category tree and render, manipulate, etc...

$_SESSION['category_array'] = array('');
$_SESSION['category_path_check'] = array('');
$_SESSION['category_path_find'] = array('');
categories_refresh();


$_SESSION['depth_history'] = array('');

// Find every directory that contains sub-directories and add it to a new array...
$parent_category_array = array();
foreach ( $_SESSION['category_array'] as $mark_parent_category ) {

	if ( $mark_parent_category['parent_category_id'] > 0 ) {
	$parent_category_array[] = $mark_parent_category['parent_category_id'];
	}

}



// Debugging
//$_SESSION['product_orders'] = FALSE;



?>