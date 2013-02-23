<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Udvikling</title>
<script type="text/javascript" src="includes/udvikling.js"></script>
<?php include("includes/udvikling.inc.php"); ?>
</head>

<body>
<h1>Test af popupic</h1>
<p><img src="images/oli7tp9.jpg" alt="xxx" width="594" height="600" /></p>
<p>Test her lokal fil: 
<?php 
$testpic = "oli7tp9.jpg";
echo popupic($testpic); 
?>
</p>
<p>Test her remote fil: 
<?php 
$testpic2 = "http://unit.bjork.com/77island/images/innocence4big.jpg";
echo popupic($testpic2); 
?>
</p>
<p>Test her simpelt vindue: <a href="test3.php" target="Test3popup" onclick="window.open(this.href, this.target, 'width=502,height=520'); return false;">Test3</a></p>
<p>Test her aktivt vindue: <?php echo "<a href=\"javascript:open_picwin_fe('images/".$testpic."',594,600);\">open_picwin_fe lokal fil</a>"; ?></p>
<p>Test her aktivt vindue: <?php echo "<a href=\"javascript:open_picwin_fe('".$testpic2."',600,600);\">open_picwin_fe remote fil</a>"; ?></p>
<?php $testpic3 = "http://unit.bjork.com/77island/tappi/images/gramm16_labela.jpg";
?>
<p>Test her aktivt vindue: <?php echo "<a href=\"javascript:open_picwin_fe('".$testpic3."',600,600);\">open_picwin_fe remote fil</a>"; ?></p>
<p>Test her lokal fil:
<?php 
echo popup_image_fe($testpic); 
?>
</p>
<p>Test her remote fil: 
<?php 
echo popup_image_fe($testpic2); 
?> - 
<?php 
echo popup_image_fe($testpic3); 
?>
</p>
</body>
</html>
