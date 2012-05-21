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



security_check($admin_key); // Verify this appears to be an administrator editing the database

// Import a plain-text, tab-delimited spreadsheet to add / update products...


// To compare what is already in the stored products when ready
stored_products_array();



// Import the temporary data into the next phase
$already_checked = $_SESSION['already_checked'];
$already_added = $_SESSION['already_added'];
$name_of_file = $_SESSION['name_of_file'];
$row_offset = $_SESSION['row_offset'];
$imported_products_history = $_SESSION['imported_products_history'];
$imported_products = $_SESSION['imported_products'];
$import_update_results = $_SESSION['import_update_results'];
$updated_imported_products = $_SESSION['updated_imported_products'];
$new_import_results = $_SESSION['new_import_results'];
$new_imported_products = $_SESSION['new_imported_products'];
$import_error = $_SESSION['import_error'];
$error_note = $_SESSION['error_note'];

$to_be_unset = $_SESSION['to_be_unset'];
$to_be_unset2 = $_SESSION['to_be_unset2'];

$spreadsheet_scan_results = $_SESSION['spreadsheet_scan_results'];
$spreadsheet_duplicate_count = $_SESSION['spreadsheet_duplicate_count'];



if ( $_POST['check_me'] || $_GET['loaded'] ) {
///////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

$to_be_unset = array();

	if ( !$_GET['loaded'] ) {
	
	$name_of_file = $_FILES['import_file']['name'];
	
	$import_file = $_FILES['import_file']['tmp_name'];
	$fp = fopen($import_file,'r');
	
		if ( !$fp ) {
		echo 'ERROR: Unable to open file.';
		exit;
		}
	
		while ( !feof($fp) ) {
		$import_file_data = $import_file_data . fgets($fp, 4096); //use 4096 for very long lines
		$fp++;
		}
		fclose($fp);
	
	
	// Increase the compatibility of finding the end of each product...
	$import_file_data = eregi_replace("end_here", "END_HERE", $import_file_data); // All uppercase
	$import_file_data = eregi_replace("\"END_HERE\"", "END_HERE", $import_file_data); // Remove quotes
	//$import_file_data = eregi_replace("\"Category_Path\"", "Category_Path", $import_file_data); // Remove quotes
	//$import_file_data = eregi_replace("(.*)Category_Path	END_HERE", "", $import_file_data); // Remove Header
	
	
	// Now we can move on to parse out everything to import it...
	
	
	// Split the products into array values
	$imported_products_history = explode("	END_HERE", $import_file_data);
	$imported_products = explode("	END_HERE", $import_file_data);
	
		if ( sizeof($imported_products) > $max_imported ) {
		?>
		<script type="text/javascript">
		window.location.href = '?error=overload';
		</script>
		<?php
		exit;
		}
		else {
		
		
		// Export needed data to sessions for the different phases of importing
		$_SESSION['already_checked'] = $already_checked;
		$_SESSION['already_added'] = $already_added;
		$_SESSION['name_of_file'] = $name_of_file;
		$_SESSION['row_offset'] = $row_offset;
		$_SESSION['debugging_imported_products'] = $imported_products; // Debugging
		$_SESSION['imported_products_history'] = $imported_products_history;
		$_SESSION['imported_products'] = $imported_products;
		$_SESSION['import_update_results'] = $import_update_results;
		$_SESSION['updated_imported_products'] = $updated_imported_products;
		$_SESSION['new_import_results'] = $new_import_results;
		$_SESSION['new_imported_products'] = $new_imported_products;
		$_SESSION['import_error'] = $import_error;
		$_SESSION['error_note'] = $error_note;
		
		$_SESSION['to_be_unset'] = $to_be_unset;
		$_SESSION['to_be_unset2'] = $to_be_unset2;
		
		$_SESSION['spreadsheet_scan_results'] = $spreadsheet_scan_results;
		$_SESSION['spreadsheet_duplicate_count'] = $spreadsheet_duplicate_count;
		
		
		?>
		<script type="text/javascript">
		window.location.href = '?key=<?=$_SESSION['sec_key']?>&loaded=1';
		window.open('<?=$set_depth?>progress.bar.php?percent=5','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=5');
		</script>
		<?php
		exit;
		}
	
	
	}

	/*
	Further break down imported items into product attribute sub-arrays
	and scan all formatting / values before deciding whether or not import
	to the database and how for each item (adding or updating)
	*/
	
	
	$imported_row = $row_offset + 0;
	foreach ( $imported_products as $import_key => $import_value ) {
	
		// Purge any garbage data added to the array during data parsing
		if ( !eregi("[_a-z0-9-]", $imported_products[$import_key]['product_name']) && !eregi("[_a-z0-9-]", $imported_products[$import_key]) ) {
		unset($imported_products[$import_key]);
		}
		else {
	
		$scan_attributes = explode('	', $imported_products[$import_key]);
		
		//$scan_log = $scan_log . "  " . sizeof($scan_attributes);  // Debugging only
		
		
		$imported_products[$import_key] = eregi_replace("	END_HERE", "", $imported_products[$import_key]);
		
			//Scan for format errors...
			//Scan for column numbers
			if ( sizeof($imported_products) < 1 ) {
			$import_error = "Your spreadsheet does not appear to contain <i>any</i> properly formatted data";
			}
			//Scan for extra column numbers, price, etc that would screw up importing
			elseif ( sizeof($scan_attributes) > 4 && $admin_config['product_id_on'] ) {
			$tab_message_format = $imported_products[$import_key];
			$tab_message_format = eregi_replace('""', '"', $tab_message_format);
			$error_note = $scan_log . "<div style='padding: 1px;'>NOTE: Correct or delete any blank space or corrupt data around the rows mentioned below, and make sure there are no blank spaces or data <i>below and to the sides</i> of <i>all</i> the spreadsheet rows and columns. <u>Additionally, since you have product ids -enabled- make sure your spreadsheet has a product id column...if it does not, go to <a href='configure.php'>the DFD Cart Configure Page</a> and disable product ids, then try importing the spreadsheet again.</u></div>";
			$import_error = $import_error . "\n<div style='padding: 1px;'>Uncomplete or corrupt data around row $imported_row" . ": $tab_message_format</div>\n";
			}
			//Scan for too little column numbers, that would screw up importing
			elseif ( sizeof($scan_attributes) < 4 && $admin_config['product_id_on'] ) {
			$tab_message_format = $imported_products[$import_key];
			$tab_message_format = eregi_replace('""', '"', $tab_message_format);
			$error_note = $scan_log . "<div style='padding: 1px;'>NOTE: Since you have product ids -enabled- make sure your spreadsheet has a product id column...if it does not, go to <a href='configure.php'>the DFD Cart Configure Page</a> and disable product ids, then try importing the spreadsheet again.</u></div>";
			$import_error = $import_error . "\n<div style='padding: 1px;'>Uncomplete or corrupt data around row $imported_row" . ": $tab_message_format</div>\n";
			}
			//Scan for extra column numbers, price, etc that would screw up importing
			elseif ( sizeof($scan_attributes) > 3 && !$admin_config['product_id_on'] ) {
			$tab_message_format = $imported_products[$import_key];
			$tab_message_format = eregi_replace('""', '"', $tab_message_format);
			$error_note = $scan_log . "<div style='padding: 1px;'>NOTE: Correct or delete any blank space or corrupt data around the rows mentioned below, and make sure there are no blank spaces or data <i>below and to the sides</i> of <i>all</i> the spreadsheet rows and columns. <u>Additionally, since you have product ids -disabled- make sure your spreadsheet -does not- have product id column...if it does, go to <a href='configure.php'>the DFD Cart Configure Page</a> and enable product ids, then try importing the spreadsheet again.</u></div>";
			$import_error = $import_error . "\n<div style='padding: 1px;'>Uncomplete or corrupt data around row $imported_row" . ": $tab_message_format</div>\n";
			}
		
			if ( !$import_error ) {
		
			// Split each product array into product attributes sub-arrays
			$product_attributes = explode('	', $import_value);


			// Description cleanup
			$product_attributes[0] = eregi_replace("\n", "", $product_attributes[0]);  // \N RETURN
			$product_attributes[0] = eregi_replace("\r", "", $product_attributes[0]);  // \R RETURN
			$product_attributes[0] = eregi_replace("\r", "", $product_attributes[0]);  // \R RETURN
			// Items that cause a ? on some linux systems rendered in html
			$product_attributes[0] = eregi_replace('“', '"', $product_attributes[0]);  // “
			$product_attributes[0] = eregi_replace('”', '"', $product_attributes[0]);  // ”
			$product_attributes[0] = eregi_replace("‘", "'", $product_attributes[0]);  // ‘
			$product_attributes[0] = eregi_replace("’", "'", $product_attributes[0]);  // ’
			$product_attributes[0] = eregi_replace('–', '-', $product_attributes[0]);  // –
				// Remove likely double quote text delimiter from alleged spreadsheet formatting
				if ( substr($product_attributes[0], 0, 1) == '"'
				&& substr($product_attributes[0], -1, 1) == '"' ) {
				$product_attributes[0] = substr_replace($product_attributes[0], "", 0, 1);
				$product_attributes[0] = substr_replace($product_attributes[0], "", -1, 1);
				}
			// Remove any remaining *dual* double quotes
			$product_attributes[0] = eregi_replace('""', '"', $product_attributes[0]);
	
				if ( $admin_config['product_id_on'] ) {
				// Product id cleanup
				$product_attributes[1] = strip_name_format($product_attributes[1]);
				// Price cleanup
				$product_attributes[2] = eregi_replace("\n", "", $product_attributes[2]);  // \N RETURN
				$product_attributes[2] = eregi_replace("\r", "", $product_attributes[2]);  // \R RETURN
				$product_attributes[2] = eregi_replace("	", "", $product_attributes[2]);  // TAB
				$product_attributes[2] = eregi_replace(" ", "", $product_attributes[2]);  // SPACE
				$product_attributes[2] = eregi_replace("\\\$", "", $product_attributes[2]); // Dollar sign
				$product_attributes[2] = eregi_replace("[a-z]", "", $product_attributes[2]); // Letters
				$product_attributes[2] = eregi_replace('"', '', $product_attributes[2]); // Double quotes
				$product_attributes[2] = eregi_replace("'", "", $product_attributes[2]); // Single quotes
				/*
				Format XX.XX for price search compatibility, but with no commas above 3
				digits to the left of the decimal point, ***so order calculations aren't ruined***
				*/
				$product_attributes[2] = sprintf("%01.2f", $product_attributes[2]); 
				
					// Remove spaces near greater than symbol AND likely double quote text delimiter from alleged spreadsheet formatting
					if ( substr($product_attributes[3], 0, 1) == '"') {
					$product_attributes[3] = substr_replace($product_attributes[3], "", 0, 1);
					}
					if ( substr($product_attributes[3], -1, 1) == '"' ) {
					$product_attributes[3] = substr_replace($product_attributes[3], "", -1, 1);
					}
			
				$imported_products[$import_key] = array('product_name'=> $product_attributes[0], 'product_id'=> $product_attributes[1],
				'unit_price'=> $product_attributes[2], 'parent_category_id'=> import_categories($product_attributes[3]), 'data_row'=> $imported_row + 1);
				}
				if ( !$admin_config['product_id_on'] ) {
				// Price cleanup
				$product_attributes[1] = eregi_replace("\n", "", $product_attributes[1]);  // \N RETURN
				$product_attributes[1] = eregi_replace("\r", "", $product_attributes[1]);  // \R RETURN
				$product_attributes[1] = eregi_replace("	", "", $product_attributes[1]);  // TAB
				$product_attributes[1] = eregi_replace(" ", "", $product_attributes[1]);  // SPACE
				$product_attributes[1] = eregi_replace("\\\$", "", $product_attributes[1]); // Dollar sign
				$product_attributes[1] = eregi_replace("[a-z]", "", $product_attributes[1]); // Letters
				$product_attributes[1] = eregi_replace('"', '', $product_attributes[1]); // Double quotes
				$product_attributes[1] = eregi_replace("'", "", $product_attributes[1]); // Single quotes
				/*
				Format XX.XX for price search compatibility, but with no commas above 3
				digits to the left of the decimal point, ***so order calculations aren't ruined***
				*/
				$product_attributes[1] = sprintf("%01.2f", $product_attributes[1]); 
				
					// Remove spaces near greater than symbol AND likely double quote text delimiter from alleged spreadsheet formatting
					if ( substr($product_attributes[2], 0, 1) == '"') {
					$product_attributes[2] = substr_replace($product_attributes[2], "", 0, 1);
					}
					if ( substr($product_attributes[2], -1, 1) == '"' ) {
					$product_attributes[2] = substr_replace($product_attributes[2], "", -1, 1);
					}
			
				$imported_products[$import_key] = array('product_name'=> $product_attributes[0], 'unit_price'=> $product_attributes[1],
				'parent_category_id'=> import_categories($product_attributes[2]), 'data_row'=> $imported_row + 1);
				}
			
			}

		$imported_row = $imported_row + 1;
	
		}
	
	}




	// If no import errors, scan for data entry errors next...
	if ( !$import_error ) {

		// Detect unvalid product data
		foreach ( $imported_products as $import_key => $import_value ) {


			if (  $imported_products[$import_key]['product_name']
			&& !eregi("[_a-z0-9-]",
			substr($imported_products[$import_key]['product_name'], 0, 1))  ) {
			
			$data_entry_error = $data_entry_error .
			"\n<div style='padding: 1px;'>" .
			"First character(s) of the description on row " .
			$imported_products[$import_key]['data_row'] .
			" are NOT alphanumeric: " .
			$imported_products[$import_key]['product_name'] .
			"&nbsp;&nbsp;&nbsp;&nbsp;" . $imported_products[$import_key]['unit_price'] .
			"</div>\n";
			
			$data_entry_error_count = $data_entry_error_count + 1;
			
			// Failsafe - remove products with errors from the import array
			unset($imported_products[$import_key]);
			
			$space_error = 1;
			
			}
			else {
			$space_error = NULL;
			}


			if ( !$space_error ) {
			
				if ( eregi("(.*)Product_Name(.*)",
				$imported_products[$import_key]['product_name'])
				|| !eregi("(.*)[a-z0-9-](.*)", $imported_products[$import_key]['product_name'])
				|| $imported_products[$import_key]['unit_price'] < 0.01 ) {
				
					/*
					Create an error message for the GUI if only partial data for
					a product was added...
					*/
					if ( !eregi("(.*)Product_Name(.*)",
					$imported_products[$import_key]['product_name']) ) {
					
						if (  !eregi("(.*)[a-z0-9-](.*)",
						$imported_products[$import_key]['product_name'])
						&& $imported_products[$import_key]['unit_price'] > 0
						|| eregi("(.*)[a-z0-9-](.*)",
						$imported_products[$import_key]['product_name']) 
						&& $imported_products[$import_key]['unit_price'] < 0.01  )  {
						
						$data_entry_error = $data_entry_error .
						"\n<div style='padding: 1px;'>" .
						"Missing product data on row " .
						$imported_products[$import_key]['data_row'] .
						": " .
						$imported_products[$import_key]['product_name'] .
						"&nbsp;&nbsp;&nbsp;&nbsp;" .
						$imported_products[$import_key]['unit_price'] .
						"</div>\n";
						
						$data_entry_error_count = $data_entry_error_count + 1;
						
						}
					
					}
				
				// Failsafe - remove products with errors from the import array
				unset($imported_products[$import_key]); 
				}
			
			}

		}

	}


///////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
}




