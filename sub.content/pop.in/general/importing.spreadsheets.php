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
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Product Importing Overview</b>';
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

&nbsp;<b>Products can be imported</b> with <a href="ms.office.summary.php" target="_self">Microsoft Office</a> or <a href="open.office.summary.php" target="_self">Open Office</a>, using a plain text tab-delimited spreadsheet format. Blank templates to get started with are available on the "Import Products" page in the admin area.
<p> &nbsp;<b>The system is designed to stop spreadsheet importing if uncomplete or corrupt data is believed to exist</b>, and it will tell you which rows <i>appear</i> affected. Be careful to avoid any junk data or spaces near your product information (for instance, extra space or data <i>to the side or below</i> the product rows). Just becuase a row says it's affected doesn't always mean it's this <i>exact</i> row...it could be at the end of the above row, or if you really misformatted your spreadsheet badly the problem could be anywhere. In short, it pays to make sure you format your spreadsheet correctly. <i>Furthermore, if your spreadsheet contains products ids but you do not have product ids enabled (in the "Configure" section of the admin area), or visa versa, you will need to change the setting before that spreadsheet will import products to your inventory.</i></p>
<p> &nbsp;<b>You should only format product description text with bold and / or italics</b>, and only in html <i>or</i> with the online formatter available within "Add Product" and "Update" links on the web site.</p>
<p> &nbsp;<b>Never format <i>prices</i> with html or anything else</b>, or you will corrupt the price data.</p>
<p> &nbsp;<b>To update products that already exist</b>, <i>the product description needs to be exactly the same text</i> (bold / italic text attributes can differ if they are in html format or done with the online editor, as well as any html or non-html spacing differences too). One way to update existing products without your original spreadsheet is to export and download a spreadsheet of the current products, then update your prices, category path, etc, and import the spreadsheet back into the site. <i>If DFD Cart detects no difference between the spreadsheet data and the online inventory, no updates will occur.</i></p>
<p> &nbsp;<b>You should always backup your current online products with the export feature first, before importing updates to the live web site</b>, and double check that your spreadsheet is formatted correctly and there is no stray data below or to the side of your product list before attempting to import it at the web site. If you don't do either of these, you may end up having to delete a bunch of incorrect or null items not properly imported (if your errors aren't caught by the import program failsafes), and you won't be able to restore your current live products with an exported backup file if you didn't make a backup beforehand.</p>



<div style="padding: 5px;"></div>
</div>


</body>
</html>