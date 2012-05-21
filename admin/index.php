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



$file_depth = 1;
$security_level = 1;
require("../main.config.php");

$admin_key = "g34m8v4cv1qvb9"; // Below config.php to hide behind the admin login procedure

$max_number_links = $max_number_links_admin;  // Numbered links shown to browse other pages

// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.front.end.php");

/////////////////////////////START OF CONTENT//////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$company_name?> &nbsp;&gt&nbsp; Admin &nbsp;&gt&nbsp; Edit Products &nbsp;&gt&nbsp; <?php $category_html = 'text_horizontal';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php"); ?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="none" />


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

<?php
require ("".$set_depth."templates/header.php");
require ("".$set_depth."app.lib/product.control/core.php/customer.area/header.wrap.php");
?>


<div align="center" id="answers" class="answers_class"></div>

<table align="center" cellspacing="0" cellpadding="0" border="0" style="border: 1px solid black; background-color: #646262; width: 100%;">
<tr>
<td>

<table align="center" cellspacing="0" cellpadding="0" border="0">
<tr id="top_nav">
	
<form name="history_form" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="history_change" value="off" />
</form>

	<td style="padding: 5px; background-color: #bfbebe; border: 3px dotted #f8f6f6;">
	&nbsp;&nbsp;<a href="index.php" style="color: black;"><b>Edit Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="import.php"><b>Import Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="export.php"><b>Export Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="records/"><b>Records</b></a>&nbsp;&nbsp;
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
<?php
if ( !$_GET['update'] && !$_GET['change_data'] ) {
?>
<div align="center" style="padding-top: 4px; white-space: nowrap;">

<form name="top_forms" action="<?=$_SERVER['PHP_SELF']?>" method="get">
<b style="font-size: <?=$font_7?>px;">Price Maximum:</b> <b>$</b><input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="search_price" size="4" maxlength="6" value="<?=$search_price?>" style="font-size: <?=$font_7?>px;" />&nbsp;
<b style="font-size: <?=$font_7?>px;">Keyword(s):</b> <input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="search_name" size="18" maxlength="" value="<?=trim($search_name)?>" style="font-size: <?=$font_7?>px;" />&nbsp;

<b>Category:</b>
<?php
$category_html = 'form_select';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>
&nbsp;
<input type="submit" value="Search" />
<input type="hidden" name="list_quantity" value="<?=$_SESSION['list_quantity']?>" />
<input type="hidden" name="search_data" value="yes" />
</form></div>
<?php
}
?>
<div align="left" style="width: 100%;">



<?php

if ($_SESSION['update_complete'] || !$_SESSION['change_data']) {

	if ($_GET['data_change_notice']) {
	$alert_status = "<b><font color='red'>Please choose an option...</font></b>";
	}
echo "<div align='center' style='font-weight: bold; padding: 6px; border: 0px solid black;'>$alert_status</div>";
}

elseif ($_SESSION['change_data'] == "add" && !$_SESSION['update_complete']) {
?>
<br />
<form name="add_form" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<div align="center" style="padding: 3px;"><b><u>***Add Product***</u></b></div>
<b>Unit Price:</b>&nbsp;&nbsp; 
<b style="padding-right: 2px;">$</b><input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="unit_price" value="<?=$unit_price?>" size="3" maxlength="7" />
<?php
if ( $admin_config['product_id_on'] ) {
?>
<p></p><b>Product ID:</b>&nbsp;&nbsp;<input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="product_id_p" value="<?=$update_product_id?>" size="10" maxlength="12" />
<?php
}
?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?=$alert_status?>
<div style="padding: 9px;"></div>

<b>Category:</b>
<?php
$category_html = 'form_select';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>


 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?=$alert_status?>
<div style="padding: 9px;"></div>

<b>Description:</b>&nbsp;&nbsp;&nbsp;&nbsp;
<input style="font-weight: bold;" type="button" name="undo" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>Undo<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="undo_history(window.document.add_form.new_name);" />&nbsp;

<input style="font-weight: bold;" type="button" name="redo" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>Redo<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="redo_history(window.document.add_form.new_name);" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<input style="font-style: italic; font-weight: bold;" type="button" name="add_italic" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>I<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.add_form.new_name; var start_tag = '[i]'; var end_tag = '[/i]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.add_form.new_name);">&nbsp;

<input style="font-weight: bold;" type="button" name="add_bold" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>B<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.add_form.new_name; var start_tag = '[b]'; var end_tag = '[/b]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.add_form.new_name);" />&nbsp;

<input style="font-style: italic; font-weight: bold;" type="button" name="add_italic_bold" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>I&B<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.add_form.new_name; var start_tag = '[ib]'; var end_tag = '[/ib]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.add_form.new_name);" /><br />

<textarea class="input_text_border" onfocus="if ( this.value == 'Enter a description...' ) { this.value=''; } this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" cols="87" rows="5" name="new_name"  onchange="editing_history(window.document.add_form.new_name);">Enter a description...<?=$new_name?></textarea>

<script language="javascript" type="text/javascript">
editing_history(window.document.add_form.new_name);
</script>

<p>
<input type='button' value='Cancel' onclick='window.document.data_control.submit();' />&nbsp;&nbsp;&nbsp;
<input type="button" value="Submit New Data" onclick="window.document.add_form.action = 'index.php?key=<?=$_SESSION['sec_key']?>&category=' + window.document.add_form.category.value; scan_product_form(window.document.add_form.new_name, window.document.add_form.unit_price, 'add');" />
<input type="hidden" name="submit_new_data" value="yes" />
<input type="hidden" name="validation_attempt" value="add" />
</form>
<?php
}

