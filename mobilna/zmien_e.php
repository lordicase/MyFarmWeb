<?php
session_start();
$email = $_POST['email'];
$user_id=$_POST['user_id'];
$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
{

  $_SESSION['e_email']="Podaj poprawny adres e-mail!";
  echo "C";
}else {

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
      $polaczenie->query("UPDATE uzytkownicy SET email='$email' WHERE id='$user_id'");


      $polaczenie->close();
      echo "A";

    }

  }
  catch(Exception $e)
  {
    echo 'B';
  ///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
  }
}

?>
