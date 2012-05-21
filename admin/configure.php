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


/////////////////////////////START OF CONTENT//////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- *Frugal Web Development*  www.dragonfrugal.com -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="none" />

<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php"); ?>


<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php"); ?>

<title><?=$company_name?> &nbsp;&gt&nbsp; Admin &nbsp;&gt&nbsp; Configure&nbsp;</title>

<script language="JavaScript" type="text/javascript">

// Set the directory depth for javascript apps...
var set_depth = "<?=$set_depth?>";
// Detect user agent
var user_agent = navigator.userAgent;

function save_changes() {

	if ( !document.options_config.save_changes.value ) {
	return 'unsaved';
	}

}

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
	&nbsp;&nbsp;<a href="index.php" onclick="if ( save_changes() == 'unsaved' ) { return confirm('Leave the configuration page without saving any changes?'); }"><b>Edit Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="import.php" onclick="if ( save_changes() == 'unsaved' ) { return confirm('Leave the configuration page without saving any changes?'); }"><b>Import Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="export.php" onclick="if ( save_changes() == 'unsaved' ) { return confirm('Leave the configuration page without saving any changes?'); }"><b>Export Products</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="records/" onclick="if ( save_changes() == 'unsaved' ) { return confirm('Leave the configuration page without saving any changes?'); }"><b>Records</b></a>&nbsp;&nbsp;
	</td>
	
	<td style="padding: 5px; background-color: #bfbebe; border: 3px dotted #f8f6f6;">
	&nbsp;<a href="<?=$set_depth?>admin/configure.php" style="color: black;"><b>Configure</b></a>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px; ">
	&nbsp;<span onclick='var answer_file = "help.index"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?" style="color: white; text-decoration: underline; font-size: <?=$font_6?>px; font-weight: bold; cursor: pointer;">Help</span>&nbsp;&nbsp;
	</td>
	<td style="padding: 5px; ">
	&nbsp;&nbsp;<a href="javascript:logoutSubmit();" onclick="if ( save_changes() == 'unsaved' ) { return confirm('Leave the configuration page without saving any changes?'); }"><b>Logout</b></a>&nbsp;&nbsp;
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


<div align="center" style="padding: 7px; font-size: <?=$font_2?>px;"><b style="color: red;">Configure</b></div>

<div align="left" style="width: 100%;"><p>
<?php


if ($email_array) {
?></p>
<div id="email_alert"></div>
<form name="options_config" action="<?=$_SERVER['PHP_SELF']?>?key=<?=$_SESSION['sec_key']?>" enctype="multipart/form-data" method="post">


	<?php
	for ($i = 0; $i < sizeof($email_array);) {
	
	$email_id = $i + 1;
	$email = $email_array[$i];
	
		if ( $email ) {
		
			list($username,$domain) = split("@",$email);
			if (!ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $email)) {
			$bad_email = $email;
			$form_validate = "The highlighted field\(s) lack a valid email address...";
			}
			elseif (function_exists("getmxrr") && !getmxrr($domain,$mxrecords)) {
			$bad_email = $email;
			$form_validate = "\"$domain\" appears incorrect...";
			$domain_message = $domain_message . $form_validate . "<br />";
			}
			else {
			$bad_email = NULL;
			$form_validate = NULL;
			}
		
		}

		if ( !$email && $i == 0 ) {
		$form_validate = 'No primary email address has been assigned...you cannot recieve email orders until you add at least one email address.';
		}
?>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Service Email #<? echo $i + 1; ?>:</b> <input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = '<?php if ( $email && $bad_email == $email || !$email && $i == 0 ) { ?>#fbba0b<?php } else { ?>white<?php } ?>';" type="text" size="40" maxlength="65" name="email_<?=$email_id?>" value="<?=$email?>" <?php if ( $email && $bad_email == $email || !$email && $i == 0 ) { ?>style="background: #fbba0b;"<?php } ?> />
</p>
<?php

		if ( $form_validate && !$already_posted || $form_validate && $domain_message ) {

		?>

<script language="javascript" type="text/javascript">

document.getElementById("email_alert").innerHTML = '<div style="padding: 6px;"></div><div style="position: relative; left: 15px; border: 2px dotted #fb5a0b; width: 399px;"><div style="border: 2px dotted #fbba0b; width: 395px;"> <div align="left" style="width: 391px; padding: 4px;"><b><font class="text_alert"><?php

	if ( $domain_message ) {
	$form_validate = $domain_message;
	}
	
echo $form_validate;

?> </font></b> </div></div></div>';

</script>

		<?php
		$already_posted = 1;
		}

	$i = $i + 1;
	}
