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



/////////////////////////////START OF CONTENT//////////////////////////////////////////
?>

<style type="text/css">

/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/




/*  Standard Objects *START*  */

body {
margin: 0px;
font-size: <?=$font_6?>px;
}

td {
font-size: <?=$font_6?>px;
}



/*  Standard Objects *END*  */


/* //////////////////////////////////////////// */
/* //////////////////////////////////////////// */


/*  Classes *START*  */
  
.js_nav_links {
position: relative;
padding-top: 3px;
padding-bottom: 3px;
padding-left: 6px;
padding-right: 6px;
white-space: nowrap;
z-index: 100;
}

.hidden_menus {
opacity: 0.95;
filter: alpha(opacity=95); 
padding-top: 3px;
padding-bottom: 3px;
position: relative;
z-index: 100;
display: none;
border: 2px solid black;
background-color: #d0d611;
}

.hidden_menus2 {
padding-top: 3px;
padding-bottom: 3px;
position: relative;
z-index: 100;
display: none;
border: 2px solid black;
background-color: #d0d611;
}

.hidden_left_side_menus {
opacity: 0.95;
filter: alpha(opacity=95); 
padding-top: 3px;
padding-bottom: 3px;
position: absolute;
z-index: 100;
right: 50px;
display: none;
border: 2px solid #696262;
background-color: #eda45c;
}

.hidden_right_side_menus {
opacity: 0.95;
filter: alpha(opacity=95); 
padding-top: 3px;
padding-bottom: 3px;
position: absolute;
z-index: 100;
left: 50px;
display: none;
border: 2px solid #696262;
background-color: #eda45c;
}

.button_span_link
{ color: red;
text-decoration: none;
cursor: pointer;
font-size: <?=$font_8?>px;
font-family: Helvetica, Symbol, Serif;
font-weight: bold;
z-index: 1;
position: relative;
bottom: 0px;
}

.button_span_link_small
{ color: red;
text-decoration: none;
border-bottom-width: 1px;
border-bottom-style: dotted;
cursor: pointer;
font-size: <?=$font_9?>px;
font-family: Helvetica, Symbol, Serif;
font-weight: bold;
z-index: 1;
position: relative;
bottom: 2px;
left: 2px;
}

.category_links {
padding: 4px;
}

.category_links2 {
position: relative;
padding: 4px;
}

.category_links3 {
position: relative;
}

.dropdown_bold {
font-weight: bold;
}

.dropdown_unbold {
font-weight: normal;
}

input.small_product_buttons {
font-size: <?=$font_9?>px;
font-weight: bold;
}

.input_text_border {
border: 1px solid #808080;
}

.list_nav {
font-size: <?=$font_7?>px;
font-weight: bold;
padding-left: 2px;
padding-right: 2px;
}

.list_nav_selected {
font-size: <?=$font_7?>px;
font-weight: bold;
padding-left: 2px;
padding-right: 2px;
color: red;
}

.parent_listings {
font-size: <?=$font_7?>px;
}

.product_td_height {
height: 30px;
}


.quantity_areas {
border: 1px solid #808080;
font-size: <?=$font_9?>px;
font-weight: bold;
}

.text_alert {
color: red;
}

.title_nav {
font-weight: bold;
font-size: <?=$font_5?>px;
}

/*  Classes *END*  */


/* //////////////////////////////////////////// */
/* //////////////////////////////////////////// */


/*  IDs *START*  */

#dfd_footer {
font-size: <?=$font_9?>px;
font-weight: bold;
padding: 12px;
color: #808080;
}

#dfd_footer a {
color: #808080;
}

div#div_one {
color: white;
}

td#category_wrapper a { color: #29583f; }

tr#top_nav a {
color: white;
}


/*  IDs *END*  */


/* //////////////////////////////////////////// */
/* //////////////////////////////////////////// */

</style>
