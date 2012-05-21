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




security_check($admin_key); // Verify this appears to be an administrator editing the database

// Get category name...
if ( $_SESSION['category'] > 0 ) {
$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");
$render_category_name = mysql_result($category_data, 0, "category_name");
}
else {
$render_category_name = 'All Products';
}


$database_request = "SELECT * FROM product_list";

// Search alogarythms...

if ( $_SESSION['category'] == 0 ) {
$search_formatting = " WHERE ";
}
else {
$search_formatting = " AND ";
}



if ( trim($search_name) ) {

$search_array1 = explode(" ", trim($search_name));

	if ( sizeof($search_array1) > 1 ) {
	
		foreach( $search_array1 as $keyword ) {
		$sql_keywords1 .= " product_name LIKE '%$keyword%' AND ";
		}
	
	$sql_keywords1 = substr_replace($sql_keywords1, '', -4, 4);
	$sql_keywords1 = " product_name LIKE '%".trim($search_name)."%' || " . $sql_keywords1;
	}
	else {
	$sql_keywords1 = " product_name LIKE '%".trim($search_name)."%' ";
	}

}



if (trim($search_name) && $search_price) {
$search_request = $search_formatting . "$sql_keywords1 || product_id LIKE '%".trim($search_name)."%' AND unit_price <= ".sprintf("%01.2f", $search_price)."";
}
elseif ($search_price && !trim($search_name)) {
$search_request = $search_formatting . "unit_price <= ".sprintf("%01.2f", $search_price)."";
}
elseif (!$search_price && trim($search_name)) {
$search_request = $search_formatting . "$sql_keywords1 || product_id LIKE '%".trim($search_name)."%'";
}

if ($update_add_id && $_SESSION['update_complete']) {
$database_request .= " WHERE id='$update_add_id'";
}

elseif ( !$update_add_id && $_SESSION['category'] > 0 ) {
$database_request .= " WHERE parent_category_id = '".$_SESSION['category']."'$search_request";
}



// If this category contains subcategories, include all those products in search results too
if ( $include_in_search && $_SESSION['category'] > 0 ) {

	foreach ( $search_subcategories_array as $include_key => $include_value ) {
	
	$database_request .= " OR parent_category_id = '$include_value' $search_request";
	
	}

}
elseif ( $search_request ) {
$database_request .= $search_request;
}


$database_request .= " ORDER BY ".( $search_price ? "unit_price" : "product_name" )." ASC " . $sql_row_list;

//echo $database_request;  // Debugging database query string

$some_product_data = mysql_query($database_request);


$num = mysql_numrows($some_product_data);