?>
<div style="padding-top: 14px;"></div>
	<?php
	
	 
	if ( $_POST['template_wrap_p'] ) {
	$template_wrap = $_POST['template_wrap_p'];
	}
	
	?>
	<p>&nbsp;&nbsp;<b>Overall listing Width:</b> <select name="template_wrap_p">
	<option value="600"<?php if ( $template_wrap == 600 ) { echo 'selected'; } ?>> 600 pixels </option>
	<option value="700"<?php if ( $template_wrap == 700 ) { echo 'selected'; } ?>> 700 pixels </option>
	<option value="800"<?php if ( $template_wrap == 800 ) { echo 'selected'; } ?>> 800 pixels </option>
	<option value="900"<?php if ( $template_wrap == 900 ) { echo 'selected'; } ?>> 900 pixels </option>
	<option value="100%"<?php if ( $template_wrap == '100%' ) { echo 'selected'; } ?>> 100% (stretch to fit window) </option>
	</select>
	</p>
	<?php	if ( $_POST['menu_width_p'] ) {
	$admin_config['menu_width'] = $_POST['menu_width_p'];
	}
	
	?>
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Left Menu Width:</b> <select name="menu_width_p">
	<option value="200"<?php if ( $admin_config['menu_width'] == 200 ) { echo 'selected'; } ?>> 200 pixels </option>
	<option value="250"<?php if ( $admin_config['menu_width'] == 250 ) { echo 'selected'; } ?>> 250 pixels </option>
	<option value="300"<?php if ( $admin_config['menu_width'] == 300 ) { echo 'selected'; } ?>> 300 pixels </option>
	<option value="350"<?php if ( $admin_config['menu_width'] == 350 ) { echo 'selected'; } ?>> 350 pixels </option>
	</select>
	<?php
	
	if ( $_POST['font_size_p'] ) {
	$selected_font_size = $_POST['font_size_p'];
	}
	
	?>
	
	<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Text Size:</b> <select name="font_size_p">
	<option value="-2"<?php if ( $selected_font_size == -2 ) { echo 'selected'; } ?>> - 2 pixel </option>
	<option value="-1"<?php if ( $selected_font_size == -1 ) { echo 'selected'; } ?>> - 1 pixel </option>
	<option value="+0"<?php if ( $selected_font_size == +0 ) { echo 'selected'; } ?>> Default </option>
	<option value="+1"<?php if ( $selected_font_size == +1 ) { echo 'selected'; } ?>> + 1 pixel </option>
	<option value="+2"<?php if ( $selected_font_size == +2 ) { echo 'selected'; } ?>> + 2 pixel </option>
	</select></p>
<p>
&nbsp;&nbsp;<b>Company Name Text:</b> <input class="input_text_border" onfocus="this.style.background = '#f9fbb9';" onblur="this.style.background = 'white';" type="text" size="40" maxlength="65" name="company_name_p" value="<?=$company_name?>" /> 
<div style="padding-top: 5px;"></div>
&nbsp;
<?php
// If GD and freetype libraries are installed on the server, print fancy text to an image file, otherwise we'll just use plain text to render the company name
if ( function_exists("imagettftext") ) {
?>
&nbsp;<b>Company Name Font:</b> 
<select name="company_font_p" onchange="document.company_font.src = '<?=$set_depth?>images/php/preview.company.font.php?style=' + this.value;">
<?php
	
	
	
	// Listing existing images for deletion if desired...
	
	$the_directory_path = $set_depth . 'fonts/';
	
		if ( $_GET['delete'] ) {
		unlink($the_directory_path . $_GET['delete']);
		}
	
	
		// Read and list
		if ($dir = @opendir("$the_directory_path")) {
			$files_array = array();
			$the_loop = 0;
		
			while (($file = readdir($dir)) !== false) {
			$the_full_path = $the_directory_path . $file;
				if ( eregi("(.*).ttf", $file) || eregi("(.*).ttf", $file) || eregi(".ttf", $file) ) {
				$files_array[$the_loop] =  $file;
				$the_loop = $the_loop + 1;
				}
			}
		
		closedir($dir);
		sort($files_array); 
		
			$the_loop = 0;
			while ( $files_array[$the_loop] ) {
			$list_the_directory = $list_the_directory . "<option value='".$files_array[$the_loop]."'".( $company_font == $files_array[$the_loop] ? " style='color: red;' selected>" : ">" ).$files_array[$the_loop]."</option>\n";
			$the_loop = $the_loop + 1;
			}
		
		}
	
	
echo $list_the_directory;
?>
</select> &nbsp;&nbsp;<img name='company_font' src='<?=$set_depth?>images/php/preview.company.font.php?style=<?=$company_font?>' alt='' align='middle' />
<?php
}
else {
?>
<b><font style='color: red;'>The freetype library doesn't appear to be installed, so a standard plain text font will be used.</font></b>
<?php
}
 ?>


