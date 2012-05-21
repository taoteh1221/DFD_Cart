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
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Import Products With Microsoft Office</b>';
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

&nbsp;After you download the Microsoft Office Template to your computer (from the "Import Products" page), open the document inside Microsoft Office...
<p>
You may get a prompt. Data type should be set to "Delimited", continue...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.1.png" alt="" width="259" height="143" hspace="0" vspace="0" border="0" align="top">

<p>
Delimiters should be set to "Tab" <i>only</i>, with the text qualifier as a double quote, continue...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.2.png" alt="" width="230" height="150" hspace="0" vspace="0" border="0" align="top">

<p>
Column data format should be set to "General", and click finish...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.3.png" alt="" width="182" height="120" hspace="0" vspace="0" border="0" align="top">

<p>
To successfully import products to the online ordering system, each product's layout <i>must be this exact format</i> inside the spreadsheet:<br><b>(also, END_HERE must always be used to designate the end of each product's data row, and it must always have an underscore in the middle)</b>
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/general.spreadsheet.screen.1.png" alt="" width="515" height="74" hspace="0" vspace="0" border="0" align="top">
<p>
When you are done editing your products, you must now save the document in the proper format. <b>Before clicking the "Save" button</b>, go to "Excel Options", "Save", find the menu for "Save files in this format", and select "Text - Tab delimited - .txt" ...Click "Ok" to continue...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.4.png" alt="" width="246" height="239" hspace="0" vspace="0" border="0" align="top">

<p>
Now you can click the "Save" button. You may get a prompt like this below...click "Ok"...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.5.png" alt="" width="260" height="91" hspace="0" vspace="0" border="0" align="top">

<p>
and you may get another prompt warning you about compatibility...click "Yes"...
</p>
<img src="<?=$set_depth?>images/png/spreadsheet.help/ms.office.screen.6.png" alt="" width="270" height="138" hspace="0" vspace="0" border="0" align="top">

<p>You are now ready to import your updated product list! &nbsp;:)</p>

<div style="padding: 5px;"></div>
</div>


</body>
</html>