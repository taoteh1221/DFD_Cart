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

require ("".$set_depth."app.lib/product.control/core.php/globals.php");


$move_category_count = 0;
$move_product_count = 0;
$delete_item_count = 0;
$category_description_parsing = array();
$product_name_parsing = array();
$subcategories_product_name_parsing = array();
$find_subcategories_array = array();


// Updating a category

if ( $_POST['category_update_id'] && $_POST['category_update_id'] != $_POST['category'] ) {

mysql_query("UPDATE category_structure SET category_name = '".$_POST['create_category_name']."', parent_category_id = '".$_POST['category']."' WHERE id = '".$_POST['category_update_id']."'");


$alert_status = "<p><b><font color='red'>Category Updated...</font></b></p><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_POST['return_category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";

header("location: $set_depth" . "admin/index.php?key=".$_SESSION['sec_key']."&category=" . $_POST['return_category']);

}
elseif ( $_POST['category_update_id'] && $_POST['category_update_id'] == $_POST['category'] ) {

$alert_status = "<p><b><font color='red'>The category path '".eregi_replace(">", " > ", category_depth_scan($_POST['category'], '', '', 1, ''))."' cannot be moved into itself...please choose different paths.</font></b></p><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_POST['return_category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";

}


// Updating a product
if ($_GET['update']) {

$update_add_id = $_GET['update'];

// Connect to DB if database-contents-request is posted...

$update_product_data = mysql_query("SELECT * FROM product_list WHERE id='$update_add_id'");

	if ($update_product_data) {
	$num = mysql_numrows($update_product_data);
	}




	if ($num) {

		for ($i = 0; $i < $num;) {
		$update_name = mysql_result($update_product_data, $i, "product_name");
		$update_product_id = mysql_result($update_product_data, $i, "product_id");
		$update_price = mysql_result($update_product_data, $i, "unit_price");
		$update_parent_id = mysql_result($update_product_data, $i, "parent_category_id");
		$i = $i + 1;
		}
	
	}

	else {
	$alert_status = "<b><font color='red'>Sorry, unknown error...</font></b>";
	}

}



// Updating the database
if ($_SESSION['change_data'] == "get_update" && $_POST['update_current_data']
&& $_POST['update_price'] && $_POST['update_name']) {


$update_name = eregi_replace("	", "", $update_name); // Failsafe TAB removal
// Format xhtml tags into WYSIWYG editor...
$update_name = eregi_replace("\[ib]", "<i><b>", $update_name);
$update_name = eregi_replace("\[/ib]", "</b></i>", $update_name);
$update_name = eregi_replace("\[b]", "<b>", $update_name);
$update_name = eregi_replace("\[/b]", "</b>", $update_name);
$update_name = eregi_replace("\[i]", "<i>", $update_name);
$update_name = eregi_replace("\[/i]", "</i>", $update_name);
$update_name = eregi_replace("\r\n", "<br />\r\n", $update_name);
$update_name = eregi_replace("  ", "&nbsp;&nbsp;", $update_name);
$update_name = eregi_replace("\[url=", "<a href=\"", $update_name);
$update_name = eregi_replace("\[/url]", "</a>", $update_name);
$update_name = eregi_replace("]", "\">", $update_name);

$update_product_id = strip_name_format($_POST['product_id_p']);

$update_price = eregi_replace("\\\$", "", $update_price); // Remove any dollar sign
$update_price = eregi_replace(" ", "", $update_price); // Remove spaces

// Connect to DB if database-contents-request is posted...


// Update the db...
/*
Format XX.XX for price search compatibility, but with no commas above 3 digits to the left of the decimal point, ***so order calculations aren't ruined***
*/
mysql_query("UPDATE product_list SET product_name = '$update_name', product_id = '$update_product_id', unit_price = '".sprintf("%01.2f", $update_price)."', parent_category_id = '".$_POST['category']."' WHERE id = '$update_add_id'");




$alert_status = "<b><font color='red'>Product Updated...</font></b>";
$_SESSION['update_complete'] = "yes";


}






