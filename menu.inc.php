<p><a href="index.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/rose.gif',1)" title="Bj&ouml;rks Purple Lodge home">The lodge</a></p>
<p><a href="alone.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/bjorkb.gif',1)" title="Enter the world of Bj&ouml;rks solo works">So alone</a></p>
<p><a href="sugar.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/cubes.gif',1)" title="Tappi T&iacute;karrass, Kukl and Sugarcubes">Joints</a></p>
<p><a href="disco.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/eye8.gif',1)" title="Discography">Retrospect</a></p>
<!--<p><a href="hunt.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/bear.gif',1)" title="Search the lodge">Hunting</a></p> -->
<p><a href="holiday.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/umurna2.gif',1)" title="Um urnot fra Bj&ouml;rk">Holiday</a></p>
<!--<p><a href="out.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/taatoo.gif',1)" title="External links">Outwards</a></p>
<p><a href="in.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/molrik.gif',1)" title="Internal links">Inwards</a></p> -->
<p><a href="http://purplelodge.net/bpl/" title="Bj&ouml;rks Purple Lodge - the old site" target="_blank">Once upon a time...</a></p>
<?php if (isset($_SESSION['user'])) { ?>
<p><a href="admin/index_test.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/molrik.gif',1)" title="Administration">Admin</a></p>
<?php } ?>
<p><a href="cookies.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('corner','','syspics/molrik.gif',1)" title="Cookies">Sweet intuition</a></p>
<?php include("includes/common.inc.php"); ?>
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="includes/common.inc.js"></script>
<script type="text/javascript" src="includes/window_fe.js"></script>
<script type="text/javascript" src="includes/mainjq.js"></script>
<?php 
if ($_SESSION['popup_lightbox']) {
?>
<script type="text/javascript" src="includes/slimbox2.js"></script>
<?php    
}
?>