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

if ( $_SESSION['export_status'] ) {
$export_status = $_SESSION['export_status'];
$_SESSION['export_status'] = FALSE;
}

$export_directory_path = "backups/";
// Delete
if ( $_GET['csv_delete'] ) {
unlink ($export_directory_path . $_GET['csv_delete']);
header ("location: export.php");
$_SESSION['export_status'] = "Backup file \"" . $_GET['csv_delete'] . "\" deleted";
}


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
<title><?=$company_name?> &nbsp;&gt&nbsp; Admin &nbsp;&gt&nbsp; Export Products&nbsp;</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="none" />

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
	
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="index.php"><b>Edit Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="import.php"><b>Import Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; background-color: #bfbebe; border: 3px dotted #f8f6f6;">
	&nbsp;&nbsp;<a href="export.php" style="color: black;"><b>Export Products</b></a>&nbsp;&nbsp;
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


<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;"><b style="color: red;">Export Products</b></div>


<div align="left" style="width: 100%;">


<?php

if ( $_POST['office_format'] ) {



$product_data = mysql_query("SELECT * FROM product_list ORDER BY product_name ASC");

	if ($product_data) {
	$num = mysql_numrows($product_data);
	}






	if ($num) {

		for ($i = 0; $i < $num;) {
		$show_id = mysql_result($product_data, $i, "id");
		$show_name = mysql_result($product_data, $i, "product_name");
		$product_id = mysql_result($product_data, $i, "product_id");
		$show_price = mysql_result($product_data, $i, "unit_price");
		$show_parent_id = mysql_result($product_data, $i, "parent_category_id");
		
			if ( !$product_id ) {
			$product_id = 'none';
			}

		$export_top_row = '"Product_Name"'. ( $admin_config['product_id_on'] ? '	"Product_ID"' : '' ) .'	"Unit_Price"	"Category_Path"	"END_HERE"' . "\n";

		$print_results = $print_results . $show_name . ( $admin_config['product_id_on'] ? '	' . $product_id : '' ) . '	' . $show_price . '	' . category_depth_scan($show_parent_id, '', '', 1, 1) . '	"END_HERE"' . "\n";

		$i = $i + 1;
		}

	$print_results = eregi_replace('""', '" "', $print_results);
	
	// Remove extra carrige returns and html breaks at the end of data fields
	$print_results = eregi_replace("\r", "", $print_results);
	$print_results = eregi_replace("\n", "", $print_results);
	$print_results = eregi_replace("<br />	", "	", $print_results);
	
		// Reformat the proper carrige return afterwards, for the spreadsheet formatting	
		if ( eregi("(.*)"."\"END_HERE\""."(.*)", $print_results) ) {
		$print_results = eregi_replace("\"END_HERE\"", "\"END_HERE\""."\n", $print_results);
		}
		else {
		$print_results = eregi_replace("END_HERE", "END_HERE"."\n", $print_results);
		}
	
	$exported_data = $export_top_row . $print_results;
	
		if ( $_POST['strip_html'] ) {
		$exported_data = eregi_replace("<b>", "", $exported_data);
		$exported_data = eregi_replace("</b>", "", $exported_data);
		$exported_data = eregi_replace("<i>", "", $exported_data);
		$exported_data = eregi_replace("</i>", "", $exported_data);
		}
	}


	if ( $exported_data ) {

		if ( $_POST['office_format'] == 'MSoffice' ) {
		$export_file = $_POST['office_format'] . "-" . time_offset($hour_offset, $minute_offset, 7) . $_POST['strip_html'] . '.txt';
		}
		elseif ( $_POST['office_format'] == 'OpenOffice' ) {
		$export_file = $_POST['office_format'] . "-" . time_offset($hour_offset, $minute_offset, 7) . $_POST['strip_html'] . '.csv';
		}
	
	$export_folder_path = $set_depth . "admin/backups/";
	$export_file_path = $export_folder_path . $export_file;

	$fp= fopen($export_file_path,"w");
	fputs($fp,$exported_data);
	fclose ($fp);

		if (function_exists("chmod")) {
		chmod ($export_file_path, 0666);
		}

	$export_status = "$num products exported to backup file \"$export_file\"";
	}

	else {
	$export_status = "No products found to export";
	}


}

