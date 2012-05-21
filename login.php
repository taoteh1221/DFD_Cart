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


if (!$_SESSION)
{ session_start();
}

$file_depth = 0;
$security_level = 0;
require("main.config.php");

if ( $_GET['activate'] ) {

$activate_account = db_connect('accounts', 'update', '', '', "status = '1', reg_key = '".md5(rand())."' WHERE reg_key = '".$_GET['activate']."'");
	
	if ( $activate_account == 1 ) {
	echo '<p><b>Thank you, your account has been activated.</b></p><a href="login.php"><b>Return to login screen</b></a>';
	exit;
	}
	else {
	echo '<p><b>Sorry, activation key not found or already used.</b></p><a href="login.php"><b>Return to login screen</b></a>';
	exit;
	}

}
elseif ( $_GET['reset_1'] ) {
	$reset_password = db_connect('accounts', 'select', '', '', "WHERE email='".$_GET['email']."'");
	
		if ( $reset_password['reg_key'] ) {
		$message = "Hello,
This is an automated password reset message regarding the cart account at ".find_host().", registered to the email address ".$_GET['email'].".
You can continue with resetting your account login with the link below:
http://".$url_base."login.php?reset_2=".$reset_password['reg_key']."&email=".$_GET['email']."
If you did not attempt to reset this account password you can safely ignore this message, as the account password -cannot be reset- without someone clicking the above link.
Thanks,
".find_host()."
		";
		$from = mailto_addresses(1);
		$headers = "From:" . $from;
		mail($_GET['email'],'Login reset request for cart account at ' . find_host(),$message,$headers);
		}
		
	echo "<p><b>If the account exists, a password reset email has been sent to the registered email address.</b></p><a href='login.php'><b>Return to login screen</b></a>";
	exit;
}
elseif ( $_GET['reset_2'] ) {

$reset_password = db_connect('accounts', 'select', '', '', "WHERE reg_key = '".$_GET['reset_2']."' AND email = '".$_GET['email']."'");

if ( $reset_password['email'] ) {
?>
<form name='reset_pass' action='login.php' method='post'>
<b>Create a new password</b>
<p>Password: <input type='password' name='pass_reset1' /></p>
<p>Confirm Password: <input type='password' name='pass_reset2' /></p>
<input type='hidden' name='email' value='<?=$_GET['email']?>' />
<input type='hidden' name='reset_3' value='<?=$_GET['reset_2']?>' />
<input type='button' value='Submit' onclick='
	if ( document.reset_pass.pass_reset1.value == document.reset_pass.pass_reset2.value ) {
	document.reset_pass.submit();
	}
	else {
	alert("Passwords do not match");
	}
	' />
<?php
}
else {
echo '<p><b>Sorry, account not found.</b></p><a href="login.php"><b>Return to login screen</b></a>';
}
	exit;
}
elseif ( $_POST['reset_3'] ) {

$activate_account = db_connect('accounts', 'update', '', '', "pass = '".substr(md5(trim($_POST['pass_reset1'])),0,15)."', reg_key = '".md5(rand())."' WHERE reg_key = '".$_POST['reset_3']."' AND email = '".$_POST['email']."'");
	
	if ( $activate_account == 1 ) {
	echo '<p><b>Your account password has been reset.</b></p><a href="login.php"><b>Return to login screen</b></a>';
	exit;
	}
	else {
	echo '<p><b>Sorry, account not found.</b></p><a href="login.php"><b>Return to login screen</b></a>';
	exit;
	}

}