// Delete single SUBCATEGORY AND ITS SUBCATEGORIES AND PRODUCTS from DB...
if ($_GET['delete_cat']) {

$update_add_id = $_GET['delete_cat'];

$find_subcategories_array = array($update_add_id);

$find_subcategories_array = array_merge($find_subcategories_array, all_subcategories($update_add_id));

	foreach ( $find_subcategories_array as $id_value ) {
	
	$listing_category_data = mysql_query("SELECT * FROM category_structure WHERE id='$id_value'");
	
	$listing_name = mysql_result($listing_category_data, 0, "category_name");
	
		if (  $listing_name ) {
		$category_description_parsing[] = $listing_name;
		
		mysql_query("DELETE FROM category_structure WHERE id='$id_value'");
		}
	
	$listing_product_data = "SELECT * FROM product_list WHERE parent_category_id='$id_value'";
	$subcategory_product_data = mysql_query($listing_product_data);
	
		if ($subcategory_product_data) {
		$subcategory_product_num = mysql_numrows($subcategory_product_data);
		}
	
		if ($subcategory_product_num) {
		
			for ($i = 0; $i < $subcategory_product_num;) {
			$subcategory_product_id = mysql_result($subcategory_product_data, $i, "id");
			$subcategory_product_name = mysql_result($subcategory_product_data, $i, "product_name");
			
				if ( $subcategory_product_name ) {
				$subcategories_product_name_parsing[] = $subcategory_product_name;
				
				mysql_query("DELETE FROM product_list WHERE id='$subcategory_product_id'");
				}
			
			$i = $i + 1;
			}
		
		}
	
	}



	// The alphabetical listing of deleted SUBCATEGORIES...
	asort($category_description_parsing);
	foreach ($category_description_parsing as $parse_value) {
	
	$delete_message = $delete_message . $parse_value . "&nbsp;&nbsp;(category)&nbsp;<br />";
	
	$delete_item_count = $delete_item_count + 1;
	}


	// The alphabetical listing of deleted PRODUCTS...
	asort($subcategories_product_name_parsing);
	foreach ($subcategories_product_name_parsing as $parse_value) {
	
	$delete_message = $delete_message . $parse_value . "&nbsp;&nbsp;(product)&nbsp;<br />";
	
	$delete_item_count = $delete_item_count + 1;
	}


$alert_status = "<div align='left'><b><font color='red'>$delete_item_count item(s) and or categories Deleted...<br />$delete_message</font></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";


}




// Delete single PRODUCT from DB...
if ($_GET['delete']) {

$update_add_id = $_GET['delete'];

$listing_product_data = mysql_query("SELECT * FROM product_list WHERE id='$update_add_id'");

$listing_name = mysql_result($listing_product_data, 0, "product_name");

mysql_query("DELETE FROM product_list WHERE id='$update_add_id'");


$alert_status = "<div align='left' style='padding-top: 10px;'><b><font color='red'>\"$listing_name\" Deleted...</font></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";



}




// Moving SUBCATEGORIES AND THEIR PRODUCTS in the database to another category...
if ( $_POST['selected_categories'] && $_POST['move_them'] ) {


// Remove the single pipes from each end
$_POST['selected_categories'] = substr_replace($_POST['selected_categories'], "", 0, 1);
$_POST['selected_categories'] = substr_replace($_POST['selected_categories'], "", -1, 1);


// Convert the dynamically-created form value into a php array...
$selected_categories = explode("||", $_POST['selected_categories']);

	if ( in_array($_POST['move_category'], $selected_categories) ) {
	$stop_script = 1;
	}

	if ( !$stop_script ) {
	
		/*
		Create a new array based on SUBCATEGORY descriptions only,
		to control an alphabetically ordered results display...
		*/
		foreach ( $selected_categories as $move_value ) {
	
		$listing_category_data = mysql_query("SELECT * FROM category_structure WHERE id='$move_value'");
		
		
		$listing_id = mysql_result($listing_category_data, 0, "id");
		$listing_name = mysql_result($listing_category_data, 0, "category_name");
		$listing_parent_id = mysql_result($listing_category_data, 0, "parent_category_id");
		
		$category_description_parsing[] = $listing_name;
		
		}
	
	
		// Move these products in the database...
		foreach ( $selected_categories as $move_value ) {
		
		mysql_query("UPDATE category_structure SET parent_category_id = '".$_POST['move_category']."' WHERE id = '$move_value'");
		
		$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_POST['move_category']."'");
		$new_category_id = mysql_result($category_data, 0, "id");
		$new_category_name = mysql_result($category_data, 0, "category_name");
		$new_parent_id = mysql_result($category_data, 0, "parent_category_id");
		$show_links_main = "<a href='?key=".$_SESSION['sec_key']."&category=$new_category_id' style='color: #f44a1d;'>$new_category_name</a>";
		
		$move_category_count = $move_category_count + 1;
		
		}
		
	
		// The alphabetical listing of moved items...
		asort($category_description_parsing);
		foreach ($category_description_parsing as $parse_value) {
		
		$move_message = $move_message . $parse_value . "&nbsp;&nbsp;(category)&nbsp;<br />";
				
		}
	
		if ( !$_POST['selected_products'] ) {
		$show_links = "<a href='?key=".$_SESSION['sec_key']."&category=0' style='color: #f44a1d;'>Product List</a> &nbsp;&gt&nbsp; " . category_depth_scan($_POST['move_category'], 1, '', '', '') . $show_links_main;
		}
	
	$alert_status = "<div align='left' style='padding-top: 10px;'><b><font color='#f44a1d'>$move_category_count item(s) moved to directory path &nbsp;\"$show_links\"&nbsp;...<br /></font><p style='color: red;'>$move_message</p></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_POST['move_category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
	$_SESSION['update_complete'] = "yes";
	
	}


	else {
	$alert_status = "<div align='left' style='padding-top: 10px;'><b><font color='red'>The category path '".eregi_replace(">", " > ", category_depth_scan($_POST['move_category'], '', '', 1, ''))."' cannot be moved into itself...please choose different paths.</font></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Return to Category</b></a></div></p>";
	$_SESSION['update_complete'] = "yes";
	}



}





