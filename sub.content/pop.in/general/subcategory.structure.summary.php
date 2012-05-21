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
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Subcategory Structure</b>';
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

<div align="center" style="padding: 6px;"><a href="help.index.php" target="_self"><b>Return to Help Index</b></a></div>

&nbsp;<b>Bla bla bla</b>, yada yada yada.


<div style="padding: 5px;"></div>
</div>


</body>
</html>