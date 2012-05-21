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





/*
***PHP OLDER THAN 4.2.X*** only can secure admin pages as of now, so if it's not admin, it's not secured...
*/
if ($security_level != 1)
{ $security_level = 0;
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

if ($security_level == 1) {

if ($user_name == $admin_user && $user_password == $admin_pass) {
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


?>