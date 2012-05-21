<?php


// Putting numbered navigation links on the page to browse by...

$list_me = 0;
$list_link = 1;
$link_array = array();
while ( $list_me < $product_num ) {
$list_check = $list_me;
	
	// Make the main array of links
	if ( $list_check == $_SESSION['list_location'] ) {
	$link_array[] = "\n<font class='list_nav_selected'>$list_link</font>\n";
	$target_page = 1;
	}
	else {
	$link_array[] = "\n<a href='?category=".$_SESSION['category']."&list_quantity=".$_SESSION['list_quantity']."&list_location=$list_me&search_price=".$_GET['search_price']."&search_name=".$_GET['search_name']."&search_data=".$_GET['search_data']."' class='list_nav' style=''>$list_link</a>\n";
	}
	
	/*
	If the main array has more links than one page can display, break it into chunks
	to display per-page
	*/
	if ( sizeof($link_array) > $max_number_links ) {
	
		$array_count = 0;
		$split_link_array = array();
		while ( $array_count < sizeof($link_array) ) {
		
		$split_link_array = array_chunk($link_array, $max_number_links);
		
		$array_count = $array_count + $max_number_links;
		}
	
	}
	
$list_me = $list_me + $_SESSION['list_quantity'];
$list_link = $list_link + 1;
}

//echo "Split = " . $_SESSION['split'] . " (starting from '0')<br />";  // Debugging

foreach ( $split_link_array[$_SESSION['split']] as $split_key => $split_value ) {

$rendered_links = $rendered_links . $split_value;
$previous_links = $_SESSION['split'] - 1;
$previous_array_value = $max_number_links - 1;
$next_links = $_SESSION['split'] + 1;

	if ( $split_link_array[$previous_links] ) {
	$mod_link_previous = eregi_replace(">(.*)</a>",
	">&lt&lt</a>",
	$split_link_array[$previous_links][$previous_array_value]);
	$mod_link_previous = eregi_replace("&split=(.*)' class='list_nav'",
	"&split=$previous_links' class='list_nav' style='text-decoration: underline; font-weight: bold;'",
	$mod_link_previous);
	}
	
	if ( $split_link_array[$next_links] ) {
	$mod_link_next = eregi_replace(">(.*)</a>",
	" style='text-decoration: underline;'><b>&gt&gt</b></a>",
	$split_link_array[$next_links][0]);
	$mod_link_next = eregi_replace("&split=(.*)' class='list_nav'",
	"&split=$next_links' class='list_nav'",
	$mod_link_next);
	}

}


/*echo "<pre>";
print_r($split_link_array);  // Debugging
echo "</pre>";*/


// If all the numbered links can fit on one page
if ( sizeof($link_array) <= $max_number_links ) {

	/*echo "<pre>";
print_r($link_array);
	echo "</pre>";*/
	foreach ( $link_array as $number_link ) {
	$rendered_links = $rendered_links . $number_link;
	}

}




?>