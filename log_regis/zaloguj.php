<?php
	session_start();

	if ((!isset($_POST['login'])) && (!isset($_POST['haslo'])))
	{
		header('Location: logowanie.php');
		exit();
	}

	require_once "../connect.php";

	$polaczenie=new mysqli($host,$db_user,$db_password,$db_name);
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];

		$login=htmlentities($login, ENT_QUOTES, "UTF-8"); 							//zabezpieczenie przed wstrzykiwaniem sql


		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$x=$rezultat->num_rows;
			if($x>0)																//sprawdzenie czy występuje pasujący wpis w bazie
			{

				$kodreCAPTCHA="6Led5L0UAAAAAIX98cjN630zZa3HkRRe1oSZYxW8";

				$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$kodreCAPTCHA.'&response='.$_POST['g-recaptcha-response']);

				$odpowiedz = json_decode($sprawdz);

				if ($odpowiedz->success==false)
				{

					$_SESSION['e_bot']="Potwierdź, reCAPTCHA";
					header('Location: logowanie.php');
						exit();
				}

				$wiersz = $rezultat->fetch_assoc();

				if(password_verify($haslo,$wiersz['pass']))
				{
					$_SESSION['user_id']=$wiersz['id'];
					$_SESSION['user']=$wiersz['user'];
					$_SESSION['pass']=$wiersz['pass'];
					$_SESSION['email']=$wiersz['email'];

					unset($_SESSION['blad']);											//czyszczenie błędu o niepoprawnym haśle
					$_SESSION['logged'] = true; 										//ustawienie zmiennej informującej o zalogowaniu
					$rezultat->close();													//czyszczenie pamięci
					header('Location: ../glowna/glowna.php');
				}else
					{																		//ustawienie zmiennej informującej o złym loginie lub haśle
						$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
						header('Location: logowanie.php');
					}


			}else
				{																		//ustawienie zmiennej informującej o złym loginie lub haśle
					$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: logowanie.php');
				}

		}




		$polaczenie->close();
	}

?>