if ($num) {

$product_bgcolor = '#eae8e8';

	for ($i = 0; $i < $num;) {
	$search_id_results = mysql_result($some_product_data, $i, "id");
	$search_name_results = mysql_result($some_product_data, $i, "product_name");
	$search_product_id_results = mysql_result($some_product_data, $i, "product_id");
	$search_price_results = mysql_result($some_product_data, $i, "unit_price");
	$search_parent_id_results = mysql_result($some_product_data, $i, "parent_category_id");

	$scan_products = $i + 1;

		if ( $scan_products == $num ) {
		$bottom_border = " border-bottom: 1px solid black;";
		}
		else {
		$bottom_border = NULL;
		}

			if ( !$search_product_id_results ) {
			$search_product_id_results = 'none';
			}
			
	$print_results = $print_results . "
<form name='form_$search_id_results'>
	<tr>
	<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black; border-left: 1px solid black;$bottom_border padding: 4px;  width: 100%;'>$search_name_results</td>
". ( $admin_config['product_id_on'] ? "
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'>
<div align='right' style='position: relative;  padding-left: 35px; padding-right: 35px; white-space: nowrap;'>".$search_product_id_results."</div>
</td>
" : "" )."
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'>
<div align='right' style='position: relative;  padding-left: 35px; padding-right: 35px; white-space: nowrap;'>\$".number_format($search_price_results, 2)."</div>
</td>
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black; border-right: 1px solid black; white-space: nowrap;$bottom_border'>
<div align='right' style='padding-right: 5px;'><a href='index.php?update=$search_id_results&key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&subcategory=$search_parent_id_results&list_quantity=".$_SESSION['list_quantity']."&list_location=".$_SESSION['list_location']."&search_price=".$_GET['search_price']."&search_name=".$_GET['search_name']."&search_data=".$_GET['search_data']."' style='font-weight: bold; position: relative; z-index: 150;'>Update</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&delete=$search_id_results' onClick=' return confirm(\"Are you sure you want to delete this product?\");' style='font-weight: bold; position: relative; z-index: 150;'>Delete</a>&nbsp;&nbsp;<input type='checkbox' name='delete_checkbox' value='$search_id_results' onchange='
if ( this.checked == true ) {
document.mass_edit.selected_products.value = document.mass_edit.selected_products.value +
\"|\" + this.value + \"|\";
/* alert(document.mass_edit.selected_products.value); */
}
else {
document.mass_edit.selected_products.value =
document.mass_edit.selected_products.value.replace(\"|\" + this.value + \"|\", \"\");
/* alert(document.mass_edit.selected_products.value); */
}
' />
</div>
</td>
</tr>
</form>
";

		if ( $product_bgcolor == '#eae8e8' ) {
		$product_bgcolor = 'white';
		}
		else {
		$product_bgcolor = '#eae8e8';
		}

	$i = $i + 1;
	}


	if ($i == 1) {
	$result_format = "search result";
	}
	elseif ($i > 1) {
	$result_format = "search results";
	}

}
?>

<div align="center" style="padding-bottom: 8px; font-size: <?=$font_2?>px;"><b style="color: red;">Search Results</b><br />
<?php

if ( $admin_config['use_breadcrumb'] == 'yes' ) {
$category_html = 'href_horizontal';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
}

?>
</div>

<div align="right" style="position: relative; bottom: 4px;">
<input type="button" value="Add Category" onclick="
var answer_file = 'category.adding';
var answer_category = 'front.end'; 
show_answer(answer_file, answer_category, 1, '?key=<?=$_SESSION['sec_key']?>&category=<?=$_SESSION['category']?>');
" style="font-size: <?=$font_8?>px;" />&nbsp;

<input type="button" value="Add Product" onclick="window.document.choose_data_option.change_data.value = 'add'; window.document.choose_data_option.submit();" style="font-size: <?=$font_8?>px;" /></div>

<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">

<tr>
	<td id="category_wrapper" valign="top" rowspan="500" style="width: <?=$admin_config['menu_width']?>px;"><div id="category_list" align="center" style="padding-right: 8px; padding-bottom: 8px;">
<div align="center" style="height: 23px; font-size: <?=$font_7?>px; color: <?php if ( $_SESSION['category'] == 0 ) { ?>#808080<?php } else { ?>blue<?php } ?>;">
<div align="center" style="width: 122px; text-decoration: none; border-bottom-width: 1px; border-bottom-style: solid;"><?php if ( $_SESSION['category'] == 0 ) { ?><img src="../images/gif/grey.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b><?php } 
else {

$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");

$show_parent_id = mysql_result($category_data, 0, "parent_category_id");

?><a href="?category=<?=$show_parent_id?>" style="color: blue; text-decoration: none;"><img src="../images/gif/blue.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b></a><?php } ?></div>
</div>

<div align="left" style="border: 1px solid black; background-color: #ededd0; width: 100%; height: 100%;">

<div align="left" style="margin: 6px;">

	<div align="center" style="padding-bottom: 10px; width: 100%;">
<b style="position: relative; bottom: 3px;"><a href="?category=0&list_quantity=<?=$_SESSION['list_quantity']?>">Product List</a></b></div>

<?php
$category_html = db_data('admin_config', 'config_id', 'general_config', 'menu_format');
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>
	

</div>

</div>

<div align="center" style="position: relative; top: 7px; font-size: <?=$font_7?>px; color: <?php if ( $_SESSION['category'] == 0 ) { ?>#808080<?php } else { ?>blue<?php } ?>;">
<?php
if ( $product_num > 7 ) {
?>
<div align="center" style="width: 122px; text-decoration: none; border-bottom-width: 1px; border-bottom-style: solid;"><?php if ( $_SESSION['category'] == 0 ) { ?><img src="../images/gif/grey.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b><?php } 
else {

$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");

$show_parent_id = mysql_result($category_data, 0, "parent_category_id");

?><a href="?category=<?=$show_parent_id?>" style="color: blue; text-decoration: none;"><img src="../images/gif/blue.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b></a><?php } ?></div>
<?php
}
?>
<div style="padding-top: 22px;"></div>
</div>

</div>
</td>
</tr>

<form name="product_js1">
<tr>
	<td valign="top" colspan='4' style="padding-top: 3px;">
	
	<div align="left" style="position: relative; float: left; bottom: 3px;">
	
	
<?php

if ( $_SESSION['list_location'] + $_SESSION['list_quantity'] >= $product_num ) {
$current_list = $list_start . "-" . $product_num;
$no_next = 1;
}
else {
$current_list = $list_start . "-" . $list_end;
}

if ( $_SESSION['list_location'] == 0 ) {
$no_previous = 1;
}

//$num_list_debug = " &nbsp;($i displayed)"; // Debugging

require ("".$set_depth."app.lib/product.control/core.php/numbered.nav.links.php");


// Render the numbered navigation links...

// "Previous" links
if ( !$mod_link_previous ) {
echo "<font class='list_nav' style='text-decoration: underline; font-weight: bold; color: #808080;'>&lt;&lt;</font>";
}

if ( $no_previous ) {
echo "<font class='list_nav' style='text-decoration: none; position: relative; left: 4px; color: #808080;'>&lt</font><font class='list_nav' style='text-decoration: underline; color: #808080;'>Previous</font>";
}
else {
?>

<?=$mod_link_previous?><a href="<?=url_get_data(1, '', 1)?>" class="list_nav" style="text-decoration: none; position: relative; left: 4px;">&lt</a><a href="<?=url_get_data(1, '', 1)?>" class="list_nav" style="">Previous</a>

<?php
}

// Numbered links
echo $rendered_links;


// "Next" links
if ( $no_next ) {
echo "<font class='list_nav' style='text-decoration: underline; color: #808080;'>Next</font><font class='list_nav' style='text-decoration: none; position: relative; right: 4px; color: #808080;'>&gt</font>";
}
else {
echo "<a href='" . url_get_data('', 1, 1) . "' class='list_nav' style=''>Next</a><a href='" . url_get_data('', 1, 1) . "' class='list_nav' style='text-decoration: none; position: relative; right: 4px;'>&gt</a>$mod_link_next";
}

if ( !$mod_link_next ) {
echo "<font class='list_nav' style='text-decoration: underline; font-weight: bold; color: #808080;'>&gt;&gt;</font>";
}


?>

</div>
	<div align="right" style="position: relative; float: right; bottom: 4px; background: #808080; border: 1px solid black;">&nbsp;<b style="font-size: <?=$font_7?>px; color: #e5e328;">
<?php

echo "$current_list &nbsp;of&nbsp; $product_num product(s) $num_list_debug";

?></b>&nbsp;<input type="button" value="Move" onclick="
	if ( document.mass_edit.selected_products.value ) {
		
	document.mass_edit.move_them.value = 1;
	var answer_file = 'mass.list.moving';
	var answer_category = 'front.end'; 
	show_answer(answer_file, answer_category, 1);

	}
	else {
	alert('Please select items for moving first...');
	}
	" style="font-size: <?=$font_8?>px;" />&nbsp;
	<input type="button" value="Delete" onclick="
	if ( document.mass_edit.selected_products.value ) {
	var yes_delete = confirm('ANY CATEGORIES SELECTED WILL HAVE *ALL* SUBCATEGORIES AND ITEMS WITHIN IT DELETED \n \n Proceed with deleting all selected items\? \n');
	
		if ( yes_delete ) {
		document.mass_edit.delete_them.value = 1;
		document.mass_edit.submit();
		}
	
	}
	else {
	alert('Please select items for deletion first...');
	}
	" style="font-size: <?=$font_8?>px;" />
	<input type="checkbox" name="check_all" value="" onclick="if ( document.product_js2 ) { document.product_js2.check_all.checked = this.checked; } check_these(document.product_js1);" />&nbsp;&nbsp;</div>
	
	</td>
</tr>
</form>
<tr>
<td valign="top" colspan='4'>
<table width="100%" cellspacing="0" cellpadding="0" border="0" id="products_height">
<?php
	if ( $print_results ) {
?>
<tr>
	<td class='product_td_height' style="background-color: #c9c4c4; border-left: 1px solid black; border-top: 1px solid black; padding: 4px;"><b style="color: red;"><?php if ($update_add_id && $_SESSION['update_complete']) { ?>Updated product within<?php } else { ?>Search results within<?php } ?> "<?=$render_category_name?>"<?php if ($update_add_id && $_SESSION['update_complete']) { ?>...<br /><a href="?category=<?=$_SESSION['category']?>">View everything in this category</a><?php } ?></b></td>
	<?php if ( $admin_config['product_id_on'] ) {  ?><td class='product_td_height' style="background-color: #c9c4c4; border-top: 1px solid black;"><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Product ID</b></div></td><?php }  ?>
	<td class='product_td_height' style="background-color: #c9c4c4; border-top: 1px solid black;"><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Unit Price</b></div></td>
	<td class='product_td_height' style="background-color: #c9c4c4; border-right: 1px solid black; border-top: 1px solid black; "><div align='right' style='position: relative; right: 5px; white-space: nowrap;'><b>Edit</b>&nbsp;&nbsp;<select style="font-size: <?=$font_8?>px; font-weight: bold;" onchange="window.location.href = '?category=<?=$_SESSION['category']?>&list_quantity=' + this.value + '&list_location=0&search_price=<?=$_GET['search_price']?>&search_name=<?=$_GET['search_name']?>&search_data=<?=$_GET['search_data']?>';">
<option value="50"<?php if ( $_SESSION['list_quantity'] == 50 ) { echo " selected"; } ?>> 50 / page </option>
<option value="100"<?php if ( $_SESSION['list_quantity'] == 100 ) { echo " selected"; } ?>> 100 / page </option>
<option value="150"<?php if ( $_SESSION['list_quantity'] == 150 ) { echo " selected"; } ?>> 150 / page </option>
<option value="200"<?php if ( $_SESSION['list_quantity'] == 200 ) { echo " selected"; } ?>> 200 / page </option>
</select>&nbsp;</div></td>
</tr>
<?php

	echo $print_results;
	}

	else {
?>

<tr>
<td colspan="3" class='product_td_height' style='font-size: <?=$font_4?>px; width: 560px;'>
<div align='center'>
<b><font color='red'>Sorry, no data was found that matches this query</font></b>
</div>
</td>
</tr>

<?php
	}
?>
</table>
</td>
</tr>

<?php
if ( $product_num > 7 ) {
?>
<form name="product_js2">
<tr>
	<td valign="top" colspan='4' style="padding-top: 6px;">
	
	<div align="left" style="position: relative; float: left; top: 1px;">
	
	
<?php


// Render the numbered navigation links...

// "Previous" links
if ( !$mod_link_previous ) {
echo "<font class='list_nav' style='text-decoration: underline; font-weight: bold; color: #808080;'>&lt;&lt;</font>";
}

if ( $no_previous ) {
echo "<font class='list_nav' style='text-decoration: none; position: relative; left: 4px; color: #808080;'>&lt</font><font class='list_nav' style='text-decoration: underline; color: #808080;'>Previous</font>";
}
else {
?>

<?=$mod_link_previous?><a href="<?=url_get_data(1, '', 1)?>" class="list_nav" style="text-decoration: none; position: relative; left: 4px;">&lt</a><a href="<?=url_get_data(1, '', 1)?>" class="list_nav" style="">Previous</a>

<?php
}

// Numbered links
echo $rendered_links;


// "Next" links
if ( $no_next ) {
echo "<font class='list_nav' style='text-decoration: underline; color: #808080;'>Next</font><font class='list_nav' style='text-decoration: none; position: relative; right: 4px; color: #808080;'>&gt</font>";
}
else {
echo "<a href='" . url_get_data('', 1, 1) . "' class='list_nav' style=''>Next</a><a href='" . url_get_data('', 1, 1) . "' class='list_nav' style='text-decoration: none; position: relative; right: 4px;'>&gt</a>$mod_link_next";
}

if ( !$mod_link_next ) {
echo "<font class='list_nav' style='text-decoration: underline; font-weight: bold; color: #808080;'>&gt;&gt;</font>";
}

?>

</div>
	<div align="right" style="position: relative; float: right; background: #808080; border: 1px solid black;">&nbsp;<b style="font-size: <?=$font_7?>px; color: #e5e328;">
<?php

echo "$current_list &nbsp;of&nbsp; $product_num product(s) $num_list_debug";

?></b>&nbsp;<input type="button" value="Move" onclick="
	if ( document.mass_edit.selected_products.value ) {
		
	document.mass_edit.move_them.value = 1;
	var answer_file = 'mass.list.moving';
	var answer_category = 'front.end'; 
	show_answer(answer_file, answer_category, 1);

	}
	else {
	alert('Please select items for moving first...');
	}
	" style="font-size: <?=$font_8?>px;" />&nbsp;
	<input type="button" value="Delete" onclick="
	if ( document.mass_edit.selected_products.value ) {
	var yes_delete = confirm('ANY CATEGORIES SELECTED WILL HAVE *ALL* SUBCATEGORIES AND ITEMS WITHIN IT DELETED \n \n Proceed with deleting all selected items\? \n');
	
		if ( yes_delete ) {
		document.mass_edit.delete_them.value = 1;
		document.mass_edit.submit();
		}
	
	}
	else {
	alert('Please select items for deletion first...');
	}
	" style="font-size: <?=$font_8?>px;" />
	<input type="checkbox" name="check_all" value="" onclick="document.product_js1.check_all.checked = this.checked; check_these(document.product_js2);" />&nbsp;&nbsp;</div>
	</td>
</tr>
</form>
<tr>
	<td id="product_filler" colspan='4' style="border: 0px solid red;">
</td>
</tr>
<?php
}
else {
?>
<tr><td id="nav_filler">&nbsp;</td></tr>
<?php
}
?>


</table>
<div align="center" style="padding: 12px;"></div>
