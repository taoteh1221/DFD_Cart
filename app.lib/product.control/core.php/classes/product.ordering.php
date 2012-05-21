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





// "product_control" class
class product_control {

// Variables
var $products = array();



	////////////////////////////////////////////////////////////////////
	function order_total() {
		// Function to add all subtotals for order total
		$order_total = 0;
		$product_count = 0;
		
		$discounted = ((100 - $_SESSION['login']['discount']) / 100);
		
		foreach( $_SESSION['product_orders'] as $product ) {
		$order_total = $order_total + $product['product_subtotal'];
		$_SESSION['order_subtotal'] = number_format($order_total, 2);
		$_SESSION['order_total'] = number_format(($order_total * $discounted), 2);
		$product_count = $product_count + 1;
		$_SESSION['product_count'] = $product_count;
		}
		
		if ( sizeof($_SESSION['product_orders']) < 1 ) {
		$_SESSION['order_subtotal'] = number_format(0, 2);
		$_SESSION['order_total'] = number_format(0, 2);
		$_SESSION['product_count'] = 0;
		}
		
	}
	////////////////////////////////////////////////////////////////////
	
	
	
	////////////////////////////////////////////////////////////////////
	// Function to Add / Update products for the order
	function update_products($db_id_here, $product_qty_here, $order_purge_here, $custom_1, $custom_2) {
	
	/*
	Disallow commas, letters, and decimal points (round any out) in the product quantity field...
	*/
	$product_qty_here = eregi_replace(",", "", $product_qty_here);
	$product_qty_here = eregi_replace("[a-z]", "", $product_qty_here);
	$product_qty_here = round($product_qty_here);
	
	// Get any current products already added to the order
	$this->products = $_SESSION['product_orders'];
	
		if ( !$order_purge_here ) {
		// Get the product's data from the database...
		
		$product_order_data = mysql_query("SELECT * FROM product_list WHERE id='$db_id_here'");
		$product_db_id = mysql_result($product_order_data, 0, "id");
		$product_name = mysql_result($product_order_data, 0, "product_name");
		$product_id = mysql_result($product_order_data, 0, "product_id");
		$product_price = mysql_result($product_order_data, 0, "unit_price");
		$product_parent_db_id = mysql_result($product_order_data, 0, "parent_category_id");
		}
		if ( !$product_name || !$product_db_id || !$db_id_here ) {
		$order_purge_here = 1;
		$_SESSION['order_alert'] = 'empty';
		}
	
	// Calculate subtotal
	$product_subtotal = $product_qty_here * $product_price;
	
		// If they already added the item to the order, just up it to the last quantity request
		$loop = 0;
		foreach ( $this->products as $product ) {
			
			/*
			If purging from order (due to admin's recent deletion of the product),
			delete from order...
			*/
			if ( $order_purge_here && $product['db_id'] == $db_id_here ) {
			unset($this->products[$loop]);
			$this->products = array_values($this->products);
			$updating_quantity = 1;
			}
			
			elseif ( $product_name
			&& strip_name_format($product_name) == strip_name_format($product['product_name']) ) {
				
				// If quantity is "0", delete from order
				if ( !$product_qty_here ) {
				unset($this->products[$loop]);
				$this->products = array_values($this->products);
				$updating_quantity = 1;
				}
				// Change the product's quantity in the order
				else {
				$this->products[$loop]['unit_price'] = $product_price; // Refresh also, in case admin was updating this item's unit price at the same time, so it will match the adjusted subtotal
				$this->products[$loop]['product_id'] = $product_id;  // Refresh
				$this->products[$loop]['custom_1'] = $custom_1;  // Refresh
				$this->products[$loop]['custom_2'] = $custom_2;  // Refresh
				$this->products[$loop]['product_quantity'] = $product_qty_here;
				$this->products[$loop]['product_subtotal'] = $product_subtotal;
				$updating_quantity = 1;
				}
			
			}
			
		$loop = $loop + 1;
		}
	
	
		// If this is a new item added to the order, a new sub-array for this product...
		if ( $product_qty_here && !$updating_quantity ) {
		$this->products[] = array('db_id' => $product_db_id, 'product_name' => $product_name, 'product_id' => $product_id,
		 'custom_1' => $custom_1,
		  'custom_2' => $custom_2,
		 'unit_price' => $product_price, 'product_quantity' => $product_qty_here, 'product_subtotal' => $product_subtotal);
		
		}
	
	// Purge this variable after use
	$updating_quantity = NULL;
	
	// Export all product orders back into it's session
	$_SESSION['product_orders'] = $this->products;
	
	// Set the total for all product subtotals after adding new product
	$this -> order_total();
	}
	////////////////////////////////////////////////////////////////////
	
	
}



?>