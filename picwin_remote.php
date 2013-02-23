<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php 
$image_file = trim($_GET['pic']); 	//initiel rensning
if (substr(strtolower($image_file),0,4)=="http") {		//extra check af om det er en remote fil
	$image_title = "Remote @ ".parse_url($image_file, PHP_URL_HOST).": ".basename($image_file);
}
?>
<title><?php echo basename($image_file); ?></title>
<style type="text/css">
<!--
body {
	margin: 0px;
	padding: 0px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #6600FF;
	background-color: #CCCCFF;
}
#iframe_td {
	height: 502px;
}
#close_td {
	height: 18px;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="center_table">
  <tr>
    <td align="center" valign="middle" id="iframe_td" colspan="2">
		<iframe height="500" width="500" src="<?php echo $image_file; ?>" frameborder="0" marginheight="1" marginwidth="1" id="remote_holder"></iframe>
	</td>
  </tr>
  <tr>
    <td align="left" valign="top" id="image_title_td">
		<acronym title="Path: <?php echo $image_file; ?>"><?php echo $image_title; ?></acronym>
	</td>
    <td align="right" valign="top" id="close_td">
		<a href="javascript:close();">Close this window</a>	
	</td>
  </tr>
</table>
</body>
</html>