/*
If there are no import errors *AND* no *current* data entry errors, then proceed to SCAN DEEPER and import VALID product data only...
*/
if ( !$import_error && !$data_entry_error ) {


	if ( $_GET['loaded'] || $_GET['phase'] == 2 ) {
	///////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////

		// Detect duplicates IN THE SPREADSHEET
		// Put every description into 1 string...
		$duplicate_scan = NULL;
		foreach ( $imported_products as $import_key => $import_value ) {

		$duplicate_scan = $duplicate_scan . " DUP_SCAN " .
		strip_name_format($imported_products[$import_key]['product_name']);

		}
		$duplicate_scan .= " DUP_SCAN ";
		//$_SESSION['duplicate_scan_debugging'] = $duplicate_scan;  // Debugging

		// Scan for duplicates...
		if ( !$spreadsheet_duplicate_count ) {
		$spreadsheet_duplicate_count = 0;
		}

		foreach ( $imported_products as $import_key => $import_value ) {

			if ( $_GET['loaded']
			&& substr_count($duplicate_scan, "DUP_SCAN " .
			strip_name_format($imported_products[$import_key]['product_name']) .
			" DUP_SCAN") > 1
			|| $_GET['phase'] == 2
			&& $imported_products[$import_key]['flag'] != "duplicate_value"
			&& substr_count($duplicate_scan, "DUP_SCAN " .
			strip_name_format($imported_products[$import_key]['product_name']) .
			" DUP_SCAN") > 1 ) {

				if ( $_GET['phase'] == 2 ) {
				$scan_count = "Scan 2";
				}
				else {
				$scan_count = "Scan 1";
				}
		
		
			$spreadsheet_duplicate_count = $spreadsheet_duplicate_count + 1;

			// Spreadsheet duplicate results
			$spreadsheet_scan_results = $spreadsheet_scan_results .
			"<div style='padding: 1px; ".
			"font-weight: bold; color: red;'>".
			"$scan_count - Duplicate on row " .
			$imported_products[$import_key]['data_row'] .
			": ".
			$imported_products[$import_key]['product_name'].
			"&nbsp;&nbsp;&nbsp;".
			$imported_products[$import_key]['unit_price'].
			"</div>";
			
			/*
			Product is a duplicate, so mark for deletion instead of doing it now,
			so spreadsheet row logging remains intact
			*/
			// Flag the description for tracking
			$imported_products[$import_key]['flag'] = "duplicate_value";
			$to_be_unset[] = $import_key;
			}

		}


	// Export needed data to sessions for the different phases of importing
	$_SESSION['already_checked'] = $already_checked;
	$_SESSION['already_added'] = $already_added;
	$_SESSION['name_of_file'] = $name_of_file;
	$_SESSION['row_offset'] = $row_offset;
	$_SESSION['debugging_imported_products'] = $imported_products; // Debugging
	$_SESSION['imported_products_history'] = $imported_products_history;
	$_SESSION['imported_products'] = $imported_products;
	$_SESSION['import_update_results'] = $import_update_results;
	$_SESSION['updated_imported_products'] = $updated_imported_products;
	$_SESSION['new_import_results'] = $new_import_results;
	$_SESSION['new_imported_products'] = $new_imported_products;
	$_SESSION['import_error'] = $import_error;
	$_SESSION['error_note'] = $error_note;
	
	$_SESSION['to_be_unset'] = $to_be_unset;
	$_SESSION['to_be_unset2'] = $to_be_unset2;
	
	$_SESSION['spreadsheet_scan_results'] = $spreadsheet_scan_results;
	$_SESSION['spreadsheet_duplicate_count'] = $spreadsheet_duplicate_count;


		if ( !$_GET['phase'] ) {
		?>
		<script type="text/javascript">
		window.location.href = '?key=<?=$_SESSION['sec_key']?>&phase=1';
		window.open('<?=$set_depth?>progress.bar.php?percent=10','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=15');
		</script>
		<?php
		exit;
		}
		elseif ( $_GET['phase'] == 2 ) {
		?>
		<script type="text/javascript">
		window.location.href = '?key=<?=$_SESSION['sec_key']?>&phase=3';
		window.open('<?=$set_depth?>progress.bar.php?percent=15','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=25');
		</script>
		<?php
		exit;
		}

	///////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////
	}
	
	
	if ( !$_GET['already_added_g'] ) {
		
		$loop_point = 75;
		$loop_count = 0;
		// Then import the UPDATE-ONLY products...
		foreach ( $imported_products as $import_key => $import_value ) {
	
			if ( $loop_count < $loop_point ) {
			
			foreach ( $_SESSION['search_all_stored'] as $stored_key => $stored_value ) {
			
				if ( strip_name_format($imported_products[$import_key]['product_name']) == strip_name_format($_SESSION['search_all_stored'][$stored_key]['product_name'])  ) {
				
				$loop_count = $loop_count + 1;
				
					if ( strip_name_format($imported_products[$import_key]['unit_price']) != strip_name_format($_SESSION['search_all_stored'][$stored_key]['unit_price'])
					|| $imported_products[$import_key]['parent_category_id'] != $_SESSION['search_all_stored'][$stored_key]['parent_category_id']  ) {

					
					mysql_query("UPDATE product_list SET product_name = '".$imported_products[$import_key]['product_name']."', product_id = '".$imported_products[$import_key]['product_id']."', unit_price = '".$imported_products[$import_key]['unit_price']."', parent_category_id = '".$imported_products[$import_key]['parent_category_id']."' WHERE id = '".$_SESSION['search_all_stored'][$stored_key]['db_id']."'");
					
					
					// Product update results
						if ( $imported_products[$import_key]['parent_category_id'] == $_SESSION['search_all_stored'][$stored_key]['parent_category_id'] ) {
						
						$import_update_results = $import_update_results .
						"<div style='padding: 1px; font-weight: bold; color: #9b2891;'>".
						"Updated from row " .
						$imported_products[$import_key]['data_row'] .
						": ".
						$imported_products[$import_key]['product_name'].
						"&nbsp;&nbsp;&nbsp;".
						$imported_products[$import_key]['unit_price'].
						"&nbsp;&nbsp;Previous value: ". 
						$_SESSION['search_all_stored'][$stored_key]['product_name'].
						"&nbsp;&nbsp;&nbsp;".
						$_SESSION['search_all_stored'][$stored_key]['unit_price'].
						"&nbsp;&nbsp;(same category path)</div>";
						
						}
						else {
						
							if ( $imported_products[$import_key]['parent_category_id'] > 0 ) {
							$category_path_log = eregi_replace(">", " > ", category_depth_scan($imported_products[$import_key]['parent_category_id'], '', '', 1, ''));
							}
							else {
							$category_path_log = "All Products";
							}
						
						$import_update_results = $import_update_results .
						"<div style='padding: 1px; font-weight: bold; color: #9b2891;'>".
						"Updated from row " .
						$imported_products[$import_key]['data_row'] .
						": ".
						$imported_products[$import_key]['product_name'].
						"&nbsp;&nbsp;&nbsp;".
						$imported_products[$import_key]['unit_price'].
						"&nbsp;&nbsp;Previous value: ". 
						$_SESSION['search_all_stored'][$stored_key]['product_name'].
						"&nbsp;&nbsp;&nbsp;".
						$_SESSION['search_all_stored'][$stored_key]['unit_price'].
						"&nbsp;&nbsp;New category path: ". $category_path_log .
						"</div>";
						
						}
					
					$updated_imported_products = $updated_imported_products + 1;
					}

				/*
				Product is a duplicate, so mark for deletion instead of doing it now,
				so spreadsheet row logging remains intact
				*/
				$to_be_unset[] = $import_key;
				}

			}
			
			$already_checked = $already_checked + 1;
			}

		}
		
	
	// Update-only products are done, so we remove them from the array of products to be added
	foreach ( $to_be_unset as $unset_key => $unset_value ) {
	unset($imported_products[$unset_value]);
	}
		
	if ( $loop_count == $loop_point && $already_checked <= sizeof($imported_products_history) ) {
		

	// Export needed data to sessions for the different phases of importing
	$_SESSION['already_checked'] = $already_checked;
	$_SESSION['already_added'] = $already_added;
	$_SESSION['name_of_file'] = $name_of_file;
	$_SESSION['row_offset'] = $row_offset;
	$_SESSION['debugging_imported_products'] = $imported_products; // Debugging
	$_SESSION['imported_products_history'] = $imported_products_history;
	$_SESSION['imported_products'] = $imported_products;
	$_SESSION['import_update_results'] = $import_update_results;
	$_SESSION['updated_imported_products'] = $updated_imported_products;
	$_SESSION['new_import_results'] = $new_import_results;
	$_SESSION['new_imported_products'] = $new_imported_products;
	$_SESSION['import_error'] = $import_error;
	$_SESSION['error_note'] = $error_note;
	
	$_SESSION['to_be_unset'] = $to_be_unset;
	$_SESSION['to_be_unset2'] = $to_be_unset2;
	
	$_SESSION['spreadsheet_scan_results'] = $spreadsheet_scan_results;
	$_SESSION['spreadsheet_duplicate_count'] = $spreadsheet_duplicate_count;


			$progress_bar = round($already_checked / sizeof($imported_products_history) * 40 + 14);
			?>
		<script type="text/javascript">
		window.location.href = '?already_checked_g=<?=$already_checked?>';
		window.open('<?=$set_depth?>progress.bar.php?percent=<?=$progress_bar?>','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=<?=$progress_bar?>');
		</script>
			<?php
			exit;
			
		
	}
	
	}


// Purge for adding new products incrementally too

	if ( !$_GET['already_added_g'] ) {
	$new_imported_products = sizeof($imported_products);
	$imported_products_history = $imported_products;
	}

	$to_be_unset2 = array();

	if ( sizeof($imported_products) > 0 || !$_SESSION['search_all_stored'] ) {

		// Import all the NEW products too
		$loop2_point = 500;
		$loop2_count = 0;
		foreach ( $imported_products as $import_key => $import_value ) {

		if ( !$imported_products[$import_key]['flag'] ) {
			
			if ( $loop2_count < $loop2_point ) {
			
			$loop2_count = $loop2_count + 1;
			
			mysql_query("INSERT INTO product_list VALUES ('', '".$imported_products[$import_key]['product_name']."', '".
			$imported_products[$import_key]['product_id']."', '".
			$imported_products[$import_key]['unit_price']."', '".
			$imported_products[$import_key]['parent_category_id']."', '')");
			
			
			
			$new_import_results = $new_import_results .
			"<div style='padding: 1px; font-weight: bold; color: #3f39ac;'>New product from row ".
			$imported_products[$import_key]['data_row'] .
			": " .
			$imported_products[$import_key]['product_name'] .
			"&nbsp;&nbsp;&nbsp;" . $imported_products[$import_key]['unit_price'] . "</div>";
			
		$to_be_unset2[] = $import_key;
		$already_added = $already_added + 1;
			}
		
		}
	
	}

	// Update-only products are done, so we remove them from the array of products to be added
	foreach ( $to_be_unset2 as $unset_key => $unset_value ) {
	unset($imported_products[$unset_value]);
	}
		
	if ( $loop2_count == $loop2_point && $already_added <= sizeof($imported_products_history) ) {
		

	// Export needed data to sessions for the different phases of importing
	$_SESSION['already_checked'] = $already_checked;
	$_SESSION['already_added'] = $already_added;
	$_SESSION['name_of_file'] = $name_of_file;
	$_SESSION['row_offset'] = $row_offset;
	$_SESSION['debugging_imported_products'] = $imported_products; // Debugging
	$_SESSION['imported_products_history'] = $imported_products_history;
	$_SESSION['imported_products'] = $imported_products;
	$_SESSION['import_update_results'] = $import_update_results;
	$_SESSION['updated_imported_products'] = $updated_imported_products;
	$_SESSION['new_import_results'] = $new_import_results;
	$_SESSION['new_imported_products'] = $new_imported_products;
	$_SESSION['import_error'] = $import_error;
	$_SESSION['error_note'] = $error_note;
	
	$_SESSION['to_be_unset'] = $to_be_unset;
	$_SESSION['to_be_unset2'] = $to_be_unset2;
	
	$_SESSION['spreadsheet_scan_results'] = $spreadsheet_scan_results;
	$_SESSION['spreadsheet_duplicate_count'] = $spreadsheet_duplicate_count;


			$progress_bar = round($already_checked / sizeof($imported_products_history) * 40 + 55);
			?>
		<script type="text/javascript">
		window.location.href = '?already_added_g=<?=$already_added?>';
		window.open('<?=$set_depth?>progress.bar.php?percent=<?=$progress_bar?>','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=<?=$progress_bar?>');
		</script>
			<?php
			exit;
			
		
	}


	}


// Reorder the arrays just to keep it clean, AFTER ALL ROW COUNTING
$imported_products = array_values($imported_products);


	// Update confirmations...
	// New
	if ( $already_added < 1 ) {
	$new_imported_products = "0";
	}
	// Updated
	if ( $updated_imported_products < 1 ) {
	$updated_imported_products = "0";
	}

	/*
	// Remove the duplicate products from the updated one's count
	if ( $updated_imported_products >= $spreadsheet_duplicate_count ) {
	$updated_imported_products = $updated_imported_products - $spreadsheet_duplicate_count;
	}
	*/

?>

		<script type="text/javascript">
		window.open('<?=$set_depth?>progress.bar.php?percent=100','progress_bar','width=550,height=100,scrollbars=no');
		//var answer_file = "progress.bar"; var answer_category = "nav"; show_answer(answer_file, answer_category, 1, '?percent=100');
		</script>
<p>
<b><font style='color: #f44a1d;'>Imported file "<?=$name_of_file?>"...
<br />
<br />
Spreadsheet Importing Summary:<br />
<font style='color: #9b2891;'><?=$updated_imported_products?> updated product(s) were updated at the web site.</font><br />

<font style='color: #3f39ac;'><?=$new_imported_products?> new product(s) were added to the web site.</font><br />

<font style='color: red;'><?=$spreadsheet_duplicate_count?> spreadsheet duplicates were NOT imported to the web site.<?php if ( $spreadsheet_duplicate_count > 0 ) { ?>..<u>leave one of each duplicate listed below, delete the rest, and try again</u>.<?php } ?></font></font></b>
</p>
<b><font style='color: #f44a1d;'>***Detailed Results***</font></b>
<?php
	if ( !$spreadsheet_scan_results && !$import_update_results
	&& !$new_import_results ) {
?>
<div style='padding: 1px; font-weight: bold; color: #f44a1d;'>No detailed results to display</div>
<?php
}
?>

<div style='padding: 1px;'><?=$import_update_results?></div>

<div style='padding: 1px;'><?=$new_import_results?></div>

<div style='padding: 1px;'><?=$spreadsheet_scan_results?></div>

<?php
}

