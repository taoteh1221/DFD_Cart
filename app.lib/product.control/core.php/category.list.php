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




/////////////////////////////////////////////////////////////////////////////////
////////// F O R M  S E L E C T   F I E L D S ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
if ( $category_html == "form_select" ) {
?>
<select id="form_select" name="category" style="position: relative; z-index: 1; font-size: <?=$font_7?>px;">
<option value="0"<?php if ( $_SESSION['category'] == 0 ) { echo ' style="color: red;"'; if ( !$_GET['update'] ) { echo ' selected'; } } ?>> All Products </option>
<?php

echo category_list(0, $category_html, $parent_category_array, '', '', '');

?>
</select>
<?php

}



/////////////////////////////////////////////////////////////////////////////////
////////// V E R T I C A L   H R E F   L I N K S ////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
elseif ( $category_html == "href_vertical" ) {

echo category_list(0, $category_html, $parent_category_array, $set_depth, '', '', '');

}



////////////////////////////////////////////////////////////////////////////////////////////////
////////// F L Y - O U T   V E R T I C A L   H R E F   L I N K S ////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
elseif ( $category_html == "fly_out_href_vertical" ) {
?>
<div style="position: relative; width: 100%;">
<?php

echo category_list(0, $category_html, $parent_category_array, $set_depth, '', '', '');

?>
</div>
<?php
}



/////////////////////////////////////////////////////////////////////////////////
////////// H O R I Z O N T A L   H R E F   L I N K S ////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
elseif ( $category_html == "href_horizontal" ) {
?>
<b style="color: red; font-size: <?=$font_5?>px;"><a href="?category=0&list_quantity=<?=$_SESSION['list_quantity']?>&list_location=0" style="color: red; font-size: <?=$font_5?>px;">Product List</a> &nbsp;&gt&nbsp; 
<?php
echo category_depth_scan($_SESSION['category'], 1, '', '', '');
?>
</b>
<?php
}




/////////////////////////////////////////////////////////////////////////////////
////////// H O R I Z O N T A L   P A T H   T E X T //////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
elseif ( $category_html == "text_horizontal" ) {
?>
Product List &nbsp;&gt&nbsp; 
<?php
echo category_depth_scan($_SESSION['category'], 2, '', '', '');
}
?>