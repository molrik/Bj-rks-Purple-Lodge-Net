<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (isset($_SESSION['login'])) header("Location: index_test.php");	//hvis en bruger allerede er logget ind, så spring til hovedsiden
?>
<?php require_once('../../Connections/db_purplelodge_net.php'); //indhent databaseoplysninger ?>
<?php if (isset($_POST['Submit'])) {
    /* Initialisering */
	$alert = "";
	$fejl = 0;
	/* Fejlmeddelelser */
	if ($_POST['brugernavn']=="") { $alert .= "Du skal skrive dit brugernavn<br />"; $fejl++; } else {
		if ($_POST['password']=="") { $alert .= "Du skal skrive din adgangskode<br />"; $fejl++; } else {
			mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
			$q_usermatch = "SELECT * FROM bpl_admins WHERE username = '".trim($_POST['brugernavn'])."'";
			$q_usermatch_res = mysql_query($q_usermatch, $db_purplelodge_net) or die(mysql_error());
			$q_usermatch_row = mysql_fetch_assoc($q_usermatch_res);
			$q_usermatch_num = mysql_num_rows($q_usermatch_res);	//hvis den er nul findes brugeren ikke
			if ($q_usermatch_num) {
				if (trim($q_usermatch_row['password'])!=trim($_POST['password'])) {
					$alert .= "Det indtastede password er forkert"; $fejl++;
				} else {	//password forkert
					if (intval($q_usermatch_row['status'])) {
						$altok = true;
					} else {	//status som bruger
						$alert .= "Du har ikke adgang til administration på dette niveau"; $fejl++;
					}
				}	//password korrekt
			} else {	//bruger findes
				$alert .= "Det indtastede brugernavn findes ikke i databasen<br />"; $fejl++;
			}	//bruger findes ikke
		}	//password
	}	//brugernavn
	/* Selve inlogningsproceduren */
	if (isset($altok) AND ($altok)) {
		$_SESSION['login'] = $q_usermatch_row['id'];
		$_SESSION['user'] = trim($_POST['brugernavn']);
		$_SESSION['check_remote_files'] = false;
		//$beuser = trim($_POST['brugernavn']);
		//$message = $q_usermatch_row['log'].' '.$beuser.' logged in ';
		//updateuserlog(intval($_SESSION['login']),$message);
		if (isset($_GET['logon'])) {
			header("Location: ".$_GET['logon']);
		} else {
			header("Location: index_test.php");
		}
	}
} //post 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge admin login</title>
<link href="../bpl.css" rel="stylesheet" type="text/css" />
</head>

<body style="margin:0; background-image:url(../syspics/left2.jpg); background-repeat:repeat-y">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td width="150" height="150" align="center" valign="middle" style="background-image:url(../syspics/corner2.jpg)"><a href="../index.php"><img src="../syspics/rose.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></a></td>
    <td height="150" align="center" valign="middle" style="background-image:url(../syspics/top2.jpg)"><h1>BPL Admin Login </h1></td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top" style="background-image:url(../syspics/left2.jpg)">&nbsp;</td>
    <td align="center" valign="middle">
	<form id="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	  <table>
		  <tr>
			  <td align="right">Brugernavn:</td>
			  <td colspan="3">
			  <input name="brugernavn" type="text" value="<?php if (isset($_POST['brugernavn'])) echo $_POST['brugernavn'] ?>" size="20" />
			  </td>
		  </tr>
		  <tr>
	      <td align="right">Adgangskode:</td><td colspan="3"><input name="password" type="password" id="password" size="20" /></td></tr>
		  <tr>
		    <td>&nbsp;</td>
	        <td align="left"><input name="Submit" type="submit" id="Submit" value="Login" /></td>
	        <td align="center">&nbsp;</td>
	        <td align="right"><input name="Reset" type="reset" id="Reset" value="Reset" /></td>
	    </tr>
	  </table>
  </form>   
    
      <?php if (isset($fejl) && ($fejl)) { ?>
      <?php echo "<p class=\"alert\">".$alert."</p>"; ?>  
      <?php } //fejl ?>  
      <p>Siden senest opdateret <?php echo date("j/n Y H:i", getlastmod()); ?></p>
      </td>
  </tr>
</table>
</body>
</html>