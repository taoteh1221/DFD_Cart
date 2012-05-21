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


if ( $_POST['update'] && $_POST['email'] ) {

$updated_data = "
email = '".trim($_POST['email'])."'
, name = '".trim($_POST['name'])."'
, store = '".trim($_POST['store'])."'
".( trim($_POST['pass']) ? " , pass = '".substr(md5(trim($_POST['pass'])),0,15)."' " : " " )." 
, address = '".trim($_POST['address'])."'
, town = '".trim($_POST['town'])."'
, postal_code = '".trim($_POST['postal_code'])."'
, country = '".trim($_POST['country'])."'
WHERE id = '".$_SESSION['login']['id']."'
";

//echo $updated_data;

db_connect('accounts', 'update', '', '', $updated_data);

$update_alert = 'Account information updated';

}
elseif ( $_POST['update'] && !$_POST['email'] ) {

$update_alert = 'Information not updated, an Email address is required';

}

$account_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$_SESSION['login']['id']."'");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Account</title>
<meta name="author" content="" />
<meta name="classification" content="" />
<meta name="revisit-after" content="31 days" />
<meta name="robots" content="all" />
<meta name="reply-to" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />


<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php"); ?>

<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php"); ?>


<script language="JavaScript" type="text/javascript">

// Set the directory depth for javascript apps...
var set_depth = "<?=$set_depth?>";
// Detect user agent
var user_agent = navigator.userAgent;


</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>


<script language="javascript" type="text/javascript">

function show_pop_in_mini() {

document.getElementById("pop_in_mini").style.border='2px solid black';
document.getElementById("pop_in_mini").style.padding='12px';
document.getElementById("pop_in_mini").style.width='37%';
document.getElementById("pop_in_mini").innerHTML = '<span style="position: relative; float: right; cursor: pointer;" onclick="hide_pop_in_mini();"> <b>X</b> </span><div align="center" style="position: relative; float: middle;"><a href="' + set_depth + 'cart/index.php" style="color: #ffffff; z-index: 31;"><b>View Cart</b></a></div><div align="center" id="div_one"></div>';
document.getElementById("pop_in_mini").style.visibility='visible';

}

function hide_pop_in_mini() {

parent.document.getElementById("pop_in_mini").innerHTML = '';
parent.document.getElementById("pop_in_mini").style.padding='0px';
parent.document.getElementById("pop_in_mini").style.border='0px';
parent.document.getElementById("pop_in_mini").style.width='0px';
parent.document.getElementById("pop_in_mini").style.visibility='hidden';

}

</script>

<?php require ("".$set_depth."app.lib/product.control/core.javascript/js.php/order.ajax.js.php"); ?>

<style type="text/css">
.pop_in_mini {
/* Browser-specific **START** */
<?php if (eregi("Opera", $_SERVER['HTTP_USER_AGENT'])) 
{ ?>
/* Opera without fixed positioning support */
position: absolute;
<?php }
elseif ( eregi("MSIE 5", $_SERVER['HTTP_USER_AGENT'])
|| eregi("MSIE 6", $_SERVER['HTTP_USER_AGENT']) ) 
{ ?>
/*  MSIE 5+6 ONLY...MSIE 7 will support fixed positioning */
position: absolute;
/* HTML VERSION top: expression( ( ignore_me = document.body.scrollTop ) + 'px' ); */
/* XHTML VERSION  top: expression( ( ignore_me = document.documentElement.scrollTop ) + 'px' ); */
/* BACKUP VERSION */ top: expression( ( ignore_me = document.body.scrollTop ) + 'px' );
<?php }
else
{ ?>
/* FireFox, etc */
position: fixed;
<?php } ?>
/* Browser-specific **END** */
background-color: #BF7035;
border: 0px;
font-size: <?=$font_6?>px;
color: #ffffff;
margin: 15% 15%;
width: 50px;
visibility: hidden;
z-index: 10;
opacity: .95; /*  FireFox and Safari  */
filter: alpha(opacity=95); /*  MSIE  */
}
</style>

</head>
<body onload="<?php if ( $admin_config['paypal_on'] && $_REQUEST['mode'] == 'pp_return' && sizeof($ipn_array) > 1 ) { 
$_SESSION["lock_security_code"] = $_SESSION["security_code"];
?>
document.customer_info.fax_option.value = 1; 
document.customer_info.Employee_Name.value = '<?=$_SESSION['Employee_Name']?> (<?=$_SESSION['print_ipn']['address_name']?>)';
document.customer_info.Employee_Email.value = '<?=$_SESSION['print_ipn']['payer_email']?>';
document.customer_info.Store_Name.value = '<?=$_SESSION['print_ipn']['address_name']?> , \n <?=$_SESSION['print_ipn']['address_street']?> \n <?=$_SESSION['print_ipn']['address_city']?> <?=$_SESSION['print_ipn']['address_state']?> , <?=$_SESSION['print_ipn']['address_zip']?> \n - <?=$_SESSION['print_ipn']['address_country']?>';
document.customer_info.submitted.value = 1;
document.customer_info.security_code.value = '<?=$_SESSION["lock_security_code"]?>';
document.customer_info.submit();
<?php } ?>">

<?php
require ("".$set_depth."templates/header.php");
require ("".$set_depth."app.lib/product.control/core.php/customer.area/header.wrap.php");

?>

<div align="center" id="answers" class="answers_class"></div>
<p>&nbsp;</p>
<div align="center"><table  align="center" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 15px;">
<tr>
	<td style="width: <?=$admin_config['menu_width']?>px; padding-right: 19px;">

<div align="center"><a href="//<?=$url_base?>"><img src="<?=$logo_image?>" alt="" align="middle" /></a></div>
<img src="<?=$set_depth?>images/gif/blank/1x1.gif" width="<?=$admin_config['menu_width']?>" height="1" alt="" hspace="0" vspace="0" border="0" />
</td>


	<td valign="middle" style="padding: 15px;">