</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Company Logo:</b> <input name="import_file" type="file" />
<br />
<img src="<?=$logo_image?>" alt="" style="padding: 5px;" />
</p>
	
<p>&nbsp;</p>

	<div id="preferred_arrival_box" style="white-space: nowrap;">
	<?php
	
	if ( $_POST['preferred_delivery_p'] ) {
	$admin_config['preferred_delivery'] = 'yes';
	}
	elseif ( $_POST && !$_POST['preferred_delivery_p'] ) {
	$admin_config['preferred_delivery'] = 'no';
	}
	
	if ( $_POST['delivery_earliest_p'] ) {
	$delivery_earliest = $_POST['delivery_earliest_p'];
	}
	
	if ( $_POST['delivery_range_p'] ) {
	$admin_config['delivery_range'] = $_POST['delivery_range_p'];
	}
	
	if ( $_POST['count_weekends_p'] ) {
	$admin_config['count_weekends'] = $_POST['count_weekends_p'];
	}
	
	if ( $_POST['preferred_required_p'] ) {
	$admin_config['preferred_required'] = 'yes';
	}
	elseif ( $_POST && !$_POST['preferred_required_p'] ) {
	$admin_config['preferred_required'] = 'no';
	}
	
	?>
	 <input type="checkbox" id="preferred_delivery_p" name="preferred_delivery_p" value="1" onclick="
	 if (this.checked == true) {
	 document.getElementById('preferred_delivery_input').style.display = 'inline';
	 document.getElementById('preferred_arrival_box').style.border = '1px solid #efe4e4';
	 document.getElementById('preferred_arrival_box').style.padding = '10px';
	 document.getElementById('preferred_arrival_box').style.background = '#f8f1f1';
	 }
	 else {
	 document.getElementById('preferred_delivery_input').style.display = 'none';
	 document.getElementById('preferred_arrival_box').style.border = '0px solid #efe4e4';
	 document.getElementById('preferred_arrival_box').style.padding = '0px';
	 document.getElementById('preferred_arrival_box').style.background = 'white';
	 }
" <?php if ( $admin_config['preferred_delivery'] == 'yes' ) { echo "checked"; } ?>> <b>Let customers choose a preferred arrival date</b>
<div align="left" id="preferred_delivery_input" style="display: <?php if ( $admin_config['preferred_delivery'] == 'yes' ) { echo "inline"; } else { echo "none"; } ?>;">
<br clear="all" />
<br clear="all" />
Earliest products can be delivered to the customer's doorstep: <select id="delivery_earliest_p" name="delivery_earliest_p">
<?php

	$day_loop = 0;
	while ( $admin_config['delivery_range'] > $day_loop ) {
	$day_loop = $day_loop + 1;
	?>
	<option value="<?=$day_loop?>"<?php if ( $delivery_earliest == $day_loop ) { echo 'selected'; } ?>> <?=$day_loop?> </option>
	<?php
	}

?>
	</select> Day(s)
