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

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Working, please wait...</title>
	
<style type="text/css">

body {
margin: 7px;
}

.show_right_border {
 border-right: 1px solid #808080;
}

.hide_right_border {
 border-right: 0px solid #808080;
}

</style>
	
</head>

<body>
<div align="center" style="position: relative;"><div align="center" style="padding-bottom: 6px;"><b>Working, please wait...</b></div>
<div align="left" style="position: relative; width: 450px; height: 35px; background: #c8b9b9; border: 1px solid #808080;">
<div align="center" style="position: absolute; top: 7px; width: 100%; color: white;"><b><?=$_GET['percent']?>%</b></div>
<div align="left" id="progress_indicator" style="width: 200px; height: 100%; background: #7ea8b7;"></div>

</div>
</div>
<script type="text/javascript">

<?php
if ( $_GET['percent'] > 0 && $_GET['percent'] < 100 ) {
?>
document.getElementById('progress_indicator').className = 'show_right_border';
<?php
}
elseif ( $_GET['percent'] == 100 ) {
?>
document.getElementById('progress_indicator').className = 'hide_right_border';
setTimeout("window.close()",2500);
<?php
}
else {
?>
document.getElementById('progress_indicator').className = 'hide_right_border';
<?php
}
?>

document.getElementById('progress_indicator').style.width = <?=$_GET['percent']?> + '%';

</script>

</body>
</html>
