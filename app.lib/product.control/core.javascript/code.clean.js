

/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/





// Remove errors/consolidate code (function "code_clean")...
function code_clean(description_input, start_tag, end_tag, textarea_cursor_point, focus_start, focus_end, sel) {

// Internet Explorer cursor position **START**
if (navigator.appName == "Microsoft Internet Explorer") {

// Find the cursor position before consolidating any tags...
description_input.focus();
var find_msie_cursor = document.selection.createRange(); // Finds general cursor location
description_input.select();
var full_text_range = document.selection.createRange(); // Finds range of ALL text
full_text_range.collapse();
full_text_range.select();
description_input.blur();

// Pinpoints cursor location, and loads it into variable "pos"...
for (var pos = 0; find_msie_cursor.compareEndPoints("StartToStart", full_text_range);) {
full_text_range.moveStart("character", 1);
pos = pos + 1;
}

// Combine duplicate tags that are next to each other
var baseline_value = description_input.value;
description_input.value = description_input.value.replace(end_tag + start_tag, "");
description_input.value = description_input.value.replace(end_tag + " " + start_tag, " ");
description_input.value = description_input.value.replace(start_tag + start_tag, start_tag);
description_input.value = description_input.value.replace(end_tag + end_tag, end_tag);
description_input.value = description_input.value.replace(start_tag + " " + start_tag, start_tag);
description_input.value = description_input.value.replace(end_tag + " " + end_tag, end_tag);

// Combine bold and italic tags
var b_and_i_combined_baseline = description_input.value;
description_input.value = description_input.value.replace("[i][b]", "[ib]");
description_input.value = description_input.value.replace("[/b][/i]", "[/ib]");
description_input.value = description_input.value.replace("[b][i]", "[ib]");
description_input.value = description_input.value.replace("[/i][/b]", "[/ib]");

// Adjust cursor position if needed (for combined **duplicate** tags)
var tag_space = start_tag + end_tag;
if (baseline_value == description_input.value && textarea_cursor_point) {
position_tracking = start_tag + textarea_cursor_point + end_tag;
}
else if (baseline_value == description_input.value && !textarea_cursor_point) {
position_tracking = start_tag + end_tag;
}
else if (baseline_value != description_input.value && textarea_cursor_point) {
position_tracking = "";
}
else {
position_tracking = "";
}

pos = pos + position_tracking.length - tag_space.length;

// Adjust cursor position if needed (for combined **different** tags)
if (b_and_i_combined_baseline != description_input.value) {
pos = pos + 6;
}

// Place the cursor in the right spot...
description_input.focus();
description_input.select();
var set_msie_cursor = document.selection.createRange(); // Finds range of ALL text
set_msie_cursor.moveStart("character", pos);
set_msie_cursor.collapse(); // Selects cursor only, without including any text with it
set_msie_cursor.select();


}
// Internet Explorer cursor position **END**



/////////////////////////////////////



// Netscape cursor position **START**
else if (navigator.appName == "Netscape") {

/*
Cursor position has already been located with FORM_FIELD.selectionStart / FORM_FIELD.selectionEnd in Netscape, so we are already set to adjust things after consolidating any tags...
*/

// Combine duplicate tags that are next to each other
var baseline_value = description_input.value;
description_input.value = description_input.value.replace(end_tag + start_tag, "");
description_input.value = description_input.value.replace(end_tag + " " + start_tag, " ");
description_input.value = description_input.value.replace(start_tag + start_tag, start_tag);
description_input.value = description_input.value.replace(end_tag + end_tag, end_tag);
description_input.value = description_input.value.replace(start_tag + " " + start_tag, start_tag);
description_input.value = description_input.value.replace(end_tag + " " + end_tag, end_tag);

// Combine bold and italic tags
var b_and_i_combined_baseline = description_input.value;
description_input.value = description_input.value.replace("[i][b]", "[ib]");
description_input.value = description_input.value.replace("[/b][/i]", "[/ib]");
description_input.value = description_input.value.replace("[b][i]", "[ib]");
description_input.value = description_input.value.replace("[/i][/b]", "[/ib]");

// Adjust cursor position if needed (for combined **duplicate** tags)
if (baseline_value == description_input.value && textarea_cursor_point) {
position_tracking = start_tag + textarea_cursor_point + end_tag;
}
else if (baseline_value == description_input.value && !textarea_cursor_point) {
position_tracking = start_tag + end_tag;
}
else if (baseline_value != description_input.value && textarea_cursor_point) {
position_tracking = textarea_cursor_point;
}
else {
position_tracking = "";
}

description_input.selectionStart = focus_end + position_tracking.length;
description_input.selectionEnd = focus_end + position_tracking.length;

// Adjust cursor position if needed (for combined **different** tags)
if (b_and_i_combined_baseline != description_input.value) {
description_input.selectionStart = focus_end + position_tracking.length + 6;
description_input.selectionEnd = focus_end + position_tracking.length + 6;
}


}
// Netscape cursor position **END**


}
// End of function "code_clean"