if ( $_POST["login"] ) {



	if ( $_POST['email'] && $_POST['pass'] && $_POST["security_code"] == $_SESSION["security_code"] ) {
	
	$account_info = db_connect('accounts', 'select', '', '', "WHERE email = '".trim($_POST['email'])."'");
	
			if ( substr(md5(trim($_POST['pass'])),0,15) == $account_info['pass'] && $account_info['status'] == 1 ) {
			
			$_SESSION['login'] = array(
			id => $account_info['id'],
			email => $account_info['email'],
			name => $account_info['name'],
			store => $account_info['store'],
  			address => $account_info['address'],
  			town => $account_info['town'],
  			postal_code => $account_info['postal_code'],
  			country => $account_info['country'],
  			discount => $account_info['discount']
			);
			
			header("Location: http://".$url_base);
			}
			
			elseif ( substr(md5(trim($_POST['pass'])),0,15) == $account_info['pass'] && $account_info['status'] == 0 ) {
			
			$_SESSION['login'] = FALSE;
			
			$login_alert =   'Account not activated yet. An activation link has been resent to your email address now.';
			
		$message = "Hello,
This is an automated message regarding activation of a cart account at ".find_host().", with the email address ".$_POST['email'].".
You can activate your new account with the link below:
http://".$url_base."login.php?activate=".$account_info['reg_key']."
If you did not create this account you can safely ignore this message, as the account -cannot be activated- without someone clicking the above link.
Thanks,
".find_host()."
		";
			$from = mailto_addresses(1);
			$headers = "From:" . $from;
			mail($_POST['email'],'Activating your cart account at ' . find_host(),$message,$headers);
			
			}
			
			else {
			$_SESSION['login'] = FALSE;
			$login_alert =   'Sorry, username / password combination not found';
			}
	
	}
	elseif ( !$_POST["email"] || !$_POST["pass"] ) {
	$login_alert =  'Please check your email address and password are filled in';
	}
	elseif ( $_POST["security_code"] != $_SESSION["security_code"] ) {
	$login_alert =  'Security code does not match';
	}

}
elseif ( $_POST["register"] ) {

	if ( $_POST['emailr'] && $_POST['passr1'] && $_POST["security_code"] == $_SESSION["security_code"] ) {
	
		if ( $_POST['register'] ) {
		$random_hash = md5(rand());
		$test555 = db_connect('accounts', 'insert', '', array( 
		email => trim($_POST['emailr']), 
		pass => substr(md5(trim($_POST['passr1'])),0,15), 
		reg_key => $random_hash,
		status => 0
		), "");
		echo '<p><b>Account created...thank you.</b></p>';
		$message = "Hello,
This is an automated message regarding the cart account just registered at ".find_host().", with the email address ".$_POST['emailr'].".
Please activate your new account with the link below:
http://".$url_base."login.php?activate=".$random_hash."
If you did not create this account you can safely ignore this message, as the account -cannot be activated- without someone clicking the above link.
Thanks,
".find_host()."
		";
		$from = mailto_addresses(1);
		$headers = "From:" . $from;
		mail($_POST['emailr'],'Cart account activation required at ' . find_host(),$message,$headers);
		echo "<p><b>Activation email sent...please check your email for account activation instructions.</b></p><a href='login.php'><b>Return to login screen</b></a>";
		exit;
		}
	
	}
	elseif ( !$_POST["emailr"] || !$_POST["passr1"] ) {
	$register_alert =  'Please check your email address and password are filled in';
	}
	elseif ( $_POST["security_code"] != $_SESSION["security_code"] ) {
	$register_alert =  'Security code does not match';
	}

}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Login/ Register</title>
<meta name="author" content="" />
<meta name="classification" content="" />
<meta name="revisit-after" content="31 days" />
<meta name="robots" content="all" />
<meta name="reply-to" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />


<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/main.css.php"); ?>

<?php require ("".$set_depth."app.lib/product.control/core.css/css.php/answer.box.css.php"); ?>


<script language="JavaScript" type="text/javascript">

// Set the directory depth for javascript apps...
var set_depth = "<?=$set_depth?>";
// Detect user agent
var user_agent = navigator.userAgent;


</script>

<script src="<?=$set_depth?>app.lib/product.control/core.javascript/answer.box.js" language="javascript" type="text/javascript"></script>


<script language="javascript" type="text/javascript">

