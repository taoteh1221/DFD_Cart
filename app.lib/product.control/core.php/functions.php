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




/////////////////////////////////////////////////////////////////////////////////
function time_offset($hour_offset_here, $minute_offset_here, $time_format) {
$local_hour_difference =  date("G")  + $hour_offset_here;
$local_minute_difference =  date("i")  + $minute_offset_here;
$the_month = date("n");
$the_day = date("j");
$the_year = date("y");

	if ( $time_format == 1 ) {
	// optional_gui_adjusted_time_n_weekday_only
	$output = date ("l, g:ia", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 2 ) {
	// optional_gui_adjusted_all_but_time
	$output = date ("l, F jS, Y", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 3 ) {
	// optional_gui_adjusted_all_but_time_and_year
	$output = date ("l, F jS", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 4 ) {
	// optional_gui_adjusted_date_and_time
	$output = date ("l, F jS, Y - g:ia", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 5 ) {
	// php_offset_date_and_time
	$output = date ("U", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 6 ) {
	// mysql_adjusted_date_and_time
	$output = date ("Y-m-d H:i:s", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}
	
	elseif ( $time_format == 7 ) {
	// filestamp_adjusted_date_and_time
	$output = date ("m-d-Y-h-iA", mktime ($local_hour_difference,$local_minute_difference,0,$the_month,$the_day,$the_year));
	}

return $output;

}
///////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
function db_data($table, $row_id_name, $row_id_value, $field) {

$database_query = mysql_query("SELECT * FROM $table WHERE $row_id_name = '$row_id_value'");

$sql_data = mysql_result($database_query, 0, $field);

return $sql_data;
}
///////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////
function path_format($array_here) {

	$loop = 0;
	foreach ( $array_here as $value ) {
	
	$return_string = $return_string . $array_here[$loop] . ">";
	
	$loop = $loop + 1;
	}


$return_string = substr_replace($return_string, "", -1, 1);



return $return_string;
}
//////////////////////////////////////////////////////////////////////////////





//////////////////////////////////////////////////////////////////////////////
function remove_backslash($form_content) {
// Removes the backslash put in front of quotes by the php interpreter
$form_content = stripslashes($form_content);
return $form_content;
}
//////////////////////////////////////////////////////////////////////////////







/////////////////////////////////////////////////////////////////////
function security_check($admin_key_here) {
$security_key = "g34m8v4cv1qvb9"; // Admin-wide security key must be hard-coded here

	// Check for proper authentication, security key, and php file path
	if ( $_SESSION['user_name'] && $_SESSION['user_password']
	&& $admin_key_here == $security_key ) { }
	else {
	echo "ACCESS DENIED";
	exit;
	}

}
/////////////////////////////////////////////////////////////////////








/////////////////////////////////////////////////////////////////////
function mailto_addresses($mode) {

$edit_email_data = mysql_query("SELECT * FROM admin_config WHERE config_id = 'general_config'");

$email_1 = mysql_result($edit_email_data, 0, "email_1");

	if ( $mode == 0 ) {
	return trim($email_1);
	}
	
	elseif ( $mode == 1 ) {
	
	$email_2 = mysql_result($edit_email_data, 0, "email_2");
	$email_3 = mysql_result($edit_email_data, 0, "email_3");
	
	
	$email_array = array($email_1, $email_2, $email_3);
	
		if ($email_array) {
		
			for ($i = 0; $i < sizeof($email_array);) {
			$email = $email_array[$i];
				if ( $email ) {
				$mailto_addresses = $mailto_addresses . $email . ", ";
				}
			
			$i = $i + 1;
			}
	
		}
	
	$mailto_addresses = substr_replace($mailto_addresses, "", -2, 2);
	
	return $mailto_addresses;
	}

}
////////////////////////////////////////////////////////////////////////







//////////////////////////////////////////////////////////////////////////
function strip_name_format($description_here) {

// Strip the html that's supported so far in this version of DFD Cart
$description_here = eregi_replace(" ", "", $description_here);
$description_here = eregi_replace("</i>", "", $description_here);
$description_here = eregi_replace("<b>", "", $description_here);
$description_here = eregi_replace("</b>", "", $description_here);
$description_here = eregi_replace("\r", "", $description_here);
$description_here = eregi_replace("\n", "", $description_here);
$description_here = eregi_replace("&nbsp;", "", $description_here);

 // Strip all other non-alphanumeric characters
$description_here = preg_replace("/[^a-zA-Z0-9s]/", "", $description_here);

return $description_here;
}
//////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////////////////////////////////////////
// Store arrays of all the category's attributes and paths for parsing html navigation algorithms, sub-category searching algorithms, import path comparision, etc
function category_parsing($make_info_here, $check_here, $find_here) {

$category_parse_array = array();

$category_data = mysql_query("SELECT * FROM category_structure");

	if ($category_data) {
	$category_data_num = mysql_numrows($category_data);
	}

	if ($category_data_num) {
	
		for ($i = 0; $i < $category_data_num;) {
		
		$show_category_id = mysql_result($category_data, $i, "id");
		$show_category_name = mysql_result($category_data, $i, "category_name");
		$show_parent_category_id = mysql_result($category_data, $i, "parent_category_id");
		
			if ( $make_info_here ) {
			$category_parse_array[] = array('id' => $show_category_id,
			'category_name' => $show_category_name,
			'parent_category_id' => $show_parent_category_id);
			}
			
			elseif ( $check_here ) {
			$category_parse_array[] = category_depth_scan($show_category_id, '', '', 1, '');
			}
			
			elseif ( $find_here ) {
			$category_parse_array[] = array('id' => $show_category_id, 'path' => category_depth_scan($show_category_id, '', '', 1, ''));
			}
		
		$i = $i + 1;
		}
	
	}

return $category_parse_array;
}
///////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////
function categories_refresh() {

// Create / Refresh the category-parsing arrays...


$_SESSION['category_array'] = category_parsing(1, '', '');
$_SESSION['category_path_check'] = category_parsing('', 1, '');
$_SESSION['category_path_find'] = category_parsing('', '', 1);



}
///////////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////
function stored_products_array() {


$product_data = mysql_query("SELECT * FROM product_list ORDER BY product_name ASC");

	if ($product_data) {
	$num = mysql_numrows($product_data);
	}



$search_all_stored = array();

	if ($num) {

		for ($i = 0; $i < $num;) {
		$show_id = mysql_result($product_data, $i, "id");
		$show_name = mysql_result($product_data, $i, "product_name");
		$product_id = mysql_result($product_data, $i, "product_id");
		$show_price = mysql_result($product_data, $i, "unit_price");
		$show_parent_id = mysql_result($product_data, $i, "parent_category_id");


		$show_price = sprintf("%01.2f", $show_price); 

		$search_all_stored[] = array('db_id'=>$show_id, 'product_name'=>$show_name, 'product_id'=>$product_id, 'unit_price'=>$show_price,
		'parent_category_id'=> $show_parent_id);

		$i = $i + 1;
		}

	}

$_SESSION['search_all_stored'] = $search_all_stored;
}
//////////////////////////////////////////////////////////////////////////////







//////////////////////////////////////////////////////////////////////////////
function url_get_data($this_previous, $this_next, $include_search_data) {

$the_get_data1 = "?category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity'];

	// Previous links START
	if ( $this_previous ) {
	
		if ( $_GET['list_location'] > 49 ) {
		$the_previous_data = $_SESSION['list_location'] - $_SESSION['list_quantity'];
		}
		else {
		$the_previous_data = "0";
		}
	
	$the_get_data2 = "&list_location=$the_previous_data";
	}
	// Previous links END


	// Next links START
	elseif ( $this_next ) {
	
	$the_next_data = $_SESSION['next_location'];
	
	$the_get_data2 = "&list_location=$the_next_data";
	
	}
	// Next links END


	// No previous or next link START
	else {
	
	$the_get_data2 = "&list_location=".$_SESSION['list_location'];
	
	}
	// No previous or next link END



	// Including search data START
	if ( $include_search_data ) {
	
		if ( $_GET['search_price'] ) {
		$get_search_price = "&search_price=" . $_GET['search_price'];
		}
		if ( $_GET['search_name'] ) {
		$get_search_name = "&search_name=" . $_GET['search_name'];
		}
		if ( $_GET['search_data'] ) {
		$get_search_data = "&search_data=" . $_GET['search_data'];
		}
	
	$the_get_data3 = $get_search_price . $get_search_name . $get_search_data;
	
	}
	// Including search data END

$requested_get_data = $the_get_data1 . $the_get_data2 . $the_get_data3;

return $requested_get_data;

}
//////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////
function category_depth_scan($category_here, $for_links, $for_space, $for_path, $for_spreadsheet, $for_count) {

	$run_scanning = 1;
	$target_category = $category_here;
	while ( $run_scanning ) {
	
		if ( $target_category > 0 ) {
		
		$scan_data = mysql_query("SELECT * FROM category_structure WHERE id = '$target_category'");
		$scanned_category_name = mysql_result($scan_data, 0, "category_name");
		$scanned_parent_id = mysql_result($scan_data, 0, "parent_category_id");
		
			if  ( $for_links == 1 ) {
			
			$render_path = "<a href='?category=$target_category&list_quantity=".$_SESSION['list_quantity']."' style='color: red;'>$scanned_category_name</a> &nbsp;&gt&nbsp; " . $render_path;
			
			}
			
			elseif  ( $for_links == 2 ) {
			
			$render_path = "$scanned_category_name &nbsp;&gt&nbsp; " . $render_path;
			
			}
			
			elseif  ( $for_space ) {
			$render_path = "&nbsp;&nbsp;" . $render_path;
			}
			
			elseif  ( $for_path ) {
			
				if ( $for_spreadsheet ) {
				$format = " > ";
				}
				else {
				$format = ">";
				}
			
			$render_path = $scanned_category_name . $format . $render_path;
			
			}
			
			elseif  ( $for_count ) {
			
			$render_path = $render_path + 1;
			
			}
			
		$target_category = $scanned_parent_id;
		}
		
		else {
		$run_scanning = NULL;
		}
	
	}

	if  ( $for_path ) {
	
		if ( $for_spreadsheet ) {
		$space = 3;
		}
		else {
		$space = 1;
		}
	
	$render_path = substr_replace($render_path, "", -$space, $space);
	}

return $render_path;
}
////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////
function category_list($category_here, $category_html_here, $parent_category_array_here, $set_depth_here, $fly_out_close, $root_menu_number, $submenu_number) {


$scan_data = mysql_query("SELECT * FROM category_structure WHERE parent_category_id = '$category_here' ORDER BY category_name ASC");

	if ($scan_data) {
	$row_num = mysql_numrows($scan_data);
	}

	if ($row_num) {
	
		for ($i = 0; $i < $row_num;) {
		$scanned_id = mysql_result($scan_data, $i, "id");
		$scanned_category_name = mysql_result($scan_data, $i, "category_name");
		$scanned_parent_id = mysql_result($scan_data, $i, "parent_category_id");
		
			if ( $category_html_here == 'href_vertical' ) {
			
			$category_list = $category_list . "<div class='category_links'>" . category_depth_scan($scanned_id, '' , 1, '', '', '') .
			"<a href='?category=".$scanned_id."&list_quantity=".$_SESSION['list_quantity'].
			"' id='v_href_id$scanned_id'>$scanned_category_name</a></div>\n";
			
				// If it's the current category, make it bold red text with an arrow
				if ( $_SESSION['category'] == $scanned_id ) {
				$category_list = eregi_replace("<div class='category_links'>" . category_depth_scan($scanned_id, '' , 1, '', '', '')."<a href='\?category=".$scanned_id, "<img src='".$set_depth_here."images/gif/red.arrow.right.gif' alt='' width='6' height='9' hspace='2' vspace='9' border='0' align='right' style='padding-right: 8px;' /><div class='category_links'>".category_depth_scan($scanned_id, '' , 1, '', '', '')."<a href='?category=".$scanned_id, $category_list);
				$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>",
				" id='v_href_id$scanned_id' style='color: red;'><b>$scanned_category_name</b></a>", $category_list);
				}
			
				// If it's a main subdirectory in "Product List", make it bold
				if ( $scanned_parent_id == 0 ) {
				$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>", " id='v_href_id$scanned_id'><b>$scanned_category_name</b></a>", $category_list);
				}
			
				if ( in_array($scanned_id, $parent_category_array_here) ) {
				
				$category_list = $category_list . category_list($scanned_id, $category_html_here, $parent_category_array_here, $set_depth_here, $fly_out_close, $root_menu_number, $submenu_number);
				
				}
			
			}
		
			elseif ( $category_html_here == 'fly_out_href_vertical' ) {
			
				if ( category_depth_scan($scanned_id, '' , '', '', '', 1) == 1 ) {
				
				$root_menu_number = $root_menu_number + 1;
				
				$submenu_number = 1;
				
				
				$last_depth_here = $_SESSION['last_depth_here'];
				
					if ( $last_depth_here > category_depth_scan($scanned_id, '' , '', '', '', 1) ) {
					
						$div_close_scan = 0;
						while ( $last_depth_here - 1 > $div_close_scan ) {
						$render_fly_out = $render_fly_out . "\n</ul></ul>\n";
						$div_close_scan = $div_close_scan + 1;
						}
					
					}
					
						if ( $render_fly_out ) {
						$render_fly_out .= "\n</ul>\n<br clear='all'>\n";
						}
					
				$category_list = $category_list . $render_fly_out . "<ul class='category_links2'>" . category_depth_scan($scanned_id, '' , 1, '', '', '') .
				"<li class='category_links3'><a href='?category=".$scanned_id."&list_quantity=".$_SESSION['list_quantity'].
				"' id='v_href_id$scanned_id' onmouseover=\" if ( document.getElementById('root_menu_".$root_menu_number."_submenu_".$submenu_number. "') ) { menu_show('root_menu_".$root_menu_number."_submenu_".$submenu_number. "'); } \">$scanned_category_name</a></li>" . ( in_array($scanned_id, $parent_category_array_here) ? '' : '</ul>' ) . "\n";
				
				$render_fly_out = NULL;
				
					// If it's the current category, make it bold red text with an arrow
					if ( $_SESSION['category'] == $scanned_id ) {
					$category_list = eregi_replace("<ul class='category_links2'>" . category_depth_scan($scanned_id, '' , 1, '', '', '')."<li class='category_links3'><a href='\?category=".$scanned_id, "<img src='".$set_depth_here."images/gif/red.arrow.right.gif' alt='' width='6' height='9' hspace='2' vspace='9' border='0' align='right' style='padding-right: 8px;' /><ul class='category_links2'>".category_depth_scan($scanned_id, '' , 1, '', '', '')."<li class='category_links3'><a href='?category=".$scanned_id, $category_list);
					$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>",
					" id='v_href_id$scanned_id' style='color: red;'><b>$scanned_category_name</b></a>", $category_list);
					}
				
					// If it's a main subdirectory in "Product List", make it bold
					if ( $scanned_parent_id == 0 ) {
					$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>", " id='v_href_id$scanned_id'><b>$scanned_category_name</b></a>", $category_list);
					}
				
				$last_depth_here = category_depth_scan($scanned_id, '' , '', '', '', 1);
				$_SESSION['last_depth_here'] = $last_depth_here;
				
					if ( in_array($scanned_id, $parent_category_array_here) ) {
					
					$category_list = $category_list . "\n\n <!--  TEST --> \n\n" . category_list($scanned_id, $category_html_here, $parent_category_array_here, $set_depth_here, $fly_out_close, $root_menu_number, $submenu_number);
					
					}
				
				}
			
			
				elseif ( category_depth_scan($scanned_id, '' , '', '', '', 1) > 1 ) {
				
				
				$last_depth_here = $_SESSION['last_depth_here'];
				
					
					if ( $last_depth_here < category_depth_scan($scanned_id, '' , '', '', '', 1) ) {
					
					$_SESSION['depth_history'][] = $last_depth_here;
					
					$notes_id = "\n<!-- root_menu_".$root_menu_number."_submenu_" . $submenu_number . "_depth_" . category_depth_scan($scanned_id, '' , '', '', '', 1) . " -->\n";
					
					$render_fly_out = $notes_id . "\n<ul id='root_menu_".$root_menu_number."_submenu_" . $submenu_number."' style='position: relative; z-index: " . category_depth_scan($scanned_id, '' , '', '', '', 1) . "; background: #eceba9; border: 1px solid #808080; padding: 7px;' class='hidden_menus'>\n";
					
					}
					else {
					$render_fly_out = NULL;
					}
				
					if ( $last_depth_here > category_depth_scan($scanned_id, '' , '', '', '', 1) ) {
					
						$div_close_scan = sizeof($_SESSION['depth_history']);
						while ( $_SESSION['depth_history'][$div_close_scan] != category_depth_scan($scanned_id, '' , '', '', '', 1) ) {
						$render_fly_out = $render_fly_out . "\n</ul></ul>\n";
						$div_close_scan = $div_close_scan - 1;
						}
					
					}
				
				$category_list = $category_list . $render_fly_out . "\n" . ( in_array($scanned_id, $parent_category_array_here) ? "<ul style='position: relative;'>" : '' ) . "\n" .
				"<li class='category_links3'><a href='?category=".$scanned_id."&list_quantity=".$_SESSION['list_quantity'].
				"' id='v_href_id$scanned_id' onmouseover=\"this.style.background = '#abb10e';  if ( document.getElementById('root_menu_".$root_menu_number."_submenu_" . ( in_array($scanned_id, $parent_category_array_here) ? $submenu_number + 1 : $submenu_number ) . "') ) { menu_show('root_menu_".$root_menu_number."_submenu_" . ( in_array($scanned_id, $parent_category_array_here) ? $submenu_number + 1 : $submenu_number ) . "'); }  \" onmouseout=\"this.style.background = '#d0d611';\" class='js_nav_links'>$scanned_category_name</a></li>\n";
				
					// If it's the current category, make it bold red text with an arrow
					if ( $_SESSION['category'] == $scanned_id ) {
					$category_list = eregi_replace("<ul class='category_links2'>" . category_depth_scan($scanned_id, '' , 1, '', '', '')."<li class='category_links3'><a href='\?category=".$scanned_id, "<img src='".$set_depth_here."images/gif/red.arrow.right.gif' alt='' width='6' height='9' hspace='2' vspace='9' border='0' align='right' style='padding-right: 8px;' /><ul class='category_links2'>".category_depth_scan($scanned_id, '' , 1, '', '', '')."<li class='category_links3'><a href='?category=".$scanned_id, $category_list);
					$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>",
					" id='v_href_id$scanned_id' style='color: red;'><b>$scanned_category_name</b></a>", $category_list);
					}
				
					// If it's a main subdirectory in "Product List", make it bold
					if ( $scanned_parent_id == 0 ) {
					$category_list = eregi_replace(" id='v_href_id$scanned_id'>$scanned_category_name</a>", " id='v_href_id$scanned_id'><b>$scanned_category_name</b></a>", $category_list);
					}
				
				$last_depth_here = category_depth_scan($scanned_id, '' , '', '', '', 1);
				$_SESSION['last_depth_here'] = $last_depth_here;
				
					if ( in_array($scanned_id, $parent_category_array_here) ) {
					
					$submenu_number = $submenu_number + 1;
					
					$category_list = $category_list . "\n\n <!--  TEST2 --> \n\n" . category_list($scanned_id, $category_html_here, $parent_category_array_here, $set_depth_here, $fly_out_close, $root_menu_number, $submenu_number);
					
					}
				
				}
			
			
			}
		
			elseif ( $category_html_here == 'form_select' ) {
			
			$category_list = $category_list . "<option value='$scanned_id'>".category_depth_scan($scanned_id, '' , 1, '', '', '')."$scanned_category_name</option>\n";
			
				if ( $_SESSION['category'] == $scanned_id ) {
				$category_list = eregi_replace("<option value='$scanned_id'>", "<option value='$scanned_id' style='color: red;' selected>", $category_list);
				}
			
			
				if ( in_array($scanned_id, $parent_category_array_here) ) {
				
				$category_list = $category_list . category_list($scanned_id, $category_html_here, $parent_category_array_here, $fly_out_close, $root_menu_number, $submenu_number);
				
				}
			
			}
		
		$i = $i + 1;
		}

	}


return $category_list;
}
////////////////////////////////////////////////////////////////////////////////////





/////////////////////////////////////////////////////////////////////////////////////////////
// Find ALL sub-categories in the target category, to include their products within the search results, mass deletion, etc
function all_subcategories($category_here) {


$return_subcategories_array = array($category_here);
$already_scanned_subcategories = array();

	if ( $category_here > 0 ) {
	
	
			foreach ( $_SESSION['category_array'] as $subcategory_scanned ) {
			
				$search_category = $subcategory_scanned['id'];
				
				if ( !in_array($subcategory_scanned['id'] , $already_scanned_subcategories)
				&& in_array($subcategory_scanned['id'] , $return_subcategories_array) ) {
				
				$subdirectory_num = NULL;
				
				$search_scan_data = mysql_query("SELECT * FROM category_structure WHERE parent_category_id = '$search_category'");
				
				$subdirectory_num = mysql_numrows($search_scan_data);
				
					if ($subdirectory_num) {
					
						for ($i = 0; $i < $subdirectory_num;) {
						$search_scanned_id = mysql_result($search_scan_data, $i, "id");
						$search_scanned_category_name = mysql_result($search_scan_data, $i, "category_name");
						$search_scanned_parent_id = mysql_result($search_scan_data, $i, "parent_category_id");
						
						//$return_subcategories_array[] = $search_scanned_id;
						
						$return_subcategories_array = array_merge($return_subcategories_array, all_subcategories($search_scanned_id));
						
						$i = $i + 1;
						}
					
					}
				
				}
			
			
			$already_scanned_subcategories[] = $subcategory_scanned['id'];
			}
	
	
	}


return $return_subcategories_array;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function find_category_id($imported_path_here) {

	foreach ( $_SESSION['category_path_find'] as $category_info ) {
	
		if ( $imported_path_here == $category_info['path'] ) {
		$target_id = $category_info['id'];
		}
	
	}


return $target_id;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function create_path($root_parent_id_here, $add_category_path_here) {

$existing_path = category_depth_scan($root_parent_id_here, '', '', 1, '');

	if ( $root_parent_id_here > 0 ) {
	$format = ">";
	}
	else {
	$format = NULL;
	$existing_path = NULL;
	}

$new_categories = explode(">", $add_category_path_here);

	$new_parent_id = $root_parent_id_here;
	$run_loop = 0;
	while ( $new_categories[$run_loop] ) {
	
	$loop_path = $loop_path . $new_categories[$run_loop] . ">";
	
	mysql_query("INSERT INTO category_structure VALUES ('', '".$new_categories[$run_loop]."', '$new_parent_id', '".date("m / d / Y")."')");
	
	// Refresh the category info arrays, and get the id of the newly-created target subcategory...
	categories_refresh();
	
	$new_parent_id = find_category_id($existing_path . $format . substr_replace($loop_path, "", -1, 1));
	
	$run_loop = $run_loop + 1;
	}


$target_id = find_category_id($existing_path . $format . substr_replace($loop_path, "", -1, 1));


return $target_id;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function import_categories($imported_path_here, $debugging_only) {

	if ( $imported_path_here != 'Category_Path' ) {

	// Remove any spaces next to greater-than symbols, extra greater than symbols at the end of the string, etc, so the program can process it...
	while ( eregi(" >", $imported_path_here) || eregi("> ", $imported_path_here) ) {
	$imported_path_here = eregi_replace(" >", ">", $imported_path_here);
	$imported_path_here = eregi_replace("> ", ">", $imported_path_here);
	}
	
	$imported_path_here = eregi_replace("<", ">", $imported_path_here);
	$imported_path_here = eregi_replace(">>", ">", $imported_path_here);
	$imported_path_here = eregi_replace("<<", ">", $imported_path_here);
	
	if ( substr($imported_path_here, -1, 1) == '>') {
	$imported_path_here = substr_replace($imported_path_here, "", -1, 1);
	}

	if ( in_array($imported_path_here, $_SESSION['category_path_check']) ) {
	
	$target_id = find_category_id($imported_path_here);
	
	}
	
	elseif ( !$imported_path_here ) { }
	
	else {
	
	// Make an array of the new category path...
	$new_path_category = explode(">", $imported_path_here);
	
	// Grab the names of all the CURRENT root categories
	$current_main_category_names = array();
	$find_scan_data = mysql_query("SELECT * FROM category_structure WHERE parent_category_id = '0'");
	$subdirectory_num = mysql_numrows($find_scan_data);
	
		if ($subdirectory_num) {
		
			for ($i = 0; $i < $subdirectory_num;) {
			$the_scanned_id = mysql_result($find_scan_data, $i, "id");
			$the_scanned_category_name = mysql_result($find_scan_data, $i, "category_name");
			$the_scanned_parent_id = mysql_result($find_scan_data, $i, "parent_category_id");
			
			$current_main_category_names[] = $the_scanned_category_name;
			
			$i = $i + 1;
			}
		
		}
	
	
		// If the new category path needs to create a brand new root category, then every subcategory is brand new too...
		if ( !in_array($new_path_category[0], $current_main_category_names) ) {
		
		//$debugging_notes = 'No, the root category DOES NOT exist already';  // Debugging
		
		$target_id = create_path(0, $imported_path_here);
		
		}
	
		// If the root category already exists, we need to find at what subcategory depth the new category path starts...
		else {
		
		//$debugging_notes = 'Yes, the root category DOES exist already';  // Debugging
		
			$path_loop = 1;
			while ( in_array(path_format(array_slice($new_path_category, 0, $path_loop)), $_SESSION['category_path_check']) ) {
			
			$existing_path = path_format(array_slice($new_path_category, 0, $path_loop));
			
			$path_loop = $path_loop + 1;
			}
		
		
		// Id of the existing subcategory where the new subcategories go, and the new subcategory path to add there
		$root_parent_id = find_category_id($existing_path, $_SESSION['category_path_find']);
		$add_category_path = eregi_replace($existing_path . ">", "", $imported_path_here);
		
		$target_id = create_path($root_parent_id, $add_category_path);
		
		}
	
	
	}
	
	
	
	if ( $debugging_only ) {
	
	// ONLY ON OF THESE IS USED AT A TIME, FOR DEBUGGING...
	
	//return $new_path_category;
	//return path_format(array_slice($new_path_category, 0, 1));
	//return $root_parent_id;
	//return $existing_path;
	//return $imported_path_here;
	//return $add_category_path;
	//return $parse_imported_path;
	//return $parse_existing_path;
	//return $debugging_notes;
	//return $current_main_category_names;
	
	}
	
	else {
	
		if ( !$target_id ) {
		$target_id = 0;
		}
	
	return $target_id;
	}

	
	
	
	
	
	}

}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////
function sanitize_requests($data) {

// remove whitespaces
$data = trim($data);

$data = strip_tags($data, '<b><u><B><U>');  // Leave bold and underline HTML tags only

/////////// S C A N N I N G   -   S T A R T /////////////////////////////
// Scan for malicious content
$scan = $data;
// Scan lowercase
$scan = strtolower($scan);
$scan = str_replace("<", " ", $scan);
$scan = str_replace(">", " ", $scan);
$scan = str_replace("/", " ", $scan);
$scan = str_replace("&gt;", " ", $scan);
$scan = str_replace("&lt;", " ", $scan);
$scan = str_replace("\n", "", $scan);
$scan = str_replace("\r", "", $scan);
$scan = str_replace("\r\n", "", $scan);
$scan = str_replace("\n\r", "", $scan);
// Scan for potentially hidden HTML tags, then scan for remaining scripting
// Detect any remaining scripting
$js_events = array("script",
                   "javascript",
                   "html",
                   "body",
                   "iframe",
                   "style",
                   "href",
                   "table",
                   "onclick",
                   "onmouseover",
                   "onmouseout",
                   "onresize",
                   "onchange",
                   "onabort",
                   "onblur",
                   "ondblclick",
                   "ondragdrop",
                   "onerror",
                   "onfocus",
                   "onkeydown",
                   "onkeypress",
                   "onkeyup",
                   "onload",
                   "onmousedown",
                   "onmousemove",
                   "onmouseup",
                   "onmove",
                   "onreset",
                   "onselect",
                   "onsubmit",
                   "onunload");
$scan = str_replace($js_events, "", $scan, $count);
   // Exit with warning if scripting is detected
   if ( $count > 0 ) {
   echo "Script and most HTML not permitted input requests...";
   exit;
   }
/////////// S C A N N I N G   -   E N D /////////////////////////////


   // apply stripslashes if magic_quotes_gpc is enabled
   if(get_magic_quotes_gpc()) {
   $data = stripslashes($data);
   }

// a mySQL connection is required before using this function
$data = mysql_real_escape_string($data);

return $data;
}
////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////////////////
function mod_unescape_sql_str($data, $html) {

$data = str_replace('\n', "\n", $data);
$data = str_replace('\r', "\r", $data);

	if ( $html ) {
	$data = str_replace("\r\n", "<br />", $data);
	$data = str_replace("\n", "<br />", $data);
	$data = str_replace("\r", "<br />", $data);
	}

return $data;
}
////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////////////////
function db_connect($db_table, $query_type, $select_columns, $insert_columns_array, $sql_conditions, $make_array) {

global $product_db_database;

//echo $product_db_database;  // DEBUGGING ONLY


	if ( strtolower($query_type) == 'select' ) {
	$z = "SELECT ".( $select_columns ? $select_columns : '*' )." FROM " . $product_db_database.".".$db_table . ( $sql_conditions ? " $sql_conditions " : '' );
	}


	elseif ( strtolower($query_type) == 'insert' ) {
	
	$no_return = 1;
	
		foreach ( $insert_columns_array as $key => $value ) {
		$new_columns = $new_columns . $key . ',';
		$column_values = $column_values . "'$value'" . ',';
		}
		
		$new_columns = $new_columns . 'ENDEND';
		$new_columns = preg_replace("/,ENDEND/i", "", $new_columns);
		
		$column_values = $column_values . 'ENDEND';
		$column_values = preg_replace("/,ENDEND/i", "", $column_values);
	
	$z = "INSERT INTO " . $product_db_database.".".$db_table . " (".$new_columns.") VALUES (".$column_values.") " .( $sql_conditions ? " $sql_conditions" : '' );
	}


	elseif ( strtolower($query_type) == 'update' ) {
	$no_return = 1;
	$z = "UPDATE " . $product_db_database.".".$db_table . " SET " .( $sql_conditions ? " $sql_conditions" : '' );
	}

	elseif ( strtolower($query_type) == 'delete' ) {
	$z = "DELETE FROM " . $product_db_database.".".$db_table . ( $sql_conditions ? " $sql_conditions" : '' );  
	}


	$result = mysql_query($z)
	or die(mysql_error());  
	$verify_results = mysql_affected_rows();

	if ( !$no_return && $make_array == 1 ) {
	
		//$data_array = array(''); // Adds blank array value at start
		while($row = mysql_fetch_array( $result )) {
		$data_array[] = $row;
		}
	
	return $data_array;
	//return $z;
	
	}
	
	elseif ( !$no_return ) {
	$row = mysql_fetch_array( $result );
	return $row;
	//return $z;
	}
	
	else {
	
		if ( $verify_results > 0 ) {
		return 1;
		}
		else {
		return 0;
		}
	//return $z;
	}
	

}
////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////////////
function find_host() {

	if ( $_SERVER['HTTP_HOST'] ) {
	$host = $_SERVER['HTTP_HOST'];
	}
	else {
	$host = $_SERVER['SERVER_NAME'];
	}

return $host;

}
////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////////////////
function mysql_io($data, $mode) {

      if ( $mode == 1 ) {
	  return mysql_real_escape_string($data);
	  }
      elseif ( $mode == 2 ) {
	  return htmlspecialchars($data);
	  }
      else {
	  return $data;
	  }

}
////////////////////////////////////////////////////////////////////////////////////////////////