elseif ($_SESSION['change_data'] == "update" && !$_SESSION['update_complete']
|| $_SESSION['change_data'] == "get_update" && !$_SESSION['update_complete']) {
?>
<br />
<form name="update_form" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<div align="center" style="padding: 3px;"><b><u>***Update Product<?php if ($update_stock_id) { echo " ($update_stock_id)"; } ?>***</u></b></div>
<b>Unit Price:</b>&nbsp;&nbsp;&nbsp;
<b style="padding-right: 2px;">$</b><input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="update_price" value="<?=$update_price?>" size="3" maxlength="7" />
<?php
if ( $admin_config['product_id_on'] ) {
?>
<p></p><b>Product ID:</b>&nbsp;&nbsp;<input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" name="product_id_p" value="<?=$update_product_id?>" size="10" maxlength="12" />
<?php
}
?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?=$alert_status?>
<div style="padding: 9px;"></div>


<b>Category:</b>
<?php
$category_html = 'form_select';

/*
Temporarily change category target to render as selected this product's correct subcategory (in cases like searching, which includes results from subcategories too)
*/
$temp_category_id = $_SESSION['category'];
$_SESSION['category'] = $_GET['subcategory'];

require ("".$set_depth."app.lib/product.control/core.php/category.list.php");

$_SESSION['category'] = $temp_category_id;
$temp_category_id = NULL;


?>


 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?=$alert_status?>
<div style="padding: 9px;"></div>

<b>Description:</b>&nbsp;&nbsp;&nbsp;&nbsp;
<input style="font-weight: bold;" type="button" name="undo" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>Undo<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="undo_history(window.document.update_form.update_name);" />&nbsp;

<input style="font-weight: bold;" type="button" name="redo" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>Redo<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="redo_history(window.document.update_form.update_name);" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<input style="font-style: italic; font-weight: bold;" type="button" name="add_italic" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>I<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.update_form.update_name; var start_tag = '[i]'; var end_tag = '[/i]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.update_form.update_name);">&nbsp;

<input style="font-weight: bold;" type="button" name="add_bold" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>B<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.update_form.update_name; var start_tag = '[b]'; var end_tag = '[/b]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.update_form.update_name);" />&nbsp;

<input style="font-style: italic; font-weight: bold;" type="button" name="add_italic_bold" value="<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>I&B<?php if ( eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) { ?>&nbsp;<?php } ?>" onclick="var description_input = window.document.update_form.update_name; var start_tag = '[ib]'; var end_tag = '[/ib]'; basic_tags(description_input, start_tag, end_tag); editing_history(window.document.update_form.update_name);" /><br />

<textarea class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" cols="87" rows="5" name="update_name" onchange="editing_history(window.document.update_form.update_name);"><?=$update_name?></textarea>

<script language="javascript" type="text/javascript">
editing_history(window.document.update_form.update_name);
</script>

<p>
<input type='button' value='Cancel' onclick='window.document.data_control.submit();' />&nbsp;&nbsp;&nbsp;
<input type="button" value="Update Current Data" onclick="window.document.update_form.action = 'index.php?key=<?=$_SESSION['sec_key']?>&category=' + window.document.update_form.category.value; scan_product_form(window.document.update_form.update_name, window.document.update_form.update_price, 'update');" />
<input type="hidden" name="update_current_data" value="yes" />
<input type="hidden" value="<?=$update_add_id?>" name="update_add_id" />
<input type="hidden" name="validation_attempt" value="update" />
</form>
<?php
}


if ( !$_GET['delete'] && !$_GET['delete_cat'] && !$_POST['move_them'] && !$_POST['delete_them'] && !$_GET['search_data'] && !$_POST['update_add_id'] && !$_POST['submit_new_data'] && !$_GET['data_change_notice'] && !$_GET['update'] && !$_GET['change_data'] ||
$_POST['show_all_data'] ) {
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.browse.list.php");
}