// Moving multiple PRODUCTS in the database to another category...
if ( !$stop_script && $_POST['selected_products'] && $_POST['move_them'] ) {


// Remove the single pipes from each end
$_POST['selected_products'] = substr_replace($_POST['selected_products'], "", 0, 1);
$_POST['selected_products'] = substr_replace($_POST['selected_products'], "", -1, 1);


// Convert the dynamically-created form value into a php array...
$selected_products = explode("||", $_POST['selected_products']);


	/*
	Create a new array based on PRODUCT descriptions only,
	to control an alphabetically ordered results display...
	*/
	foreach ( $selected_products as $move_value ) {

	$listing_product_data = mysql_query("SELECT * FROM product_list WHERE id='$move_value'");
	
	
	$listing_id = mysql_result($listing_product_data, 0, "id");
	$listing_name = mysql_result($listing_product_data, 0, "product_name");
	$listing_price = mysql_result($listing_product_data, 0, "unit_price");
	$listing_parent_id = mysql_result($listing_product_data, 0, "parent_category_id");
	
	$product_name_parsing[] = $listing_name;
	
	}


	// Move these products in the database...
	foreach ( $selected_products as $move_value ) {
	
	mysql_query("UPDATE product_list SET parent_category_id = '".$_POST['move_category']."' WHERE id = '$move_value'");
	
	$category_data = mysql_query("SELECT * FROM category_structure WHERE id = '".$_POST['move_category']."'");
	$new_category_id = mysql_result($category_data, 0, "id");
	$new_category_name = mysql_result($category_data, 0, "category_name");
	$new_parent_id = mysql_result($category_data, 0, "parent_category_id");
	$show_links_main = "<a href='?key=".$_SESSION['sec_key']."&category=$new_category_id' style='color: #f44a1d;'>$new_category_name</a>";
	
	$move_product_count = $move_product_count + 1;
	
	}
	

	// The alphabetical listing of moved items...
	asort($product_name_parsing);
	foreach ($product_name_parsing as $parse_value) {
	
	$move_message = $move_message . $parse_value . "&nbsp;&nbsp;(product)&nbsp;<br />";
			
	}

$move_items_count = $move_category_count + $move_product_count;

$show_links = "<a href='?key=".$_SESSION['sec_key']."&category=0' style='color: #f44a1d;'>Product List</a> &nbsp;&gt&nbsp; " . category_depth_scan($_POST['move_category'], 1, '', '', '') . $show_links_main;


$alert_status = "<div align='left' style='padding-top: 10px;'><b><font color='#f44a1d'>$move_items_count item(s) moved to directory path &nbsp;\"$show_links\"&nbsp;...<br /></font><p style='color: red;'>$move_message</p></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_POST['move_category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";


}





