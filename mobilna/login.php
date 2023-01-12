<?php
$host = "myfarm.pl";
$db_user = "31568620_inz";
$db_password = "656432156kK";
$db_name = "31568620_inz";


$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
if ($polaczenie->connect_errno!=0)
{
  throw new Exception(mysqli_connect_errno());
}
else
{
$login = $_POST['user'];
$haslo = $_POST['pass'];

$login=htmlentities($login, ENT_QUOTES, "UTF-8"); 							//zabezpieczenie przed wstrzykiwaniem sql


  if ($rezultat = @$polaczenie->query(
  sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
  mysqli_real_escape_string($polaczenie,$login))))
  {
    $x=$rezultat->num_rows;
    if($x>0)																//sprawdzenie czy występuje pasujący wpis w bazie
    {
      $wiersz = $rezultat->fetch_assoc();

      if(password_verify($haslo,$wiersz['pass']))
      {
        $id=$wiersz['id'];
      echo "$id";

      }else
        {																		//ustawienie zmiennej informującej o złym loginie lub haśle
        echo"Błędny login lub hasło";
        }


    }else
      {																		//ustawienie zmiennej informującej o złym loginie lub haśle
      echo"Błędny login lub hasło";
      }

  }
}



$polaczenie->close();



?>
