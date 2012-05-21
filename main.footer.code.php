
<form name="js_menu_track" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="status" value="off">
</form>

<!--
IF WEB SITE OWNERS WANT THIS TEXT FOOTER ADVERTISEMENT REMOVED, PLEASE CONSIDER AT LEAST A $1 DONATION AT WWW.DFDCART.COM

THANK YOU FOR YOUR INTEREST / SUPPORT IN THE DEVELOPMENT OF THIS APPLICATION  :)
-->
<div id="dfd_footer" align="center">Powered by <a href="http://www.dfdcart.com/" target="_blank"><img src="<?=$set_depth?>images/png/footer2.png" alt="" width="49" height="10" border="0" align="middle"></a> under <a href="<?=$set_depth?>gpl.license.php" target="_blank">GPL</a></div>
<!--
IF WEB SITE OWNERS WANT THIS TEXT FOOTER ADVERTISEMENT REMOVED, PLEASE CONSIDER AT LEAST A $1 DONATION AT WWW.DFDCART.COM

THANK YOU FOR YOUR INTEREST / SUPPORT IN THE DEVELOPMENT OF THIS APPLICATION  :)
-->


<?php

mysql_close(); // Close all database connection(s) at the end of script runtime to maximize server efficiency

?>



<script language="JavaScript" type="text/javascript">

// Gecko browser text/image alignment compatibility
if ( user_agent.search(/gecko/i) != -1 ) {

	var loop = 0;
	while ( loop < document.images.length ) {
	
		if ( document.images[loop].align == 'middle' ) {
		document.images[loop].align = 'absmiddle';
		}
	
	var loop = loop + 1;
	}

}


</script>



<?php
/*
echo "<br clear='all'>Sessions: <pre>";
print_r($_SESSION);
echo "</pre>";
*/
?>
