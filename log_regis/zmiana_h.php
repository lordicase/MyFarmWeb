<?php
session_start();
if (isset($_POST['haslo1'])) {
	$user_id=$_GET['id'];
	$email=$_GET['e'];


  $haslo1 = $_POST['haslo1'];
  $haslo2 = $_POST['haslo2'];
$test=ok;
  if (strlen($haslo1)<10)
  {
    $test=false;
    $error="Hasło musi posiadać od 10!";
  }

  if ($haslo1!=$haslo2)
  {
    $test=false;
    $error="Podane hasła nie są identyczne!";
  }
  //////////////////////////////////////////////////////////////////////////////////////////////HASHOWANIE HASŁA
  $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
  if ($test==false) {
    header("Location:zmiana_h.php?id=$user_id&e=$email&error=$error");

  }else
  {


    //////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE
    require_once "../connect.php";

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
        $polaczenie->query("UPDATE uzytkownicy SET pass='$haslo_hash' WHERE id='$user_id' AND email='$email'");
        $error="Udana zmiana hasła";

        $polaczenie->close();

        header('Location: logowanie.php');
      }

    }
    catch(Exception $e)
    {
      echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
    ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
    }
  }
}
?>

<!DOCTYPE HTML>
<html lang="pl">



<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Twoje konto</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="Stylesheet" href="../style.css">
</head>
  <body>

<?php

    echo"<div id='login'>
        <form  method='post'>
        <input type='hidden' name='id' value='$user_id'>
				<input type='hidden' name='id' value='$email'>
        Podaj nowe hasło:<br /><input type='password' name='haslo1' required><br />";

        if (isset($_GET['error']))
        {
          echo '<div class="error">'.$_GET['error'].'</div>';
          unset($_GET['error']);
        }

   echo"Powtórz nowe hasło:<br /><input type='password' name='haslo2' required><br />
        <br /><input type='submit' value='Zatwierdz'></form><br />";

?>
</body>
</html>
