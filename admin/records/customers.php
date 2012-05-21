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




$file_depth = 2;
$security_level = 1;
require("../../main.config.php");

$admin_key = "g34m8v4cv1qvb9"; // Below config.php to hide behind the admin login procedure

// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.front.end.php");

if ( $_POST['update'] && $_POST['email'] ) {

$_POST['discount'] = ereg_replace("[^0-9]","",$_POST['discount']); // Strip non numeric values from doscount value

$updated_data = "
email = '".trim($_POST['email'])."'
, name = '".trim($_POST['name'])."'
, store = '".trim($_POST['store'])."'
".( trim($_POST['pass']) ? " , pass = '".substr(md5(trim($_POST['pass'])),0,15)."' " : " " )." 
, address = '".trim($_POST['address'])."'
, town = '".trim($_POST['town'])."'
, postal_code = '".trim($_POST['postal_code'])."'
, country = '".trim($_POST['country'])."'
, discount = '".trim($_POST['discount'])."'
WHERE id = '".$_GET['id']."'
";

//echo $updated_data;

db_connect('accounts', 'update', '', '', $updated_data);

$update_alert = 'Account information updated';

}
elseif ( $_POST['update'] && !$_POST['email'] ) {

$update_alert = 'Information not updated, an Email address is required';

}

/////////////////////////////START OF CONTENT//////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$company_name?> &nbsp;&gt&nbsp; Admin &nbsp;&gt&nbsp; Records&nbsp;</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="none" />

<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php"); ?>


<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php"); ?>

<script language="JavaScript" type="text/javascript">

// Set the directory depth for javascript apps...
var set_depth = "<?=$set_depth?>";
// Detect user agent
var user_agent = navigator.userAgent;


</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>


<script src="<?=$set_depth?>app.lib/product.control/core.javascript/basic.xhtml.js" language="JavaScript" type="text/javascript">
</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/code.clean.js" language="JavaScript" type="text/javascript">
</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/scanning.js" language="JavaScript" type="text/javascript">
</script>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<!-- Starts the call for the form submit in the head of the document, so it's more reliable... -->
<script src="<?=$set_depth?>app.lib/security/javascript/text.submit.js" language="JavaScript" type="text/javascript"></script>
<?php }
?>


</head>
<body>


<div align="center" id="answers" class="answers_class"></div>

<table align="center" cellspacing="0" cellpadding="0" border="0" style="border: 1px solid black; background-color: #646262; width: 100%;">
<tr>
<td>

<table align="center" cellspacing="0" cellpadding="0" border="0">
<tr id="top_nav">
	
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="index.php"><b>Edit Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="import.php"><b>Import Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="export.php"><b>Export Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; background-color: #bfbebe; border: 3px dotted #f8f6f6;">
	&nbsp;&nbsp;<a href="./" style="color: black;"><b>Records</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;<a href="<?=$set_depth?>admin/configure.php"><b>Configure</b></a>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px;">
	&nbsp;<span onclick='var answer_file = "help.index"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?" style="color: white; text-decoration: underline; font-size: <?=$font_6?>px; font-weight: bold; cursor: pointer;">Help</span>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="javascript:logoutSubmit();"><b>Logout</b></a>&nbsp;&nbsp;
	</td>
<td>
<!--  ALWAYS ONE EXTRA TO TAKE THE REST OF THE TABLE WIDTH  -->
&nbsp;
</td>
</tr>
</table>

</td>
</tr>
</table>



<table width="100%" align="center" cellspacing="0" cellpadding="14" border="0">
<tr>
	<td valign="top">


<noscript>
<div align="center"><p><b><font color="red">Sorry, your browser must support javascript...</font></b></p></div>
</noscript>


<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;"><a href="./" style="color: red;"><b>Records</b></a> &gt;&gt; <a href='<?=$_SERVER['PHP_SELF']?>'>Customers</a></div>

