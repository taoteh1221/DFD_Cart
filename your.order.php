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

// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");

if ( !$_SESSION['cart_id'] ) {
$_SESSION['cart_id'] = md5(rand());
}



$pp_debugging = NULL; // 1 or NULL

if ( $admin_config['paypal_on'] && $_REQUEST['mode'] == 'pp_notify' ) {
require ("".$set_depth."app.lib/product.control/core.php/customer.area/paypal.ipn.php");
}
elseif ( $admin_config['paypal_on'] && $_REQUEST['mode'] == 'pp_return' ) {

	if ( file_get_contents("./orders/".$_SESSION['cart_id'].".txt") ) {
	
	$ipn_array = explode("\n", file_get_contents("./orders/".$_SESSION['cart_id'].".txt"));
	
		foreach ( $ipn_array as $ipn_data ) {
		$ipn_field = explode(": ", $ipn_data);
		
			if ( sizeof($ipn_field) > 1 ) {
			$print_ipn[$ipn_field[0]] = $ipn_field[1];
			}
		
		}
	
	$_SESSION['print_ipn'] = $print_ipn;
	}

}

// If admin has deleted a product right after it was added to the cart, purge it and alert the customer...

$show_id_array = array();
$delete_by_id = array();
$out_of_stock_summary = '';
$purge_loop = array();

