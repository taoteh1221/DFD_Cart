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





$file_depth = 0;
$security_level = 0;
require("main.config.php");

$max_number_links = $max_number_links_customer;  // Numbered links shown to browse other pages

// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");

$product_control_class -> order_total();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$company_name?> &nbsp;&gt&nbsp; Ordering Online &nbsp;&gt&nbsp; <?php $category_html = 'text_horizontal';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php"); ?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="" />
<meta name="classification" content="" />
<meta name="revisit-after" content="31 days" />
<meta name="robots" content="all" />
<meta name="reply-to" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />


<?php

require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php");

require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php");

require ("".$set_depth."app.lib/product.control/core.javascript/js.php/general.functions.js.php");

?>


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
document.getElementById("pop_in_mini").innerHTML = '<span style="position: relative; z-index: 150; float: right; cursor: pointer; bottom: 13px; left: 7px;" onclick="hide_pop_in_mini();"> <b>X</b> </span><div align="center" style="position: relative; float: middle;"><a href="' + set_depth + 'your.order.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>" style="color: #ffffff; z-index: 31;"><b>View Your Order</b></a></div><div align="center" id="div_one"></div>';
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
<?php if ( eregi("Opera", $_SERVER['HTTP_USER_AGENT']) ) 
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
background-color: #cf844d;
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
<body>

<?php
require ("".$set_depth."templates/header.php");
?>

<table width="<?=$template_wrap?>" align="center" cellspacing="0" cellpadding="14" border="0">
<tr>
	<td valign="top">

<div align="center" id="answers" class="answers_class"></div>

<div align="center" id="pop_in_mini" class="pop_in_mini"></div>

<table width="<?=$template_wrap?>" align="center" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 15px;">
<tr>
	<td style=" padding-right: 9px;">

<div align="left"><a href="//<?=$url_base?>"><img src="<?=$logo_image?>" alt="" align="left" /></a></div>
</td>


	<td valign="middle" style="padding: 8px;">

<noscript>
<div align="center"><p><b><font color="red">Sorry, your browser must support javascript...</font></b></p></div>
</noscript>
<div style="padding-left: 35px;"><?php if ( function_exists("imagettftext") ) { echo "<img src='".$set_depth."images/custom/company.name.png' alt='' align='middle' />"; } else { echo "<div style='position: relative; font-weight: bold; font-size: ".$font_2."px;'>".$company_name."</div>"; } ?></div>


</td>
<td>
<div align="right" style="padding-top: 4px; white-space: nowrap;">

<?php
if ( $_SESSION['login'] ) {
?>
<div align="right" style="padding-bottom: 8px; font-size: 15px; font-weight: bold;"><a href="dfdaccount.php" style="color: red;">My Account (<?=$_SESSION['login']['email']?>)</a> &nbsp;&nbsp; <a href="logout.php" style="color: red;">Logout</a></div>
<?php
}
else {
?>
<div align="right" style="padding-bottom: 8px; font-size: 15px; font-weight: bold;"><a href="login.php" style="color: red;">Login / Register</a></div>
<?php
}
?>

<form name="top_forms" action="<?=$_SERVER['PHP_SELF']?>" method="get">

<b style="font-size: <?=$font_7?>px;">Price Maximum:</b> <b>$</b><input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="search_price" size="4" maxlength="6" value="<?=$search_price?>" style="font-size: <?=$font_7?>px;" />

<div style="padding-bottom: 8px;"></div>

<b style="font-size: <?=$font_7?>px;">Keyword(s):</b> <input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="search_name" size="18" maxlength="" value="<?=trim($search_name)?>" style="font-size: <?=$font_7?>px;" />

<div style="padding-bottom: 8px;"></div>

<b>Category:</b>
<?php
$category_html = 'form_select';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>

<div style="padding-bottom: 8px;"></div>

<input type="submit" value="Search" />
<div style="padding-bottom: 18px;"></div>

<a href="your.order.php?category=<?php echo $_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']; ?>"><b>View Your Order - (</b><b id="order_total" style="font-size: <?=$font_7?>px;">$<?=$_SESSION['order_total']?></b><b>)</b></a>

<input type="hidden" name="list_quantity" value="<?=$_SESSION['list_quantity']?>" />
<input type="hidden" name="search_data" value="yes" />
</form></div>

<div style="padding-top: 10px;"></div>
</td>
</tr>
</table>

<?php
require ("".$set_depth."app.lib/product.control/core.php/customer.area/header.wrap.php");
?>

<?php

if ( !$_GET['search_data'] || $_POST['show_all_data'] ) {
require ("".$set_depth."app.lib/product.control/core.php/customer.area/customer.browse.list.php");
}

if ( $_GET['search_data'] ) {
require ("".$set_depth."app.lib/product.control/core.php/customer.area/customer.browse.search.php");
}



?>


<form name="show" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" value="yes" name="show_all_data" />
</form>

</td>
</tr>
</table>


<?php
require ($set_depth . "main.footer.code.php");
?>

<?php
require ("".$set_depth."app.lib/product.control/core.php/customer.area/footer.wrap.php");
require ("".$set_depth."templates/footer.php");
?>

<script language="javascript" type="text/javascript">

if ( document.getElementById('category_list') ) {

document.getElementById('category_list').style.height = document.getElementById('category_wrapper').offsetHeight - 51 + 'px';

	if ( document.getElementById('nav_filler') ) {
	document.getElementById('category_list').style.height = document.getElementById('category_wrapper').offsetHeight + 20 + 'px';
	document.getElementById('nav_filler').style.height = document.getElementById('category_wrapper').offsetHeight + 'px';


	}
	

	if ( document.getElementById('product_filler') ) {

	document.getElementById('product_filler').style.height = document.getElementById('category_wrapper').offsetHeight - 51 - document.getElementById('products_height').offsetHeight + 'px';

	}

	
}

</script>

</body>
</html>
<?php
/*
// Debugging
echo "<br clear='all'>Sessions: <pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>
