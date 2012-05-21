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


if ( !$_GET['key'] ) {
echo 'Invalid security key';
exit;
}



$file_depth = 3;
require("../../../main.config.php");

?><html>
<head>
<title></title>
<link type="text/css" href="<?=$set_depth?>app.lib/product.control/core.css/answer.box.iframe.css" rel="stylesheet" title="Answer Box IFrame CSS" />

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/scanning.js" language="javascript" type="text/javascript"></script>

<!-- INSERT THIS ANSWER BOX'S TITLE BELOW -->

<script language="javascript" type="text/javascript">
parent.document.getElementById("answers_title").innerHTML = '<b style="font-size: <?=$font_5?>px; color: #BC5938;">Category Creation</b>';
</script><base target="_top">
</head>
<body bgcolor="#F5EFE9" onload="loading_message_control(); document.category_editing.create_category_name.focus();">

<!-- Loading message **START** -->
<!-- onload="loading_message_control();" TO BODY AFTER BACKGROUND TO AVIOD POSSIBLE MSIE BUG -->
<!-- <STYLE> *INTERNALLY* AVOIDING POSSIBLE MSIE BUG TOO -->
<script language="javascript" type="text/javascript">
loading_message_display();
</script>
<!-- Loading message **END** -->

<div align="left" style="font-size: <?=$font_7?>px; margin-left: 2px; margin-right: 2px;">



<form name="category_editing" action="<?=$set_depth?>admin/index.php?key=<?=$_SESSION['sec_key']?>" method="post">
<div style="padding: 6px;"></div>
<div align="center" style="padding: 3px;"><b><u>***Creating a new category***</u></b></div>
<?=$alert_status?>
<div style="padding-top: 9px;"></div>

<div align="center"><b>Category Name:</b><br />
<input type="text" size="25" name="create_category_name" /></div>

<div style="padding: 9px;"></div>


<div align="center"><b>As a Sub-Category Within:</b><br />
<?php
$category_html = 'form_select';
require ("".$set_depth."app.lib/product.control/core.php/category.list.php");
?></div>

<div style="padding-top: 15px;"></div>
<div align="center">
<input type='button' value='Cancel' onclick='parent.document.data_control.submit();' />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Create Category" onclick="scan_product_form(document.category_editing.create_category_name, '', 'add_category', 1);" />
<input type="hidden" name="return_category" value="<?=$_GET['category']?>" />
<input type="hidden" name="submit_new_data" value="yes" />
<input type="hidden" name="validation_attempt" value="add" /></div>
</form>


<div style="padding: 5px;"></div>
</div>


</body>
</html>