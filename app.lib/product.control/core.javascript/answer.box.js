

/*
  DFD Cart
  www.DFDcart.com
  
  DragonFrugal.com - Web Site Solutions

  Copyright (c) 2007 DragonFrugal.com

  Released under the GNU General Public License
*/




function show_answer(answer_file, answer_category, no_x_here, get_data_here)
{ 

	if ( no_x_here ) {
	
	var answer_box_nav_html = '<span style="position: relative; z-index: 300; float: right; width: 5%;"><b style="font-size: 15px; color: #BC5938;">&nbsp;&nbsp;&nbsp;</b></span><div id="answers_title" style="position: relative; z-index: 300; padding: 0px; float: middle; text-align: center;"></div><br clear="all" />';
	
	}
	
	else {
	
	var answer_box_nav_html = '<span style="position: relative; z-index: 300; float: right; width: 5%;" onclick="hide_answer();"><b style="position: relative; z-index: 300; font-size: 15px; cursor: pointer; color: #BC5938;">&nbsp;X&nbsp;</b></span><div id="answers_title" style="padding: 0px; float: middle; text-align: center;"></div><br clear="all" />';
	
	}

	
	if ( get_data_here ) {
	var answer_content = '<iframe src="' + set_depth + 'sub.content/pop.in/' + answer_category + '/' + answer_file + '.php' + get_data_here + '" width="98%" height="91%" scrolling="auto" frameborder="0" name="answer_pop_in_iframe" style="position: relative; z-index: 300; bottom: 16px; left: 4px; padding: 0px; border: 0px solid #F5EFE9;">You need Explorer v.6+ or FireFox v.1+ or equivelent...</iframe>';
	}

	
	else {
	var answer_content = '<iframe src="' + set_depth + 'sub.content/pop.in/' + answer_category + '/' + answer_file + '.php" width="98%" height="91%" scrolling="auto" frameborder="0" name="answer_pop_in_iframe" style="position: relative; z-index: 300; bottom: 16px; left: 4px; padding: 0px; border: 0px solid #F5EFE9;">You need Explorer v.6+ or FireFox v.1+ or equivelent...</iframe>';
	}

document.getElementById('answers').style.width = '550px';
document.getElementById('answers').style.height = '410px';
document.getElementById('answers').style.padding = '3px';
document.getElementById('answers').style.border = '2px solid #808080';
document.getElementById('answers').innerHTML = answer_box_nav_html + answer_content;
document.getElementById('answers').style.visibility = 'visible';
}


function hide_answer()
{ parent.document.getElementById('answers').style.visibility = 'hidden';
parent.document.getElementById('answers').innerHTML = '';
parent.document.getElementById('answers').style.width = '0px';
parent.document.getElementById('answers').style.height = '0px';
parent.document.getElementById('answers').style.padding = '0px';
parent.document.getElementById('answers').style.border = '0px solid #808080';
}


function loading_message_control()
{ document.getElementById("wait").style.visibility='hidden';
/*
Use styling changes below *besides* the above code, to avoid image-edge distortion in FireFox
*/
document.getElementById("wait").innerHTML = "";
document.getElementById("wait").style.width='0%';
document.getElementById("wait").style.height='0%';
document.getElementById("wait").style.padding='0px';
document.getElementById("wait").style.border='0px';
}

function loading_message_display()
{ document.write ('<div align="center" id="wait" style="position: absolute; margin: 5% 30%; width: 33%; height: 7%; background-color: #808080; color: #000000; border: 1px solid black; padding: 5px; visibility: visible; opacity: .7; filter: alpha(opacity=70);"></div>');
document.getElementById("wait").innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Loading...</b>&nbsp;&nbsp;&nbsp;&nbsp;';
}