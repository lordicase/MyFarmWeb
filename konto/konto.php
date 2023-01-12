<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location:logowanie.php');
		exit();
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

	<script>
		function pokaz_f_z_email()
			{
				$('#f_z_email').fadeIn();
			}
		function pokaz_f_z_haslo()
			{
				$('#f_z_haslo').fadeIn();
			}
		function pokaz_usun_k()
			{
				$('#usun_k').fadeIn();
			}
		function schowaj()
			{
				$('#f_z_haslo').fadeOut();
				$('#f_z_email').fadeOut();
				$('#usun_k').fadeOut();

			}
	</script>
</head>
  <body>
    <?php require_once "../menu.html"; //DODAJEMY MENU?>
      <div class="content">
				<?php
				$user_id=$_SESSION['user_id'];
				$nazwa=$_SESSION['user'];
				$email=$_SESSION['email'];
				$e_h=$_SESSION['e_haslo'];
				$e_e=$_SESSION['e_email'];

					echo"<h1>Nazwa użytkownika: $nazwa</h1> <br />
						   <h2>E-mail:$email</h2><br /><button type='button' onclick='pokaz_f_z_email()' name='button' class='form'>Zmień E-mail</button>$e_e<br />
							 <br /><button type='button' onclick='pokaz_f_z_haslo()' name='button' class='form'>Zmień hasło</button>$e_h<br />
							 <br /><button type='button' onclick='pokaz_usun_k()' name='button' class='formnegative'>Usuń konto</button><br />";

				?>
					<?php

					    echo"<div id='f_z_haslo'>
									<form action='zmien_h.php' method='post'>
					        <input type='hidden' name='id' value='$user_id'>
									Podaj nowe hasło:<br /><input type='password' name='haslo1'><br />";

									if (isset($_SESSION['e_haslo']))
									{
										echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
										unset($_SESSION['e_haslo']);
									}

						 echo"Powtórz nowe hasło:<br /><input type='password' name='haslo2'><br />
					        <br /><input type='submit' value='Zatwierdz' class='formpositive'></form><br />
									<br /><button type='button' onclick='schowaj()' name='button' class='formnegative'>Anuluj</button>";
					?>
				</div>
				<div id="f_z_email">
					<?php
						$user_id=$_SESSION['user_id'];
					    echo"<form action='zmien_e.php' method='post'>
					        <input type='hidden' name='id' value='$user_id'>
									Podaj nowy e-mail:<br /><input type='email' id='email' name='email'><br />";
									if (isset($_SESSION['e_email']))
									{
										echo '<div class="error">'.$_SESSION['e_email'].'</div>';
										unset($_SESSION['e_email']);
									}
					   echo"<br /><input type='submit' value='Zatwierdz' class='formpositive'></form><br />
									<br /><button type='button' onclick='schowaj()' name='button' class='formnegative'>Anuluj</button>";
					?>
				</div>
				<div id="usun_k">
					<?php
						$user_id=$_SESSION['user_id'];
					    echo"Czy na pewno chcesz usunąć konto?<br /><form action='usun.php' method='post'>
					        <input type='hidden' name='id' value='$user_id'>
					        <input type='submit' value='Usuń konto' class='formnegative'></form><br />
									<br /><button type='button' onclick='schowaj()' name='button' class='formpositive'>Anuluj</button>";
					?>
				</div>




      </div>
    </div> <!-- ZAKOŃCZENIE <div class="wrapper"> Z MENU -->
			<div class="footer">Myfarm.pl &copy; 2019 Wszelkie prawa zastrzeżone.</div>

  </body>
</html>
