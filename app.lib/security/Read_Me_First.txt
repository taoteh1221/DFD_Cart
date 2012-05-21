
Security Setup:
Maximum length for users/passes is 22 characters each, and only ONE user/pass will work on anything below PHP 4.2.X, so save it for administration pages.
To secure a web page with this application, add the below code at the top of each page, but at least below the call for the /main.config.php file, IN THE EXACT ORDER IT IS IN.
Fill in the security clearance level, and protected page's name + extension.
Security...
Security level #0: Anyone can access.
Security level #1: Administrator access.
Security level #2: Administrator and VIP access.
Security level #3: Administrator and Guest access.
Security level #4: Administrator and Other access.

// *Security settings start*
// This page's file name, including extension.
$protected_page_name = "FILE.NAME.AND.EXTENSION.HERE";
// Level of security between 0-4...see Read_Me_First.txt.
$security_level = SECURITY.LEVEL.#.HERE;
require("".$set_depth."app.lib/security/security.php");
// *Security settings end*

Add this code to for a logout button in the page's html:

<p>
<form action="<?=$protected_page_name?>" method="post">
<input type="hidden" name="my_logout" value="yes">
<input type="submit" value="Log Out">
</form>