if ( !$_POST['category_update_id'] && $_GET['search_data'] && !$_GET['delete'] && !$_GET['delete_cat'] && !$_POST['move_them'] && !$_POST['delete_them'] && !$_GET['update'] && !$_GET['change_data'] || $_POST && $update_add_id ) {
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.browse.search.php");
}

?>

<form name="data_control" action="<?=$_SERVER['PHP_SELF']?>" method="get">
<input type="hidden" name="no_data_change" value="yes" />
<!-- If user is in search results, save their spot before adding/updating products  -->
<input type="hidden" name="key" value="<?=$_SESSION['sec_key']?>" />
<input type="hidden" name="category" value="<?=$_SESSION['category']?>" />
<input type="hidden" name="list_quantity" value="<?=$_SESSION['list_quantity']?>" />
<input type="hidden" name="list_location" value="<?=$_SESSION['list_location']?>" />
<input type="hidden" name="search_price" value="<?=$_GET['search_price']?>" />
<input type="hidden" name="search_name" value="<?=$_GET['search_name']?>" />
<input type="hidden" name="search_data" value="<?=$_GET['search_data']?>" />
</form>

<form name="choose_data_option" action="<?=$_SERVER['PHP_SELF']?>" method="get">
<input type="hidden" name="key" value="<?=$_SESSION['sec_key']?>" />
<input type="hidden" name="change_data" value="" />
<input type="hidden" name="data_change_notice" value="yes" />
<!-- If user is in search results, save their spot before adding/updating products  -->
<input type="hidden" name="category" value="<?=$_SESSION['category']?>" />
<input type="hidden" name="list_quantity" value="<?=$_SESSION['list_quantity']?>" />
<input type="hidden" name="list_location" value="<?=$_SESSION['list_location']?>" />
<input type="hidden" name="search_price" value="<?=$_GET['search_price']?>" />
<input type="hidden" name="search_name" value="<?=$_GET['search_name']?>" />
<input type="hidden" name="search_data" value="<?=$_GET['search_data']?>" />
</form>

<form name="show" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" value="yes" name="show_all_data" />
</form>


</div>
</td>
</tr>
</table>

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





if ( document.getElementById('list_quantity_select') ) {
<?php
if ( $_SESSION['list_quantity'] == 50 ) {
?>
document.getElementById('list_quantity_select').set_to.options[0].selected = true;
<?php
}
if ( $_SESSION['list_quantity'] == 100 ) {
?>
document.getElementById('list_quantity_select').set_to.options[1].selected = true;
<?php
}
if ( $_SESSION['list_quantity'] == 150 ) {
?>
document.getElementById('list_quantity_select').set_to.options[2].selected = true;
<?php
}
if ( $_SESSION['list_quantity'] == 200 ) {
?>
document.getElementById('list_quantity_select').set_to.options[3].selected = true;
<?php
}
?>
}








</script>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<form name="logout" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="my_logout" value="yes">
</form>
<?php }
?>


<form name="mass_edit" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="selected_categories" value="" />
<input type="hidden" name="selected_products" value="" />
<input type="hidden" name="move_category" value="" />
<input type="hidden" name="return_category" value="<?=$_SESSION['category']?>" />
<input type="hidden" name="move_them" value="" />
<input type="hidden" name="delete_them" value="" />
</form>



<?php
require ($set_depth . "main.footer.code.php");
?>


</body>
</html>


<?php
/*
// Debugging
echo "<br clear='all'>Sessions: <pre>";
print_r($_SESSION);
echo "</pre>";
echo "<br clear='all'>category_array: <pre>";
print_r($_SESSION['category_array']);
echo "</pre>";

echo "<br clear='all'>parsed_category_array: <pre>";
print_r($parsed_category_array);
echo "</pre>";

echo "<br clear='all'>search_subcategories_array: <pre>";
print_r($search_subcategories_array);
echo "</pre>";

echo "<br clear='all'>category_path_find: <pre>";
print_r($_SESSION['category_path_find']);
echo "</pre>";

$test_a = "Category 1 > Category 1.5 > Category 1.5.1 > Category 1.5.1.8 > Category 1.5.1.8.1 > Category 1.5.1.8.1.1"; // Test category path
echo "<br clear='all'>import_categories(debugging_only): <pre>";
print_r(import_categories($test_a, 1));
echo "</pre>";

echo "<br clear='all'>category_path_check: <pre>";
print_r($_SESSION['category_path_check']);
echo "</pre>";

*/


?>