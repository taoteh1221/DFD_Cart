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

if ( !$_SESSION['change_data'] && !$_POST['selected_products'] ) {



	// Get category name...
	if ( $_SESSION['category'] > 0 ) {
	$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");
	$render_category_name = mysql_result($category_data, 0, "category_name");
	$render_timestamp = mysql_result($category_data, 0, "timestamp");
	}
	else {
	$render_category_name = 'All Products';
	}


// Get products list...

$database_query = "SELECT * FROM product_list";

	if ( $_SESSION['category'] > 0 ) {
	$database_query .= " WHERE parent_category_id = '".$_SESSION['category']."'";
	}

$database_query .= " ORDER BY product_name ASC $sql_row_list";

//echo $database_query;  // Debugging database query string

$product_data = mysql_query($database_query);

	if ($product_data) {
	$num = mysql_numrows($product_data);
	}

	if ($num) {

	$product_bgcolor = '#eae8e8';
	
		for ($i = 0; $i < $num;) {
		$show_id = mysql_result($product_data, $i, "id");
		$show_name = mysql_result($product_data, $i, "product_name");
		$product_id = mysql_result($product_data, $i, "product_id");
		$show_price = mysql_result($product_data, $i, "unit_price");
		$show_parent_id = mysql_result($product_data, $i, "parent_category_id");

		$scan_products = $i + 1;

			if ( $scan_products == $num ) {
			$bottom_border = " border-bottom: 1px solid black;";
			}
			else {
			$bottom_border = NULL;
			}

			if ( !$product_id ) {
			$product_id = 'none';
			}
			
		$print_results = $print_results . "
<form name='form_$show_id'>
<tr>
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black; border-left: 1px solid black;$bottom_border padding: 4px; width: 100%;'>$show_name</td>
". ( $admin_config['product_id_on'] ? "
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'>
<div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
".$product_id."
</div>
</td>
" : "" )."
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'>
<div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
\$".number_format($show_price, 2)."
</div>
</td>
<td class='product_td_height' style='background-color: $product_bgcolor; border-top: 1px solid black; border-right: 1px solid black;$bottom_border'>
<div align='right' style='padding-right: 5px; white-space: nowrap;'>
<a href='index.php?update=$show_id&key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&subcategory=$show_parent_id&list_quantity=".$_SESSION['list_quantity']."&list_location=".$_SESSION['list_location']."&search_price=".$_GET['search_price']."&search_name=".$_GET['search_name']."&search_data=".$_GET['search_data']."' style='font-weight: bold; position: relative; z-index: 150;'>Update</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?key=".$_SESSION['sec_key']."&category=".$_GET['category']."&delete=$show_id' onclick=' return confirm(\"Are you sure you want to delete this product?\");' style='font-weight: bold; position: relative; z-index: 150;'>Delete</a>&nbsp;&nbsp;<input type='checkbox' name='delete_checkbox' value='$show_id' onchange='
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
		$result_format = "total record";
		}
		elseif ($i > 1) {
		$result_format = "total records";
		}

	}


// Get subdirectories list...

$category_db_data = mysql_query("SELECT * FROM category_structure WHERE parent_category_id = '".$_SESSION['category']."' ORDER BY category_name ASC");

	if ($category_db_data) {
	$num2 = mysql_numrows($category_db_data);
	}

	if ($num2) {

	$category_bgcolor = '#cfbdbc';
	
		for ($z = 0; $z < $num2;) {
		$show_id2 = mysql_result($category_db_data, $z, "id");
		$show_name2 = mysql_result($category_db_data, $z, "category_name");
		$show_parent_id2 = mysql_result($category_db_data, $z, "parent_category_id");

		$scan_categories = $z + 1;

			if ( $scan_categories == $num2 && !$num ) {
			$cat_bottom_border = " border-bottom: 1px solid black;";
			}
			else {
			$cat_bottom_border = NULL;
			}

		$print_results2 = $print_results2 . "
<form name='form_cat_$show_id'>
<tr>
<td colspan='".( $admin_config['product_id_on'] ? "3" : "2" )."' class='product_td_height' style='background-color: $category_bgcolor; border-top: 1px solid black; border-left: 1px solid black; padding: 4px; width: 100%;$cat_bottom_border'><a href='?category=$show_id2'><b>$show_name2</b></a></td>
<td class='product_td_height' style='background-color: $category_bgcolor; border-top: 1px solid black; border-right: 1px solid black; white-space: nowrap; $cat_bottom_border'>
<div align='right' style='padding-right: 5px;'>
<span onclick='
var answer_file = \"category.updating\";
var answer_category = \"front.end\"; 
show_answer(answer_file, answer_category, 1, \"?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&updating_category=$show_id2\");
' style='font-weight: bold; position: relative; z-index: 150; color: #0000ee; text-decoration: underline; cursor: pointer;'>Update</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&delete_cat=$show_id2' onclick=' return confirm(\"Are you sure you want to delete this category, AND EVERYTHING IN IT?\");' style='font-weight: bold; position: relative; z-index: 150;'>Delete</a>&nbsp;&nbsp;<input type='checkbox' name='delete_cat_checkbox' value='$show_id2' onchange='
if ( this.checked == true ) {
document.mass_edit.selected_categories.value = document.mass_edit.selected_categories.value +
\"|\" + this.value + \"|\";
/* alert(document.mass_edit.selected_categories.value); */
}
else {
document.mass_edit.selected_categories.value =
document.mass_edit.selected_categories.value.replace(\"|\" + this.value + \"|\", \"\");
/* alert(document.mass_edit.selected_categories.value); */
}
' />
</div>
</td>
</tr>
</form>
";

			if ( $category_bgcolor == '#cfbdbc' ) {
			$category_bgcolor = '#d7cbca';
			}
			else {
			$category_bgcolor = '#cfbdbc';
			}

		$z = $z + 1;
		}

	}


	if ( $num ) {
	$print_results2 = $print_results2 . "
". ( $render_timestamp ? "
<tr>
	<td colspan='4' style='padding: 5px; background-color: #c9c4c4; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; color: red;'><div style='width: 180px; border: 1px solid red; padding: 1px; background: #fbd8d8;'><i>Updated on:&nbsp; $render_timestamp</i></div></td>
" : "" )."
</tr>
<form id='list_quantity_select' action='index.php' method='get'>
<tr>
	<td class='product_td_height' style='background-color: #c9c4c4; border-left: 1px solid black; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )." padding: 4px;'><b>Products within \"$render_category_name\"</b></td>
". ( $admin_config['product_id_on'] ? "
	<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Product ID</b></div></td>
" : "" )."
	<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Unit Price</b></div></td>
	<td class='product_td_height' style='background-color: #c9c4c4; border-right: 1px solid black; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )." white-space: nowrap;'><div align='right' style='position: relative; right: 5px;'><b>Edit</b>&nbsp;&nbsp;<select name='set_to' style='font-size: ".$font_7."px; font-weight: bold;' onchange=\"window.location.href = '?category=".$_SESSION['category']."&list_quantity=' + this.value + '&list_location=0';\">
<option value='50'> 50 / page </option>
<option value='100'> 100 / page </option>
<option value='150'> 150 / page </option>
<option value='200'> 200 / page </option>
</select>&nbsp;</div></td>
</tr>
<input type='hidden' name='key' value='".$_SESSION['sec_key']."' />
</form>
";
	}


$print_results = $print_results2 . $print_results;



?>

<div align="center" style="padding-bottom: 8px; font-size: <?=$font_2?>px;"><b style="color: red;">Edit Products</b><br />
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
<table align="center" cellspacing="0" cellpadding="0" border="0" width="100%">

<tr>
	<td id="category_wrapper" valign="top" rowspan="500" style="width: <?=$admin_config['menu_width']?>px;"><div id="category_list" align="center" style="padding-right: 8px; padding-bottom: 8px;">
<div align="center" style="height: 23px; font-size: <?=$font_7?>px; color: <?php
	if ( $_SESSION['category'] == 0 ) { ?>#808080<?php } else { ?>blue<?php } ?>;">
<div align="center" style="width: 122px; text-decoration: none; border-bottom-width: 1px; border-bottom-style: solid;"><?php if ( $_SESSION['category'] == 0 ) { ?><img src="../images/gif/grey.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b>
<?php
	} 
	else {

$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");

$show_parent_id = mysql_result($category_data, 0, "parent_category_id");

?><a href="?category=<?=$show_parent_id?>" style="color: blue; text-decoration: none;"><img src="../images/gif/blue.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b></a><?php
	} ?></div>
</div>

<div align="left" style="border: 1px solid black; background-color: #ededd0; width: 100%; height: 100%;">

<div align="left" style=" width: 100%; margin: 6px;">

	<div align="center" style="padding-bottom: 10px; width: 100%;">
<b style="position: relative; bottom: 3px;"><a href="?category=0&list_quantity=<?=$_SESSION['list_quantity']?>">Product List</a></b></div>

<?php
$_SESSION['menu_number'] = FALSE;
$category_html = db_data('admin_config', 'config_id', 'general_config', 'menu_format');
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>
	

<div style="padding-top: 22px;"></div>
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
	if ( document.mass_edit.selected_products.value
	|| document.mass_edit.selected_categories.value ) {
	
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
	if ( document.mass_edit.selected_products.value
	|| document.mass_edit.selected_categories.value ) {
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
	<input type="checkbox" name="check_all" value="" onclick=" if ( subcategories_select_too == 'yes' ) { var subcategories_too = confirm(' Include all listed sub-categories too? \n\n \(Click \'Cancel\' to only include the listed products\) \n '); } if ( document.product_js2 ) { document.product_js2.check_all.checked = this.checked; } check_these(document.product_js1, subcategories_too);" />&nbsp;</div>
	
	<script language="javascript" type="text/javascript">
	
	// Default to 'no', since javascript doesn't like null values that much
	var subcategories_select_too = 'no';
	
	</script>
	</td>
</tr>
</form>
<?php
	if ( $num2 ) {
?>
	<script language="javascript" type="text/javascript">
	
	var subcategories_select_too = 'yes';
	
	</script>
	<tr>
		<td colspan="2" class='product_td_height' style='background-color: #c9c4c4; border-left: 1px solid black; border-top: 1px solid black; padding: 4px;'><b>Sub-Categories within "<?=$render_category_name?>"</b></td>
		<td class='product_td_height' style='background-color: #c9c4c4; border-right: 1px solid black; border-top: 1px solid black;'><div align='right' style="position: relative; right: 115px;"><b>Edit</b></div></td>
	</tr>
<?php
	}
?>
<tr>
<td valign="top" colspan='4'>
<table width="100%" cellspacing="0" cellpadding="0" border="0" id="products_height">
<?php
	if ( $print_results ) {
	echo $print_results;
	}

	else {
?>

<tr>
<td colspan="3" class='product_td_height' style='font-size: <?=$font_4?>px; width: 560px;'>
<div align='center'>
<b><font color='red'>No listings found for this category</font></b>
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
	if ( document.mass_edit.selected_products.value
	|| document.mass_edit.selected_categories.value ) {
	
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
	if ( document.mass_edit.selected_products.value
	|| document.mass_edit.selected_categories.value ) {
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
	<input type="checkbox" name="check_all" value="" onclick="var subcategories_too = confirm(' Include all listed sub-categories too? \n\n \(Click \'Cancel\' to only include the listed products\) \n '); document.product_js1.check_all.checked = this.checked; check_these(document.product_js2, subcategories_too);" />&nbsp;</div>
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
<?php
}
?>
