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


$table_top = '


<table align="center" width="'.$template_wrap.'" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	
	




<table align="center" cellspacing="0" cellpadding="2" border="0" width="100%">

<tr>
<td colspan="'.$create_colspan.'">
<b style="color: red;"><center>Customer\'s Order ('.$_SESSION['product_count'] . " product$plural".'):</center></b><br />
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


$table_bottom = '</table>
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

$email_order_data = $email_order_data . '

<tr>
<td class="product_td_height" style="background-color: '.$product_bgcolor.';  border-top: 1px solid black; padding-right: 7px; border-left: 1px solid black; width: 100%; '.$bottom_border.'">
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

<td class="product_td_height" style="background-color: '.$product_bgcolor.'; border-top: 1px solid black; border-right: 1px solid black; padding-right: 15px;'.$bottom_border.'">
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


$email_order_data = $table_top . $email_order_data . $table_bottom . $print_total;


?>