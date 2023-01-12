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
			$_SESSION['e_nazwa']="Nazwa może składać się tylko z cyfr i liter (bez polskich znaków)";
		}

		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE DŁUGOŚCI NAZWY
		if ((strlen($nazwa)<3) || (strlen($nazwa)>20))
		{
			$test=false;
			//////////////////////////////////////////////////////////////////////////////////////////////PRZESYŁANIE ZMIENNEJ Z BŁĘDEM
			$_SESSION['e_nazwa']="Nazwa musi posiadać od 3 do 20 znaków!";
		}

		////////////////////////////////////////////////////////////////////////////////////////////// SPRAWDZANIE EMAIL
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$test=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}

		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE HASEŁ
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];

		if (strlen($haslo1)<10)
		{
			$test=false;
			$_SESSION['e_haslo']="Hasło musi posiadać więcej niż 10";
		}

		if ($haslo1!=$haslo2)
		{
			$test=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}
		//////////////////////////////////////////////////////////////////////////////////////////////HASHOWANIE HASŁA
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

		//////////////////////////////////////////////////////////////////////////////////////////////AKCEPTACJA REGULAMINU
		if (!isset($_POST['regulamin']))
		{
			$test=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}



		//////////////////////////////////////////////////////////////////////////////////////////////SPRAWDZANIE RECAPTCHA
		$kodreCAPTCHA="6Led5L0UAAAAAIX98cjN630zZa3HkRRe1oSZYxW8";

		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$kodreCAPTCHA.'&response='.$_POST['g-recaptcha-response']);

		$odpowiedz = json_decode($sprawdz);

		if ($odpowiedz->success==false)
		{
			$test=false;
			$_SESSION['e_bot']="Potwierdź, reCAPTCHA";
		}


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
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}

			///////////////////////////////////////////////////////////////////////////////////////////CZY TAKA NAZWA JUŻ WYSTĘPUJE
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nazwa'");

				if (!$rezultat) throw new Exception($polaczenie->error);

				$ile_nazw = $rezultat->num_rows;
				if($ile_nazw>0)
				{
					$test=false;
					$_SESSION['e_nazwa']="Istnieje już użytkownik o takiej nazwie! Wybierz inną.";
				}
				///////////////////////////////////////////////////////////////////////////////////////////TEST UDANY
				if ($test==true)
				{


					if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nazwa', '$haslo_hash', '$email','0','53','19')"))
					{
						$_SESSION['udanarejestracja']=true;
						header('Location: logowanie.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}

				}

				$polaczenie->close;
			}


		}catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Proszę spróbować później</span>';
			///////////////////////////////////////////////////////////////////////////////////////////echo '<br />Informacja developerska: '.$e;
		}






	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Rejestracja</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<link rel="Stylesheet" href="../style.css">

</head>

<body  style="	background-image: url('../img/tlo2.jpg'); background-size:cover; background-repeat:no-repeat; overflow:hidden;">
<h1>Rejestracja</h1>
	<div id="login">
		<div class="logo">
		MY FARM
		</div>
		<form method="post">
			Nazwa użytkownika: <br /> <input type="text" name="nazwa" class="form" required /><br />

			<?php																				//WYŚWIETLANIE BŁĘDU
			if (isset($_SESSION['e_nazwa']))
			{
				echo '<div class="error">'.$_SESSION['e_nazwa'].'</div>';
				unset($_SESSION['e_nazwa']);
			}
			?>
			E-mail: <br /> <input type="email" name="email" class="form" required /><br />

			<?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
			?>
			Hasło: <br /> <input type="password" name="haslo1" class="form" required /><br />

			<?php
			if (isset($_SESSION['e_haslo']))
			{
				echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);
			}
			?>

			Powtórz hasło: <br /> <input type="password" name="haslo2" class="form" required /><br />

			<label><input type="checkbox" name="regulamin"/> Akceptuje regulamin</label>

			<?php
			if (isset($_SESSION['e_regulamin']))
			{
				echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);
			}
			?>
			<center>
				<div class="g-recaptcha" data-sitekey="6Led5L0UAAAAAEKJVzYux240RnmVZ2BSNiIDyrIq" style="margin-left:auto; margin-right:auto;"></div>
			</center>
			<br/>
			<?php
			if (isset($_SESSION['e_bot']))
			{
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);
			}
			?>


			<input type="submit" value="Zarejestruj się" class="form" />	<br /><br />

			<a href="logowanie.php">Lub zaloguj się jeśli masz już konto.<br />
			<input type="button" value="Zaloguj się" class="form" /> </a>
		</form>
	</div>
</body>
</html>
