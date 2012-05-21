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





$file_depth = 1;
$security_level = 1;
require("../main.config.php");

$admin_key = "g34m8v4cv1qvb9"; // Below config.php to hide behind the admin login procedure

// Must be below main.config.php
require ("".$set_depth."app.lib/product.control/core.php/globals.php");
require ("".$set_depth."app.lib/product.control/core.php/data.control.php");
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.front.end.php");


/////////////////////////////START OF CONTENT//////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$company_name?> &nbsp;&gt&nbsp; Admin &nbsp;&gt&nbsp; Import Products&nbsp;</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="none" />
<?php
// Reload after half the heavy processing...
if ( $_GET['phase'] == 1 ) {
?>
<meta http-equiv="refresh" content="2; url=?key=<?=$_SESSION['sec_key']?>&phase=2" />
<?php
}
// Reload after half the heavy processing...
elseif ( $_GET['phase'] == 3 ) {
?>
<meta http-equiv="refresh" content="2; url=import.php" />
<?php
}
// Reload after half the heavy processing...
elseif ( $_GET['duplicate_delete'] == 2 ) {
?>
<meta http-equiv="refresh" content="2; url=?key=<?=$_SESSION['sec_key']?>&duplicate_delete=3" />
<?php
}
?>
<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php"); ?>
<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php"); ?>


<script language="JavaScript" type="text/javascript">

// Set the directory depth for javascript apps...
var set_depth = "<?=$set_depth?>";
// Detect user agent
var user_agent = navigator.userAgent;


</script>


<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/basic.xhtml.js" language="JavaScript" type="text/javascript">
</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/code.clean.js" language="JavaScript" type="text/javascript">
</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/scanning.js" language="JavaScript" type="text/javascript">
</script>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<!-- Starts the call for the form submit in the head of the document, so it's more reliable... -->
<script src="<?=$set_depth?>app.lib/security/javascript/text.submit.js" language="JavaScript" type="text/javascript"></script>
<?php }
?>


</head>
<body>

<div align="center" id="answers" class="answers_class"></div>

<table align="center" cellspacing="0" cellpadding="0" border="0" style="border: 1px solid black; background-color: #646262; width: 100%;">
<tr>
<td>

<table align="center" cellspacing="0" cellpadding="0" border="0">
<tr id="top_nav">
	
	
	<td style="padding: 5px;">
	&nbsp;&nbsp;<a href="index.php"><b>Edit Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; background-color: #bfbebe; border: 3px dotted #f8f6f6;">
	&nbsp;&nbsp;<a href="import.php" style="color: black;"><b>Import Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="export.php"><b>Export Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="records/"><b>Records</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;<a href="<?=$set_depth?>admin/configure.php"><b>Configure</b></a>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px; ">
	&nbsp;<span onclick='var answer_file = "help.index"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?" style="color: white; text-decoration: underline; font-size: <?=$font_6?>px; font-weight: bold; cursor: pointer;">Help</span>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="javascript:logoutSubmit();"><b>Logout</b></a>&nbsp;&nbsp;
	</td>
<td>
<!--  ALWAYS ONE EXTRA TO TAKE THE REST OF THE TABLE WIDTH  -->
&nbsp;
</td>
</tr>
</table>

</td>
</tr>
</table>



<table width="100%" align="center" cellspacing="0" cellpadding="14" border="0">
<tr>
	<td valign="top">


<noscript>
<div align="center"><p><b><font color="red">Sorry, your browser must support javascript...</font></b></p></div>
</noscript>



<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;"><b style="color: red;">Import Products</b></div>


<div align="left" style="width: 100%;">

<div style="padding: 6px; border: 1px solid red; background: orange;">Product IDs <i><?php echo ( $admin_config['product_id_on'] ? "Enabled" : "Disabled" ); ?></i></div>

<div style="padding: 6px;"></div>
<form name="import_form" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<b>Spreadsheet:</b> <input name="import_file" type="file" />
<input type="hidden" name="check_me" value="1" />
<input type="hidden" name="duplicate_scan" value="" />
<input type="hidden" name="submitted" value="1" />&nbsp;&nbsp;
<input type="button" value="Import Product Data" onclick="if ( document.import_form.duplicate_scan.value ) { window.open('<?=$set_depth?>progress.bar.php?percent=0','progress_bar','width=550,height=100,scrollbars=no'); document.import_form.submit(); /* var answer_file = 'progress.bar'; var answer_category = 'nav'; show_answer(answer_file, answer_category, 1, '?percent=0'); */ } else { alert(' Scan for and delete duplicates from the site first... \n\n This makes sure you never have two of the same product online, \n and properly imports updated prices on existing products. \n\n \Use the link to the right of the importing button.'); }" />&nbsp;&nbsp;&nbsp;
<a href="?key=<?=$_SESSION['sec_key']?>&duplicate_delete=1"><b style="font-size: <?=$font_7?>px;">Scan for online duplicates first</b></a>
<div style="padding: 0px;"></div>
</form>

<?php

if ( $_GET['duplicate_delete'] == 1 || $_GET['duplicate_delete'] == 3 ) {

	if ( $_GET['duplicate_delete'] ) {
	$_SESSION['duplicate_delete'] = $_GET['duplicate_delete'];
	}

require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.duplicate.scan.php");
}


