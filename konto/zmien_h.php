<?php
  session_start();
  $haslo1 = $_POST['haslo1'];
  $haslo2 = $_POST['haslo2'];
$test=ok;
  if (strlen($haslo1)<10)
  {
    $test=false;
    $_SESSION['e_haslo']="Hasło musi posiadać od 10!";
  }

  if ($haslo1!=$haslo2)
  {
    $test=false;
    $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
  }
  //////////////////////////////////////////////////////////////////////////////////////////////HASHOWANIE HASŁA
  $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
  if ($test==false) {
    header('Location:konto.php');
  }else
  {


    //////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE
    require_once "../connect.php";
    $user_id=$_SESSION['user_id'];
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
      $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
      if ($polaczenie->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        $polaczenie->query("UPDATE uzytkownicy SET pass='$haslo_hash' WHERE id='$user_id'");
        $_SESSION['e_haslo']="Udana zmiana hasła";

        $polaczenie->close();

        header('Location:konto.php');

      }

    }
    catch(Exception $e)
    {
      echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
    ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
    }
  }
?>
