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




?>
<style type="text/css">
/*  CSS for the loading message **START**  */
/*  Load all related CSS internally to avoid possible MSIE bug  */
div.answers_class
{
/* Browser-specific **START** */
<?php if (eregi("Opera", $_SERVER['HTTP_USER_AGENT'])) 
{ ?>
/* Opera */
position: fixed;
top: 0%;
<?php }
elseif ( eregi("MSIE 5", $_SERVER['HTTP_USER_AGENT'])
|| eregi("MSIE 6", $_SERVER['HTTP_USER_AGENT']) ) 
{ ?>
/*  MSIE 5+6 ONLY...MSIE 7 will support fixed positioning */
position: absolute;
/* HTML VERSION top: expression( ( ignore_me = document.body.scrollTop ) + 'px' );  */
/* XHTML VERSION */ top: expression( ( ignore_me = document.documentElement.scrollTop ) + 'px' );
<?php }
elseif ( eregi ( "/php-bin/pmos(.*)", $_SERVER['REQUEST_URI'] )
&&  eregi("(.*)msie(.*)", strtolower($_SERVER['HTTP_USER_AGENT'])) ) 
{ ?>
/*  PMOS APP'S NON-XHTML INTEGRATION DIDN'T LIKE FIXED POSITIONING IN MSIE */
position: absolute;
/* HTML VERSION top: expression( ( ignore_me = document.body.scrollTop ) + 'px' );  */
/* XHTML VERSION */ top: expression( ( ignore_me = document.documentElement.scrollTop ) + 'px' );
<?php }
else
{ ?>
/* FireFox, etc */
position: fixed;
top: 0%;
<?php } ?>
/* Browser-specific **END** */  
z-index: 300; /*  Layer 2 on the CSS layout, so "z-index: 1;" items don't show transparently  */
margin: 6% 10%;
visibility: hidden;
width: 0px; /* Shrunk while invisible for FireFox Ghost Image Bug */
height: 0px; /* Shrunk while invisible for FireFox Ghost Image Bug */
background-color: #F5EFE9;
padding: 0px; /* Shrunk while invisible for FireFox Ghost Image Bug */
border: 0px solid #808080; /* Shrunk while invisible for FireFox Ghost Image Bug */
font-size: <?=$font_5?>px;
color: black;
}
/*  CSS for the loading message **END**  */
</style>