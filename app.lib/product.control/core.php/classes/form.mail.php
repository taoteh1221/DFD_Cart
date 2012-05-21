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




class form_mail {
// Variables
var $form_validate;
var $required = array();
var $headers;
var $form_data;
var $form_mail_success;


	///////////////////////////////////////////////////////////////////////////
	// Removes the backslash put in front of quotes by the php interpreter
	function email_headers($from_name_here, $from_email_here, $email_format, $x_info_here) {
		if ( !$from_email_here ) {
		$this->headers  = "From: $from_name_here <noreply@".preg_replace("/www./i", "", $_SERVER['SERVER_NAME']).">\r\n";
		}
		else {
		$this->headers  = "From: $from_name_here <".$from_email_here.">\r\n";
		}
		if ( $email_format == 'html' ) {
		$this->headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
		}
		if ( $x_info_here ) {
		$this->headers .= "X-Info: " . $x_info_here . "\r\n";
		}
	$this->headers .= "X-Mailer: DFD Cart\r\n";
	return $this->headers;
	}
	///////////////////////////////////////////////////////////////////////////



	///////////////////////////////////////////////////////////////////////////
	// Removes the backslash put in front of quotes by the php interpreter
	function check_required($required_data_value) {
	$this->required = explode("|", $required_data_value);
	return $this->required;
	}
	///////////////////////////////////////////////////////////////////////////



	///////////////////////////////////////////////////////////////////////////
	// Removes the backslash put in front of quotes by the php interpreter
	function remove_backslash($form_data_here) {
	$form_data_here = eregi_replace ("\\\\\"", "\"", $form_data_here);
	// Single-quotes too...
	$form_data_here = eregi_replace ("\\\\\'", "'", $form_data_here);
	return $form_data_here;
	}
	///////////////////////////////////////////////////////////////////////////



	///////////////////////////////////////////////////////////////////////////
	// Parse through the form data
	function process_email($order_data_here, $html_top_here, $html_bottom_here, $sender_name_here, $sender_email_here, $site_email_here, $subject_here, $test_only_here) {
	
	// To lowercase
	$_POST["security_code"] = strtolower($_POST["security_code"]);
	
		foreach($_POST as $key => $value) {
		// Call fuction to remove backslashes in front of quotes
		$_POST[$key] = $this -> remove_backslash($value);
		// 	Add HTML spacing to data that was sanatized 
		$_POST[$key] = mod_unescape_sql_str($value, 1);
			
			if ( eregi("(.*)_email(.*)", strtolower($key)) && $_POST[$key] ) {
			
			$_POST[$key] = strtolower($_POST[$key]);
			
				list($username,$domain) = split("@",$_POST[$key]);
				if (!ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $_POST[$key])) {
				$bad_email = "yes";
				$this -> form_validate = "Please enter a valid email address...";
				}
				elseif (function_exists("getmxrr") && !getmxrr($domain,$mxrecords)) {
				$bad_email = "yes";
				$this -> form_validate = "\"$domain\" appears incorrect.";
				}
				else {
				$bad_email = NULL;
				$this -> form_validate = NULL;
				}
			
			}
		
		}
	
		
		
		
		if ( !$bad_email ) {
		$this -> check_required($_REQUEST['required']);
			
			foreach($_POST as $key => $value) {
				
				if ( !$value ) {
				
					if ( in_array($key, $this->required) ) {
					$missing_required = 1;
						if ( !$this -> form_validate ) {
						$this -> form_validate = "Please fill in required fields...";
						}
					}
					else {
					$key = eregi_replace("$key", "<b>$key</b>", $key);
					$this -> form_data = $this -> form_data . eregi_replace("_", " ", $key) . "<b>:</b><br />"."NO DATA ENTERED<br /><br />";
					}
				}
				elseif ( $value && !$missing_required ) {
				
					if ( eregi("(.*)_message(.*)", strtolower($key)) ) {
					$value = eregi_replace("\n", "<br />", $value);
					}
					$key = eregi_replace("$key", "<b>$key</b>", $key);
				$this -> form_data = $this -> form_data . eregi_replace("_", " ", $key) . "<b>:</b><br />" . $value . "<br /><br />";
				}
				
			}
		
		}
		
		if ( $_POST["security_code"] != $_SESSION["security_code"] ) {
		$no_security_code = 1;
			if ( !$this -> form_validate ) {
			$this -> form_validate = "Please correctly enter the security code...";
			}
		}
		
		if (  !$bad_email && !$missing_required && !$no_security_code ) {
		$this -> form_data = eregi_replace("<b>security code</b><b>:</b>(.*)", "", $this -> form_data);
			if ( !$test_only_here ) {
			mail($site_email_here, $subject_here, $html_top_here . '<div style="padding: 15px;">' . $this -> form_data . '</div>' . $order_data_here . $html_bottom_here, $this->email_headers($sender_name_here, $sender_email_here, "html"));
			}
		$_SESSION['form_data'] = ( $_SESSION['print_ipn'] ? preg_replace("/Employee Email/i", "Employee / PayPal Email", $this -> form_data) : $this -> form_data );
		$_SESSION['success'] = 1;
		
		
		
			db_connect('orders', 'insert', '', array( 
			user_id => ( $_SESSION['login']['id'] ? $_SESSION['login']['id'] : 'None' ),
			name => $_POST['Employee_Name'], 
			store => $_POST['Store_Name'], 
			time_stamp => time(), 
			order_type =>  ( $_POST['fax_option'] && !$_SESSION['print_ipn']['payment_status'] ? "printed" : ( $_SESSION['print_ipn']['payment_status'] == 'Completed' ? 'ordered_paypal' : 'ordered' ) ), 
			order_data =>  mysql_io($order_data_here, 1), 
			order_subtotal =>  $_SESSION['order_subtotal'], 
			applied_discount =>  $_SESSION['login']['discount'], 
			order_total => $_SESSION['order_total'], 
			shipping_status =>  'No', 
			payment_status =>  ( $_SESSION['print_ipn']['payment_status'] == 'Completed' ? 'Yes' : 'No' ),
			paypal_trans_id =>  $_SESSION['print_ipn']['txn_id']
			), "");


			db_connect('logs_for_user', 'insert', '', array( 
			log_data => $_POST['Employee_Name'] ." at " . $_POST['Store_Name'] . " store " . ( $_SESSION['login']['id'] ? " (registered user id=" .$_SESSION['login']['id']. ") " : '' ) . ( $_POST['fax_option'] ? ( $_SESSION['print_ipn']['payment_status'] == 'Completed' ? 'ordered and payed via paypal (transaction id = '.$_SESSION['print_ipn']['txn_id'].') ' : 'printed to order later ' ) : "ordered " ) .$_SESSION['product_count']." product(s) totaling \$" . $_SESSION['order_total'] . "."
			), "");
		
		
		}
	
	}
	///////////////////////////////////////////////////////////////////////////




}

?>