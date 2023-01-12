<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:https://myfarm.pl/log_regis/logowanie.php');
		exit();
	}else {
    header('Location:https://myfarm.pl/glowna/glowna.php');
  }
?>
