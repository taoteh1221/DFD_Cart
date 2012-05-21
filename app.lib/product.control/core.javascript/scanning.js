
/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/



//////////////////////////////////////////////////////////////////////////////////////
/////////   S C A N   F O R   D A T A   E N T R Y   E R R O R S   ////////////////////
//////////////////////////////////////////////////////////////////////////////////////

function scan_product_form(description_input, price_input, confirm_form, category_editing_here) {

	
	// Product validation
	
	if ( !category_editing_here ) {

	// Price variables
	var price_tab_scan = price_input.value.search(/	/);
	// Description variables
	var description_space_scan = (description_input.value.substr(0,1));
	var description_html_scan = (description_input.value.substr(0,1));
	var description_tab_scan = description_input.value.search(/	/);
	
	
		// Price scanning
		// Warn if TAB spacing is in the price
		if ( price_tab_scan != -1 ) {
		alert(' You have TAB spacing within your **PRICE** ... \n This must be removed before adding or updating this item, \n or import / export compatibility will be corrupted.');
		var price_ok = '';
		}
		else {
		var price_ok = 1;
		}
		
		// Warn about prices not a number greater than zero...
		if ( price_input.value != 0 && price_input.value > 0 )
		{ }
		else {
		alert(' The price must be a number greater than zero. ');
		var price_ok = '';
		}
		
		
		// Description scanning
		/*
		Scan for bold or italic description formatting that could mess up alphabetical list ordering
		*/
		if ( confirm_form && description_html_scan == "<" || confirm_form && description_html_scan == "[" ) {
		alert(' Text formatting in front of the product description disables the alphabetical listing of this product. ');
		var description_ok = '';
		}
		else {
		var description_ok = 1;
		}
		
		/*
		Scan for a space in the beginning of the description formatting that could mess up 
		alphabetical list ordering
		*/
		if ( confirm_form && description_space_scan == " " || confirm_form && description_space_scan == " " ) {
		alert(' Empty space\(s) in front of the product description disables the alphabetical listing of this product. ');
		var description_ok = '';
		}
		
		// Stop everything if a TAB space is in the description
		if ( description_tab_scan != -1 ) {
		alert(' You have TAB spacing within your **DESCRIPTION** ... \n This must be removed before adding or updating this item, \n or import / export compatibility will be corrupted.');
		var description_ok = '';
		}
	
		if ( price_ok && description_ok && confirm_form == 'add' ) {
		parent.document.add_form.submit();
		}
		else if ( price_ok && description_ok && confirm_form == 'update' ) {
		parent.document.update_form.submit();
		}
		
	}
	
	
	
	// Category Validation
	
	else {
	
	
	// Description variables
	var description_space_scan = (description_input.value.substr(0,1));
	var description_html_scan = (description_input.value.substr(0,1));
	var description_tab_scan = description_input.value.search(/	/);
	
		// Description scanning
		/*
		Scan for bold or italic description formatting that could mess up alphabetical list ordering
		*/
		if ( confirm_form && description_html_scan == "<" || confirm_form && description_html_scan == "[" ) {
		alert(' Text formatting in front of the category description disables the alphabetical listing of this category. ');
		var description_ok = '';
		}
		else {
		var description_ok = 1;
		}
		
		// Scan for a blank category name field
		if ( confirm_form && !description_space_scan ) {
		alert(' Please enter a name for your category. ');
		var description_ok = '';
		}
		
		/*
		Scan for a space in the beginning of the description formatting that could mess up 
		alphabetical list ordering
		*/
		if ( confirm_form && description_space_scan == " " || confirm_form && description_space_scan == " " ) {
		alert(' Empty space\(s) in front of the category description disables the alphabetical listing of this category. ');
		var description_ok = '';
		}
		
		// Stop everything if a TAB space is in the description
		if ( description_tab_scan != -1 ) {
		alert(' You have TAB spacing within your **DESCRIPTION** ... \n This must be removed before adding or updating this item, \n or import / export compatibility will be corrupted.');
		var description_ok = '';
		}
	
		if ( description_ok && confirm_form == 'add_category' ) {
		document.category_editing.submit();
		}
		else if ( description_ok && confirm_form == 'update_category' ) {
		document.category_editing.submit();
		}
	
	}
	
}




//////////////////////////////////////////////////////////////////////////////////////
//////////   U N D O   A N D   R E D O   B U T T O N S   /////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////



var history_array = new Array();
var undo_array = new Array();
var redo_array = new Array();

// Track the text's editing history
function editing_history(form_field) {

history_array.push(form_field.value);
window.document.history_form.history_change.value = 'on';

}


// Undo text editing
function undo_history(form_field) {

var undo_array = history_array;


	if ( undo_array[0] && undo_array.length > 1 ) {
	
	var store_redo = undo_array.length - 1;
	redo_array.push(history_array[store_redo]); // Store to REDO before UNDOING
	
	undo_array.pop();
	
	var undo_spot = undo_array.length - 1;
	form_field.value = undo_array[undo_spot];
	
	
	// Debugging
	//alert(undo_array[undo_spot] + '\n\n undo_array length = ' + undo_array.length + '\n\n undo_spot = ' + undo_spot);
	}
	
	else {
	alert('You have returned to the document\'s original state.');
	}
	
window.document.history_form.history_change.value = 'off';
}



// Redo text editing
function redo_history(form_field) {

var redo_spot = redo_array.length - 1;

	if ( redo_array[redo_spot] ) {
	
		if ( window.document.history_form.history_change.value == 'off' ) {
		
		history_array.push(redo_array[redo_spot]); // Store to history_array before REDOING
		
		form_field.value = redo_array[redo_spot];
		
		// Debugging
		//alert(redo_array[redo_spot] + '\n\n redo_array length before deletion = ' + redo_array.length + '\n\n redo_spot = ' + redo_spot);

		redo_array.pop();
		}
		
	}

	else {
	alert('You have redone everything.');
	}
	
window.document.history_form.history_change.value = 'off';
}
