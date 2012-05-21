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

var options_jump = 0; // Drop-down ordering control



////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



function check_these(document_form, subcategories_too_here) {

	if ( document_form.check_all.checked == true ) {
	// Purge any current value(s) added to avoid duplicates
	document.mass_edit.selected_products.value = '';
	
		var count_forms = 0;
		while (  document.forms.length > count_forms ) {
		
			var count_checkboxes = 0;
			while (  document.forms[count_forms].elements.length > count_checkboxes ) {
			
				if ( document.forms[count_forms].elements[count_checkboxes].name == 'delete_checkbox' ) {
				
				document.forms[count_forms].elements[count_checkboxes].checked = true;
				document.mass_edit.selected_products.value = document.mass_edit.selected_products.value + "|" + document.forms[count_forms].elements[count_checkboxes].value + "|";
				}
				
				if ( subcategories_too_here ) {
				
					if ( document.forms[count_forms].elements[count_checkboxes].name == 'delete_cat_checkbox' ) {
					
					document.forms[count_forms].elements[count_checkboxes].checked = true;
					document.mass_edit.selected_categories.value = document.mass_edit.selected_categories.value + "|" + document.forms[count_forms].elements[count_checkboxes].value + "|";
					}
				
				}
			
			count_checkboxes = count_checkboxes + 1;
			}
		
		count_forms = count_forms + 1;
		}
	
	}
	
	else if ( document_form.check_all.checked == false ) {
	
		var count_forms = 0;
		while (  document.forms.length > count_forms ) {
		
			var count_checkboxes = 0;
			while (  document.forms[count_forms].elements.length > count_checkboxes ) {
			
				if ( document.forms[count_forms].elements[count_checkboxes].name == 'delete_checkbox' ) {
				
				document.forms[count_forms].elements[count_checkboxes].checked = false;
				document.mass_edit.selected_products.value = '';
				}
			
				if ( subcategories_too_here ) {
				
					if ( document.forms[count_forms].elements[count_checkboxes].name == 'delete_cat_checkbox' ) {
				
				document.forms[count_forms].elements[count_checkboxes].checked = false;
				document.mass_edit.selected_categories.value = '';
				}
				
				}
			
			count_checkboxes = count_checkboxes + 1;
			}
		
		count_forms = count_forms + 1;
		}
	
	}
	
//alert('finished');
//alert(document_form.elements[0].type);
//alert(document.mass_edit.selected_categories.value);
//alert(document.mass_edit.selected_products.value);
}





////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////


function menu_show(menu_id_here) {

	document.js_menu_track.status.value = 'on';
	document.getElementById(menu_id_here).style.display = 'block';

		if ( menu_id_here == 'menu_1' ) {
		SwitchImg('document.button_1','document.button_1', set_depth +
		'images/gif/layout/navigation/rollover/r.light.blank.main.button.gif')
		}
		else if ( menu_id_here == 'menu_2' ) {
		SwitchImg('document.button_2','document.button_2', set_depth +
		'images/gif/layout/navigation/rollover/r.light.blank.main.button.gif')
		}
		else if ( menu_id_here == 'menu_3' ) {
		SwitchImg('document.button_3','document.button_3', set_depth +
		'images/gif/layout/navigation/rollover/r.light.blank.main.button.gif')
		}
		else if ( menu_id_here == 'menu_4' ) {
		SwitchImg('document.button_4','document.button_4', set_depth +
		'images/gif/layout/navigation/rollover/r.light.blank.main.button.gif')
		}
		else if ( menu_id_here == 'menu_5' ) {
		SwitchImg('document.button_5','document.button_5', set_depth +
		'images/gif/layout/navigation/rollover/r.light.blank.main.button.gif')
		}
		
	
}
	

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////


function side_menu_hide(menu_id_here) {
	
	if ( menu_id_here ) {
	document.getElementById(menu_id_here).style.display = 'none';
	}
	
}
	

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////


function menu_hide() {
	
	if ( document.js_menu_track.status ) {
	
		if ( document.js_menu_track.status.value == 'on' ) {

		document.js_menu_track.status.value = 'off';

		document.getElementById('menu_1').style.display = 'none';
		document.getElementById('menu_2').style.display = 'none';
		document.getElementById('menu_3').style.display = 'none';
		document.getElementById('menu_4').style.display = 'none';
		document.getElementById('menu_5').style.display = 'none';

			if ( window.location.href.search(/.com\/examples/i) == -1 ) {
			SwitchImg('document.button_1','document.button_1', set_depth +
			'images/gif/layout/navigation/light.blank.main.button.gif')
			}
			if ( window.location.href.search(/.com\/services/i) == -1 ) {
			SwitchImg('document.button_2','document.button_2', set_depth +
			'images/gif/layout/navigation/light.blank.main.button.gif')
			}
			if ( window.location.href.search(/.com\/contact/i) == -1 ) {
			SwitchImg('document.button_3','document.button_3', set_depth +
			'images/gif/layout/navigation/light.blank.main.button.gif')
			}
			if ( window.location.href.search(/.com\/about/i) == -1 ) {
			SwitchImg('document.button_4','document.button_4', set_depth +
			'images/gif/layout/navigation/light.blank.main.button.gif')
			}
			if ( window.location.href.search(/.com\/billing/i) == -1 ) {
			SwitchImg('document.button_5','document.button_5', set_depth +
			'images/gif/layout/navigation/light.blank.main.button.gif')
			}
		
		}
	
	//alert('running menu hide...');  // Debugging
	}

}



</script>
