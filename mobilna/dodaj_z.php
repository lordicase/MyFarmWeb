<?php
  $user_id=$_POST['user_id'];
  $data=$_POST['data'];
  $typ_z=$_POST['typ_z'];
  $opis=$_POST['opis'];
  $nazwa_w=$_POST['nazwa_w'];

  $user_id=htmlentities($user_id, ENT_QUOTES, "UTF-8");
  $data=htmlentities($data, ENT_QUOTES, "UTF-8");
  $typ_z=htmlentities($typ_z, ENT_QUOTES, "UTF-8");
  $opis=htmlentities($opis, ENT_QUOTES, "UTF-8");
  $nazwa_w=htmlentities($nazwa_w, ENT_QUOTES, "UTF-8");

  require_once "../connect.php";

  mysqli_report(MYSQLI_REPORT_STRICT);//////////////////////////////////////////////////////////ZMIANA RAPORTOWANIA BŁĘDÓW
  try
  {
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_errno!=0)
    {
      throw new Exception(mysqli_connect_errno());
    }else
    {
      $rezultat=$polaczenie->query("SELECT id FROM dzialki WHERE user_id='$user_id' AND nazwa_w='$nazwa_w'");
      $wiersz = $rezultat->fetch_assoc();
      $id_dzialki=$wiersz['id'];
      $polaczenie->query(
      sprintf("INSERT INTO zabiegi VALUES (NULL, '%s', '%s','%s', '%s','%s','%s')",
      mysqli_real_escape_string($polaczenie,$data),
      mysqli_real_escape_string($polaczenie,$typ_z),
      mysqli_real_escape_string($polaczenie,$opis),
      mysqli_real_escape_string($polaczenie,$nazwa_w),
      mysqli_real_escape_string($polaczenie,$user_id),
      mysqli_real_escape_string($polaczenie,$id_dzialki)));
      $polaczenie->close();
      echo"A";

    }
  }
  catch(Exception $e)
  {
  	echo"B";
  ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
  }

?>
