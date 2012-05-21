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


if ( !$_SESSION['sec_key'] ) {
$_SESSION['sec_key'] = md5(rand(1000000000, 10000000000));
}


// Sanatize / validate all REQUEST data ////////////////////
if ( $_GET['key'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['update'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['update'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['delete'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['updating_category'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['delete_cat'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['duplicate_delete'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['csv_delete'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['product_qty'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['loaded'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['phase'] && $_GET['key'] != $_SESSION['sec_key']
|| $_GET['data_change_notice'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['Store_Name'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['edit_config'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['my_logout'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['office_format'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['submit_new_data'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['update_current_data'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['user_name'] && $_GET['key'] != $_SESSION['sec_key']
|| $_POST['user_password'] && $_GET['key'] != $_SESSION['sec_key'] ) {
echo "Invalid security key";
exit;
}

foreach ( $_GET as $get_key2 => $get_value2 ) {
$_GET[$get_key2] = sanitize_requests($_GET[$get_key2]);
}
foreach ( $_POST as $post_key2 => $post_value2 ) {
$_POST[$post_key2] = sanitize_requests($_POST[$post_key2]);
}

/////////////////////////////////////////////////////////////




if ($_POST['user_name'])
{ $user_name = $_POST['user_name'];
$user_password = $_POST['user_password'];
}


/* Username(s) and password(s) go here... ADMIN is the only active user/pass for ***PHP OLDER THAN 4.2.X***
Maximum length for each is 22 characters!
*/
/*
// Admin Login user name and password
$admin_user = "USERNAME";
$admin_pass = md5("PASSWORD");

// VIP Login
$vip_user = "USERNAME";
$vip_pass = md5("PASSWORD");
// Guest Login
$guest_user = "USERNAME";
$guest_pass = md5("PASSWORD");
// Other Login
$other_user = "USERNAME";
$other_pass = md5("PASSWORD");
*/

if (!function_exists("md5")) {
$md5_alert = "Your system doesnt support md5 encryption needed for password protection.";
}
else {
$md5_alert = NULL;
}

$protected_page_path = $_SERVER['SCRIPT_NAME'];
$protected_page_name = basename($protected_page_path);

$php_build = substr_replace(phpversion(), '', 3);


if ($php_build < "4.2" && $php_build >= "4.1")
{ require("".$set_depth."app.lib/security/older_php.php");
}


if ($php_build >= "4.2") {

if ($security_level == 1) {
$user_login = array(array("$admin_user", "$admin_pass"));
}
elseif ($security_level == 2) {
$user_login = array(array("$admin_user", "$admin_pass"), array("$vip_user", "$vip_pass"));
}
elseif ($security_level == 3) {
$user_login = array(array("$admin_user", "$admin_pass"), array("$guest_user", "$guest_pass"));
}
elseif ($security_level == 4) {
$user_login = array(array("$admin_user", "$admin_pass"), array("$guest_user", "$guest_pass"), array("$other_user", "$other_pass"));
}

if ($_POST['my_logout'] == "yes")
{ $_SESSION['user_name'] = FALSE;
$_SESSION['user_password'] = FALSE;
}

if ($_POST['user_name'])
{ $_SESSION['user_name'] = $_POST['user_name'];
$_SESSION['user_password'] = md5($_POST['user_password']);
}

$user_name = $_SESSION['user_name'];
$user_password = $_SESSION['user_password'];

if ($security_level > 0) {

if (in_array(array ("$user_name", "$user_password"), $user_login)) {
$_SESSION['user_name'] = $user_name;
$_SESSION['user_password'] = $user_password;
	
	if ( $_SESSION['user_name'] == $admin_user && $_SESSION['user_password'] == $admin_pass ){
	$_SESSION['show_admin_link'] = '
<div style="position: relative; float: right; height: 24px;"><div style="position: relative;  top: 4px;">&nbsp;&nbsp;<a href="/admin/?admin_visit=1"><b>Admin Area</b></a>&nbsp;&nbsp;&nbsp;<a href="javascript:logoutSubmit();"><b>Logout</b></a>&nbsp;&nbsp;</div></div>';
	}
	else {
	$_SESSION['show_admin_link'] = FALSE;
	}
	
}
else {
$_SESSION['user_name'] = FALSE;
$_SESSION['user_password'] = FALSE;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Authorization Required</title>
<meta name="robots" content="none">
<style>
body {
margin:0px 0px; padding:0px;
color: #ffffff;
font-size: 14px;
font-family: Palatino Linotype;
}
a:link {color: #8AE1C7}
a:active {color: #BCB19C}
a:visited {color: #e2a258}
a:hover {color: #c48b4e;}

td {
color: #ffffff;
font-size: 14px;		
font-family: Palatino Linotype;
}
</style>
</head>

<body onLoad="window.document.login.user_name.focus();" bgcolor="#0f302f" text="#ffffff" link="#8AE1C7" alink="#BCB19C" vlink="#e2a258">

<p>&nbsp;<br>
<?php
if ($md5_alert) {
echo "<div align='center'><b> $md5_alert </b></div>";
}
?>
<p>&nbsp;<br>

<table width="300" align="center" cellspacing="2" cellpadding="2" border="0" style="border: 1px solid white">
<tr><td valign="top"><div align="center">
<form name="login" method="post" action="<?=$protected_page_name?>?key=<?=$_SESSION['sec_key']?>">
<b>Authorization Required:</b>
<p>User Name:<br><input type="text" name="user_name" maxlength="22" size="28"> 
<p>Password:<br><input type="password" name="user_password" maxlength="22" size="28">
<p>
<a href="<?=$set_depth?>" onClick="return confirm('Are you sure you want to exit the login page?')">
<img src="<?=$set_depth?>app.lib/security/images/cancel.gif" align="absmiddle" alt="" width="31" height="31" border="0"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="image" name="submit" src="<?=$set_depth?>app.lib/security/images/login.gif" align="absmiddle" alt="">
<br>
</form>
</div></td></tr>
</table>
<p><div align="center"><a href="<?=$set_depth?>"><b>Return to Home</b></a></div>
</body>
</html>
<?php exit;
}
}

}

?>