function show_pop_in_mini() {

document.getElementById("pop_in_mini").style.border='2px solid black';
document.getElementById("pop_in_mini").style.padding='12px';
document.getElementById("pop_in_mini").style.width='37%';
document.getElementById("pop_in_mini").innerHTML = '<span style="position: relative; float: right; cursor: pointer;" onclick="hide_pop_in_mini();"> <b>X</b> </span><div align="center" style="position: relative; float: middle;"><a href="' + set_depth + 'cart/index.php" style="color: #ffffff; z-index: 31;"><b>View Cart</b></a></div><div align="center" id="div_one"></div>';
document.getElementById("pop_in_mini").style.visibility='visible';

}

function hide_pop_in_mini() {

parent.document.getElementById("pop_in_mini").innerHTML = '';
parent.document.getElementById("pop_in_mini").style.padding='0px';
parent.document.getElementById("pop_in_mini").style.border='0px';
parent.document.getElementById("pop_in_mini").style.width='0px';
parent.document.getElementById("pop_in_mini").style.visibility='hidden';

}

</script>

<?php require ("".$set_depth."app.lib/product.control/core.javascript/js.php/order.ajax.js.php"); ?>

<style type="text/css">
.pop_in_mini {
/* Browser-specific **START** */
<?php if (eregi("Opera", $_SERVER['HTTP_USER_AGENT'])) 
{ ?>
/* Opera without fixed positioning support */
position: absolute;
<?php }
elseif ( eregi("MSIE 5", $_SERVER['HTTP_USER_AGENT'])
|| eregi("MSIE 6", $_SERVER['HTTP_USER_AGENT']) ) 
{ ?>
/*  MSIE 5+6 ONLY...MSIE 7 will support fixed positioning */
position: absolute;
/* HTML VERSION top: expression( ( ignore_me = document.body.scrollTop ) + 'px' ); */
/* XHTML VERSION  top: expression( ( ignore_me = document.documentElement.scrollTop ) + 'px' ); */
/* BACKUP VERSION */ top: expression( ( ignore_me = document.body.scrollTop ) + 'px' );
<?php }
else
{ ?>
/* FireFox, etc */
position: fixed;
<?php } ?>
/* Browser-specific **END** */
background-color: #BF7035;
border: 0px;
font-size: <?=$font_6?>px;
color: #ffffff;
margin: 15% 15%;
width: 50px;
visibility: hidden;
z-index: 10;
opacity: .95; /*  FireFox and Safari  */
filter: alpha(opacity=95); /*  MSIE  */
}
</style>

</head>
<body onload="<?php if ( $admin_config['paypal_on'] && $_REQUEST['mode'] == 'pp_return' && sizeof($ipn_array) > 1 ) { 
$_SESSION["lock_security_code"] = $_SESSION["security_code"];
?>
document.customer_info.fax_option.value = 1; 
document.customer_info.Employee_Name.value = '<?=$_SESSION['Employee_Name']?> (<?=$_SESSION['print_ipn']['address_name']?>)';
document.customer_info.Employee_Email.value = '<?=$_SESSION['print_ipn']['payer_email']?>';
document.customer_info.Store_Name.value = '<?=$_SESSION['print_ipn']['address_name']?> , \n <?=$_SESSION['print_ipn']['address_street']?> \n <?=$_SESSION['print_ipn']['address_city']?> <?=$_SESSION['print_ipn']['address_state']?> , <?=$_SESSION['print_ipn']['address_zip']?> \n - <?=$_SESSION['print_ipn']['address_country']?>';
document.customer_info.submitted.value = 1;
document.customer_info.security_code.value = '<?=$_SESSION["lock_security_code"]?>';
document.customer_info.submit();
<?php } ?>">

<?php
require ("".$set_depth."templates/header.php");
require ("".$set_depth."app.lib/product.control/core.php/customer.area/header.wrap.php");

?>

<div align="center" id="answers" class="answers_class"></div>
<p>&nbsp;</p>
<div align="center"><table  align="center" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 15px;">
<tr>
	<td style="width: <?=$admin_config['menu_width']?>px; padding-right: 19px;">

<div align="center"><a href="//<?=$url_base?>"><img src="<?=$logo_image?>" alt="" align="middle" /></a></div>
<img src="<?=$set_depth?>images/gif/blank/1x1.gif" width="<?=$admin_config['menu_width']?>" height="1" alt="" hspace="0" vspace="0" border="0" />
</td>


	<td valign="middle" style="padding: 15px;">

