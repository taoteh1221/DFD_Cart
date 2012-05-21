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
	$show_timestamp = mysql_result($product_data, $i, "timestamp");

	$scan_products = $i + 1;

		if ( $scan_products == $num ) {
		$bottom_border = " border-bottom: 1px solid black;";
		}
		else {
		$bottom_border = NULL;
		}
	
		// If they already added the item, highlight it in the product list
		$loop = 0;
		$current_quantity = 0;  // Desired default quantity
		$custom_1 = '';  // Default custom_1
		$custom_2 = '';  // Default custom_2
		foreach ( $_SESSION['product_orders'] as $product ) {
		
			if ( $show_name == $product['product_name'] ) {
			$product_bgcolor = '#efebb0';
			$current_quantity = $product['product_quantity'];
			
			$custom_1 = $product['custom_1'];
			$custom_2 = $product['custom_2'];
				
			}
		
		}

			if ( !$product_id ) {
			$product_id = 'none';
			}
			
	$print_results = $print_results . "\n<form name='id_".$show_id."'>\n<input type='hidden' name='db_id' value='".$show_id."' />\n<tr>\n<td class='product_td_height' id='id1_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black; border-left: 1px solid black;$bottom_border padding: 4px; width: 100%;'>$show_name</td>\n
". ( $admin_config['product_id_on'] ? "
<td class='product_td_height' id='id1_1_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>".$product_id."</div></td>
" : "" ). 


 ( $admin_config['custom_1'] ? "
<td class='product_td_height' id='id1_c1_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
<textarea  name='custom_1' id='custom_1' style='width: 100px; height: 20px;'>".$custom_1."</textarea>
</div></td>
" : "" ).


 ( $admin_config['custom_2'] ? "
<td class='product_td_height' id='id1_c2_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>
<textarea  name='custom_2' id='custom_2' style='width: 100px; height: 20px;'>".$custom_2."</textarea>
</div></td>
" : "" )."


<td class='product_td_height' id='id2_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black;$bottom_border'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px;'>\$".number_format($show_price, 2)."</div></td>
\n<td class='product_td_height' id='id3_".$show_id."' style='background-color: $product_bgcolor; border-top: 1px solid black; border-right: 1px solid black; white-space: nowrap;$bottom_border'><div align='right' style='position: relative; right: 5px;'><font style='font-weight: bold; '>Qty:</font><input onfocus=\"this.style.background = '#f4c9a9'; if ( this.value == '0' ) { this.value = ''; }\" onblur=\"this.style.background = 'white'; if ( this.value == '' ) { this.value = '0'; }\" type='text' maxlength='7' name='dyn_prod_qty' size='4' class='quantity_areas' onchange='this.value = this.value.replace(\",\", \"\"); this.value = Math.round(this.value);' value='$current_quantity' />&nbsp;&nbsp;<input type='button' class='small_product_buttons'  onclick=\"update_products(document.id_".$show_id.");\" value='Update' class='quantity_areas' /></div></td>\n</tr>\n</form>\n";

		if ( $product_bgcolor == '#eae8e8' ) {
		$product_bgcolor = 'white';
		}
		else {
		$product_bgcolor = '#eae8e8';
		}

	$i = $i + 1;
	}


	if ($i == 1) {
	$result_format = "record";
	}
	elseif ($i > 1) {
	$result_format = "records";
	}

}


// Get subdirectories list...

