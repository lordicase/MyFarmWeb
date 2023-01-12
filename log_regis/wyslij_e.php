<?php
session_start();
// if (!isset($_SESSION['logged']))
// 	{
// 		header('Location:logowanie.php');
// 		exit();
// 	}
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


	<div id='login'>
		<form method='post'>

			Podaj e-mail przypisany do konta:<br /><input type='email' name='email'><br />

			<br /><input type='submit' value='Zatwierdz'></form><br />

		</div>
	</body>
	</html>


	<?php
	require_once "../connect.php";
	//////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE
$adres = $_POST['email'];
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
			//////////////////////////////////////////////////////////////////////////////////////CZY EMAIL JUŻ WYSTĘPUJE
			$rezultat = $polaczenie->query("SELECT * FROM uzytkownicy WHERE email='$adres'");

			if (!$rezultat) throw new Exception($polaczenie->error);

			$ile_emaili = $rezultat->num_rows;
			$wiersz = $rezultat->fetch_assoc();
			$nick=$wiersz['user'];
			$id=$wiersz['id'];
			if($ile_emaili>0)
			{

				$tytul = "Link do odzyskania hasła";
				$wiadomosc = "Twój nick to:".$nick."link do zmiany hasła:https://myfarm.pl/log_regis/zmiana_h.php?id=".$id."&e=".$adres."";

				// użycie funkcji mail
				mail($adres, $tytul, $wiadomosc);


			}




		}

		$polaczenie->close;
	}catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
		///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
	}

?>
