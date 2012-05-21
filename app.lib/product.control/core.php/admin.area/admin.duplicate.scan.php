<?php


/*
THIS MUST BE RUN SEPERATELY FROM THE IMPORTER,
BECAUSE THAT USES ENOUGH CPU TIME AS IT IS WITH BIG PRODUCT LISTS,
AND THIS IS CREATING AND ALTERING A RUN THOUGH OF THE "stored_products_array()" ROUTINE TOO...
*/

if ( !$_SESSION['duplicate_scan'] ) {

// To compare what is already in the stored products when ready
stored_products_array();

	// Put every description into 1 string...
	$duplicate_scan = NULL;
	foreach ( $_SESSION['search_all_stored'] as $stored_key => $stored_value ) {

	$duplicate_scan = $duplicate_scan . " DUP_SCAN " .
	strip_name_format($_SESSION['search_all_stored'][$stored_key]['product_name']);

	}
	$_SESSION['duplicate_scan'] = $duplicate_scan . " DUP_SCAN ";
	//$_SESSION['duplicate_scan_debugging'] = $_SESSION['duplicate_scan'];  // Debugging

?>
<script language="javascript" type="text/javascript">
window.location.href = '?key=<?=$_SESSION['sec_key']?>&duplicate_delete=2';
</script>
<?php

}


else {

	// Scan for duplicates
	$online_duplicate_count = 0;
	foreach ( $_SESSION['search_all_stored'] as $stored_key => $stored_value ) {

		if ( substr_count($_SESSION['duplicate_scan'], "DUP_SCAN " .
			strip_name_format($_SESSION['search_all_stored'][$stored_key]['product_name']) .
			" DUP_SCAN " .
			strip_name_format($_SESSION['search_all_stored'][$stored_key]['product_name']) .
			" DUP_SCAN") > 0 ) {
		
		$online_duplicate_count = $online_duplicate_count + 1;
		$start_tag = "<font style='color: red;'>";
		$end_tag = "</font>";
		$logged_name = 'Duplicate near';
					
		// Delete any duplicate from the database
		
		mysql_query("DELETE FROM product_list WHERE id='".$_SESSION['search_all_stored'][$stored_key]['db_id']."'");
		
	
		// Duplicate deletion's results
		$online_delete_results = $online_delete_results .
		"<div style='padding: 1px; ".
		"font-weight: bold; color: red;'>".
		"Duplicate deleted from live site: ". 
		$_SESSION['search_all_stored'][$stored_key]['product_name'].
		"&nbsp;&nbsp;&nbsp;".
		$_SESSION['search_all_stored'][$stored_key]['unit_price'].
		"&nbsp;&nbsp;(Id: ".
		$_SESSION['search_all_stored'][$stored_key]['db_id'].
		")</div>";
	
		unset($_SESSION['search_all_stored'][$stored_key]);
		$_SESSION['search_all_stored'] = array_values($_SESSION['search_all_stored']);
		}


	}




	if ( !$online_delete_results ) {
	$online_delete_results = "<div style='padding: 1px; font-weight: bold; color: red;'>No duplicates detected.</div>";
	}

$_SESSION['online_delete_results'] = "<div style='padding: 1px; font-weight: bold; color: red;'><p><b style='color: #f44a1d;'>Online Duplicates Summary:</b><br />" . $online_duplicate_count . " online duplicate(s) deleted...</p>\n<b><font style='color: #f44a1d;'>***Detailed Results***</font></b>\n" . $online_delete_results . "<div style='padding: 4px;'></div>&nbsp;<b style='color: #f44a1d;'><u>You can now import your spreadsheet</u></b>.</div>";


// Clear the temporary data
$_SESSION['search_all_stored'] = FALSE;
$_SESSION['duplicate_scan'] = FALSE;

?>

<script language="javascript" type="text/javascript">
window.location.href = 'import.php';
</script>

<?php

}
?>
