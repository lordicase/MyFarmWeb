<?php
session_start();


if(isset($_POST['id'])){
  $user_id=$_POST['id'];
  require_once "../connect.php";
  $polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
    if ($polaczenie->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {

        $polaczenie->query("DELETE FROM uzytkownicy WHERE   id='$user_id'");
  			$polaczenie->close;
        	header('Location:../log_regis/logout.php');
      }

}





 ?>