else {


	if ( $data_entry_error ) {
	$data_entry_error_message = "<div style='padding: 1px;'>$data_entry_error_count product(s) contain data entry errors from no alphabetical ordering capability due to white space or formatting in front of the product description, <i>&nbsp;or&nbsp;</i> missing product data in a column: <br />\n------------------------------------------------------------------------------------<br />\n" . $data_entry_error . "\n</div>\n";
	$_SESSION['phases_complete'] = 1;
	?>
	<script type="text/javascript">
	window.open('<?=$set_depth?>progress.bar.php?percent=100','progress_bar','width=550,height=100,scrollbars=no');
	</script>
	<?php
	}

	if ( $import_error ) {
	$import_error_message = "<div style='padding: 1px;'>Spreadsheet formatting error(s) detected...<br />------------------------------------------------------------------------------------<br />\n" . $import_error . "</div>"; // Spreadsheet formatting error
	$_SESSION['phases_complete'] = 1;
	?>
	<script type="text/javascript">
	window.open('<?=$set_depth?>progress.bar.php?percent=100','progress_bar','width=550,height=100,scrollbars=no');
	</script>
	<?php
	}

?>

<p><b><font color='red'>Errors were detected in file "<?=$name_of_file?>"...</p>
<p><?=$error_note?></p>
<p>You can look in <span onclick='var answer_file = "importing.spreadsheets"; var answer_category = "general"; show_answer(answer_file, answer_category);' title="What's This?" style="color: red; text-decoration: underline; cursor: pointer;">Spreadsheet Import Help</span> for more details on correcting import issues.</font></b></p>
<p><b><font color='red'>
***Error Messages***<br />
<?php

echo $data_entry_error_message;  // Data entry error

echo $import_error_message;  // Spreadsheet formatting error

?>
</font></b></p>

<?php
}

