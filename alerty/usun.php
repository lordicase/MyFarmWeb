<?php
session_start();


if((isset($_POST['lat']))&&(isset($_POST['lon']))){
  $lat = $_POST['lat'];
  $lon = $_POST['lon'];
   $uprawa = $_POST['uprawa'];
   $szkodnik =  $_POST['szkodnik'];
   $user_id=$_SESSION['user_id'];
require_once "../connect.php";
$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
  if ($polaczenie->connect_errno!=0)
    {
      throw new Exception(mysqli_connect_errno());
    }
    else
    {

      $polaczenie->query("DELETE FROM markers WHERE   lat='$lat' AND lng='$lon'");

      $rezultat=$polaczenie->query("SELECT ilosc_znacznikow FROM uzytkownicy WHERE id='$user_id'");
      $wiersz = $rezultat->fetch_assoc();
      $ilosc_znacznikow=$wiersz['ilosc_znacznikow'];
      $ilosc_znacznikow--;
      $polaczenie->query("UPDATE uzytkownicy SET ilosc_znacznikow = '$ilosc_znacznikow' WHERE id='$user_id'");
			$polaczenie->close;
    }
};
?>
