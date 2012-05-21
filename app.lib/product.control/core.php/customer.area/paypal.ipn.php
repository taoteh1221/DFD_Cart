<?php

// PHP 4.1

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// Debugging
if ( $pp_debugging ) {
//echo "<br clear='all'>IPN POST DATA: <pre>";
//print_r($_POST);
//echo "</pre>";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.'.( $pp_debugging ? 'sandbox.' : '').'paypal.com', 443, $errno, $errstr, 30);


if (!$fp) {
// HTTP ERROR
} else {

	if ( $_POST['payment_status'] == 'Completed' ) {
	
		foreach ($_POST as $key => $value) {
		$orderdata .= "$key: $value\n";
		}

	$order_file = "./orders/".$_POST['custom'].".txt";
	$fh = fopen($order_file, 'a+') or die("can't open file");
	fwrite($fh, $orderdata);
	fclose($fh);

	}

fclose ($fp);
}


if ( $pp_debugging && $_POST['txn_id'] ) {

	foreach ($_POST as $key => $value) {
	$logdata .= "$key: $value\n";
	}

$log_file = "./logs/ipnlog.txt";
$fh = fopen($log_file, 'a+') or die("can't open file");
$datasplit = "\n\n==========================================\n==========================================\n\n";
fwrite($fh, $datasplit . $logdata . $datasplit);
fclose($fh);

}

?>