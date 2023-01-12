<?php
  session_start();
  $haslo1 = $_POST['haslo1'];
  $haslo2 = $_POST['haslo2'];
  $user_id=$_POST['user_id'];


  if ((strlen($haslo1)<10) || (strlen($haslo1)>20))
  {
    $_SESSION['e_haslo']="Hasło musi posiadać od 10 do 20 znaków!";
    echo"C";
    exit();
  }

  if ($haslo1!=$haslo2)
  {
    $_SESSION['e_haslo']="Podane hasła nie są identyczne!";
    echo"D";
    exit();
  }
  //////////////////////////////////////////////////////////////////////////////////////////////HASHOWANIE HASŁA
  $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

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

        echo"A";

      }

    }
    catch(Exception $e)
    {
        echo"B";
    }
?>
