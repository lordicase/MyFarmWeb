<?php
session_start();
//DODANIE ZNACZNIKA DO BAZY

if((isset($_POST['lat']))&&(isset($_POST['lon']))){
  $lat = $_POST['lat'];
  $lon = $_POST['lon'];
  $id_dzialki = $_POST['id_dzialki'];
  $user_id=$_SESSION['user_id'];

  require_once "../connect.php";
  $polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
    if ($polaczenie->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        $polaczenie->query("INSERT INTO mapy_dzialek VALUES ('NULL','$lat','$lon','$user_id','$id_dzialki')");
      }
      $polaczenie->close;
}

?>
