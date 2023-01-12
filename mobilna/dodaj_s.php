<?php
session_start();
//DODANIE ZNACZNIKA DO BAZY


$lat = $_POST['lat'];
$lon = $_POST['lon'];
$uprawa = $_POST['uprawa'];
$szkodnik =  $_POST['szkodnik'];
$user_id=$_POST['user_id'];
$ilosc_znacznikow=0;
require_once "../connect.php";
$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
  if ($polaczenie->connect_errno!=0)
    {
      throw new Exception(mysqli_connect_errno());
      echo"B";
    }
    else
    {
      $czas1 = time()-2678400;

      $rezultat=$polaczenie->query("SELECT address FROM markers WHERE address='$user_id' AND data>'$czas1'");
      while($wiersz = $rezultat->fetch_assoc()){

        $ilosc_znacznikow++;
      }

      if ($ilosc_znacznikow<=50) {
        $czas1 = time();
        $polaczenie->query("INSERT INTO markers VALUES ('NULL','$szkodnik','$user_id','$lat','$lon','$uprawa','$czas1')");
        $ilosc_znacznikow++;
        $polaczenie->query("UPDATE uzytkownicy SET ilosc_znacznikow = '$ilosc_znacznikow' WHERE id='$user_id'");
        echo"A";
      }else{
        echo"C";
      }
    $polaczenie->close;
    
    }

?>