<br /><br />
Range of days to offer to customer for their selection: <select id="delivery_range_p" name="delivery_range_p">
	<option value="7"<?php if ( $admin_config['delivery_range'] == 7 ) { echo 'selected'; } ?>> 7 </option>
	<option value="14"<?php if ( $admin_config['delivery_range'] == 14 ) { echo 'selected'; } ?>> 14 </option>
	<option value="30"<?php if ( $admin_config['delivery_range'] == 30 ) { echo 'selected'; } ?>> 30 </option>
	<option value="60"<?php if ( $admin_config['delivery_range'] == 60 ) { echo 'selected'; } ?>> 60 </option>
	<option value="90"<?php if ( $admin_config['delivery_range'] == 90 ) { echo 'selected'; } ?>> 90 </option>
	<option value="180"<?php if ( $admin_config['delivery_range'] == 180 ) { echo 'selected'; } ?>> 180 </option>
	<option value="365"<?php if ( $admin_config['delivery_range'] == 365 ) { echo 'selected'; } ?>> 365 </option>
	</select> Day(s)
<br /><br />
Count weekends as delivery dates? <select id="delivery_earliest_p" name="count_weekends_p">
	<option value="yes"<?php if ( $admin_config['count_weekends'] == 'yes' ) { echo 'selected'; } ?>> Yes </option>
	<option value="no"<?php if ( $admin_config['count_weekends'] == 'no' ) { echo 'selected'; } ?>> No </option>
	</select>
	
<br /><br />
	Require customers to choose a preferred arrival date <input type="checkbox" id="preferred_required_p" name="preferred_required_p" value="1" <?php if ( $admin_config['preferred_required'] == 'yes' ) { echo "checked"; } ?>>
</div>
	</div>
	<?php
	
	if ( $admin_config['preferred_delivery'] == 'yes' ) {
	?>
	
	<script type="text/javascript">
	
	 document.getElementById('preferred_delivery_input').style.display = 'inline';
	 document.getElementById('preferred_arrival_box').style.border = '1px solid #efe4e4';
	 document.getElementById('preferred_arrival_box').style.padding = '10px';
	 document.getElementById('preferred_arrival_box').style.background = '#f8f1f1';
	 
	</script>
	
	
	<?php
	}
	 
?>
	<p><input type="checkbox" name="flyout_subcat_on_p" value="1" <?php if ( $admin_config['menu_format'] == 'fly_out_href_vertical' ) { echo 'checked'; } ?> /> <b>Fly-Out Subcategory Menu</b>  <font style="color: red;"><b>(partially functional beta)</b></font></p>
	<p><input type="checkbox" name="use_breadcrumb_p" value="1" <?php if ( $admin_config['use_breadcrumb'] == 'yes' ) { echo 'checked'; } ?> /> <b>Breadcrumb Links</b> (doubles as location title above listings, disable with care)</p>
	<p><input type="checkbox" name="product_id_on_p" value="1" <?php if ( $admin_config['product_id_on'] == '1' ) { echo 'checked'; } ?>  /> <b>Enable Product IDs</b></p>
	<p><input type="checkbox" name="paypal_on_p" value="1"  <?php if ( $admin_config['paypal_on'] == '1' ) { echo 'checked'; } ?>   /> <b>PayPal Cart Checkout</b></p>
	
	
	
	
	
	
	<p><input type="checkbox" name="custom_fields_p" value="1"  <?php if ( $admin_config['custom_fields'] == '1' ) { echo 'checked'; } ?> onclick="
	if ( this.checked == true ) {
	document.getElementById('custom_fields').style.display = 'block';
	}
	else {
	document.getElementById('custom_fields').style.display = 'none';
	}
	"  /> <b>Custom fields for customer input per item</b>
	
	<div id="custom_fields" style='<?php if ( $admin_config['custom_fields'] != '1' ) { echo 'display: none;'; } ?> padding: 8px;'>
	
	<p>Custom Field Name #1: <input type='text' name='custom_fields1_p' value='<?=$admin_config['custom_1']?>' /></p>
	
	<p>Custom Field Name #2: <input type='text' name='custom_fields2_p' value='<?=$admin_config['custom_2']?>' /></p>
	
	</div>
	</p>
	
	
	
	
	
	
	
	
	<p><input type="checkbox" name="inventory_tracking_on" value="1" disabled /> <font style="color: #808080;"><b>Inventory Tracking</b> (coming soon)</font></p>
	<p><br /><input type="submit" value="Save Changes" /></p>
	<input type="hidden" name="edit_config" value="1" />
	<input type="hidden" name="save_changes" value="<?=$_POST['edit_config']?>" />
	</form>

	<?php

echo $alert_status;

}

else {
echo "<b><font color='red'>Sorry, unknown error...</font></b>";
}

?>


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