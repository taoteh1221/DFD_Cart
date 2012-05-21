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


if ( $_POST['update'] ) {

$updated_data = "
shipping_status = '".trim($_POST['shipping_status'])."'
, payment_status = '".trim($_POST['payment_status'])."'
WHERE id = '".$_POST['order_id']."'
";

//echo $updated_data;

db_connect('orders', 'update', '', '', $updated_data);

$update_alert = 'Order status updated';

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


<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;"><a href="./" style="color: red;"><b>Records</b></a> &gt;&gt; <a href='<?=$_SERVER['PHP_SELF']?>'>Orders</a></div>

<div align="center" style="width: 100%;">
<?php
if ( $_GET['id'] ) {

$order_info = db_connect('orders', 'select', '', '', "WHERE id = '".$_GET['id']."'");

	$order_date = getdate($order_info['time_stamp']);
	
	$user_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$order_info['user_id']."'");
?>

<table width='850' align="center" cellspacing="2" cellpadding="2" border="0" style='border: 1px solid black;'>
<tr>
	<form name='order_info' action='<?=$_SERVER['PHP_SELF']?>?id=<?=$_GET['id']?>&key=<?=$_SESSION['sec_key']?>' method='post'><td> 

	<?php
	if ( $update_alert ) {
	?>
	<div align="center" style='padding: 2px; border: 1px dotted red;'><b style='color: red;'><?=$update_alert?></b></div>
	<?php
	}
	?>
	<p><b>Order Id:</b> <?=$order_info['id']?> </p>
	<p><b>Order Date:</b> <?php echo $order_date['month'] . " " . $order_date['mday'] . " " . $order_date['year'] . "&nbsp; , &nbsp;" . ( $order_date['hours'] < 10 ? $order_date['hours'] . '0' : $order_date['hours']) . ":" . ( $order_date['minutes'] < 10 ? $order_date['minutes'] . '0' : $order_date['minutes']); ?> hours</p> 
	<p><b>Order Type:</b> <?=$order_info['order_type']?></p> 


	<p><b>Registered Customer Account:</b> <?php echo ( $user_info['email'] ? '<a href="customers.php?id='.$user_info['id'].'" target="_blank"><b>Yes</b></a> &nbsp;&nbsp;&nbsp;&nbsp;(<a href="mailto:'.$user_info['email'].'">'.$user_info['email'].'</a>)' : 'No' ); ?></p> 
	<p><b>Store Name:</b> <?=$order_info['store']?> </p>
	<p><b>Employee Name:</b> <?=$order_info['name']?></p> 
	
	<p><b>Paid Status:</b> <select name="payment_status">
	<option value="No" <?=( $order_info['payment_status'] == 'No' ? ' selected': '' )?>> No </option>
	<option value="Yes" <?=( $order_info['payment_status'] == 'Yes' ? ' selected': '' )?>> Yes </option>
	</select>
	
	&nbsp;<?=( $order_info['paypal_trans_id'] ? " &nbsp;&nbsp;(PayPal Transaction Id: ".$order_info['paypal_trans_id'].")" : '' )?> </p>
	<p><b>Shipped Status:</b> <select name="shipping_status">
	<option value="No" <?=( $order_info['shipping_status'] == 'No' ? ' selected': '' )?>> No </option>
	<option value="Yes" <?=( $order_info['shipping_status'] == 'Yes' ? ' selected': '' )?>> Yes </option>
	</select>
	<p></p><input type='submit' value='Update Order Status' />
	
	</td>
	<input type='hidden' name='update' id='update' value='1' />
	<input type='hidden' name='order_id' id='update' value='<?=$_GET['id']?>' />
	</form>
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
	<td> Registered Customer </td>
	<td> Store Name </td>
	<td> Employee Name </td>
	<td> Order Subtotal </td>
	<td> Applied Discount </td>
	<td> Order Total </td>
	<td> Paid Status </td>
	<td> Shipped Status </td>
</tr>
<?php
$orders_array = db_connect('orders', 'select', '', '', '', 1);

	foreach ( $orders_array as $order ) {
	
	$user_info = db_connect('accounts', 'select', '', '', "WHERE id = '".$order['user_id']."'");
	
	$order_date = getdate($order['time_stamp']);
?>
<tr>
	<td> <a href='?id=<?=$order['id']?>'><b><?=$order['id']?></b></a> </td>
	<td> <?php echo $order_date['month'] . " " . $order_date['mday'] . " " . $order_date['year'] . ",<br />" . ( $order_date['hours'] < 10 ? $order_date['hours'] . '0' : $order_date['hours']) . ":" . ( $order_date['minutes'] < 10 ? $order_date['minutes'] . '0' : $order_date['minutes']); ?> hours </td>
	<td> <?=$order['order_type']?> </td>
	<td> <?php echo ( $user_info['email'] ? '<a href="customers.php?id='.$user_info['id'].'" target="_blank"><b>Yes</b></a><p>(<a href="mailto:'.$user_info['email'].'">'.$user_info['email'].'</a>)</p>' : 'No' ); ?> </td>
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