$category_db_data = mysql_query("SELECT * FROM category_structure WHERE parent_category_id = '".$_SESSION['category']."' ORDER BY category_name ASC");

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
		
		
		
	if ($category_db_data) {
	$num2 = mysql_numrows($category_db_data);
	}

	if ($num2) {

	$category_bgcolor = '#cfbdbc';
	
		for ($z = 0; $z < $num2;) {
		$show_id2 = mysql_result($category_db_data, $z, "id");
		$show_name2 = mysql_result($category_db_data, $z, "category_name");
		$show_parent_id2 = mysql_result($category_db_data, $z, "parent_category_id");
	$show_timestamp2 = mysql_result($category_db_data, $z, "timestamp");

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
<td colspan='".$create_colspan."' class='product_td_height' style='background-color: $category_bgcolor; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; padding: 4px; width: 100%; $cat_bottom_border'><a href='?category=$show_id2'><b>$show_name2</b></a></td>
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
	<td colspan='".$create_colspan."' style='padding: 5px; background-color: #c9c4c4; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; color: red;'><div style='width: 180px; border: 1px solid red; padding: 1px; background: #fbd8d8;'><i>Updated on:&nbsp; $render_timestamp</i></div></td>
" : "" )."
</tr>
<tr>
	<td class='product_td_height' style='background-color: #c9c4c4; border-left: 1px solid black; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='left'>&nbsp;&nbsp;<b>Products within \"$render_category_name\"</b></div></td>
	". ( $admin_config['product_id_on'] ? "
<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Product ID</b></div></td>
" : "" ).

 ( $admin_config['custom_1'] ? "
<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>".$admin_config['custom_1']."</b></div></td>
" : "" ).

 ( $admin_config['custom_2'] ? "
<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>".$admin_config['custom_2']."</b></div></td>
" : "" ).

"
	<td class='product_td_height' style='background-color: #c9c4c4; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; padding-left: 35px; padding-right: 35px; white-space: nowrap;'><b>Unit Price</b></div></td>
	<td class='product_td_height' style='background-color: #c9c4c4; border-right: 1px solid black; ". ( $render_timestamp ? "" : "border-top: 1px solid black; " )."'><div align='right' style='position: relative; right: 5px;'>&nbsp;&nbsp;<b>Ordering</b> <span class='button_span_link' onclick=\"var answer_file = 'customer.ordering'; var answer_category = 'general'; show_answer(answer_file, answer_category);\" title='What's This?'>[?]</span></div></td>
</tr>
";
	}


$print_results = $print_results2 . $print_results;


?>
<table width="<?=$template_wrap?>" align="center" cellspacing="0" cellpadding="0" border="0">


<?php
if ( $admin_config['use_breadcrumb'] == 'yes' ) {
?>

<tr>
<td colspan="5">

<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;">
<?php
$category_html = 'href_horizontal';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?>

</div>
<div style="padding-top: 7px;"></div>
</td>
</tr>
<?php
}
?>


<tr>
	<td id="category_wrapper" valign="top" rowspan="500" style="width: <?=$admin_config['menu_width']?>px;"><div id="category_list" align="center" style="padding-right: 8px;">
<div align="center" style="height: 23px; font-size: <?=$font_7?>px; color: <?php if ( $_SESSION['category'] == 0 ) { ?>#808080<?php } else { ?>blue<?php } ?>;">
<div align="center" style="width: 122px; text-decoration: none; border-bottom-width: 1px; border-bottom-style: solid;"><?php if ( $_SESSION['category'] == 0 ) { ?><img src="images/gif/grey.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b><?php } 
else {

$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");

$show_parent_id = mysql_result($category_data, 0, "parent_category_id");

?><a href="?category=<?=$show_parent_id?>" style="color: blue; text-decoration: none;"><img src="images/gif/blue.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b></a><?php } ?></div>
</div>


<div align="left" style="border: 1px solid black; background-color: #ededd0; width: <?=$admin_config['menu_width']?>px; height: 100%;">

<div align="left" style="margin: 6px;">

	<div align="center" style="padding-bottom: 10px; width: 100%;">
<b style="position: relative; bottom: 3px;"><a href="?category=0&list_quantity=<?=$_SESSION['list_quantity']?>">Product List</a></b></div>

<?php
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
<div align="center" style="width: 122px; text-decoration: none; border-bottom-width: 1px; border-bottom-style: solid;"><?php if ( $_SESSION['category'] == 0 ) { ?><img src="images/gif/grey.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b><?php } 
else {

$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_SESSION['category']."'");

$show_parent_id = mysql_result($category_data, 0, "parent_category_id");

?><a href="?category=<?=$show_parent_id?>" style="color: blue; text-decoration: none;"><img src="images/gif/blue.arrow.up.gif" alt="" width="9" height="6" hspace="4" vspace="0" border="0" align="bottom"><b class="parent_listings">Parent Listings&nbsp;</b></a><?php } ?></div>
<?php
}
?>
</div>

</div>
</td>
</tr>

<tr>
	<td valign="top" colspan='4' style="width: 100%; padding-top: 3px;">
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
	<div align="right" style="position: relative; float: right; bottom: 4px; white-space: nowrap;">&nbsp;<b style="font-size: <?=$font_7?>px; color: green;">
<?php

echo "$current_list &nbsp;of&nbsp; $product_num product(s) $num_list_debug";

?></b>&nbsp;<select style="font-size: <?=$font_7?>px; font-weight: bold;" onchange="window.location.href = '?category=<?=$_SESSION['category']?>&list_quantity=' + this.value + '&list_location=0';">
<option value="50"<?php if ( $_SESSION['list_quantity'] == 50 ) { echo " selected"; } ?>> 50 / page </option>
<option value="100"<?php if ( $_SESSION['list_quantity'] == 100 ) { echo " selected"; } ?>> 100 / page </option>
<option value="150"<?php if ( $_SESSION['list_quantity'] == 150 ) { echo " selected"; } ?>> 150 / page </option>
<option value="200"<?php if ( $_SESSION['list_quantity'] == 200 ) { echo " selected"; } ?>> 200 / page </option>
</select></div>
	
	</td>
</tr>
<?php
if ( $num2 ) {
?>
<tr>
	<td colspan="4" class='product_td_height' style='width: 100%; background-color: #c9c4c4; border-left: 1px solid black; border-right: 1px solid black;  border-top: 1px solid black;'><div align='left'>&nbsp;&nbsp;<b>Sub-Categories within "<?=$render_category_name?>"</b></div></td>
</tr>
<?php
}
?>

<tr>
<td valign="top" colspan='4' style="width: 100%;">
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
	<div align="right" style="position: relative; float: right; white-space: nowrap;">&nbsp;<b style="font-size: <?=$font_7?>px; color: green;">
<?php

echo "$current_list &nbsp;of&nbsp; $product_num product(s) $num_list_debug";

?></b>&nbsp;<select style="font-size: <?=$font_7?>px; font-weight: bold;" onchange="window.location.href = '?category=<?=$_SESSION['category']?>&list_quantity=' + this.value + '&list_location=0';">
<option value="50"<?php if ( $_SESSION['list_quantity'] == 50 ) { echo " selected"; } ?>> 50 / page </option>
<option value="100"<?php if ( $_SESSION['list_quantity'] == 100 ) { echo " selected"; } ?>> 100 / page </option>
<option value="150"<?php if ( $_SESSION['list_quantity'] == 150 ) { echo " selected"; } ?>> 150 / page </option>
<option value="200"<?php if ( $_SESSION['list_quantity'] == 200 ) { echo " selected"; } ?>> 200 / page </option>
</select></div>
	</td>
</tr>
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