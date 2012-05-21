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



		$create_colspan = 3;
		
		if ( $admin_config['product_id_on'] ) {
		$create_colspan = $create_colspan + 1;
		}
		if ( $admin_config['custom_1'] ) {
		$create_colspan = $create_colspan + 1;
		}
		if ( $admin_config['custom_2'] ) {
		$create_colspan = $create_colspan + 1;
		}
		
		

if ( $_SESSION['product_count'] > 1 ) {
$plural = 's';
}


if ( $_POST['fax_option'] ) {
// Warn customers leaving "print and fax" page about cleared session
$echo_print_confirm = "return confirm(' This order session has ended... \\n If you did not print it you\'ll have to start over. \\n Do you want to continue\? ');";
?>
<div align="center"><b style="color: red;">Print / Fax order</b><p><a href="javascript: print();"><img src="<?=$set_depth?>images/gif/icons/print.gif" hspace="22" alt="Print" border="0" /></a><br /><a href="javascript: print();"><b>Print Order</b></a></p></div>
<?php
}
else {
$echo_print_confirm = NULL;
?>
<div align="center"><b style="color: red;">Confirmation of order</b></div>
<?php
}
?>

<div style="padding: 15px;">
<div align="center"><b style="font-size: <?=$font_3?>px;">New Customer Order</b></div>

<?php if ( $_SESSION['print_ipn'] ) { 
?>
<p><b>PayPal Total:</b> $<?=$_SESSION['print_ipn']['payment_gross']?></p>
<p><b>PayPal Payment Status:</b> <?=$_SESSION['print_ipn']['payment_status']?></p>
<p><b>PayPal Merchant Transaction ID:</b> <?=$_SESSION['print_ipn']['txn_id']?></p>

<?=preg_replace("/Employee Email/i", "Employee / PayPal Email", $_SESSION['form_data'])?>
<?php
}
else {
echo $_SESSION['form_data'];
} ?>
</div>
<?php

$table_top = '


<table align="center" width="'.$template_wrap.'" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	
	



<table align="center" cellspacing="0" cellpadding="2" border="0" width="100%">

<tr>
<td colspan="'.$create_colspan.'">
<b style="color: red;"><center>Customer\'s Order ('.$_SESSION['product_count'] . " product$plural".'):</center></b>
<div align="center" style="padding: 7px;"><a href="index.php" onclick="'.$echo_print_confirm.'"><b>Return to Products</b></a></div>
</td>
</tr>
<tr>
	<td class="product_td_height" style="background-color: #c9c4c4; width: '. ( $admin_config['product_id_on'] ? '355' : '445' ) . 'px; border-left: 1px solid black; border-top: 1px solid black; padding-right: 15px;"><div align="left" style=" white-space: nowrap;"><b>Description</b></div></td>
'. ( $admin_config['product_id_on'] ? '
	<td class="product_td_height" style="background-color: #c9c4c4; width: 90px; border-top: 1px solid black; padding-right: 15px;"><div align="center" style=" white-space: nowrap;"><b>Product ID</b>&nbsp;</div></td>
' : '' ) .

 ( $admin_config['custom_1'] ? "
<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>".$admin_config['custom_1']."</b></div></td>
" : "" ).

 ( $admin_config['custom_2'] ? "
<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>".$admin_config['custom_2']."</b></div></td>
" : "" ). '
	<td class="product_td_height" style="background-color: #c9c4c4; width: 90px; border-top: 1px solid black; padding-right: 15px;"><div align="center" style=" white-space: nowrap;"><b>Unit Price</b>&nbsp;</div></td>
	<td class="product_td_height" style="background-color: #c9c4c4; width: 80px; border-top: 1px solid black; padding-right: 15px;"><div align="center" style=" white-space: nowrap;"><b>Quantity</b></div></td>
	<td class="product_td_height" style="background-color: #c9c4c4; width: 85px; border-right: 1px solid black; border-top: 1px solid black; padding-right: 15px;"><div align="center" style=" white-space: nowrap;"><i><b>Subtotal</b></i></div></td>
</tr>


';


$table_bottom = '
</td>
</tr>
</table>
';  


$print_total = '

<div align="right"><div align="right" style="border: 0px solid black;"><div align="right" style="padding-top: 28px; padding-bottom: 28px; color: red; font-size: 20px;"><div align="right" style="postion: relative; float: right; font-weight: bold; padding: 6px;  border: 2px solid red;">'. ( $_SESSION['login']['discount'] ? "Order Subtotal:&nbsp; \$".$_SESSION['order_subtotal']."<br />Customer Discount:&nbsp; ".$_SESSION['login']['discount'].'%<br />' : "" ) .'Order Total:&nbsp; $'.$_SESSION['order_total'].'</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div></div>

';



$loop = 0;
$product_bgcolor = '#eae8e8';
foreach( $_SESSION['product_orders'] as $product ) {

	if ( $loop == sizeof($_SESSION['product_orders']) - 1 ) {
	$bottom_border = " border-bottom: 1px solid black;";
	}
	else {
	$bottom_border = NULL;
	}

$print_order_data = $print_order_data . '

<tr>
<td class="product_td_height" style="background-color: '.$product_bgcolor.';  border-top: 1px solid black; border-left: 1px solid black; padding-right: 7px; width: 100%;'.$bottom_border.'">
<div align="left" style="position: relative;  padding: 7px;">
'.$_SESSION['product_orders'][$loop]['product_name'].'
</div>
</td>

'. ( $admin_config['product_id_on'] ? '
<td class="product_td_height" style="background-color: '.$product_bgcolor.'; border-top: 1px solid black; padding-right: 15px;'.$bottom_border.'">
<div align="left" style="position: relative; ">
'.$_SESSION['product_orders'][$loop]['product_id'].'
</div>
</td>
' : '' ) . 


 ( $admin_config['custom_1'] ? "
<td class='product_td_height' id='id1_c1_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
".$_SESSION['product_orders'][$loop]['custom_1']."
</div></td>
" : "" ).


 ( $admin_config['custom_2'] ? "
<td class='product_td_height' id='id1_c2_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
".$_SESSION['product_orders'][$loop]['custom_2']."
</div></td>
" : "" ). '

<td class="product_td_height" style="background-color: '.$product_bgcolor.'; border-top: 1px solid black; padding-right: 15px;'.$bottom_border.'">
<div align="left" style="position: relative; ">
$'.number_format($_SESSION['product_orders'][$loop]['unit_price'], 2).'
</div>
</td>

<td class="product_td_height" style="background-color: '.$product_bgcolor.'; border-top: 1px solid black; padding-right: 15px;'.$bottom_border.'">
<div align="left" style="position: relative; ">
<font style="font-weight: bold; ">&nbsp;&nbsp;x&nbsp; '.$_SESSION['product_orders'][$loop]['product_quantity'].'</font>
</div>
</td>

<td class="product_td_height" style="background-color: '.$product_bgcolor.'; border-top: 1px solid black; border-right: 1px solid black;  padding-right: 15px;'.$bottom_border.'">
<div align="right" style="position: relative;">
<i>$'.number_format($_SESSION['product_orders'][$loop]['product_subtotal'], 2).'</i>
</div>
</td>
</tr>



';


	if ( $product_bgcolor == '#eae8e8' ) {
	$product_bgcolor = 'white';
	}
	else {
	$product_bgcolor = '#eae8e8';
	}
	

$loop = $loop + 1;
}


echo $table_top . $print_order_data. $table_bottom;


?>