// Deleting multiple SUBCATEGORIES AND THEIR PRODUCTS from the database...
if ( $_POST['selected_categories'] && $_POST['delete_them'] ) {


// Remove the single pipes from each end
$_POST['selected_categories'] = substr_replace($_POST['selected_categories'], "", 0, 1);
$_POST['selected_categories'] = substr_replace($_POST['selected_categories'], "", -1, 1);


// Convert the dynamically-created form value into a php array...
$selected_categories = explode("||", $_POST['selected_categories']);



	// Put every category and it's subcategories into an array for processing...
	foreach ( $selected_categories as $scan_value ) {
	
	$find_subcategories_array = array_merge($find_subcategories_array, all_subcategories($scan_value));
	
	}


$selected_categories = array_merge($selected_categories, $find_subcategories_array);

	/*
	Create a new array based on product descriptions only,
	to control an alphabetically ordered results display...
	*/
	
	// Delete these SUBCATEGORIES from the database after getting category names for output summary...
	foreach ( $selected_categories as $id_value ) {
	
	$listing_category_data = mysql_query("SELECT * FROM category_structure WHERE id='$id_value'");
	
	$listing_name = mysql_result($listing_category_data, 0, "category_name");
	
		if (  $listing_name ) {
		$category_description_parsing[] = $listing_name;
		
		mysql_query("DELETE FROM category_structure WHERE id='$id_value'");
		}
	
	
	
	$listing_product_data = "SELECT * FROM product_list WHERE parent_category_id='$id_value'";
	$subcategory_product_data = mysql_query($listing_product_data);
	
		if ($subcategory_product_data) {
		$subcategory_product_num = mysql_numrows($subcategory_product_data);
		}
	
		if ($subcategory_product_num) {
		
			for ($i = 0; $i < $subcategory_product_num;) {
			$subcategory_product_id = mysql_result($subcategory_product_data, $i, "id");
			$subcategory_product_name = mysql_result($subcategory_product_data, $i, "product_name");
			
				if ( $subcategory_product_name ) {
				$subcategories_product_name_parsing[] = $subcategory_product_name;
				
				mysql_query("DELETE FROM product_list WHERE id='$subcategory_product_id'");
				}
			
			$i = $i + 1;
			}
		
		}
	
	
	}




	// The alphabetical listing of deleted SUBCATEGORIES...
	asort($category_description_parsing);
	foreach ($category_description_parsing as $parse_value) {
	
	$delete_message = $delete_message . $parse_value . "&nbsp;&nbsp;(category)&nbsp;<br />";
	
	$delete_item_count = $delete_item_count + 1;
	}


	// The alphabetical listing of deleted PRODUCTS...
	asort($subcategories_product_name_parsing);
	foreach ($subcategories_product_name_parsing as $parse_value) {
	
	$delete_message = $delete_message . $parse_value . "&nbsp;&nbsp;(product)&nbsp;<br />";
	
	$delete_item_count = $delete_item_count + 1;
	}



$alert_status = "<div align='left'><b><font color='red'>$delete_item_count item(s) and or categories Deleted...<br />$delete_message</font></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";


}





// Deleting multiple PRODUCTS from the database...
if ( $_POST['selected_products'] && $_POST['delete_them'] ) {


// Remove the single pipes from each end
$_POST['selected_products'] = substr_replace($_POST['selected_products'], "", 0, 1);
$_POST['selected_products'] = substr_replace($_POST['selected_products'], "", -1, 1);


// Convert the dynamically-created form value into a php array...
$selected_products = explode("||", $_POST['selected_products']);

	/*
	Create a new array based on product descriptions only,
	to control an alphabetically ordered results display...
	*/
	
	// Delete these PRODUCTS from the database after getting their names for output summary...
	foreach ( $selected_products as $delete_value ) {

	$listing_product_data = mysql_query("SELECT * FROM product_list WHERE id='$delete_value'");
	
	
	$listing_name = mysql_result($listing_product_data, 0, "product_name");
	
		if ( $listing_name ) {
		$product_name_parsing[] = $listing_name;
		
		mysql_query("DELETE FROM product_list WHERE id='$delete_value'");
		}
	
	
	}


	// The alphabetical listing of deleted items...
	asort($product_name_parsing);
	foreach ($product_name_parsing as $parse_value) {
	
	$delete_message = $delete_message . $parse_value . "&nbsp;&nbsp;(product)&nbsp;<br />";
	
	$delete_item_count = $delete_item_count + 1;
	}

$alert_status = "<div align='left'><b><font color='red'>$delete_item_count item(s) and or categories Deleted...<br />$delete_message</font></b></div><p><div align='center'><a href='index.php?key=".$_SESSION['sec_key']."&category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."'><b>Continue</b></a></div></p>";
$_SESSION['update_complete'] = "yes";


}


?>