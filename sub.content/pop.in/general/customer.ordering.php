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




$file_depth = 3;
require("../../../main.config.php");

?><html>
<head>
<title></title>
<link type="text/css" href="<?=$set_depth?>app.lib/product.control/core.css/answer.box.iframe.css" rel="stylesheet" title="Answer Box IFrame CSS" />
<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>
<!-- INSERT THIS ANSWER BOX'S TITLE BELOW -->
<script language="javascript" type="text/javascript">
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Customer Ordering</b>';
</script><base target="_top">
</head>
<body bgcolor="#F5EFE9" onload="loading_message_control();">

<!-- Loading message **START** -->
<!-- onload="loading_message_control();" TO BODY AFTER BACKGROUND TO AVIOD POSSIBLE MSIE BUG -->
<!-- <STYLE> *INTERNALLY* AVOIDING POSSIBLE MSIE BUG TOO -->
<script language="javascript" type="text/javascript">
loading_message_display();
</script>
<!-- Loading message **END** -->

<div align="left" style="font-size: <?=$font_7?>px; margin-left: 2px; margin-right: 2px;">


<div style="padding: 5px;"></div>
 &nbsp;<b>Hitting the TAB key twice after you put the cursor inside a quantity box</b> will toggle through every next quantity box...you will still need to press each product's "Update" button <i>after you have filled in all your quantities</i>, but this may help add those products to your order faster. Also, SHIFT + TAB will go in the opposite direction if you need to go back a spot or two.

<div style="padding: 12px;"></div>
 &nbsp;<b>Your <i>checkout page</i> has delete buttons</b>, to remove items from your order quickly:
<div style="padding: 3px;"></div>

<img src="<?=$set_depth?>images/png/ordering.help/ordering.delete.2.png" alt="" width="185" height="47" hspace="0" vspace="0" border="0" align="top">

<div style="padding: 12px;"></div>
 &nbsp;<b>Besides adding and updating quantities</b>, you can <i>delete items without having to visit your checkout page</i> by setting them to zero:
<div style="padding: 3px;"></div>

<img src="<?=$set_depth?>images/png/ordering.help/ordering.delete.1.png" alt="" width="147" height="48" hspace="0" vspace="0" border="0" align="top">

<div style="padding: 5px;"></div>
</div>


</body>
</html>