if ( $_FILES['import_file']['tmp_name'] && $_POST['check_me'] || $_GET['loaded'] || $_GET['phase'] == 2 || $_SESSION['phases_complete'] ) {
require ("".$set_depth."app.lib/product.control/core.php/admin.area/admin.import.php");
}
elseif ( !$_GET['duplicate_delete'] && $_SESSION['online_delete_results'] ) {
echo "<p>".$_SESSION['online_delete_results']."</p>";
$_SESSION['online_delete_results'] = FALSE;
}
else {
	if ( $_POST['submitted'] ) {
	echo '<p><font color="#FF0000"><b>You need to choose the spreadsheet from your computer, with the "Browse" button above...</b></font></p>';
	?>
	<script type="text/javascript">
	window.open('<?=$set_depth?>progress.bar.php?percent=100','progress_bar','width=550,height=100,scrollbars=no');
	</script>
	<?php
	}

	// Reload after half the error scanning...
	if ( $_GET['phase'] == 1 ) {
	?>
<div align="center" style="padding: 45px;"><b><font color="#FF0000">Completed error scanning pass &nbsp;#1&nbsp;, please wait...</font></b></div>
	<?php
	}
	// Reload after second half of error scanning...
	elseif ( $_GET['phase'] == 3 ) {
	$_SESSION['phases_complete'] = 1; // Finishes things on reload
	?>
<div align="center" style="padding: 45px;"><b><font color="#FF0000">Completed error scanning pass &nbsp;#2&nbsp;, please wait...</font></b></div>
	<?php
	}
	elseif ( $_GET['duplicate_delete'] == 2 ) {
	?>
<div align="center" style="padding: 45px;"><b><font color="#FF0000">Processing duplicates scan, please wait...</font></b></div>
	<?php
	}
	// Reload after half the heavy processing...
	elseif ( $_GET['error'] == 'overload' ) {
	?>
<div align="center" style="padding: 45px;"><b><font color="#FF0000">You can only import <?=$max_imported?> products at a time...</font></b></div>
	<?php
	}
	else {
	?>
<p>
&nbsp;By importing a plain text tab-delimited spreadsheet from your computer, you can add or update your online products. <br />&nbsp;<a href='http://www.openoffice.org/' target="_blank">Open Office</a> and <a href='http://office.microsoft.com/' target="_blank">Microsoft Office</a> formats are supported.
</p>

<p>&nbsp;To update products that already exist, the <i>product description needs to be exactly the same text</i> (bold / italic text attributes can differ if they are in html format or done with the online editor, as well as any html or non-html spacing differences too). <b>One way to update existing products without your original spreadsheet is to <a href="export.php">export and download</a> a spreadsheet of the current products</b>, then update your prices, category path, etc, and import the spreadsheet back into the site.</p>


<p>&nbsp;<b>For more instructions on properly formatting your spreadsheet, use the <span onclick='var answer_file = "help.index"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="Help Index" style="color: red; text-decoration: underline; cursor: pointer;">help section.</span></b>
<br />&nbsp;To get started with a blank template, download the desired format below and open it with your spreadsheet program. </p>
<div align="center">
<div align="center" style="width: 450px;">
<p><b><u>Opposite-click your template link, and "Save As":</u></b></p>
<fieldset>
<legend> With Product IDs <i>Disabled</i> <?php echo ( $admin_config['product_id_on'] ? "" : "(currently active)" ); ?> </legend>
<a href='Open_Office_Import_Template.csv'>Open Office Template</a> <span class="button_span_link" onclick='var answer_file = "open.office.summary"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?">[?]</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='MS_Office_Import_Template.txt'>Microsoft Office Template</a> <span class="button_span_link" onclick='var answer_file = "ms.office.summary"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?">[?]</span>
</fieldset>
<div style="padding-top: 10px;"></div>
<fieldset>
<legend> With Product IDs <i>Enabled</i> <?php echo ( $admin_config['product_id_on'] ? "(currently active)" : "" ); ?> </legend>
<a href='Open_Office_Import_Template_Product_IDs.csv'>Open Office Template</a> <span class="button_span_link" onclick='var answer_file = "open.office.summary"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?">[?]</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href='MS_Office_Import_Template_Product_IDs.txt'>Microsoft Office Template</a> <span class="button_span_link" onclick='var answer_file = "ms.office.summary"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?">[?]</span>
</fieldset>

</div>
</div>

<?php
	}

}

?>


</div>

</td>
</tr>
</table>

<script language="javascript" type="text/javascript">
document.import_form.duplicate_scan.value = '<?=$_SESSION['duplicate_delete']?>';
</script>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<form name="logout" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="my_logout" value="yes">
</form>
<?php }

/*

echo "<br clear='all'>debugging_imported_products: <pre>";
print_r($_SESSION['debugging_imported_products']);
echo "</pre>";

echo "<br clear='all'>duplicate_scan_debugging: <pre>";
print_r($_SESSION['duplicate_scan_debugging']);
echo "</pre>";

echo "<br clear='all'>imported_products: <pre>";
print_r($imported_products);
echo "</pre>";

*/

?>

<?php
require ($set_depth . "main.footer.code.php");
?>

</body>
</html>