if ( $_SESSION['phases_complete'] ) {

// Clear temp data used in the phases of importing products from spreadsheet
$_SESSION['phases_complete'] = FALSE;
$_SESSION['duplicate_delete'] = FALSE;

$_SESSION['name_of_file'] = FALSE;
$_SESSION['already_checked'] = FALSE;
$_SESSION['already_added'] = FALSE;
$_SESSION['row_offset'] = FALSE;
$_SESSION['imported_products_history'] = FALSE;
$_SESSION['imported_products'] = FALSE;
$_SESSION['import_update_results'] = FALSE;
$_SESSION['updated_imported_products'] = FALSE;
$_SESSION['new_import_results'] = FALSE;
$_SESSION['new_imported_products'] = FALSE;
$_SESSION['import_error'] = FALSE;
$_SESSION['error_note'] = FALSE;

$_SESSION['to_be_unset'] = FALSE;
$_SESSION['to_be_unset2'] = FALSE;

$_SESSION['spreadsheet_scan_results'] = FALSE;
$_SESSION['spreadsheet_duplicate_count'] = FALSE;

}



// Debugging

//echo ereg_replace(" DUP_SCAN ", "\n<br />", $duplicate_scan);

/*

echo "<br clear='all'><br clear='all'>\"\$imported_products\" Array: <pre>";
print_r($imported_products);
echo "</pre>";


echo "<br clear='all'>Session \"search_all_stored\": <pre>";
print_r($_SESSION['search_all_stored']);
echo "</pre>";

*/

?>