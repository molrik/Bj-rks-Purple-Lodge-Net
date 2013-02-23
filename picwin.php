<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php 
$image_file = str_replace("../", "", trim($_GET['pic'])); 	//fjern tilbageskridt (skal virke både i admin OG i normal)
$image_info = @getimagesize($image_file);					//indhent filoplysninger hvis tilgændelige (undertryk evt. fejlmeddelelser)
if (substr(strtolower($image_file),0,4)!="http") {			//lokal fil
	$image_title = $_SERVER['SERVER_NAME'].": ".basename($image_file)." - ".$image_info[0]." &bull; ".$image_info[1]." px";
} else {													//remote fil
	$image_title = "Remote @ ".parse_url($image_file, PHP_URL_HOST).": ".basename($image_file);
}
//$image_title .= " w=".trim($_GET['w'])." h=".trim($_GET['h']);
?>
<title><?php echo basename($image_file); /* echo $image_file; */ ?></title>
<style type="text/css">
<!--
body {
	height: 100%;
	margin: 0px;
	padding: 0px;
}
html {
	height: 100%;
}
#center_table {
	height: 100%;
	text-align: center;
	vertical-align: middle;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="center_table">
  <tr>
    <td align="center" valign="middle" style="height:100%">
		<a href="javascript:close();">
			<img src="<?php echo $image_file ?>" alt="<?php echo $image_file; ?>" <?php echo $image_info[3] ?> border="0" title="<?php echo $image_title; ?>" align="middle" />
		</a>
	</td>
  </tr>
</table>
</body>
</html>