if ( !$_SESSION['purge_order_notice'] ) {

	foreach( $_SESSION['product_orders'] as $scan_product ) {
	
	$product_id_scan = mysql_query("SELECT * FROM product_list WHERE product_name = '".$scan_product['product_name']."'");
	$product_in_stock = mysql_numrows($product_id_scan);
	
		if ( $scan_product['product_name'] && !$product_in_stock ) {
		$out_of_stock_summary = $out_of_stock_summary . "<div style='padding: 3px;'> >>> &nbsp;&nbsp; " . $scan_product['product_name'] . " &nbsp;&nbsp; <<< </div> ";
		$purge_loop[] = $scan_product['db_id'];
		//$product_control_class -> update_products($scan_product['db_id'], 0, 'order_purge');
		$purge_order = 1;
		}
	
	}
	
	
	
	if ( $purge_order ) {
	$_SESSION['purge_order_notice'] = "We are very sorry, but we have just removed the following products from inventory, that were in your order beforehand:<div style='padding: 3px;'>$out_of_stock_summary</div>These items have been purged from your order, and you can now continue with your ordering. <div style='padding: 4px;'>If you have any questions about this occurrence, please feel free to contact us about it.</div>";
	
		foreach ( $purge_loop as $remove_id ) {
		$product_control_class -> update_products($remove_id, 0, 'order_purge');
		}
	
	header("location: ".$_SERVER['PHP_SELF']."?category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']);
	exit;
	}

}




if ( $_REQUEST['db_id'] ) {

$product_control_class -> update_products($_REQUEST['db_id'], $_REQUEST['dyn_prod_qty'], '', $_REQUEST["custom_1"], $_REQUEST["custom_2"]);
// Remove processed GET data...
header("location: ".$_SERVER['PHP_SELF']."?category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']);
}
else {
$product_control_class -> order_total();
}

if ( $_SESSION['order_alert'] == 'empty' ) {
$_SESSION['product_orders'] = FALSE;
$_SESSION['purge_order_notice'] = "***PRODUCTS WERE JUST MASS-UPDATED BY ADMIN - WE ARE VERY SORRY, BUT ALL ITEMS MUST BE RE-ADDED***";
$_SESSION['order_alert'] = FALSE;
	header("location: ".$_SERVER['PHP_SELF']."?category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']);
	exit;
}


// Store form data in sessions until successfull order completion
if ( $_POST['submitted'] ) {
$_SESSION['Store_Name'] = $_POST['Store_Name'];
$_SESSION['Preferred_Delivery_Day'] = $_POST['Preferred_Delivery_Day'];
$_SESSION['Employee_Name'] = $_POST['Employee_Name'];
$_SESSION['Employee_Email'] = $_POST['Employee_Email'];
$_SESSION['Comments_and_Questions_Message'] = $_POST['Comments_and_Questions_Message'];
}

// Remove backslashes in front of quotes in text area
if ( $_SESSION['Comments_and_Questions_Message'] ) {
$_SESSION['Comments_and_Questions_Message'] = $form_mail_class -> remove_backslash($_SESSION['Comments_and_Questions_Message']);
}


// Process the order data for submission / fax printout
if ( $_POST['submitted'] ) {
	
	if ( !$_POST['fax_option'] || $_POST['fax_option'] && $admin_config['paypal_on'] ) {
	require ("".$set_depth."app.lib/product.control/core.php/order.data/email.formatting.php");
	
	
$html_top = '
<html>
<head>
<title>Customer Order</title>
<style type="text/css">

.product_td_height {
height: 30px;
}

</style>
</head>
<body>
<table width="100%" align="center" cellspacing="0" cellpadding="14" border="0"><tr><td valign="top">
<div align="center"><b style="font-size: '.$font_3.'px;">New Customer Order</b></div>
<br />

'.( $_SESSION['print_ipn'] ? '
<p><b>PayPal Total:</b> $'.$_SESSION['print_ipn']['payment_gross'].'</p>
<p><b>PayPal Payment Status:</b> '.$_SESSION['print_ipn']['payment_status'].'</p>
<p><b>PayPal Merchant Transaction ID:</b> '.$_SESSION['print_ipn']['txn_id'].'</p>
' : '');
	
$html_bottom = '
<br /><br />
</td></tr></table>
</body>
</html>
';
	
	
	$form_mail_class -> process_email($email_order_data, $html_top, $html_bottom, $_POST['Employee_Name'], $_POST['Employee_Email'], mailto_addresses(1), "New Customer Order");
	}
	else {
	// Run even if it's a fax order (in test mode), to validate the form data
	$form_mail_class -> process_email($email_order_data, $html_top, $html_bottom, $_POST['Employee_Name'], $_POST['Employee_Email'], mailto_addresses(1), "New Customer Order", "test only");
	}

}

 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$company_name?> &nbsp;&gt&nbsp; Ordering Online &nbsp;&gt&nbsp; Your Order&nbsp;</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
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

<?php
if ( $_SESSION['login'] ) {
?>
<div align="right" style="padding-bottom: 8px; font-size: 15px; font-weight: bold; padding-top:25px; padding-right: 25px;"><a href="dfdaccount.php" style="color: red;">My Account (<?=$_SESSION['login']['email']?>)</a> &nbsp;&nbsp; <a href="logout.php" style="color: red;">Logout</a></div>
<?php
}
else {
?>
<div align="right" style="padding-bottom: 8px; font-size: 15px; font-weight: bold; padding-top:25px; padding-right: 25px;"><a href="login.php" style="color: red;">Login / Register</a></div>
<?php
}
?>
<table  align="center" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 15px;">
<tr>
	<td style="width: <?=$admin_config['menu_width']?>px; padding-right: 19px;">

<div align="center"><img src="<?=$logo_image?>" alt="" align="middle" /></div>
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
</table>

<table align="center" cellspacing="0" cellpadding="14" border="0">
<tr>
	<td valign="top" style="position: relative;">

<div align="center" id="pop_in_mini" class="pop_in_mini"></div>

<?php
if ( $_SESSION['purge_order_notice'] ) {
?>

<div align="center" style="position: relative; z-index: 99; width: 95%; top: 5px; left: 5px; border: 2px solid #e5e328; background-color: #cb672a; color: white; padding: 7px; font-weight: bold;">
<?=$_SESSION['purge_order_notice']?>
</div>

<?php
$_SESSION['purge_order_notice'] = FALSE;
}
?>

<?php
if ( $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' ) {
?>
<div align="center" style="padding: 7px;"><a href="index.php?list_quantity=<?=$_SESSION['list_quantity']?>"><b>Product List</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>"><b>Last Category Browsed</b></a></div>
<div align="center" style="padding: 7px; font-size: <?=$font_1?>px;"><b style="color: red;">Ordering</b></div>
<?php
}
elseif ( $_REQUEST['mode'] == 'pp_checkout' ) {
?>
<div align="center" style="padding: 7px;"><a href="index.php?list_quantity=<?=$_SESSION['list_quantity']?>"><b>Product List</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>"><b>Last Category Browsed</b></a></div>
<div align="center" style="padding: 7px; font-size: <?=$font_1?>px;"><b style="color: red;">Important: After payment via PayPal, please click the 'Return to Merchant' link to complete the ordering process, or your order may not be received. <br /><br />Please <a href="javascript: document.paypal_1.submit();" style="color: red;">Click Here To Continue To PayPal</a>.</b></div>
<?php
}
elseif ( $_REQUEST['mode'] == 'pp_return' ) {
?>
<div align="center" style="padding: 7px;"><a href="index.php?list_quantity=<?=$_SESSION['list_quantity']?>"><b>Product List</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>"><b>Last Category Browsed</b></a></div>
<div align="center" style="padding: 7px; font-size: <?=$font_1?>px;"><b style="color: red;">Redirecting to order receipt, please wait...</b></div>
<?php
}
?>

<?php

if ( !$_SESSION['success'] && $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' || $_SESSION['order_total'] < 0.01 && $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' ) {
?>
<!-- Order START -->
<table align="center" cellspacing="0" cellpadding="2" border="0">

<tr>
<td colspan="4">
<b style="color: red;"><center>Your Current Order (<?php echo $_SESSION['product_count'] . " product"; if ( $_SESSION['product_count'] > 1 ) { echo "s"; } ?>):</center></b>
</td>
</tr>
<tr>
	<td class='product_td_height' style="background-color: #9cdcc2;   border-left: 1px solid black; border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="left" style=" white-space: nowrap; padding-right: 9px;"><b>Description</b></div></td>
	<?php if ( $admin_config['product_id_on'] ) {  ?><td class='product_td_height' style="background-color: #9cdcc2;  border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 9px;"><b>Product ID</b>&nbsp;&nbsp;</div></td><?php }  ?>


	<?php if ( $admin_config['custom_1'] ) {  ?><td class='product_td_height' style="background-color: #9cdcc2;  border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 9px;"><b><?=$admin_config['custom_1']?></b>&nbsp;&nbsp;</div></td><?php }  ?>

	<?php if ( $admin_config['custom_2'] ) {  ?><td class='product_td_height' style="background-color: #9cdcc2;  border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 9px;"><b><?=$admin_config['custom_2']?></b>&nbsp;&nbsp;</div></td><?php }  ?>


	<td class='product_td_height' style="background-color: #9cdcc2;  border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 9px;"><b>Unit Price</b>&nbsp;&nbsp;</div></td>
	<td class='product_td_height' style="background-color: #9cdcc2;  border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 9px;"><b>Change Order</b> <span class="button_span_link" onclick='var answer_file = "customer.ordering"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?">[?]</span></div></td>
	<td class='product_td_height' style="background-color: #9cdcc2; border-right: 1px solid black; border-top: 1px solid black;<?php if ( $_SESSION['product_count'] < 1 ) { echo " border-bottom: 1px solid black;"; } ?>"><div align="center" style=" white-space: nowrap; padding-right: 15px;">&nbsp;&nbsp;&nbsp;<i><b>Subtotal</b></i></div></td>
</tr>
<?php
}


// Sorting order  *START*

// Sort by description
$description_parsing = array();

foreach( $_SESSION['product_orders'] as $product ) {
$description_parsing[] = $product['product_name'];
}

asort($description_parsing);
foreach ($description_parsing as $key => $val) {
	
	foreach( $_SESSION['product_orders'] as $product ) {
		
		if ( $val == $product['product_name'] ) {
		$product_sorting[] = $product;
		}
		
	}
	
}

/*
**CUSTOMER'S ORDER IS NOW REFORMATTED BY PRODUCT DESCRIPTION, TO REMAIN SIMILAR TO THE PRODUCT LISTINGS AND SEARCH PAGE'S SORTING ORDER**
*/
$_SESSION['product_orders'] = $product_sorting;

// Sorting order  *END*


if ( !$_SESSION['success'] && $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' || $_SESSION['order_total'] < 0.01 && $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' ) {

	// Print the UNSUBMITTED products
	$loop = 0;
	$product_bgcolor = '#f6f2ba';
	foreach( $_SESSION['product_orders'] as $product ) {

		if ( $loop == sizeof($_SESSION['product_orders']) - 1 ) {
		$bottom_border = " border-bottom: 1px solid black;";
		}
		else {
		$bottom_border = NULL;
		}

			if ( !$_SESSION['product_orders'][$loop]['product_id'] ) {
			$_SESSION['product_orders'][$loop]['product_id'] = 'none';
			}
?>

<tr>

<form name='id_<?=$_SESSION['product_orders'][$loop]['db_id']?>' method='post' action="?key=<?=$_SESSION['sec_key']?>&category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>">
<input type='hidden' name='db_id' value='<?=$_SESSION['product_orders'][$loop]['db_id']?>' />
<td class='product_td_height' style='background-color: <?=$product_bgcolor?>;  border-top: 1px solid black; border-left: 1px solid black; padding-right: 7px;<?=$bottom_border?>'>
<div align="left" style=" padding: 7px;"><?=$_SESSION['product_orders'][$loop]['product_name']?><br>
<img src="<?=$set_depth?>images/gif/blank/1x1.gif" width="200" height="1" alt="" hspace="0" vspace="0" border="0" /></div>
</td>
<?php if ( $admin_config['product_id_on'] ) {  ?>
<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='left' style="position: relative;  padding-right: 9px;">
<?php echo ( $admin_config['product_id_on'] ? $_SESSION['product_orders'][$loop]['product_id'] : "" ); ?>
</div>
</td>
<?php }  ?>



<?php if ( $admin_config['custom_1'] ) {  ?>
<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='left' style="position: relative;  padding-right: 9px;">

<textarea  name='custom_1' id='custom_1' style='width: 100px; height: 20px;'><?php echo ( $admin_config['custom_1'] ? $_SESSION['product_orders'][$loop]['custom_1'] : "" ); ?></textarea>
</div>
</td>
<?php }  ?>


<?php if ( $admin_config['custom_2'] ) {  ?>
<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='left' style="position: relative;  padding-right: 9px;">

<textarea  name='custom_2' id='custom_2' style='width: 100px; height: 20px;'><?php echo ( $admin_config['custom_2'] ? $_SESSION['product_orders'][$loop]['custom_2'] : "" ); ?></textarea>
</div>
</td>
<?php }  ?>




<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='left' style="position: relative;  padding-right: 9px;">
$<?php echo number_format($_SESSION['product_orders'][$loop]['unit_price'], 2); ?>
</div>
</td>

<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='center' style='white-space: nowrap; padding-right: 9px;'>
<font style='font-weight: bold; '>Qty:</font>
<input onfocus="this.style.background = '#f4c9a9';" onblur="this.style.background = 'white';" type='text' maxlength='7' size='4' name='dyn_prod_qty' class='quantity_areas' onchange='this.value = this.value.replace(",", ""); this.value = Math.round(this.value);' value='<?=$_SESSION['product_orders'][$loop]['product_quantity']?>' />


<input type='button' class='small_product_buttons'  onclick="document.id_<?=$_SESSION['product_orders'][$loop]['db_id']?>.submit();" value='Update' class='quantity_areas' />

<input type='button' class='small_product_buttons' onclick="var delete_product = confirm('Are you sure you want to delete this product\?'); if ( delete_product ) { window.location.href = '?key=<?=$_SESSION['sec_key']?>&category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>&db_id=<?=$_SESSION['product_orders'][$loop]['db_id']?>&product_qty=' + 0; }" value='<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>X<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>' class='quantity_areas' />

</div>
</td>

<td class='product_td_height' style='background-color: <?=$product_bgcolor?>; border-right: 1px solid black; border-top: 1px solid black;<?=$bottom_border?>'>
<div align='right' style="position: relative;  padding-right: 15px;">
<i>$<?php echo number_format($_SESSION['product_orders'][$loop]['product_subtotal'], 2); ?></i>
</div>
</td>
</form></tr>

<?php
		if ( $product_bgcolor == '#f6f2ba' ) {
		$product_bgcolor = '#dcd89c';
		}
		else {
		$product_bgcolor = '#f6f2ba';
		}
		
	$loop = $loop + 1;
	}
	
}
elseif ( $_REQUEST['mode'] != 'pp_checkout' ) {
require ("".$set_depth."app.lib/product.control/core.php/order.data/printer.formatting.php");
}



?>


</table>
<!-- Order END -->
<div align="right"><div align="right" style=" border: 0px solid black;"><div align="right" style="padding-top: 28px; padding-bottom: 28px; color: red; font-size: 20px;"><div align="right" style="postion: relative; float: right; font-weight: bold; padding: 6px;  border: 2px solid red;">
<?php

if ( $_SESSION['login']['discount'] ) {
?>
Order Subtotal:&nbsp; $<?php

echo $_SESSION['order_subtotal'];

?><br />
Customer Discount:&nbsp; <?php

echo $_SESSION['login']['discount'] .'%<br />';

}
?>
Order Total:&nbsp; $<?php

echo $_SESSION['order_total'];

?></div>&nbsp;<?php if ( $_SESSION['success'] ) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } // Needs extra spaces to line up for some reason ?></div></div></div>


<?php

if ( !$_SESSION['success'] && $_REQUEST['mode'] != 'pp_checkout' || $_SESSION['order_total'] < 0.01 && $_REQUEST['mode'] != 'pp_checkout' ) {

	if ( $_SESSION['order_total'] < 0.01 && !$form_mail_class -> form_validate ) {
	$form_mail_class -> form_validate = "No products added yet...";
	}
?>

<table align="center" border="0"><tr><td>
<?php
	if ( $form_mail_class -> form_validate ) {
?>
	<div style="padding: 2px;"></div>
	<div align="center" style="width: 100%;"><div style="position: relative; right: 5px; border: 2px dotted #fb5a0b; width: 409px;"><div style="border: 2px dotted #fbba0b; width: 405px;"> <div align="left" style="width: 401px; padding: 4px;"><b><font class="text_alert"><?php echo $form_mail_class -> form_validate; ?> </font></b> </div></div></div></div>
<div style="padding: 8px;"></div>
<?php
	}
	
	
	if ( !$_SESSION['Store_Name'] && $_SESSION['login']['store'] ) {
	$_SESSION['Store_Name'] = $_SESSION['login']['store'];
	}
	if ( !$_SESSION['Employee_Name'] && $_SESSION['login']['name'] ) {
	$_SESSION['Employee_Name'] = $_SESSION['login']['name'];
	}
?>
<div align="right"><form name="customer_info" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font color="#FF0000">*</font>Store:</b> &nbsp;&nbsp;<input onchange="document.paypal_0.Store_Name.value = this.value;" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" size="35" maxlength="75" value="<?=$_SESSION['Store_Name']?>" name="Store_Name" />
 <br />
 <br />
<?php

	if ( $admin_config['preferred_delivery'] == 'yes' ) {
	
		if ( $admin_config['preferred_required'] == 'yes' ) {
		$render_preferred_required1 = '<font color="#FF0000">*</font>';
		$render_preferred_required2 = '|Preferred_Delivery_Day';
		}
	
	?>
 &nbsp;&nbsp;&nbsp;&nbsp;<b><?=$render_preferred_required1?>Preferred Delivery Day:</b> &nbsp;&nbsp;<select name="Preferred_Delivery_Day" onchange="document.paypal_0.Preferred_Delivery_Day.value = this.value;">
			<option value=""> Choose a day... </option>
<?php
	
		$delivery_loop = 0;
		$delivery_offset = $hour_offset + $delivery_earliest;
		
		while ( $admin_config['delivery_range'] > $delivery_loop ) {
		
			if ( $admin_config['count_weekends'] == 'no' && eregi("(.*)saturday(.*)", time_offset($delivery_offset, $minute_offset, 3))
			|| $admin_config['count_weekends'] == 'no' && eregi("(.*)sunday(.*)", time_offset($delivery_offset, $minute_offset, 3)) )
			{ }
			
			else {
			?>
			<option value="<?=time_offset($delivery_offset, $minute_offset, 3)?>" <?php if ( $_SESSION['Preferred_Delivery_Day'] == time_offset($delivery_offset, $minute_offset, 3) ) { echo 'selected'; } ?>> <?=time_offset($delivery_offset, $minute_offset, 3)?> </option>
			<?php
			$delivery_loop = $delivery_loop + 1;
			}
		
		$delivery_offset = $delivery_offset + 24;
		}
?>
 </select>
 <br />
 <br />
<?php
	}
	
?>
 <b><font color="#FF0000">*</font>Employee Name:</b> &nbsp;&nbsp;<input onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" size="35" maxlength="75" value="<?=$_SESSION['Employee_Name']?>" name="Employee_Name" onchange="document.paypal_0.Employee_Name.value = this.value;" />
 <br />
 <br />
 &nbsp;<b>Employee Email:</b> &nbsp;&nbsp;<input onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" size="35" maxlength="75" value="<?=$_SESSION['Employee_Email']?>" name="Employee_Email" onchange="document.paypal_0.Employee_Email.value = this.value;" />
<br />
<br />
<b>Comments and Questions:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br />
<textarea onchange="document.paypal_0.Comments_and_Questions_Message.value = this.value;" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" name="Comments_and_Questions_Message" cols="45" rows="11" wrap="hard"><?php echo mod_unescape_sql_str($_SESSION['Comments_and_Questions_Message'], ''); ?></textarea>
 <br />
 <br />
 <b><font color="#FF0000">*</font>Please enter the security code:</b> &nbsp;&nbsp;<input onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="security_code" size="5" maxlength="10" value="" style="font-weight: bold;"> &nbsp;&nbsp;<img src="app.lib/captcha.php" alt="" border="0" align="absmiddle" style="border: 1px solid #808080;">&nbsp;&nbsp;&nbsp;
<input type="hidden" name="required" value="Store_Name|Employee_Name|security_code<?=$render_preferred_required2?>" />
<br /><br />
<input type="button" onclick="document.customer_info.fax_option.value = 1; customer_info.submit();" value="Print and Order Later" />&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size: <?=$font_3?>px;<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?> position: relative; bottom: 3px;<?php } ?>">or</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.customer_info.fax_option.value = ''; document.customer_info.submit();" value="Order Now<?php if ( $admin_config['paypal_on'] ) { ?> and Pay Later<?php } ?>" /><?php if ( $admin_config['paypal_on'] ) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size: <?=$font_3?>px;<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?> position: relative; bottom: 3px;<?php } ?>">or</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.paypal_0.submit();" value="Order Now and Pay With PayPal" /><?php } ?>
<input type="hidden" name="fax_option" value="" />
<input type="hidden" name="submitted" value="1" />
</form></div></td></tr></table>
<?php


}
elseif ( $_SESSION['success'] && $_SESSION['order_total'] > 0 && $_REQUEST['mode'] != 'pp_checkout' && $_REQUEST['mode'] != 'pp_return' ) {
?>
<script language="javascript" type="text/javascript">



</script>
<div align="center"><b>Thank you for your order<?php echo " " . $_SESSION['Employee_Name']; ?>!</b></div>
<?php

unlink("./orders/".$_SESSION['cart_id'].".txt");  // Delete temporary order data

// Delete temporary session data
$_SESSION['cart_id'] = FALSE;
$_SESSION['Store_Name'] = FALSE;
$_SESSION['Preferred_Delivery_Day'] = FALSE;
$_SESSION['Employee_Name'] = FALSE;
$_SESSION['Employee_Email'] = FALSE;
$_SESSION['Comments_and_Questions_Message'] = FALSE;
$_SESSION['product_orders'] = FALSE;
$_SESSION['success'] = FALSE;
$_SESSION['order_subtotal'] = FALSE;
$_SESSION['order_total'] = FALSE;
$_SESSION['form_data'] = FALSE;
$_SESSION['print_ipn'] = FALSE;

}
?>

