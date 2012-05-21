

/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/




// Basic XHTML Tags (function "basic_tags")...
function basic_tags(description_input, start_tag, end_tag) {

// Scan for selected text
if (navigator.appName == "Microsoft Internet Explorer") {
var selection_range = document.selection.createRange().text;
}
else if (navigator.appName == "Netscape") {
var focus_start = description_input.selectionStart;
var focus_end = description_input.selectionEnd;
}

// Internet Explorer if something selected
if (navigator.appName == "Microsoft Internet Explorer" && selection_range) {
selected_text = document.selection.createRange().text;
description_input.focus();
var sel = document.selection.createRange();
sel.text = start_tag + selected_text + end_tag;
sel.moveStart('character', -sel);
sel.moveEnd('character', -sel);
sel.collapse();
sel.select();
/***code.clean.js***/
code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel);
/**************************************/
}

// Netscape if something selected
else if (navigator.appName == "Netscape" && focus_start != focus_end) {
description_input.value = description_input.value.substring(0, focus_start) + start_tag + description_input.value.substring(focus_start, focus_end) + end_tag +  description_input.value.substring(focus_end, description_input.value.length);
/***code.clean.js***/
code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel);
/**************************************/
description_input.focus();
}

// Internet Explorer if cursor is somewhere in the middle of the text
else if (navigator.appName == "Microsoft Internet Explorer" && !selection_range) {
var textarea_cursor_point = prompt("Bold Text\nPlease enter the text:", "");
if (textarea_cursor_point) {
description_input.focus();
var sel = document.selection.createRange();
sel.text = start_tag + textarea_cursor_point + end_tag;
sel.moveStart('character', -sel);
sel.moveEnd('character', -sel);
sel.collapse();
sel.select();
/***code.clean.js***/
code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel);
/**************************************/
}
else if (!textarea_cursor_point)
{ 
description_input.focus();
var sel = document.selection.createRange();
sel.moveStart('character', -sel);
sel.moveEnd('character', -sel);
sel.collapse();
sel.select();
}
}

// Netscape if cursor is somewhere in the middle of the text
else if (navigator.appName == "Netscape" && focus_start == focus_end) {
var textarea_cursor_point = prompt("Bold Text\nPlease enter the text:", "");
if (textarea_cursor_point) {
description_input.value = description_input.value.substring(0, focus_start) + start_tag + textarea_cursor_point + end_tag + description_input.value.substring(focus_end, description_input.value.length);
/***code.clean.js***/
code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel);
/**************************************/
description_input.focus();
}
else if (!textarea_cursor_point) {
description_input.selectionStart = focus_end;
description_input.selectionEnd = focus_end;
description_input.focus();
}
}

// If nothing selected and cursor isn't inside data (Internet Explorer *AND* Netscape)
else {
var textarea_end_text = prompt("Bold Text\nPlease enter the text:", "");
if (textarea_end_text) {
my_value.value = my_value.value + start_tag + textarea_end_text + end_tag + " ";
/***code.clean.js***/
code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel);
/**************************************/
}
}


}
// End of function "basic_tags"