<div align="center" style="width: 100%;">
<?php
if ( $_GET['id'] ) {

$account_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$_GET['id']."'");
?>

<table width='850' align="center" cellspacing="2" cellpadding="2" border="0" style='border: 1px solid black;'>
<tr>
	<td valign='top'>
	
	<form name='account_info' action='<?=$_SERVER['PHP_SELF']?>?id=<?=$_GET['id']?>&key=<?=$_SESSION['sec_key']?>' method='post'>
	<div align="center"><h3>Account Informtion</h3></div>
	<?php
	if ( $update_alert ) {
	?>
	<div align="center" style='padding: 2px; border: 1px dotted red;'><b style='color: red;'><?=$update_alert?></b></div>
	<?php
	}
	?>
	<div align="left" style='border: 0px solid green; width: 520px;'><div align="right">
	
	<p>Name: <input type='text' name='name' value='<?=$account_info['name']?>' /></p>
	
	<p>Store: <input type='text' name='store' value='<?=$account_info['store']?>' /></p>
	
	<p>Email: <input type='text' name='email' value='<?=$account_info['email']?>' size="35" /></p>
	
	<p>New password (or leave blank): <input type='password' name='pass' value='' /></p>
	
	<p>Confirm New Password: <input type='password' name='pass2' value='' /></p>
	
	
	<p>Address: 
	<textarea name='address'><?=$account_info['address']?></textarea>
	</p>
	
	<p>Town: <input type='text' name='town' value='<?=$account_info['town']?>' /></p>
	
	<p>Postal Code: <input type='text' name='postal_code' value='<?=$account_info['postal_code']?>' /></p>
	
	<p>Country: 
	<select name='country' id='country'>
	<?php require ("".$set_depth."app.lib/product.control/core.php/customer.area/country.list.php"); ?>
	</select>
	<script type='text/javascript'>
	
	for (loop=0; loop < document.account_info.country.options.length; loop++) {
	
		if ( document.account_info.country.options[loop].value == "<?=$account_info['country']?>" ) {
		document.account_info.country.options[loop].selected=true;
		}
	
	}
	
	
	</script>
	</p>
	
	
	<p>Discount: <input type='text' name='discount' value='<?=$account_info['discount']?>' />%</p>
	
	
	
	<div align="center"><input type='button' value='Submit' onclick='
	if ( document.account_info.pass.value == document.account_info.pass2.value ) {
	document.account_info.submit();
	}
	else {
	alert("Passwords do not match");
	}
	' style='position: relative; left: 45px;' /></div>
	</div></div>
	<input type='hidden' name='update' id='update' value='1' />
	</form>
	
	</td>
</tr>
</table>

<?php
}
else {
?>
<table cellspacing="2" cellpadding="8" border="1">

<tr>
	<td> ID </td>
	<td> Name </td>
	<td> Email </td>
	<td> Country </td>
	<td> Discount </td>
	<td> Status </td>
</tr>
<?php
$accounts_array = db_connect('accounts', 'select', '', '', '', 1);

	foreach ( $accounts_array as $account ) {
?>
<tr>
	<td> <a href='?id=<?=$account['id']?>'><b><?=$account['id']?></b></a> </td>
	<td> <?=$account['name']?> </td>
	<td> <a href="mailto:<?=$account['email']?>"><?=$account['email']?></a> </td>
	<td> <?=$account['country']?> </td>
	<td> <?=( $account['discount'] ? $account['discount'] .'%' : 'None' )?> </td>
	<td> <?php
	if ( $account['status'] == 0 ) {
	echo 'Not Activated';
	}
	elseif ( $account['status'] == 1 ) {
	echo 'Activated';
	}
	?> </td>
</tr>
<?php
	}

}
?>

</table>
</div>

</td>
</tr>
</table>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<form name="logout" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="my_logout" value="yes">
</form>
<?php }
?>


<?php
require ($set_depth . "main.footer.code.php");
?>

</body>
</html>