<div style="padding: 12px;"></div>





<?php

// Purge no matter what, must be processed every time
$_SESSION['success'] = FALSE;

?>

</td>
</tr>
</table>

	<?php if ( $admin_config['paypal_on'] ) { ?>
	
<form method="post" name="paypal_0" action="?mode=pp_checkout&key=<?=$_SESSION['sec_key']?>">
	<input type="hidden" maxlength="75" value="<?=$_SESSION['Store_Name']?>" name="Store_Name" />
	<input type="hidden" maxlength="75" value="<?=$_SESSION['Employee_Name']?>" name="Employee_Name" />
<input type="hidden" maxlength="75" value="<?=$_SESSION['Employee_Email']?>" name="Employee_Email" />
<input type="hidden" maxlength="75" value="<?=$_SESSION['Preferred_Delivery_Day']?>" name="Preferred_Delivery_Day" />

<textarea style="display: none;" cols="45" rows="11" name="Comments_and_Questions_Message" wrap="hard"><?php echo mod_unescape_sql_str($_SESSION['Comments_and_Questions_Message'], ''); ?></textarea>
	
<input type="hidden" name="submitted" value="1" />
	</form>
	
	
	
<form method="post" name="paypal_1" action="https://www.<?php echo ( $pp_debugging ? 'sandbox.' : ''); ?>paypal.com/cgi-bin/webscr">
<?php	

		$loop2 = 0;
		$paypal_count = 1;
		foreach( $_SESSION['product_orders'] as $product ) { ?>
		<input name="item_name_<?=$paypal_count?>" type="hidden" value="<?php echo preg_replace("/\"/", "&quot;", $_SESSION['product_orders'][$loop2]['product_name']) . ( trim($_SESSION['product_orders'][$loop2]['custom_1']) !='' ? "( " . $_SESSION['product_orders'][$loop2]['custom_1'] . " )" : '' ) .
		( trim($_SESSION['product_orders'][$loop2]['custom_2']) !='' ? "( " . $_SESSION['product_orders'][$loop2]['custom_2'] . " )" : '' ); ?>" />
		<input name="item_number_<?=$paypal_count?>" type="hidden" value="<?=$_SESSION['product_orders'][$loop2]['product_id']?>" />
		<input name="amount_<?=$paypal_count?>" type="hidden" value="<?=$_SESSION['product_orders'][$loop2]['unit_price']?>" />
		<input name="quantity_<?=$paypal_count?>" type="hidden" value="<?=$_SESSION['product_orders'][$loop2]['product_quantity']?>" />
		<?php 
		$loop2 = $loop2 + 1;
		$paypal_count = $paypal_count + 1;
		}
 ?>
	
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="<?=mailto_addresses(0)?>">
<input type="hidden" name="custom" value="<?=$_SESSION['cart_id']?>">
<input name="shipping_1" type="hidden" value="0">
<input type="hidden" name="return" value="<?php echo 'http://' . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT']  . $_SERVER['PHP_SELF']; ?>?mode=pp_return">
<input type="hidden" name="notify_url" value="<?php echo  'http://' . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT']  . $_SERVER['PHP_SELF']; ?>?mode=pp_notify">
</form>
<?php
	}
	?>

<?php
require ($set_depth . "main.footer.code.php");
?>

<?php
require ("".$set_depth."app.lib/product.control/core.php/customer.area/footer.wrap.php");
require ("".$set_depth."templates/footer.php");
?>

</body>
</html>


<?php


/*


// Debugging
echo "<br clear='all'>product_orders: <pre>";
print_r($_SESSION['product_orders']);
echo "</pre>";

// Debugging
echo "<br clear='all'>out_of_stock_summary: <pre>";
print_r($_SESSION['out_of_stock_summary']);
echo "</pre>";


// Debugging
echo "<br clear='all'>Sessions: <pre>";
print_r($_SESSION);
echo "</pre>";

// Debugging
echo "<br clear='all'>SESSION['print_ipn']: <pre>";
print_r($_SESSION['print_ipn']);
echo "</pre>";

*/


?>
