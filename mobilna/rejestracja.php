<?php
	session_start();
	if(isset($_POST['nazwa']))
	{
		//////////////////////////////////////////////////////////////////////////////////////////////CZY WARTOŚCI SĄ POPRAWNE?
		$test=true;


		$nazwa=$_POST['nazwa'];

		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE ZNAKÓW
		if (ctype_alnum($nazwa)==false)
		{
			$test=false;
		$blad="A";
		}

		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE DŁUGOŚCI NAZWY
		if ((strlen($nazwa)<3) || (strlen($nazwa)>20))
		{
			$test=false;
			//////////////////////////////////////////////////////////////////////////////////////////////PRZESYŁANIE ZMIENNEJ Z BŁĘDEM
				$blad="B";
		}

		////////////////////////////////////////////////////////////////////////////////////////////// SPRAWDZANIE EMAIL
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$test=false;
			$blad="C";
		}

		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE HASEŁ
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];

		if ((strlen($haslo1)<10) || (strlen($haslo1)>20))
		{
			$test=false;
			$blad="D";
		}

		if ($haslo1!=$haslo2)
		{
			$test=false;
			$blad="E";
		}
		//////////////////////////////////////////////////////////////////////////////////////////////HASHOWANIE HASŁA
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

		//////////////////////////////////////////////////////////////////////////////////////////////AKCEPTACJA REGULAMINU
		// if (!isset($_POST['regulamin']))
		// {
		// 	$test=false;
		// 	$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		// }



		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE RECAPTCHA
		// $kodreCAPTCHA="6Led5L0UAAAAAIX98cjN630zZa3HkRRe1oSZYxW8";
		//
		// $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$kodreCAPTCHA.'&response='.$_POST['g-recaptcha-response']);
		//
		// $odpowiedz = json_decode($sprawdz);
		//
		// if ($odpowiedz->success==false)
		// {
		// 	$test=false;
		// 	$_SESSION['e_bot']="Potwierdź, reCAPTCHA";
		// }


		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE CZY EMAIL LUB LOGIN JUŻ NIE WYSTĄPIŁO

		require_once "../connect.php";
		//////////////////////////////////////////////////////////////////////////////////////////////POŁĄCZENIE

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
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

				if (!$rezultat) throw new Exception($polaczenie->error);

				$ile_emaili = $rezultat->num_rows;
				if($ile_emaili>0)
				{
					$test=false;
					$blad="F";
				}

			///////////////////////////////////////////////////////////////////////////////////////////CZY TAKA NAZWA JUŻ WYSTĘPUJE
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nazwa'");

				if (!$rezultat) throw new Exception($polaczenie->error);

				$ile_nazw = $rezultat->num_rows;
				if($ile_nazw>0)
				{
					$test=false;
					$blad="G";
				}
				///////////////////////////////////////////////////////////////////////////////////////////TEST UDANY
				if ($test==true)
				{


					if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nazwa', '$haslo_hash', '$email','0','53','19')"))
					{
						$_SESSION['udanarejestracja']=true;
						echo "udana rejestracja";
				//		header('Location: logowanie.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
							echo "$blad";
					}

				}

				$polaczenie->close;
				echo "$blad";
			}


		}catch(Exception $e)
		{
			echo 'Błąd serwera! Proszę spróbować później';
			///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}






	}
?>
