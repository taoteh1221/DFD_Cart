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





// Editing admin info...
if ( $_POST['edit_config'] ) {

		// Update company logo
		if ( $_FILES['import_file']['tmp_name'] ) {
		
		
		require ("".$set_depth."app.lib/product.control/core.php/admin.area/logo.manipulation.php");
		
		
		}
		
		
	foreach ( $_POST as $key => $value ) {
	
	// Connect to DB if database-contents-request is posted...
	
	
		// Update company name
		if ( $_POST['company_name_p'] ) {
		mysql_query("UPDATE template_config SET company_name = '".$_POST['company_name_p']."' WHERE config_id = 'template'");
		}
		
		if ( $_POST['company_font_p'] ) {
		mysql_query("UPDATE template_config SET company_font = '".$_POST['company_font_p']."' WHERE config_id = 'template'");
		
		require ("".$set_depth."app.lib/product.control/core.php/admin.area/cache.company.font.php");
		}
		
		
		// Overall listing width
		if ( $_POST['template_wrap_p'] ) {
		mysql_query("UPDATE template_config SET template_wrap = '".$_POST['template_wrap_p']."' WHERE config_id = 'template'");
		}
		
		
		
		// Update email addresses
		if ( eregi ("email_(.*)", $key) ) {
		mysql_query("UPDATE admin_config SET $key = '$value' WHERE config_id = 'general_config'");
		}
		
		
		// Update fly-out menu status
		if ( $_POST['flyout_subcat_on_p'] ) {
		mysql_query("UPDATE admin_config SET menu_format = 'fly_out_href_vertical' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET menu_format = 'href_vertical' WHERE config_id = 'general_config'");
		}
		
		
		// Update left menu width
		if ( $_POST['menu_width_p'] ) {
		mysql_query("UPDATE admin_config SET menu_width = '".$_POST['menu_width_p']."' WHERE config_id = 'general_config'");
		}
		
		
		// Update breadcrumb status
		if ( $_POST['use_breadcrumb_p'] ) {
		mysql_query("UPDATE admin_config SET use_breadcrumb = 'yes' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET use_breadcrumb = 'no' WHERE config_id = 'general_config'");
		}
		
		
		// Update product id status
		if ( $_POST['product_id_on_p'] ) {
		mysql_query("UPDATE admin_config SET product_id_on = '1' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET product_id_on = '' WHERE config_id = 'general_config'");
		}
		
		
		// Update Paypal status
		if ( $_POST['paypal_on_p'] ) {
		mysql_query("UPDATE admin_config SET paypal_on = '1' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET paypal_on = '' WHERE config_id = 'general_config'");
		}
		
		
		
		// Update Custom fields status
		if ( $_POST['custom_fields_p'] ) {
		mysql_query("UPDATE admin_config SET custom_fields = '1' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET custom_fields = '' WHERE config_id = 'general_config'");
		}
		
		
		// Update Custom field #1
		if ( $_POST['custom_fields1_p'] ) {
		mysql_query("UPDATE admin_config SET custom_1 = '".$_POST['custom_fields1_p']."' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET custom_1 = '' WHERE config_id = 'general_config'");
		}
		
		
		
		// Update Custom field #2
		if ( $_POST['custom_fields2_p'] ) {
		mysql_query("UPDATE admin_config SET custom_2 = '".$_POST['custom_fields2_p']."' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET custom_2 = '' WHERE config_id = 'general_config'");
		}
		
		
		
		
		
		// Update preferred delivery status
		if ( $_POST['preferred_delivery_p'] ) {
		mysql_query("UPDATE admin_config SET preferred_delivery = 'yes' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET preferred_delivery = 'no' WHERE config_id = 'general_config'");
		}
		
		
		// Update delivery_earliest
		if ( $_POST['delivery_earliest_p'] ) {
		mysql_query("UPDATE admin_config SET delivery_earliest = '".$_POST['delivery_earliest_p']."' WHERE config_id = 'general_config'");
		}
		
		
		// Update delivery_range
		if ( $_POST['delivery_range_p'] ) {
		mysql_query("UPDATE admin_config SET delivery_range = '".$_POST['delivery_range_p']."' WHERE config_id = 'general_config'");
		}
		
		
		// Update delivery_range
		if ( $_POST['count_weekends_p'] ) {
		mysql_query("UPDATE admin_config SET count_weekends = '".$_POST['count_weekends_p']."' WHERE config_id = 'general_config'");
		}
		
		
		// Update font sizes
		if ( $_POST['font_size_p'] ) {
		mysql_query("UPDATE template_config SET font_size = '".$_POST['font_size_p']."' WHERE config_id = 'template'");
		}
		
		
		// Update preferred delivery required or not
		if ( $_POST['preferred_required_p'] ) {
		mysql_query("UPDATE admin_config SET preferred_required = 'yes' WHERE config_id = 'general_config'");
		}
		else {
		mysql_query("UPDATE admin_config SET preferred_required = 'no' WHERE config_id = 'general_config'");
		}
		
		
		
		if ( !$alert_status ) {
		$alert_status = "<b><font color='red'>Record(s) Updated...</font></b>";
		}
	
	$_SESSION['update_complete'] = "yes";
	
	
	}

}


// Connect to DB to grab admin info...

$edit_admin_config = mysql_query("SELECT * FROM admin_config WHERE config_id = 'general_config'");
$edit_template_config = mysql_query("SELECT * FROM template_config WHERE config_id = 'template'");

// Refresh everything here, even if it's in product.control.config.php,  as some data isn't fully registering immeadiately following a user's POST

// admin_config


// Grab admin email addresses, and parse though them for format errors
$email_1 = mysql_result($edit_admin_config, 0, "email_1");
$email_2 = mysql_result($edit_admin_config, 0, "email_2");
$email_3 = mysql_result($edit_admin_config, 0, "email_3");


$email_array = array($email_1, $email_2, $email_3);


// template_config

$company_name = mysql_result($edit_template_config, 0, "company_name");

$company_font = mysql_result($edit_template_config, 0, "company_font");

$template_wrap = mysql_result($edit_template_config, 0, "template_wrap");




?>