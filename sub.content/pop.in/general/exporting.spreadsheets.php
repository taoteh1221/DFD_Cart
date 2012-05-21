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
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Product Exporting Overview</b>';
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


&nbsp;<b>Products can be exported to a plain text tab-delimited spreadsheet</b> in Microsoft Office or Open Office format.
<p> &nbsp;<b>You can backup live product data</b> with the export system...so if something goes wrong, you can restore it to it's original condition (before <i>importing</i> anything, you should always do this as a precaution). 
<br /> &nbsp;The system will automatically save your backup file online, and list that backup file on the export page with a download link to it (then you can save a copy to your computer too). You can create or delete these files as many times as you want to.</p>
<p> &nbsp;<b>If you want to update prices and don't have your original import sheet handy</b>, or it's on someone else's computer, you can create and download a fresh export backup and edit the prices, category paths, etc (the product name must stay the same)...then you can <i>import</i> the updated products back into the web site, and it will update these pre-existing products on the web site.</p>

<p> &nbsp;<b>If you want an up-to-date spreadsheet with all your products listed in alphabetical order</b>, the exporter will do this for you automatically...then you can find what your looking for in long product lists easier (see the <a href="tips.and.tricks.php" target="_self">tips and tricks section</a> for more spreadsheet editing insight).</p>



<div style="padding: 5px;"></div>
</div>


</body>
</html>