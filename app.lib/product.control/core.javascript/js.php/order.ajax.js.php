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
?>


<script type="text/javascript">


/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/



//var post_data = form_data_array(document.form_data_mail);
function form_data_array(form_name) {

			
	for (var i = 0; i < form_name.length; i++) {
	
	var scan_keys = '';
	var scan_values = '';
	
		if ( form_name[i].name ) {
		
			if ( form_name[i].type == 'checkbox' && form_name[i].checked == false ) {
			form_name[i].value = '';
			}
			else if ( form_name[i].type == 'radio' && form_name[i].checked == false ) {
			form_name[i].value = '';
			}
		
			if ( !ajax_string ) {
			var scan_keys = form_name[i].name.replace("\&", "ESCAPE_AMPERSAND");
			var scan_values = form_name[i].value.replace("\&", "ESCAPE_AMPERSAND");
			
			var ajax_string = scan_keys + "=" + scan_values + "&";
			}
			else {
			var scan_keys = form_name[i].name.replace("\&", "ESCAPE_AMPERSAND");
			var scan_values = form_name[i].value.replace("\&", "ESCAPE_AMPERSAND");
			
			var ajax_string = ajax_string + scan_keys + "=" + scan_values + "&";
			}
			
		}
		else if ( form_name[i].id ) {
		
			if ( form_name[i].type == 'checkbox' && form_name[i].checked == false ) {
			form_name[i].value = '';
			}
			else if ( form_name[i].type == 'radio' && form_name[i].checked == false ) {
			form_name[i].value = '';
			}
		
			if ( !ajax_string ) {
			var scan_keys = form_name[i].id.replace("\&", "ESCAPE_AMPERSAND");
			var scan_values = form_name[i].value.replace("\&", "ESCAPE_AMPERSAND");
			
			var ajax_string = scan_keys + "=" + scan_values + "&";
			}
			else {
			var scan_keys = form_name[i].id.replace("\&", "ESCAPE_AMPERSAND");
			var scan_values = form_name[i].value.replace("\&", "ESCAPE_AMPERSAND");
			
			var ajax_string = ajax_string + scan_keys + "=" + scan_values + "&";
			}
			
		}
		
		
		
		//var ajax_string = ajax_string.replace("  ", " ");
		//var ajax_string = ajax_string.replace(" ", "_");
	
	
	}

var ajax_string = ajax_string.substring(0, ajax_string.length-1)

return ajax_string;

}



function update_products(the_form) {

		if ( the_form.dyn_prod_qty.value > 0 ) {
	document.getElementById('id1_' + the_form.db_id.value).style.background = '#efebb0';
	<?php echo ( $admin_config['product_id_on'] ? "document.getElementById('id1_1_' + the_form.db_id.value).style.background = '#efebb0';" : "" ); ?>
	<?php echo ( $admin_config['custom_1'] ? "document.getElementById('id1_c1_' + the_form.db_id.value).style.background = '#efebb0';" : "" ); ?>
	<?php echo ( $admin_config['custom_2'] ? "document.getElementById('id1_c2_' + the_form.db_id.value).style.background = '#efebb0';" : "" ); ?>
	document.getElementById('id2_' + the_form.db_id.value).style.background = '#efebb0';
	document.getElementById('id3_' + the_form.db_id.value).style.background = '#efebb0';
		}
		else {
	document.getElementById('id1_' + the_form.db_id.value).style.background = '#d0cbcb';
	document.getElementById('id1_1_' + the_form.db_id.value).style.background = '#d0cbcb';
	document.getElementById('id2_' + the_form.db_id.value).style.background = '#d0cbcb';
	document.getElementById('id3_' + the_form.db_id.value).style.background = '#d0cbcb';
		}

	var format_ajax = form_data_array(the_form);
			
var http_request = false;

	// If *POST* occurs in Netscape (disables script in MSIE if not run inside an IF statement!)
	if (http_request.overrideMimeType)
	{ http_request.overrideMimeType('text/xml');
	}

// Create a xmlhttp request...

	// Mozilla, Safari, etc
	if (window.XMLHttpRequest)
	{ http_request = new XMLHttpRequest();
	}
	// MSIE
	else if (window.ActiveXObject)
	{ http_request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else
	{ alert('Your browser settings don\'t seem to support AJAX,\n or you need to upgrade your browser :(');
	return false;
	}

// Javascript function to call as the xmlhttp request is processed and sent back
http_request.onreadystatechange = function() { alertContents(http_request, the_form); };

// Were're ready to make the xmlhttp request now...

// SEND DATA





// POST
http_request.open('POST', set_depth + 'update.order.php?key=<?=$_SESSION['sec_key']?>', true);
http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
http_request.setRequestHeader("Content-length", format_ajax.length);
http_request.setRequestHeader("Connection", "close");
http_request.send(format_ajax);
// alert(post_data);   // Debugging






//http_request.open('GET', set_depth + 'update.order.php?key=<?=$_SESSION['sec_key']?>&db_id=' + the_db_id + '&product_qty=' + the_product_qty, true);
//http_request.send(null);

}

//////////////////////////////////////////////////////////////////////////////////////

function alertContents(http_request, the_form) {

/* 
Display "Loading...", and see if the request has been responded to, and that it wasn't a 404/500 etc...
*/
	if (http_request.readyState == 4 && http_request.status == 200) {
	show_pop_in_mini();
	
		if ( the_form.dyn_prod_qty.value > 0 ) {
	show_pop_in_mini();
	document.getElementById("div_one").innerHTML = "<b>You now have " + the_form.dyn_prod_qty.value + " unit\(s) of this product in your order.<br />\(" + "Order Total: \$" + http_request.responseText + ")</b>";
	document.getElementById("order_total").innerHTML = "\$" + http_request.responseText;
		}
		else {
	show_pop_in_mini();
	document.getElementById("div_one").innerHTML = "<b>Product *deleted* from order.<br />\(" + "Order Total: \$" + http_request.responseText + ")<br /><a href='javascript:location.reload\(true);' style='color: #ffffff; z-index: 31;'><b>Refresh Color Codes</b></a></b>";
	document.getElementById("order_total").innerHTML = "\$" + http_request.responseText;
		}

	}
	else if (http_request.readyState == 4 && http_request.status != 200) {
	show_pop_in_mini();
	document.getElementById("div_one").innerHTML = "<b>There was a problem with the request. <br>Please Try Again.</b>";
	}
	else {
	show_pop_in_mini();
	document.getElementById("div_one").innerHTML = "<b>Loading, Please Wait...</b>";
	}

}


///////////////////////////////////////////////////////////////////////////////////////


</script>
