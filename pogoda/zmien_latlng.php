<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:../log_regis/logowanie.php');
		exit();
	}
require_once "../connect.php";

$user_id=$_SESSION['user_id'];
$lat=$_POST['lat'];
$lng=$_POST['lng'];
mysqli_report(MYSQLI_REPORT_STRICT);//////////////////////////////////////////////////////////ZMIANA RAPORTOWANIA BŁĘDÓW
try
{
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());
  }
  else
  {
  //////////////////////////////////////////////////////////////////////////////////////ZMIANA DANYCH LAT,LNG

    $rezultat = $polaczenie->query("UPDATE uzytkownicy SET lat='$lat', lng='$lng'  WHERE id='$user_id'");

    $polaczenie->close();
  }

}
catch(Exception $e)
{
  echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
}
//header('Location:pogoda.php');

?>
