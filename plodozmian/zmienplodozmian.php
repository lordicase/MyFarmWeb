<?php
session_start();
if(!isset($_POST["id0"]))
{
header('Location:plodozmian.php');
}else{
  $user_id=$_SESSION['user_id'];
  $user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
  require_once "../connect.php";
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


      $rezultat = $polaczenie->query("SELECT * FROM dzialki WHERE user_id='$user_id'");

      if (!$rezultat) throw new Exception($polaczenie->error);
      else
      {

      	$ile_dzialek = $rezultat->num_rows;
        for ($i=0; $i <=4 ; $i++) {
          $id=$_POST["id$i"];
          $przedplon=$_POST["przedplon$i"];
          $uprawa_g=$_POST["uprawa_g$i"];
          $dodatkowa_pra=$_POST["dodatkowa_pra$i"];

          $id=htmlentities($id, ENT_QUOTES, "UTF-8");
      		$przedplon=htmlentities($przedplon, ENT_QUOTES, "UTF-8");
      		$uprawa_g=htmlentities($uprawa_g, ENT_QUOTES, "UTF-8");
      		$dodatkowa_pra=htmlentities($dodatkowa_pra, ENT_QUOTES, "UTF-8");


          $polaczenie->query(
          sprintf("UPDATE plodozmian SET przedplon='%s',uprawa_g='%s',dodatkowa_pra='%s' WHERE user_id='%s' AND id='%s'",
          mysqli_real_escape_string($polaczenie,$przedplon),
          mysqli_real_escape_string($polaczenie,$uprawa_g),
          mysqli_real_escape_string($polaczenie,$dodatkowa_pra),
          mysqli_real_escape_string($polaczenie,$user_id),
          mysqli_real_escape_string($polaczenie,$id)));

        }




      }
      $polaczenie->close();
    }
  }
  catch(Exception $e)
  {
    echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
  ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
  }
header('Location:plodozmian.php');






}
?>
