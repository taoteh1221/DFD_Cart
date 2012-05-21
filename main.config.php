<?php


/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/





//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    C O N F I G U R A T I O N     I S     B E L O W    A N T I - H A C K I N G    C O D E       ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





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









///////////////////////////////////////////////////////////////////////////////////
//    S T A R T    O F    C O N F I G U R A T I O N    ////////////////////////////
//    D O    N O T    E D I T    A B O V E    T H I S    A R E A    ///////////////
//    U N L E S S    Y O U    K N O W    W H A T    Y O U ' R E    D O I N G    ///
///////////////////////////////////////////////////////////////////////////////////



// Turn off all error reporting on live web sites (what hackers don't know can't hurt you)
error_reporting(0);

// Offset server time to your time zone
$hour_offset = +0;      //  +5, -5, ETC...  ANY NON-DECIMAL NUMERIC VALUE YOU WANT
$minute_offset = +0;    //  +5, -5, ETC...  ANY NON-DECIMAL NUMERIC VALUE YOU WANT

// Admin Login user name and password
$admin_user = "admin"; // Change admin username
$admin_pass = md5("password"); // Change admin password

// A unique session name
$unique_session_name = "product_orders";  // Should be implemented by v.2 at the latest (to allow multiple server sessions)...leave or change to whatever you want

// Database Connection Information
/////////////////////////////////////////////////
$product_db_host = "localhost";  // Change MySQL Host
$product_db_username = "";  // Change MySQL Username
$product_db_password = "";  // Change MySQL Password
$product_db_database = "";  // Change Database Name
/////////////////////////////////////////////////

$max_number_links_admin = 4;  // Numbered links shown to browse other pages (4 SEEMS GOOD WITH DEFAULT FONT SIZE)
$max_number_links_customer = 6;  // Numbered links shown to browse other pages (6 SEEMS GOOD WITH DEFAULT FONT SIZE)

$max_imported = 1100;  // The maximum number of products imported per-spreadsheet import session (1100 SEEMS OK)


/*
If your on a Windows server and php mail() function does not appear setup properly, this set to a valid email on the included server should reveal it (and make mail function work)...
*/
//ini_set("SMTP", "www.yourdomain.com");
//ini_set("sendmail_from", "you@yourdomain.com");
//mail();  // Kickstarts a funky windows setup it seems...leave "mail();" as-is





///////////////////////////////////////////////////////////////////////////////////
//    E N D    O F    C O N F I G U R A T I O N    ////////////////////////////////
//    D O    N O T    E D I T    B E L O W    T H I S    A R E A    ///////////////
//    U N L E S S    Y O U    K N O W    W H A T    Y O U ' R E    D O I N G    ///
///////////////////////////////////////////////////////////////////////////////////




// Process the origin page's directory depth varaible (set relative to web root)...
$loop_count = 0;
$set_depth = NULL;
while ($file_depth > $loop_count)
{ $set_depth = "../" . $set_depth;
$loop_count = $loop_count +1;
}


require ("".$set_depth."app.lib/product.control/core.php/product.control.config.php");

//$_SESSION['search_all_stored'] = FALSE; // DEBUGGING
//$_SESSION['phases_complete'] = FALSE; // DEBUGGING

?>