?>
<div style="padding: 6px; border: 1px solid red; background: orange;">Product IDs <i><?php echo ( $admin_config['product_id_on'] ? "Enabled" : "Disabled" ); ?></i></div>

<div style="padding: 6px;"></div>
<form action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<b>Spreadsheet format:</b> <select name="office_format">
<option value="MSoffice">Microsoft Office</option>
<option value="OpenOffice">Open Office</option>
</select>&nbsp;&nbsp;
<input type="submit" value="Export to Backup File" />&nbsp;&nbsp;
<input type="checkbox" name="strip_html" value="-NoFormatting" /> Remove description formatting
</form>
<p><b><font color='#FF0000'><?=$export_status?></font></b></p>

<p></p>&nbsp;Here you can backup your current product data as often as you want, in your desired spreadsheet format.<br />
&nbsp;The backup files are saved online, and are also available to download to your computer. Besides serving as backup, you can edit them and <a href="import.php">import the changes</a> back into the web site afterwards.

<table cellspacing="0" cellpadding="2" border="0">
<tr>
	<td colspan="2"><b><u>Exported Backup Files:</u></b><br />(Opposite-click, and "Save As" to download)</td>
</tr>
<?php


// Read and list to sort anyway we desire...
if ($dir = @opendir($export_directory_path)) {
	$files_array = array();
	$the_loop = 0;
	
		while (($file = readdir($dir)) !== false) {
		$the_full_path = $export_directory_path . $file;
	
			if ( $file != "." && $file != ".." && !eregi("(.*).php", $file)
			&& !eregi("(.*).htpasswd", $file) && !eregi(".htaccess", $file) ) {
			$files_array[$the_loop] =  $file;
			$the_loop = $the_loop + 1;
			}
			
		}
	
closedir($dir);


sort($files_array);  // Now we can sort this file list however HERE...
	
		$product_bgcolor = 'white';
		$the_loop = 0;
		while ( $files_array[$the_loop] ) {
					
			if ( $product_bgcolor == 'white' ) {
			$product_bgcolor = '#eae8e8';
			}
			else {
			$product_bgcolor = 'white';
			}
			
			if ( eregi("(.*)MSoffice(.*)", $files_array[$the_loop])
			|| eregi("(.*)OpenOffice(.*)", $files_array[$the_loop]) ) {
			
			// The rendered file list...
			$list_the_directory = $list_the_directory . '<tr style="background: '.$product_bgcolor.';">
	<td><a href="'.$export_directory_path . $files_array[$the_loop].'">'.$files_array[$the_loop].'</a>&nbsp;&nbsp;</td>
	<td>&nbsp;&nbsp;<a href="?key='.$_SESSION['sec_key'].'&csv_delete='.$files_array[$the_loop].'" onClick="return confirm(\'Are you sure you want to delete &quot '.$files_array[$the_loop].' &quot \?\')"><b>Delete</b></a></td>
</tr>';

			}

		$the_loop = $the_loop + 1;
		}
	
}

if ( !$list_the_directory ) {
$list_the_directory = '<tr><td><font color="#FF0000"><b>No files currently saved to backup</b></font></td></tr>';
}

echo $list_the_directory ;



?>
</table>

</div>

</td>
</tr>
</table>

<?php
if ( $security_level == 1 || $_SESSION['show_admin_link'] ) {
?>
<form name="logout" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" method="post">
<input type="hidden" name="my_logout" value="yes">
</form>
<?php }
?>


<?php
require ($set_depth . "main.footer.code.php");
?>

</body>
</html>
