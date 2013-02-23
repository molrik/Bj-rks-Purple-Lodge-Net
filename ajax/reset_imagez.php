<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (isset($_SESSION['latest_imagez_arr'])) {
	unset($_SESSION['latest_imagez_arr']);
	echo "Latest unset - reload to update";
}
?>