<noscript>
<div align="center"><p><b><font color="red">Sorry, your browser must support javascript...</font></b></p></div>
</noscript>
<div style="padding-left: 35px;"><?php if ( function_exists("imagettftext") ) { echo "<img src='".$set_depth."images/custom/company.name.png' alt='' align='middle' />"; } else { echo "<div style='position: relative; font-weight: bold; font-size: ".$font_2."px;'>".$company_name."</div>"; } ?></div>


</td>
<td style="width: 245px;">&nbsp;</td>
</tr>
</table></div>
<div align="center">
<p><a href="dfdaccount.php?history=1"><b>View Your Order History</b></a></p>
<?php
if ( $_SESSION['order_total'] > 0 ) {
?>
<p><a href="your.order.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>"><b>View Your Current Order - (</b><b id="order_total" style="font-size: <?=$font_7?>px;">$<?=$_SESSION['order_total']?></b><b>)</b></a></p>
<?php
}
?>

</div>

<?php
if ( $_GET['history'] ==1 ) {
?>















<div align="center" style="width: 100%;">
<?php
if ( $_GET['id'] ) {

$order_info = db_connect('orders', 'select', '', '', "WHERE id = '".$_GET['id']."' AND user_id = '".$_SESSION['login']['id']."'");

	$order_date = getdate($order_info['time_stamp']);
	
	$user_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$order_info['user_id']."'");
?>

<table width='850' align="center" cellspacing="2" cellpadding="2" border="0" style='border: 1px solid black;'>
<tr><td> 

	<p><b>Order Id:</b> <?=$order_info['id']?> </p>
	<p><b>Order Date:</b> <?php echo $order_date['month'] . " " . $order_date['mday'] . " " . $order_date['year'] . "&nbsp; , &nbsp;" . ( $order_date['hours'] < 10 ? $order_date['hours'] . '0' : $order_date['hours']) . ":" . ( $order_date['minutes'] < 10 ? $order_date['minutes'] . '0' : $order_date['minutes']); ?> hours</p> 
	<p><b>Order Type:</b> <?=$order_info['order_type']?></p> 


	<p><b>Store Name:</b> <?=$order_info['store']?> </p>
	<p><b>Employee Name:</b> <?=$order_info['name']?></p> 
	
	<p><b>Paid Status:</b> <?=( $order_info['paypal_trans_id'] ? $order_info['payment_status'] . " &nbsp;&nbsp;(PayPal Transaction Id: ".$order_info['paypal_trans_id'].")" : $order_info['payment_status'] )?> </p>
	<p><b>Shipped Status:</b> <?=$order_info['shipping_status']?> </p>
	
	</td>
</tr>
<tr>
	<td valign='top'>
	
	<?php
	
	echo mysql_io($order_info['order_data']);
	
	?>
	
	</td>
</tr>
</table>

<?php
}
else {
?>
<table cellspacing="2" cellpadding="8" border="1">

<tr>
	<td> Order ID </td>
	<td> Order Date </td>
	<td> Order Type </td>
	<td> Store Name </td>
	<td> Employee Name </td>
	<td> Order Subtotal </td>
	<td> Applied Discount </td>
	<td> Order Total </td>
	<td> Paid Status </td>
	<td> Shipped Status </td>
</tr>
<?php
$orders_array = db_connect('orders', 'select', '', '', "WHERE user_id = '".$_SESSION['login']['id']."'", 1);

	foreach ( $orders_array as $order ) {
	
	$user_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$_SESSION['login']['id']."'");
	
	$order_date = getdate($order['time_stamp']);
?>
<tr>
	<td> <a href='?history=1&id=<?=$order['id']?>'><b><?=$order['id']?></b></a> </td>
	<td> <?php echo $order_date['month'] . " " . $order_date['mday'] . " " . $order_date['year'] . ",<br />" . ( $order_date['hours'] < 10 ? $order_date['hours'] . '0' : $order_date['hours']) . ":" . ( $order_date['minutes'] < 10 ? $order_date['minutes'] . '0' : $order_date['minutes']); ?> hours </td>
	<td> <?=$order['order_type']?> </td>
	<td> <?=$order['store']?> </td>
	<td> <?=$order['name']?> </td>
	<td> $<?=$order['order_subtotal']?> </td>
	<td> <?=( $order['applied_discount'] ? $order['applied_discount'] . '%' : 'None' )?> </td>
	<td> $<?=$order['order_total']?> </td>
	<td> <?=( $order['paypal_trans_id'] ? $order['payment_status'] . "<br />(PayPal Transaction Id: ".$order['paypal_trans_id'].")" : $order['payment_status'] )?> </td>
	<td> <?=$order['shipping_status']?> </td>
</tr>
<?php
	}

}
?>

</table>
</div>




























<?php
}
else {
?>
<table width='850' align="center" cellspacing="2" cellpadding="2" border="0" style='border: 1px solid black;'>
<tr>
	<td valign='top'>
	
	<form name='account_info' action='<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>' method='post'>
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
	
	<?php
	if ( $account_info['discount'] > 0 ) {
	?>
	<p>Discount: <?=$account_info['discount']?>%</p>
	<?php
	}
	?>
	
	
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
?>

<?php
require ($set_depth . "main.footer.code.php");
?>

<?php
require ("".$set_depth."app.lib/product.control/core.php/customer.area/footer.wrap.php");
require ("".$set_depth."templates/footer.php");


// Debugging
//echo "<br clear='all'>_SESSION['login']: <pre>";
//print_r($_SESSION['login']);
//echo "</pre>";

?>

</body>
</html>