<noscript>
<div align="center"><p><b><font color="red">Sorry, your browser must support javascript...</font></b></p></div>
</noscript>
<div style="padding-left: 35px;"><?php if ( function_exists("imagettftext") ) { echo "<img src='".$set_depth."images/custom/company.name.png' alt='' align='middle' />"; } else { echo "<div style='position: relative; font-weight: bold; font-size: ".$font_2."px;'>".$company_name."</div>"; } ?></div>


</td>
<td style="width: 245px;">&nbsp;</td>
</tr>
</table></div>


<table width='850' align="center" cellspacing="2" cellpadding="2" border="0" style='border: 1px solid black;'>
<tr>
	<td valign='top' style='width: 425px; border-right: 1px solid black;'><form name='signin' action='' method ='post'>
	<div align="center"><h3>Login</h3></div>
	<?php
	if ( $login_alert ) {
	?>
	<div align="center" style='padding: 2px; border: 1px dotted red;'><b style='color: red;'><?=$login_alert?></b></div>
	<?php
	}
	?>
	<div align="left" style='border: 0px solid green; width: 320px;'><div align="right">
	<p>Email: <input type='text' name='email' id='email' value='<?=$_POST['email']?>' size="30" /></p>
	<p>Password: <input type='password' name='pass' id='pass' value='<?=$_POST['pass']?>' /></p>
	
  <p>Security code: <input onfocus="this.style.background = '#f9fbb9';" type="text" name="security_code" size="8" maxlength="10" value=""> &nbsp;&nbsp;<img src="app.lib/captcha.php" alt="" border="0" align="absmiddle" style="border: 1px solid #808080;"></p>
	<div align="center"><input type='button' value='Submit' onclick='document.signin.submit();' style='position: relative; left: 45px;' /></div>
	</div></div>
	<div align="center"><p><a href="#" onclick="
	var email = prompt('Please enter your Email...');
	if ( email ) {
	window.location.href = '?reset_1=1&email='+email;
	}
	else{}
	">Forgot your password?</a></p></div>
	<input type='hidden' name='login' id='login' value='1' />
	</form>
	</td>
	<td valign='top' style='width: 425px;'>
	<form name='register' action='' method ='post'>
	<div align="center"><h3>Register</h3></div>
	<?php
	if ( $register_alert ) {
	?>
	<div align="center" style='padding: 2px; border: 1px dotted red;'><b style='color: red;'><?=$register_alert?></b></div>
	<?php
	}
	?>
	<div align="left" style='border: 0px solid green; width: 320px;'><div align="right" style='border: 0px solid blue;'>
	<p>Email: <input type='text' name='emailr' id='emailr' value='<?=$_POST['emailr']?>' size="30" /></p>
	<p>Password: <input type='password' name='passr1' id='passr1' value='<?=$_POST['passr1']?>' /></p>
	<p>Confirm Password: <input type='password' name='passr2' id='passr2' value='<?=$_POST['passr2']?>' /></p>
  <p>Security code: <input onfocus="this.style.background = '#f9fbb9';" type="text" name="security_code" size="8" maxlength="10" value=""> &nbsp;&nbsp;<img src="app.lib/captcha.php" alt="" border="0" align="absmiddle" style="border: 1px solid #808080;"></p>
	<div align="center"><input type='button' value='Submit' onclick='
	if ( document.register.passr1.value == document.register.passr2.value ) {
	document.register.submit();
	}
	else {
	alert("Passwords do not match");
	}
	' style='position: relative; left: 45px;' /></div>
	</div></div>
	<input type='hidden' name='register' id='register' value='1' />
	</form>
	</td>
</tr>
</table>


<?php
require ($set_depth . "main.footer.code.php");
?>

<?php
require ("".$set_depth."app.lib/product.control/core.php/customer.area/footer.wrap.php");
require ("".$set_depth."templates/footer.php");
?>

</body>
</html>
